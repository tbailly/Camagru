<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/User.class.php';
include_once CLASSES_D . '/Token.class.php';
include_once CLASSES_D . '/Mail.class.php';
Database::setDBConnection($DB_DSN, $DB_USER, $DB_PASSWORD);

init();

function init() {
	if ($_POST && isset($_POST['mail']) && isset($_POST['username']) && isset($_POST['firstname']) &&
		isset($_POST['lastname']) && isset($_POST['password']) && isset($_POST['mailPreference']))
	{
		cleanPostDatas();
		try {
			if ($_POST['username'] !== null || $_POST['firstname'] !== null || $_POST['lastname'] !== null ||
				$_POST['mail'] !== null || $_POST['password'] !== null || $_POST['mailPreference'] !== null)
				User::updateUserDetails($_POST);
			if ($_POST['mail'] !== null)
			{
				$token = Token::newToken($_SESSION['logged_on_user']['username'], 'confirm-account');
				$mail = new Mail;
				$mail->setReceiver($_SESSION['logged_on_user']);
				$mail->setConfirmationMessage($token);
				$mail->send();
			}
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return;
		}
		echo 'Personal informations successfully updated';
	}
	else
	{
		echo 'Error: Datas missing';
	}
}

function cleanPostDatas() {
	if ($_SESSION['logged_on_user']['mail'] == $_POST['mail'] || strlen($_POST['mail']) === 0)
		$_POST['mail'] = null;
	if ($_SESSION['logged_on_user']['username'] == $_POST['username'] || strlen($_POST['username']) === 0)
		$_POST['username'] = null;
	if ($_SESSION['logged_on_user']['firstname'] == $_POST['firstname'] || strlen($_POST['firstname']) === 0)
		$_POST['firstname'] = null;
	if ($_SESSION['logged_on_user']['lastname'] == $_POST['lastname'] || strlen($_POST['lastname']) === 0)
		$_POST['lastname'] = null;
	if (strlen($_POST['password']) === 0)
		$_POST['password'] = null;
	$_POST['mailPreference'] = ($_POST['mailPreference'] == 'true') ? 1 : 0;
}
