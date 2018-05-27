<?php

Class Token {

	public static function newToken($username, $purpose) {
		$token = md5(uniqid($username . uniqid(), true));

		$deleteQuery = "DELETE FROM `token`
						WHERE `id_token`
						IN (
							SELECT tk.`id_token` FROM (SELECT * FROM `token`) AS tk
							INNER JOIN `user` ON tk.`id_user` = `user`.`id_user`
							WHERE `user`.`username` = :username AND `token`.`purpose` = :purpose
						);";
		$paramsDel = array(
			':username' => array( $username, PDO::PARAM_STR ),
			':purpose' => array( $purpose, PDO::PARAM_STR )
		);

		$insertQuery = "INSERT INTO `token` (`id_user`, `token`, `purpose`)
						VALUES(
							(SELECT `id_user` FROM `user` WHERE `username` = :username),
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
		$query = "DELETE FROM `token` WHERE `token` = :token";

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
		$query = "SELECT * FROM `token` WHERE `token` = :token";

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