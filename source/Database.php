<?php

class Database extends PDO {
	
	public function __construct ($config, $options = array()) {
		$uri = self::buildURI('mysql', $config);
		return parent::__construct(
			$uri, 
			$config['user'], 
			$config['pass'], 
			$options
		);
	}

	public static function buildURI ($type, $config) {
		switch ($type) {
			case 'mysql':
				return 
					'mysql:host=' . $config['host'] 
					. ';dbname='  . $config['schema'];
			default:
				throw new Exception('This feature has not been implemented.');
		}
	}
}
