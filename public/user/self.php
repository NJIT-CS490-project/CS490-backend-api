<?php

$app = require(__DIR__ . '/../../app.php');
$app->on('get', function ($request, $services) {
	session_start();

	$stmt = $services['pdo']->prepare('
			SELECT 
				`id`, 
				`username`, 
				UNIX_TIMESTAMP(`created`) AS `created`,
				`admin`
			FROM `User` 
			WHERE `id` = (
				SELECT `ownerID` 
				FROM `Session` 
				WHERE `id` = :sessionID 
					AND `active` = 1
				LIMIT 1)');
	$stmt->bindValue('sessionID', session_id(), PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($result) {
		$result['id'] = intval($result['id']);
		$result['created'] = intval($result['created']);
		$result['admin'] = $result['admin'] !== '0';
		return $result;
	} else {
		throw new UnauthorizedException();
	}
});
$app->route();
