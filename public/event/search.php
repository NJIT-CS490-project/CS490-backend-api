<?php

$app = require(__DIR__ . '/../../app.php');
$app->on('get', function ($request, $services) {

	$sql = '
		SELECT 
			a.`id`, 
			a.`name`,
			a.`start`,
			a.`end`,
			a.`location`,
			a.`fromNJIT`,
			b.`eventID` IS NOT NULL AS `favorite` 
		FROM `Event` AS a 
		LEFT JOIN ( 
			SELECT `eventID` 
			FROM `EventFavorites` 
			WHERE `userID` = :userID
		) AS b ON a.`id` = b.`eventID`
	';

	$hasQuery = $request->params->has('query') && $request->params['query'] !== '';

	if ($hasQuery) {
		$sql .= ' WHERE `name` LIKE :query';
		$query = $request->params['query'];
		$query = str_replace('%', '\%', $query);
		if (!$request->params->get('matchWord', false)) {
			$query = "%$query%";
		}
	}

	$currentUserID = Session::getCurrentUserID($services);
	$stmt = $services['pdo']->prepare($sql);
	$stmt->bindValue('userID', $currentUserID, PDO::PARAM_INT);
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
