<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/User.class.php';

init();

function init() {
	Database::setDBConnection($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
	if ($_SESSION && isset($_SESSION['logged_on_user']))
	{
		unset($_SESSION['logged_on_user']);
		echo "Successfully logged you out";
	}
	else
		echo "Error: No connected user";
}