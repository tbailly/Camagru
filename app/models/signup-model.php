<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/Token.class.php';
include_once CLASSES_D . '/User.class.php';
include_once CLASSES_D . '/Mail.class.php';
Database::setDBConnection($DB_DSN, $DB_USER, $DB_PASSWORD);

init();

function init() {
	if (isset($_POST['username']) && $_POST['username'] != "" &&
		isset($_POST['mail']) && $_POST['mail'] != "" &&
		isset($_POST['password']) && $_POST['password'] != "")
	{
		try {
			$status = User::newUser($_POST["username"], $_POST["mail"], $_POST["password"], $_POST["firstname"], $_POST["lastname"]);
			$token = Token::newToken($_POST["username"], 'confirm-account');
			$mail = new Mail;
			$mail->setReceiver($_POST);
			$mail->setConfirmationMessage($token);
			$mail->send();
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return;
		}
		session_regenerate_id();
		echo "Your account was successfully created!<br>We sent you an e-mail to confirm your account";
	}
	else
		echo "Error: Missing or empty field";
}