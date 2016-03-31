<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../src/Router.php');
require_once(__DIR__ . '/../../src/HTTPException.php');
	
$router  = new Router();
$router->on('get', function ($request, $services) {
	session_start();

	$stmt = $services['pdo']->prepare('
			SELECT 
				`id`, 
				`username`, 
				`created` 
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
		return $result;
	} else {
		throw new UnauthorizedException();
	}
});
$router->route();
