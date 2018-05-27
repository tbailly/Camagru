<?php

session_start();
include_once '../config/config.php';
include_once CLASSES_D . '/Database.class.php';
include_once CLASSES_D . '/Image.class.php';
include_once CLASSES_D . '/User.class.php';
include_once CLASSES_D . '/Filter.class.php';

init();

function init() {
	Database::setDBConnection($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
	if ($_POST)
	{
		if ($_POST['callType'] == 'pictureMontage')
			pictureMontage();
		else if ($_POST['callType'] == 'savePicture')
			savePicture();
		else if ($_POST['callType'] == 'getFilters')
			getFilters();
	}
}

function pictureMontage() {
	if ($_POST && isset($_POST['jsonDatas']))
	{
		$datas = json_decode($_POST['jsonDatas']);
		$main = $datas->main;
		if (!($mainImage = imagecreatefromstring(base64_decode($main->b64datas))))
			exit("Error: Invalid datas, can't create image");

		if ($datas->classicFilter)
			applyClassicFilter($mainImage, $datas->classicFilter);

		foreach ($datas->filters as $key => $filter)
		{
			if (!($originalFilterImage = imagecreatefrompng(FILTERS_D . "/" . $filter->path . ".png")))
				exit("Error: Invalid datas, can't create image");
			imagealphablending($originalFilterImage, false);
			imagesavealpha($originalFilterImage, true);
			$pngTransparency = imagecolorallocatealpha($originalFilterImage , 0, 0, 0, 127);

			if ($filter->width > imagesx($originalFilterImage))
				$filter->width = imagesx($originalFilterImage);
			$filterImage = imagescale($originalFilterImage, (1280 * $filter->width / $main->width));

			$filterImageOriginalSize = array(
				"width" => imagesx($filterImage),
				"height" => imagesy($filterImage),
			);

			$filterImage = imagerotate($filterImage, -$filter->rotation, $pngTransparency);

			$filterImageAfterRotationSize = array(
				"width" => imagesx($filterImage),
				"height" => imagesy($filterImage),
			);
			
			$coeff = $filterImageOriginalSize['width'] / imagesx($mainImage);
			$diffW = $filterImageAfterRotationSize['width'] - $filterImageOriginalSize['width'];
			$diffH = $filterImageAfterRotationSize['height'] - $filterImageOriginalSize['height'];
			if ($datas->classicFilter)
				applyClassicFilter($filterImage, $datas->classicFilter);

			$filter->left *= 1280 / $main->width;
			$filter->left -= ($diffW / 2);
			$filter->top *= 1280 / $main->width;
			$filter->top -= ($diffH / 2);

			imagecopy(
				$mainImage,
				$filterImage,
				$filter->left,
				$filter->top,
				0,
				0,
				imagesx($filterImage),
				imagesy($filterImage)
			);
		}
	}
	ob_start();
	imagejpeg($mainImage, NULL, 90);
	$data = ob_get_clean();
	$b64 = base64_encode($data);
	echo $b64;
}

function applyClassicFilter($image, $contrastStr) {
	$effectType = explode('(', $contrastStr)[0];
	$effectValue = explode(')', explode('(', $contrastStr)[1])[0];
	switch ($effectType) {
		case 'contrast':
			if ($effectValue == '2')
				imagefilter($image, IMG_FILTER_CONTRAST, -50);
			else if ($effectValue == '0.3')
				imagefilter($image, IMG_FILTER_CONTRAST, 50);
			break;
		case 'grayscale':
			imagefilter($image, IMG_FILTER_GRAYSCALE);
			break;
		case 'invert':
			imagefilter($image, IMG_FILTER_NEGATE);
			break;
	}
}

function savePicture() {
	if (isset($_POST['pictureDatas']) && isset($_POST['description']))
	{
		$id_user = $_SESSION['logged_on_user']['id_user'];
		$data = base64_decode($_POST['pictureDatas']);
		try {
			$imageName = Image::saveImage($id_user, htmlentities($_POST['description']));
		} catch (Exception $e) {
			echo $e->getMessage();
			return;
		}
		if (!is_dir(PICTURES_D . '/' . $id_user))
			mkdir(PICTURES_D . '/' . $id_user, 0755, true);
		file_put_contents(PICTURES_D . '/' . $id_user . '/' . $imageName . '.jpg', $data);
		echo 'Photo successfully shared';
	}
	else if (isset($_POST['pictureDatas']))
	{
		echo 'Error: Your photo need a description';
	}
}

function getFilters() {
	if (isset($_POST['type']) && $_POST['type'] != "")
	{
		$type = $_POST['type'];
		try {
			$filters = Filter::getFilters($_POST["type"]);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
			return;
		}
		echo json_encode($filters);
	}
	else
		echo "Error: Missing type of filters";
}
