<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../Router.php');

$router  = new Router();
$router->on('post', function ($request, $pdo) {

    $request->params->require('password');
    $request->params->require('username');

    $stmt = $pdo->prepare('SELECT `password` FROM `User` WHERE `username` = :username LIMIT 1');
    $stmt->bindValue('username', $parameters['username'], PDO::PARAM_STR);
    $stmt->execute();
    $passwordHash = $stmt->fetchColumn(0);

    if (!$success || !$passwordHash || !password_verify($parameters['password'], $passwordHash)) {
        throw new Exception('Login failed.');
    }
});
$router->route();
