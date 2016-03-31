<?php

require_once(__DIR__ . '/../config.php');

require_once(__DIR__ . '/../src/Request.php');
require_once(__DIR__ . '/../src/Map.php');

$request = Request::generate();
echo json_encode($request);

$map = new Map([
    'foo' => 'bar'
]);
