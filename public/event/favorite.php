<?php

$app = require(__DIR__ . '/../../app.php');
$app->on('post', function ($request, $services) {
	session_start();
	$stmt = $services['pdo']->prepare('
		SELECT `ownerID` 
		FROM `Session` 
		WHERE `id` = :sessionID
	');
	$stmt->bindValue('sessionID', session_id(), PDO::PARAM_STR);
	$stmt->execute();
	$currentUserID = $stmt->fetchColumn(0);
	if ($currentUserID === false) {
		throw new UnauthorizedException('You must be logged in to favorite an event.');
	}


	$request->params->mustHave('eventID');
	$stmt = $services['pdo']->prepare('
		INSERT IGNORE INTO `EventFavorites` (
			`eventID`,
			`userID`
		) VALUES (
			:eventID,
			:userID
		)
	');
	$stmt->bindValue('eventID', $request->params['eventID'], PDO::PARAM_INT);
	$stmt->bindValue('userID',  $currentUserID,              PDO::PARAM_INT);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		if (-1 !== strpos($e->getMessage(), 'Integrity constraint violation')) {
			throw new ObjectNotFoundException('Event does not exist.');
		}
	}
	if ($stmt->rowCount() < 1) {
		return new Response('', 304);
	}
});
$app->route();
