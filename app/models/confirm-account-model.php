<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . "/Database.class.php";
include_once CLASSES_D . "/Token.class.php";
include_once CLASSES_D . "/User.class.php";

init();

function init() {
	Database::setDBConnection($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
	if ($_POST && isset($_POST['token']) && $_POST['token'] != "" && $_POST['token'] != "undefined")
		confirmAccount();
	else
		echo 'Error: Missing token';
}

function confirmAccount() {
	try {
		User::confirmAccount($_POST['token']);
		Token::deleteToken($_POST['token']);
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	echo 'Your account is now confirmed, you can now log in and enjoy Camagru !';
}