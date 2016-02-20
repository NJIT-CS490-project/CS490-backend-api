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
$parameters = json_decode($requestBody, true);

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

$stmt = $pdo->prepare('INSERT INTO `User` (`username`, `password`) VALUES (:username, :password)');
$stmt->bindValue('username', $parameters['username'], PDO::PARAM_STR);

$password = password_hash($parameters['username'], PASSWORD_DEFAULT);
$stmt->bindValue('password', $password, PDO::PARAM_STR);

try {
    $stmt->execute();
    $success = true;
} catch (Exception $e) {
    $success = false;
}

if ($stmt->rowCount() < 1 || !$success) {
    http_response_code(500);
    echo json_encode([
        'message' => 'Failed to create account.'
    ]);
    die();
}

http_response_code(200);
die();
