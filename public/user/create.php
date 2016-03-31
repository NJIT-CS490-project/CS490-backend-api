<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../Router.php');
require_once(__DIR__ . '/../HTTPException.php');

$router = new Router();
$router->on('post', function ($request, $services) {
	$request->params->mustHave('username');
	$request->params->mustHave('password');

	$stmt = $services['pdo']->prepare('INSERT INTO `User` (`username`, `password`) VALUES (:username, :password)');
	$stmt->bindValue('username', $request->params['username'], PDO::PARAM_STR);
	$password = password_hash($request->params['password'], PASSWORD_DEFAULT);
	$stmt->bindValue('password', $password, PDO::PARAM_STR);
	try {
		$stmt->execute();
	} catch (PDOException $e) {
		if (-1 !== strpos($e->getMessage(), 'Integrity constraint violation')) {
			throw new ConflictExistsException('Username taken.');
		}
	}
	if ($stmt->rowCount() < 1) {
	  throw new ConflictExistsException('Username taken.');
	}
});
$router->route();

