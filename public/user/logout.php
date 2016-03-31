<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../src/Router.php');

$router = new Router();
$router->on('post', function ($request, $services) {
	session_start();
	$stmt = $services['pdo']->prepare('UPDATE `Session` SET `active` = 0 WHERE `id` = :sessionID');
	$stmt->bindValue('sessionID', session_id(), PDO::PARAM_STR);
	$stmt->execute();
});
$router->route();
