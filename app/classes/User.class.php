<?php

Class User {

	public static function checkInputs($username, $mail, $pwd, $firstName, $lastName) {
		try {
			$allUsers = self::getAllUsers('username', 'mail');
		} catch (Exception $e) {
			throw $e;
		}

		if (!filter_var($mail, FILTER_VALIDATE_EMAIL) && $mail !== null)
			throw new Exception("Mail is invalid");
		else
		{
			foreach($allUsers as $user)
			{
				if ($user["mail"] === $mail)
					throw new Exception("An account already uses this mail");
			}
		}

		if (preg_match('/^[a-zA-z]+([ \'-][a-zA-Z]+)*$/', $username) !== 1 && $username !== null)
			throw new Exception("Username is invalid");
		else
		{
			foreach($allUsers as $user)
			{
				if ($user["username"] === $username)
					throw new Exception("An account already uses this username");
			}
		}
		
		if (preg_match('/^[a-zA-z]+([ \'-][a-zA-Z]+)*$/', $firstName) !== 1 && $firstName !== null)
			throw new Exception("First name is invalid");
		
		if (preg_match('/^[a-zA-z]+([ \'-][a-zA-Z]+)*$/', $lastName) !== 1 && $lastName !== null)
			throw new Exception("Last name is invalid");

		if ($pwd !== null)
		{
			if (strlen($pwd) < 8)
				throw new Exception("Password too short!");
		    else if (!preg_match('/[0-9]+/', $pwd))
				throw new Exception("Password must include at least one number!");
		    else if (!preg_match('/[A-Z]+/', $pwd))
				throw new Exception("Password must include at least one upper case letter!");
		    else if (!preg_match('/[a-z]+/', $pwd))
				throw new Exception("Password must include at least one lower case letter!");
		}
	}

	public static function newUser($username, $mail, $pwd, $firstName, $lastName) {
		$allUsers = null;

		try {
			self::checkInputs(
				$username,
				$mail,
				$pwd,
				$firstName,
				$lastName
			);
		} catch (Exception $e) {
			throw $e;
		}

		$pwd = hash('whirlpool', $pwd);

		$query = "INSERT INTO `user` (`username`, `mail`, `password`, `firstname`, `lastname`)
				VALUES (:username, :mail, :pwd, :firstName, :lastName)";

		$params = array(
			':username'  => array( $username, PDO::PARAM_STR ),
			':mail' 	 => array( $mail, PDO::PARAM_STR ),
			':pwd' 		 => array( $pwd, PDO::PARAM_STR ),
			':firstName' => array( $firstName, PDO::PARAM_STR ),
			':lastName'  => array( $lastName, PDO::PARAM_STR )
		);
		
		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return ($query);
	}

	public static function deleteAccount($username) {
		$query = "DELETE FROM `user` WHERE `username` = :username";

		$params = array(
			':username' => $username
		);
		
		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return ($query);
	}

	public static function logIn($username, $pwd) {
		$pwd = hash('whirlpool', $pwd);
		$query = "SELECT * FROM `user` WHERE `username` = :username AND `password` = :pwd";

		$params = array(
			':username'  => array( $username, PDO::PARAM_STR ),
			':pwd' 		 => array( $pwd, PDO::PARAM_STR )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		$queryResult = $query->fetch();
		
		if ($queryResult === FALSE)
			throw new Exception("Incorrect username/password");
		if ($queryResult['account_confirmed'] == 0)
			throw new Exception("Your account has not been confirmed yet");
		
		unset($queryResult['password']);
		$_SESSION['logged_on_user'] = $queryResult;
		return ($queryResult);
	}

	public static function signOut() {
		if ($_SESSION && isset($_SESSION['logged_on_user']))
			unset($_SESSION['logged_on_user']);
	}

	public static function confirmAccount($token) {
		$query = "UPDATE `user`
				INNER JOIN `token` ON `user`.`id_user` = `token`.`id_user`
				SET `user`.`account_confirmed` = 1 
				WHERE `token`.`token` = :token";

		$params = array(
			':token'  => array( $token, PDO::PARAM_STR )
		);

		try {
			$query = Database::newQuery($query, $params);
			if ($query->rowCount() === 0)
				throw new Exception("This token is invalid");
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public static function getAllUsers(...$columnsToSelect) {
		$query = "SELECT ";
		$count = 0;
		foreach ($columnsToSelect as $columnName) {
			$query .= $columnName;
			if (($count) + 1 < count($columnsToSelect))
				$query .= ", ";
			$count++;
		}
		$query .= " FROM `user`";

		try {
			$query = Database::newQuery($query, null);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		$queryResult = $query->fetchAll();
		return ($queryResult);
	}

	public static function getUserByMail($mail) {
		$query = "SELECT * FROM `user` WHERE `mail` = :mail";

		$params = array(
			':mail'  => array( $mail, PDO::PARAM_STR )
		);

		try {
			$query = Database::newQuery($query, $params);
			$queryResult = $query->fetch();
			if ($queryResult === FALSE)
				throw new Exception("There is no account registered with this e-mail");
		} catch (Exception $e) {
			throw $e;
		}
		return ($queryResult);
	}

	public static function updateUserDetails($detailsArray) {
		try {
			self::checkInputs(
				$detailsArray['username'],
				$detailsArray['mail'],
				$detailsArray['password'],
				$detailsArray['firstname'],
				$detailsArray['lastname']
			);
		} catch (Exception $e) {
			throw $e;
		}
		$params = array();

		$query = "UPDATE `user` SET";
		if ($detailsArray['mail'] !== null)
		{
			$query .= " `mail`=:mail,";
			$query .= " `account_confirmed`=0,";
			$params[':mail'] = array( $detailsArray['mail'], PDO::PARAM_STR );
		}
		if ($detailsArray['password'] !== null)
		{
			$query .= " `password`=:password,";
			$params[':password'] = array( hash('whirlpool', $detailsArray['password']), PDO::PARAM_STR );
		}
		if ($detailsArray['username'] !== null)
		{
			$query .= " `username`=:username,";
			$params[':username'] = array( $detailsArray['username'], PDO::PARAM_STR );
		}
		if ($detailsArray['firstname'] !== null)
		{
			$query .= " `firstname`=:firstname,";
			$params[':firstname'] = array( $detailsArray['firstname'], PDO::PARAM_STR );
		}
		if ($detailsArray['lastname'] !== null)
		{
			$query .= " `lastname`=:lastname,";
			$params[':lastname'] = array( $detailsArray['lastname'], PDO::PARAM_STR );
		}
		if ($detailsArray['mailPreference'] !== null)
		{
			$query .= " `mail_preference`=:mailPreference,";
			$params[':mailPreference'] = array( $detailsArray['mailPreference'], PDO::PARAM_INT );
		}

		$query = substr($query, 0, -1);
		$query .= " WHERE `id_user`=:id_user";

		$params[':id_user'] = array( $_SESSION['logged_on_user']['id_user'], PDO::PARAM_STR );
		try {
			$query = Database::newQuery($query, $params);
			if ($detailsArray['mail'] !== null)
				$_SESSION['logged_on_user']['mail'] = $detailsArray['mail'];
			if ($detailsArray['password'] !== null)
				$_SESSION['logged_on_user']['password'] = $detailsArray['password'];
			if ($detailsArray['username'] !== null)
				$_SESSION['logged_on_user']['username'] = $detailsArray['username'];
			if ($detailsArray['firstname'] !== null)
				$_SESSION['logged_on_user']['firstname'] = $detailsArray['firstname'];
			if ($detailsArray['lastname'] !== null)
				$_SESSION['logged_on_user']['lastname'] = $detailsArray['lastname'];
			if ($detailsArray['mailPreference'] !== null)
				$_SESSION['logged_on_user']['mail_preference'] = $detailsArray['mailPreference'];
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function updatePasswordById($idUser, $password) {
		try {
			self::checkInputs(null, null, $password, null, null);
			$query = "UPDATE `user`
					SET `password` = :password 
					WHERE `id_user` = :idUser";

			$params = array(
				':password'  => array( hash('whirlpool', $password), PDO::PARAM_STR ),
				':idUser'  => array( $idUser, PDO::PARAM_STR )
			);
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

}