<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../src/Router.php');
require_once(__DIR__ . '/../../src/HTTPException');

$router = new Router();
$router->on('get', function ($request, $services) {

	$sql = '
	SELECT 
		`id`, 
		`name`, 
		`ownerID`, 
		`start`, 
		`end`, 
		`location` 
	FROM `Event` 
	WHERE `name` LIKE "%:query%"';

	$stmt = $services['pdo']->prepare($sql);
	$stmt->bindValue('query', $request['query'], PDO::PARAM_STR);
	$stmt->execute();

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($result) {
		return $result;
	} else {
		throw new InternalServerError('Failed to search events.');
	}
});
$router->route();
