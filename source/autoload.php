<?php

$requirements = array(
	'Curl.php',
	'Database.php',
	'HTTPException.php',
	'Request.php',
	'Response.php',
	'Router.php',
	'Session.php',
	'Util.php'
);

foreach ($requirements as $requirement) {
	require_once(__DIR__ . '/' . $requirement);
}
