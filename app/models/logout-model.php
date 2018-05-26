<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/User.class.php';
Database::setDBConnection($DB_DSN, $DB_USER, $DB_PASSWORD);

init();

function init() {
	if ($_SESSION && isset($_SESSION['logged_on_user']))
	{
		unset($_SESSION['logged_on_user']);
		echo "Successfully logged you out";
	}
	else
		echo "Error: No connected user";
}