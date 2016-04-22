<?php

date_default_timezone_set('UTC');

$app = require(__DIR__ . '/../../app.php');
$app->on('post', function ($request, $services) {
	$currentUID = Session::getCurrentUserID($services, true, 'You must be logged in to modify an event.');

	$updates = array();
	if ($request->params->has('description')) {
		$updates []= '`description` = :description';
	}
	if ($request->params->has('startTime')) {
		$updates []= '`start` = timestamp(date(start), :startTime)';
	}
	if ($request->params->has('endTime')) {
		$updates []= '`end` = timestamp(date(end), :endTime)';
	}
	if ($request->params->has('building')) {
		$updates []= '`building` = :building';
	}
	if ($request->params->has('room')) {
		$updates []= '`room` = :room';
	}
	
	$updateString = '';
	foreach ($updates as $update) {
		if ($updateString === '') {
			$updateString .= " $update";
		} else {
			$updateString .= ", $update";
		}
	}

	$query = "UPDATE Event SET $updateString WHERE `id` = :id AND `ownerID` = :currentUserID LIMIT 1";

	$stmt = $services['pdo']->prepare($query);
	if ($request->params->has('description')) {
		$stmt->bindValue('description', $request->params['description'], PDO::PARAM_STR);
	}
	if ($request->params->has('startTime')) {
		$stmt->bindValue('startTime', $request->params['startTime'], PDO::PARAM_STR);
	} 
	if ($request->params->has('endTime')) {
		$stmt->bindValue('endTime', $request->params['endTime'], PDO::PARAM_STR);
	} 
	if ($request->params->has('building')) {
		$stmt->bindValue('building', $request->params['building'], PDO::PARAM_STR);
	}

	if ($request->params->has('room')) {
		$stmt->bindValue('room', $request->params['room'], PDO::PARAM_STR);
	}
	$stmt->bindValue('id', $request->params->get('id'), PDO::PARAM_INT);
	$stmt->bindValue('currentUserID', $currentUID, PDO::PARAM_INT);
	$stmt->execute();

	if ($stmt->rowCount() < 1) {
		// update failed.	

	}
});

$app->route();

