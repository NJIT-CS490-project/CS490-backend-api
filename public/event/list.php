<?php

$app = require(__DIR__ . '/../../app.php');
$app->on('get', function ($request, $services) {
	$stmt = $services['pdo']->prepare('
		SELECT 
			`id`, 
			`name`, 
			`ownerID`, 
			`start` AS `start`, 
			`end` AS `end`, 
			`location`
		FROM `Event` 
		ORDER BY `id` ASC');
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($result === false) {
		throw new InternalServiceException('Failed to list events.');
	}
	foreach ($result as &$event) {
		$event['id']      = intval($event['id']);
		$event['ownerID'] = intval($event['ownerID']);
	}
	return $result;
});
$app->route();
