<?php

$app = require(__DIR__ . '/../../app.php');;
$app->on('post', function ($request, $services) {
	session_start();
	$stmt = $services['pdo']->prepare('
			UPDATE `Session` 
			SET `active` = 0 
			WHERE `id` = :sessionID
	');
	$stmt->bindValue('sessionID', session_id(), PDO::PARAM_STR);
	$stmt->execute();
});
$app->route();
