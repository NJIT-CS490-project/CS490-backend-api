<?php

require_once(__DIR__ . '/../../config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'message' => 'Incorrect request method.'
    ]);
    die();
}

$requestBody = file_get_contents('php://input');
$parameters  = json_decode($requestBody, true);

if (!array_key_exists('username', $parameters)) {
    http_response_code(400);
    echo json_encode([
        'message' => 'Missing parameter: username'
    ]);
    die();
}

if (!array_key_exists('password', $parameters)) {
    http_response_code(400);
    echo json_encode([
        'message' => 'Missing parameter: password'
    ]);
    die();
}

$pdo = new PDO('mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->prepare('SELECT `password` FROM `User` WHERE `username` = :username LIMIT 1');
$stmt->bindValue('username', $parameters['username'], PDO::PARAM_STR);

try {
    $stmt->execute();
    $success = true;
} catch (Exception $e) {
    $success = false;
}

$passwordHash = $stmt->fetchColumn(0);
if (!$success || !$passwordHash || !password_verify($parameters['password'], $passwordHash)) {
    http_response_code(400);
    echo json_encode([
        'message' => 'Failed to log in.'
    ]);
} else {
    http_response_code(200);
}
