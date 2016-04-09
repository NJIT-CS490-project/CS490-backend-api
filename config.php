<?php

$configPath = __DIR__ . '/config.json';
$configData = file_get_contents($configPath);
$config     = json_decode($configData, true);

if (array_key_exists('_ini', $config)) {
	foreach ($config['_ini'] as $key => $value) {
		ini_set($key, $value);
	}
}

if (array_key_exists('_php', $config)) {
	$_php = $config['_php'];
	if (array_key_exists('error_reporting', $_php)) {
		$value = 0;
		switch ($_php['error_reporting']) {
			case 'E_WARNING':           $value = E_WARNING;           break;
			case 'E_PARSE':             $value = E_PARSE;             break;
			case 'E_NOTICE':            $value = E_NOTICE;            break;
			case 'E_CORE_ERROR':        $value = E_CORE_ERROR;        break;
			case 'E_CORE_WARNING':      $value = E_CORE_WARNING;      break;
			case 'E_USER_ERROR':        $value = E_USER_ERROR;        break;
			case 'E_USER_NOTICE':       $value = E_USER_NOTICE;       break;
			case 'E_STRICT':            $value = E_STRICT;            break;
			case 'E_RECOVERABLE_ERROR': $value = E_RECOVERABLE_ERROR; break;
			case 'E_DEPRECATED':        $value = E_DEPRECATED;        break;
			case 'E_USER_DEPRECATED':   $value = E_USER_DEPRECATED;   break;
			case 'E_ALL':               $value = E_ALL;               break;
			default:                    $value = 0;                   break;
		}
		error_reporting($value);
	}
}

if (array_key_exists('_define', $config)) {
	foreach ($config['_define'] as $key => $value) {
		define($key, $value);
	}
}

return $config;
