<?php

include_once '../config/config.php';
include_once CLASSES_D . "/Database.class.php";
include_once CLASSES_D . "/Token.class.php";
include_once CLASSES_D . "/User.class.php";
Database::setDBConnection($DB_DSN, $DB_USER, $DB_PASSWORD);

function init() {
	if ($_GET && isset($_GET['token']) && $_GET['token'] != "")
	{
		try {
			User::confirmAccount($_GET['token']);
			Token::deleteToken($_GET['token']);
		} catch (Exception $e) {
			return ('Error: ' . $e->getMessage());
		}
		return ('Your account is now confirmed, you can now log in and enjoy Camagru !');
	}
	else
		return ('Error: Missing token');
}
