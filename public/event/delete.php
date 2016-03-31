<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../src/Router.php');
require_once(__DIR__ . '/../../src/HTTPException.php');

$router = new Router();
$router->on('delete', function ($request, $services) {
	session_start();
	$stmt = $services['pdo']->prepare('SELECT `ownerID` FROM `Session` WHERE `id` = :sessionID');
	$stmt->bindValue('sessionID', session_id(), PDO::PARAM_STR);
	$stmt->execute();
	$currentUID = $stmt->fetchColumn(0);
	if ($currentUID === false) {
		throw new UnauthroizedException('You must be logged in to delete events.');
	}

	$request->params->mustHave('id');
	$stmt = $services['pdo']->prepare('
		DELETE e FROM `Event` AS e
		WHERE e.`id` = :eventID 
			AND EXISTS (
				SELECT * FROM `User` AS u
				WHERE u.`id` = :currentUID
					AND (e.`ownerID` = u.`id` OR u.`admin` = 1)
				LIMIT 1
		)');
	$stmt->bindValue('eventID',    $request->params['id'], PDO::PARAM_INT);
	$stmt->bindValue('currentUID', $currentUID,            PDO::PARAM_INT);

	$stmt->execute();
	if ($stmt->rowCount() < 1) {
		return new Response('', 304);
	}
});
$router->route();
