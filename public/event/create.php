<?php

date_default_timezone_set('UTC');

$app = require(__DIR__ . '/../../app.php');
$app->on('post', function ($request, $services) {
	if ($request->params->get('njit', false) !== true) {
		$currentUID = Session::getCurrentUserID($services, true, 'You must be logged in to create an event.');
	} else {
		$currentUID = 1;
	}

	if (!$request->params->has('events')) {
		$request->params->mustHave(array(
			'name',
			'start',
			'end'
		));
		$event = array(
			'name' => $request->params['name'],
			'start' => $request->params['start'],
			'end'   => $request->params['end']
		);
		if ($request->params->has('description')) {
			$event['description'] = $request->params['description'];
		}
		if ($request->params->has('building')) {
			$event['building'] = $request->params['building'];
		}
		if ($request->params->has('room')) {
			$event['room'] = $request->params['room'];
		}
		$request->params['events'] = array($event);
	}

	$stmt = $services['pdo']->prepare('
		INSERT INTO `Event` (
			`name`, 
			`ownerID`, 
			`start`, 
			`end`, 
			`description`,
			`building`,
			`room`,
			`fromNJIT`
		) VALUES (
			:name,
			:ownerID,
			:start,
			:end,
			:description,
			:building,
			:room,
			:fromNJIT
		)'
	);

	foreach ($request->params['events'] as $event) {
		$stmt->bindValue('name',    $event['name'],  PDO::PARAM_STR);
		$stmt->bindValue('ownerID', $currentUID,     PDO::PARAM_INT);
		if (gettype($event['start']) === 'integer') {
			$start = date('Y-m-d H:i:s', $event['start']);
			$stmt->bindValue('start',   $start, PDO::PARAM_STR);
		} else {
			$stmt->bindValue('start', $event['start'], PDO::PARAM_STR);
		}
		if (gettype($event['end']) === 'integer') {
			$end = date('Y-m-d H:i:s', $event['end']);
			$stmt->bindValue('end',     $end,   PDO::PARAM_STR);
		} else {
			$stmt->bindValue('end',     $event['end'],   PDO::PARAM_STR);
		}
		if (array_key_exists('description', $event)) {
			$stmt->bindValue('description', $event['description'], PDO::PARAM_STR);
		} else {
			$stmt->bindValue('description', null, PDO::PARAM_NULL);
		}
		if (array_key_exists('room', $event)) {
			$stmt->bindValue('room', $event['room'], PDO::PARAM_STR);
		}	else {
			$stmt->bindValue('room', null, PDO::PARAM_NULL);
		}
		if (array_key_exists('building', $event)) {
			$stmt->bindValue('building', $event['building'], PDO::PARAM_STR);
		} else {
			$stmt->bindValue('building', null, PDO::PARAM_NULL);
		}
		if ($currentUID === 1) {
			$stmt->bindValue('fromNJIT', 1, PDO::PARAM_INT);
		} else {
			$stmt->bindValue('fromNJIT', 0, PDO::PARAM_INT);
		}
		$stmt->execute();
		if ($stmt->rowCount() < 0) {
			// return new Response('', 304);
		}
	}
});
$app->route();
