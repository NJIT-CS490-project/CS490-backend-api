<?php

require_once(__DIR__ . '/autoload.php');
$config = require(__DIR__ . '/config.php');

$router = new Router();
$router->register('pdo', new Database($config['mysql'], array(
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
)));
$router->register('config', $config);
return $router;
