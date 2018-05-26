<?php

Class Token {

	public static function newToken($username, $purpose) {
		$token = md5(uniqid($username . uniqid(), true));

		$deleteQuery = "DELETE FROM `Token`
						WHERE `id_token`
						IN (
							SELECT tk.`id_token` FROM (SELECT * FROM `Token`) AS tk
							INNER JOIN `User` ON tk.`id_user` = `user`.`id_user`
							WHERE `User`.`username` = :username AND `Token`.`purpose` = :purpose
						);";
		$paramsDel = array(
			':username' => array( $username, PDO::PARAM_STR ),
			':purpose' => array( $purpose, PDO::PARAM_STR )
		);

		$insertQuery = "INSERT INTO `Token` (`id_user`, `token`, `purpose`)
						VALUES(
							(SELECT `id_user` FROM `User` WHERE `username` = :username),
							:token,
							:purpose
						)";

		$paramsIns = array(
			':username' => array( $username, PDO::PARAM_STR ),
			':token' => array( $token, PDO::PARAM_STR ),
			':purpose' => array( $purpose, PDO::PARAM_STR )
		);

		try {
			$deleteQuery = Database::newQuery($deleteQuery, $paramsDel);
			$insertQuery = Database::newQuery($insertQuery, $paramsIns);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return ($token);
	}

	public static function deleteToken($token) {
		$query = "DELETE FROM `Token` WHERE `token` = :token";

		$params = array(
			':token' => array( $token, PDO::PARAM_STR )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function getToken($token) {
		$query = "SELECT * FROM `Token` WHERE `token` = :token";

		$params = array(
			':token' => array( $token, PDO::PARAM_STR )
		);

		try {
			$query = Database::newQuery($query, $params);
			$queryResult = $query->fetch();
			if (!$queryResult)
				throw new Exception("Invalid token");
		} catch (Exception $e) {
			throw $e;
		}
		return ($queryResult);
	}

}