<?php

ini_set('display_errors', 1);

$app = require(__DIR__ . '/../../app.php');
$app->on('get', function ($request, $services) {
	
	$sql = '
		SELECT 
			a.`id`, 
			a.`name`,
			a.`updated`,
			a.`start`,
			a.`ownerID`,
			a.`end`,
			a.`description`,
			a.`building`,
			a.`room`,
			a.`fromNJIT`,
			b.`eventID` IS NOT NULL AS `favorite`,
			IFNULL(f.`n`, 0) AS `numFavorites`
		FROM `Event` AS a
		LEFT JOIN (
				SELECT `eventID`, COUNT(*) AS `n`
				FROM `EventFavorites`
				GROUP BY `eventID`
		) AS f ON a.`id` = f.`eventID`
	';

	$currentUserID = Session::getCurrentUserID($services, false);
	if ($currentUserID !== null) {
		$sql .= '
			LEFT JOIN ( 
				SELECT `eventID` 
				FROM `EventFavorites` 
				WHERE `userID` = :userID
			) AS b ON a.`id` = b.`eventID`
		';
	}

	$hasQuery = $request->params->has('query') && $request->params['query'] !== '';
	$conditions = [];

	if ($hasQuery) {
		$conditions []= '(`name` LIKE :query OR `description` LIKE :query)';
		$query = $request->params['query'];
		$query = str_replace('%', '\%', $query);
		if (!$request->params->get('matchWord', false)) {
			$query = "%$query%";
		}
	}

	if ($request->params->has('building')) {
		$conditions []= 'a.`building` = :building';
	}

	if ($request->params->has('room')) {
		$conditions []= 'a.`room` = :room';
	}

	if ($request->params->get('limitToFavorites', false) === 'true') {
		$conditions []= 'b.`eventID` IS NOT NULL';
	}

	if ($request->params->get('limitToNJIT', false) === 'true') {
		$conditions []= 'a.`fromNJIT` = 1';
	}

	if ($request->params->get('limitToUser', false) === 'true') {
		$conditions []= 'a.`fromNJIT` = 0';
	}

	if ($request->params->get('limitToMine', false) === 'true') {
		$conditions []= 'a.`ownerID` = :currentUserID';
	}

	if ($request->params->has('id')) {
		$conditions []= 'a.`id` = :id';
	}

	if ($request->params->has('startDate')) {
		$conditions []= 'date(a.`start`) >= :startDate';
	}

	if ($request->params->has('endDate')) {
		$conditions []= 'date(a.`end`) <= :endDate';
	}

	if ($request->params->has('startTime')) {
		$conditions []= 'time(a.`start`) >= :startTime';
	}

	if ($request->params->has('endTime')) {
		$conditions []= 'time(a.`end`) <= :endTime';
	}

	$conditionString = '';
	foreach ($conditions as $condition) {
		if ($conditionString === '') {
			$conditionString .= "WHERE $condition";
		} else {
			$conditionString .= " AND $condition";
		}
	}
	if ($conditionString !== '') {
		$sql .= " $conditionString";
	}

	$sorting = '';
	switch (strtolower($request->params->get('sorting', 'title'))) {
		case 'updated': 
			$sorting = 'a.`updated`'; 
			break;
		case 'due': 
			$sorting = 'a.`start`'; 
			break;
		case 'favorites':
			$sorting = '`numFavorites`';
			break;
		case 'id':
			$sorting = 'a.`id`';
			break;
		case 'title': 
		default:
			$sorting = 'a.`name`'; 
			break;
	}
	$order = '';
	switch (strtolower($request->params->get('order', 'asc'))) {
		case 'desc':
			$order = 'DESC';
			break;
		case 'asc':
		default:
			$order = 'ASC';
			break;
	}


	$sql .= " ORDER BY $sorting $order";

	if ($request->params->has('limit')) {
		$limit = $request->params->get('limit');
		$sql .= " LIMIT $limit";
	}

	$stmt = $services['pdo']->prepare('set time_zone = "US/Eastern"');
	$stmt->execute();

	$stmt = $services['pdo']->prepare($sql);
	
	if ($currentUserID !== null) {
		$stmt->bindValue('userID', $currentUserID, PDO::PARAM_INT);
	}
	if ($hasQuery) {
		$stmt->bindValue('query', $query, PDO::PARAM_STR);
	}
	if ($request->params->has('building')) {
		$stmt->bindValue('building', $request->params->get('building'), PDO::PARAM_STR);
	}
	if ($request->params->has('room')) {
		$stmt->bindValue('room', $request->params->get('room'), PDO::PARAM_STR);
	}
	if ($request->params->has('id')) {
		$stmt->bindValue('id', $request->params->get('id'), PDO::PARAM_INT);
	}
	if ($request->params->has('startDate')) {
		$stmt->bindValue('startDate', $request->params->get('startDate'), PDO::PARAM_STR);
	}
	if ($request->params->has('endDate')) {
		$stmt->bindValue('endDate', $request->params->get('endDate'), PDO::PARAM_STR);
	}
	if ($request->params->has('startTime')) {
		$stmt->bindValue('startTime', $request->params->get('startTime'), PDO::PARAM_STR);
	}
	if ($request->params->has('endTime')) {
		$stmt->bindValue('endTime', $request->params->get('endTime'), PDO::PARAM_STR);
	}
	if ($request->params->get('limitToMine', false) === 'true') {
		$stmt->bindValue('currentUserID', $currentUserID, PDO::PARAM_INT);
	}
		
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($result as &$res) {
		$res['numFavorites'] = intval($res['numFavorites']);
		$res['favorite'] = $res['favorite'] === '1';
		$res['fromNJIT'] = $res['fromNJIT'] === '1';
		$res['id'] = intval($res['id']);
		$res['ownerID'] = intval($res['ownerID']);
	}

	if ($result !== false) {
		return $result;
	} else {
		throw new InternalServerException('Failed to search events.');
	}
});
$app->route();
