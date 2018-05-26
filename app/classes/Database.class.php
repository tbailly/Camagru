<?php

Class Database {

	private static $_connection = NULL;

	private function __construct() {
		// Don't use it, there is no instance of this class
	}

	public static function setDBConnection($dsn, $user, $password) {
		if (self::$_connection === NULL) {
			$driverOptions = array(
		        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	    	);

			try {
				self::$_connection = new PDO($dsn, $user, $password, $driverOptions);
			} catch (PDOException $e) {
				throw new Exception($e->getMessage());
			}
		}
	}

	public static function newQuery($queryStr, $paramsBinded) {
		try {
			$query = self::$_connection->prepare($queryStr);

			if ($paramsBinded !== NULL)
			{
				foreach ($paramsBinded as $key => $param) {
					$query->bindValue($key, $param[0], $param[1]);
				}
			}

			$query->execute();
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());
		}
		return ($query);
	}

}
