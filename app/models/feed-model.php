<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/Image.class.php';
include_once CLASSES_D . '/Comment.class.php';
include_once CLASSES_D . '/Like.class.php';
include_once CLASSES_D . '/Mail.class.php';
Database::setDBConnection($DB_DSN, $DB_USER, $DB_PASSWORD);

init();

function init() {
	if ($_POST && isset($_POST['callType']))
	{
		if ($_POST['callType'] == 'getPictures' && isset($_POST['skip']) && isset($_POST['limit']))
		{
			getPictures($_POST['skip'], $_POST['limit']);
		}
		else if ($_POST['callType'] == 'getCommentsAndLikes' && isset($_POST['idImage']))
		{
			if ($_SESSION && isset($_SESSION['logged_on_user']))
				getCommentsAndLikes($_POST['idImage'], $_SESSION['logged_on_user']['id_user']);
			else
				getCommentsAndLikes($_POST['idImage'], null);
		}
		else if ($_POST['callType'] == 'addComment' && isset($_POST['idImage'])
				&& isset($_POST['comment']) && $_SESSION && isset($_SESSION['logged_on_user']))
		{
			addComment($_POST['comment'], $_POST['idImage'], $_SESSION['logged_on_user']['id_user']);
		}
		else if ($_POST['callType'] == 'toggleLike' && isset($_POST['idImage'])
				&& $_SESSION && isset($_SESSION['logged_on_user']))
		{
			toggleLike($_POST['idImage'], $_SESSION['logged_on_user']['id_user']);
		}
	}
	else
		echo 'Error: No connected user';
}


function getPictures($skip, $limit) {
	try {
		$imagesDatas = Image::getImages($skip, $limit);
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	echo json_encode($imagesDatas);
}

function getCommentsAndLikes($idImage, $idUser) {
	$commentsLikesDatas = array(
		'comments' => null,
		'likes' => null,
		'userConnected' => FALSE
	);
	if ($idUser !== null)
		$commentsLikesDatas['userConnected'] = TRUE;
	try {
		$commentsLikesDatas['comments'] = Comment::getCommentsByImage($idImage);
		$commentsLikesDatas['likes'] = Like::getLikesByImage($idImage, $idUser);
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	echo json_encode($commentsLikesDatas);
}

function addComment($comment, $idImage, $idUser) {
	$user = array(
		'id_user' => $_SESSION['logged_on_user']['id_user'],
		'firstname' => $_SESSION['logged_on_user']['firstname'],
		'lastname' => $_SESSION['logged_on_user']['lastname'],
		'username' => $_SESSION['logged_on_user']['username'],
		'profile_img' => (int)$_SESSION['logged_on_user']['profile_img']
	);
	try {
		$comment = Comment::addComment($comment, $idImage, $idUser);
		// $poster = Image::getPosterOfImageById($idImage);
		$poster = Image::getImage($idImage);
		if ($_SESSION['logged_on_user']['id_user'] != $poster['id_user'] && $poster['mail_preference'] === '1')
		{
			$mail = new Mail;
			$mail->setReceiver($poster);
			$mail->setSender($_SESSION['logged_on_user']);
			$mail->setNotificationMessage();
			$mailSent = $mail->send();
			if ($mailSent === FALSE)
				throw new Exception('Failed to send mail to picture owner');
		}
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	$datas = array(
		'user' => $user,
		'comment' => $comment
	);
	echo json_encode($datas);
}

function toggleLike($idImage, $idUser) {
	$user = array(
		'id_user' => $_SESSION['logged_on_user']['id_user'],
		'firstname' => $_SESSION['logged_on_user']['firstname'],
		'lastname' => $_SESSION['logged_on_user']['lastname'],
		'username' => $_SESSION['logged_on_user']['username'],
		'profile_img' => (int)$_SESSION['logged_on_user']['profile_img']
	);
	try {
		$likes = Like::toggleLike($idImage, $idUser);
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	$res = Array(
		'user' => $user,
		'likes' => $likes
	);
	echo json_encode($res);
}