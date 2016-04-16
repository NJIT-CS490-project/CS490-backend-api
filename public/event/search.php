<?php

$app = require(__DIR__ . '/../../app.php');
$app->on('get', function ($request, $services) {

	$request->params->mustHave('query');
	$sql = '
	SELECT 
		`id`, 
		`name`, 
		`ownerID`, 
		`start`, 
		`end`, 
		`location` 
	FROM `Event` 
	WHERE `name` LIKE :query';

	$query = $request->params['query'];
	$query = str_replace('%', '\%', $query);
	if (!$request->params->get('matchWord', false)) {
		$query = "%$query%";
	}
	
	$stmt = $services['pdo']->prepare($sql);
	$stmt->bindValue('query', $query, PDO::PARAM_STR);
	$stmt->execute();

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($result !== false) {
		return $result;
	} else {
		throw new InternalServerException('Failed to search events.');
	}
});
$app->route();
