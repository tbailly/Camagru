<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/User.class.php';

init();

function init() {
	Database::setDBConnection($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
	if (isset($_POST['usernameOrMail']) && $_POST['usernameOrMail'] != "" &&
		isset($_POST['password']) && $_POST['password'] != "")
	{
		try {
			$status = User::logIn($_POST["usernameOrMail"], $_POST["password"]);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return;
		}
		session_regenerate_id();
		echo "Welcome back " . $_SESSION['logged_on_user']['username'] . "!";
	}
	else
		echo "Error: Missing or empty field";
}