<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../src/Router.php');
require_once(__DIR__ . '/../../src/HTTPException.php');

$router  = new Router();
$router->on('post', function ($request, $services) {
		
	$request->params->mustHave('username');
  $request->params->mustHave('password');
		
  $stmt = $services['pdo']->prepare('SELECT `password`, `id` FROM `User` WHERE `username` = :username LIMIT 1');
  $stmt->bindValue('username', $request->params['username'], PDO::PARAM_STR);
  $stmt->execute();
	$stmt->bindColumn('password', $passwordHash, PDO::PARAM_STR);
	$stmt->bindColumn('id',       $userID,       PDO::PARAM_INT);

  if (!$stmt->fetch(PDO::FETCH_BOUND) || !$passwordHash || !password_verify($request->params['password'], $passwordHash)) {
		throw new UnauthorizedException('Login failed.');
  }

	session_start();
	$stmt = $services['pdo']->prepare('UPDATE `Session` SET `active` = 0 WHERE `id` = :sessionID LIMIT 1');
	$stmt->bindValue('sessionID', session_id(), PDO::PARAM_STR);
	$stmt->execute();

	session_regenerate_id();
	$stmt = $services['pdo']->prepare('INSERT INTO `Session` (`id`, `ownerID`, `expiration`, `active`) VALUES (:id, :ownerID, CURRENT_TIMESTAMP() + INTERVAL 1 HOUR, 1)');
	$stmt->bindValue('id', session_id(), PDO::PARAM_STR);
	$stmt->bindValue('ownerID', $userID, PDO::PARAM_INT);
	$stmt->execute();

	if ($stmt->rowCount() < 1) {
		throw new InternalServerException('Failed to register session.');
	}

});
$router->route();
