<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/Token.class.php';
include_once CLASSES_D . '/User.class.php';
include_once CLASSES_D . '/Mail.class.php';

init();

function init() {
	Database::setDBConnection($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
	if ($_POST)
	{
		if ($_POST['callType'] == 'sendMail' && isset($_POST['mail']) && $_POST['mail'] != "")
			requestResetPassword();
		else if ($_POST['callType'] == 'getToken' && isset($_POST['token']) && $_POST['token'] != "")
			getToken($_POST['token']);
		else if ($_POST['callType'] == 'resetPassword' && isset($_POST['password']) && $_POST['password'] != "" &&
				isset($_POST['idUser']) && $_POST['idUser'] != "" && isset($_POST['token']) && $_POST['token'] != "")
			resetPassword($_POST['idUser'], $_POST['password'], $_POST['token']);
		else
			echo "Error: Missing or empty field";
	}
}

function requestResetPassword() {
	try {
		$user = User::getUserByMail($_POST['mail']);
		$token = Token::newToken($user['username'], 'reset-password');
		$mail = new Mail();
		$mail->setReceiver($user);
		$mail->setResetPasswordMessage($token);
		$mail->send();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return;
	}
	echo "We sent you an e-mail with a link to reset your password!";
}

function getToken($token) {
	try {
		$tokenRes = Token::getToken($token);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return;
	}
	echo json_encode($tokenRes);
}

function resetPassword($idUser, $password, $token) {
	try {
		User::updatePasswordById($idUser, $password);
		Token::deleteToken($token);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return;
	}
	echo "Password successfuly reset !";
}