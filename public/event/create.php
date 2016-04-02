<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../src/Router.php');
require_once(__DIR__ . '/../../src/Response.php');
require_once(__DIR__ . '/../../src/HTTPException.php');
require_once(__DIR__ . '/../..//src/Session.php');

$router = new Router();
$router->on('post', function ($request, $services) {
	$currentUID = Session::getCurrentUserID($services, true, 'You must be logged in to create an event.');

	$request->params->mustHave('name');
	$request->params->mustHave('start');
	$request->params->mustHave('end');
	$request->params->mustHave('location');

	$stmt = $services['pdo']->prepare('
		INSERT INTO `Event` (
			`name`, 
			`ownerID`, 
			`start`, 
			`end`, 
			`location`
		) VALUES (
			:name,
			:ownerID,
			FROM_UNIXTIME(:start),
			FROM_UNIXTIME(:end),
			:location
		)'
	);
	$stmt->bindValue('name',     $request->params['name'],     PDO::PARAM_STR);
	$stmt->bindValue('ownerID',  $currentUID,                  PDO::PARAM_INT);
	$stmt->bindValue('start',    $request->params['start'],    PDO::PARAM_INT);
	$stmt->bindValue('end',      $request->params['end'],      PDO::PARAM_INT);
	$stmt->bindValue('location', $request->params['location'], PDO::PARAM_STR);
	$stmt->execute();
	if ($stmt->rowCount() < 0) {
		return new Response('', 304);
	}
});
$router->route();
