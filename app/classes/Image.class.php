<?php

Class Image {

	public static function saveImage($idUser, $description) {
		$imageName = uniqid();
		$query = "INSERT INTO `image` (`creation_date`, `id_user`, `path`, `description`)
				VALUES (NOW(), :idUser, :imageName, :description)";

		$params = array(
			':idUser' 		=> array( (int)$idUser, PDO::PARAM_INT ),
			':description' 	=> array( $description, PDO::PARAM_STR ),
			':imageName' 	=> array( $imageName, PDO::PARAM_STR ),
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw $e;
		}
		return ($imageName);
	}
	
	public static function getImages($start, $number) {
		$query = "SELECT * FROM `image`
					INNER JOIN `user` ON `user`.`id_user` = `image`.`id_user`
					ORDER BY `creation_date` DESC
					LIMIT :start, :number";

		$params = array(
			':start' 	=> array( (int)$start, PDO::PARAM_INT ),
			':number' 	=> array( (int)$number, PDO::PARAM_INT )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw $e;
		}
		$queryResult = $query->fetchAll();
		return ($queryResult);
	}

	public static function getImagesOfUser($idUser, $start, $number) {
		$query = "SELECT `image`.* FROM `image`
					INNER JOIN `user` ON `user`.`id_user` = `image`.`id_user`
					WHERE `user`.`id_user` = :id_user
					ORDER BY `image`.`creation_date` DESC LIMIT :start, :number";

		$params = array(
			':id_user' 	=> array( (int)$idUser, PDO::PARAM_INT ),
			':start' 	=> array( (int)$start, PDO::PARAM_INT ),
			':number' 	=> array( (int)$number, PDO::PARAM_INT )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw $e;
		}
		$queryResult = $query->fetchAll();
		return ($queryResult);
	}

	public static function getImage($idImage) {
		$query = "SELECT * FROM `image`
					INNER JOIN `user` ON `user`.`id_user` = `image`.`id_user`
					WHERE `id_image` = :idImage";

		$params = array(
			':idImage' => array( (int)$idImage, PDO::PARAM_INT )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw $e;
		}
		$queryResult = $query->fetch();
		return ($queryResult);
	}

	public static function deleteImage($idImage) {
		$query = "DELETE FROM `image` WHERE `id_image`=:idImage";

		$params = array(
			':idImage' => array( (int)$idImage, PDO::PARAM_INT )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw $e;
		}
	}

	public static function enableProfilePicture($idUser) {
		$query = "UPDATE `user` SET `profile_img`=1 WHERE `id_user`=:idUser";

		$params = array(
			':idUser' => array( (int)$idUser, PDO::PARAM_INT )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw $e;
		}
	}

}
