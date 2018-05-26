<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/Image.class.php';
Database::setDBConnection($DB_DSN, $DB_USER, $DB_PASSWORD);

init();

function init() {
	if ($_POST && isset($_POST['callType']))
	{
		if ($_POST['callType'] == 'getPicturesOfUser' && isset($_POST['skip']) && isset($_POST['limit']))
		{
			getPicturesOfUser($_SESSION['logged_on_user']['id_user'], $_POST['skip'], $_POST['limit']);
		}
		else if ($_POST['callType'] == 'deletePicture' && isset($_POST['idImage']))
		{
			deletePicture($_POST['idImage']);
		}
		else if ($_POST['callType'] == 'setAsProfilePicture' && isset($_POST['b64datas']))
		{
			setAsProfilePicture($_POST['b64datas']);
		}
	}
	else
		echo 'Error: No connected user';
}

function getPicturesOfUser($idUser, $skip, $limit) {
	try {
		$imagesDatas = Image::getImagesOfUser($idUser, $skip, $limit);
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	echo json_encode($imagesDatas);
}

function deletePicture($idImage) {
	try {
		$image = Image::getImage($idImage);
		Image::deleteImage($idImage);
		$fullPath = '../pictures/' . $image['id_user'] . '/' . $image['path'] . '.jpg';
		unlink($fullPath);
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	echo "Image successfully deleted";
}

function setAsProfilePicture($b64datas) {
	$data = base64_decode($b64datas);
	if (!is_dir(PICTURES_D . '/profiles'))
			mkdir(PICTURES_D . '/profiles', 0755, true);
	file_put_contents(PICTURES_D . '/profiles/' . $_SESSION['logged_on_user']['id_user'] . '.jpg', $data);

	try {
		Image::enableProfilePicture($_SESSION['logged_on_user']['id_user']);
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
		return;
	}
	$_SESSION['logged_on_user']['profile_img'] = '1';
	echo "Image successfully set as profile picture";
}