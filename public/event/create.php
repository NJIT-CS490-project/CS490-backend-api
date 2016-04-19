<?php

$app = require(__DIR__ . '/../../app.php');
$app->on('post', function ($request, $services) {
	$currentUID = Session::getCurrentUserID($services, true, 'You must be logged in to create an event.');
	
	$request->params->mustHave(array(
		'name',
		'start',
		'end'
	));

	$stmt = $services['pdo']->prepare('
		INSERT INTO `Event` (
			`name`, 
			`ownerID`, 
			`start`, 
			`end`, 
			`location`,
			`building`,
			`room`
		) VALUES (
			:name,
			:ownerID,
			:start,
			:end,
			:location,
			:building,
			:room
		)'
	);
	$stmt->bindValue('name',     $request->params['name'],     PDO::PARAM_STR);
	$stmt->bindValue('ownerID',  $currentUID,                  PDO::PARAM_INT);
	$stmt->bindValue('start',    $request->params['start'],    PDO::PARAM_STR);
	$stmt->bindValue('end',      $request->params['end'],      PDO::PARAM_STR);
	if ($request->params->has('location')) {
		$stmt->bindValue('location', $request->params['location'], PDO::PARAM_STR);
	} else {
		$stmt->bindValue('location', null, PDO::PARAM_NULL);
	}
	if ($request->params->has('room')) {
		$stmt->bindValue('room', $request->params['room'], PDO::PARAM_STR);
	} else {
		$stmt->bindValue('room', null, PDO::PARAM_NULL);
	}
	if ($request->params->has('building')) {
		$stmt->bindValue('building', $request->params['building'], PDO::PARAM_STR);
	} else {
		$stmt->bindValue('building', null, PDO::PARAM_NULL);
	}
	$stmt->execute();
	if ($stmt->rowCount() < 0) {
		return new Response('', 304);
	}
});
$app->route();
