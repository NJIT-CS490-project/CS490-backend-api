<?php

ini_set('display_errors', 1);

$app = require(__DIR__ . '/../../app.php');
$app->on('get', function ($request, $services) {

	$sql = '
		SELECT 
			a.`id`, 
			a.`name`,
			a.`start`,
			a.`end`,
			a.`location`,
			a.`building`,
			a.`room`,
			a.`fromNJIT`,
			b.`eventID` IS NOT NULL AS `favorite` 
		FROM `Event` AS a 
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
		$conditions []= '`name` LIKE :query';
		$query = $request->params['query'];
		$query = str_replace('%', '\%', $query);
		if (!$request->params->get('matchWord', false)) {
			$query = "%$query%";
		}
	}

	if ($request->params->get('limitToFavorites', false) === 'true') {
		$conditions []= 'b.`eventID` IS NOT NULL';
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

	$stmt = $services['pdo']->prepare($sql);
	
	if ($currentUserID !== null) {
		$stmt->bindValue('userID', $currentUserID, PDO::PARAM_INT);
	}
	if ($hasQuery) {
		$stmt->bindValue('query', $query, PDO::PARAM_STR);
	}
	$stmt->execute();

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($result !== false) {
		return $result;
	} else {
		throw new InternalServerException('Failed to search events.');
	}
});
$app->route();
