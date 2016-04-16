<?php

$app = require(__DIR__ . '/../../app.php');
$app->on('post', function ($request, $services) {
	$currentUserID = Session::getCurrentUserID($services, true, 'You must be logged in to remove an event from your favorites.');

	$request->params->mustHave('eventID');
	$stmt = $services['pdo']->prepare('
			DELETE FROM `EventFavorites` WHERE `eventID` = :eventID AND `userID` = :userID LIMIT 1
	');
	$stmt->bindValue('eventID', $request->params['eventID'], PDO::PARAM_INT);
	$stmt->bindValue('userID',  $currentUserID,              PDO::PARAM_INT);
	$stmt->execute();
	if ($stmt->rowCount() < 1) {
		return new Response('', 304);
	}
});
$app->route();
