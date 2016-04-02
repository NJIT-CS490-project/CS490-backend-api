<?php

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/HTTPException.php');

class Session {

	public static function getCurrentUserID ($services, $shouldThrow = true, $message = 'Session Invalid.') {
		session_start();
		$stmt = $services['pdo']->prepare('SELECT `ownerID` FROM `Session` WHERE `id` = :sessionID AND `active` = 1');
		$stmt->bindValue('sessionID', session_id(), PDO::PARAM_STR);
		$stmt->execute();
		$currentUID = $stmt->fetchColumn(0);
		if ($currentUID === false) {
			if ($shouldThrow) {
				throw new UnauthorizedException($message);
			} else {
				return false;
			}
		} else {
			return $currentUID;
		}
	}

}
