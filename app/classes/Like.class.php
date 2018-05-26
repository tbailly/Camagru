<?php

Class Like {

	public static function toggleLike($idImage, $idUser) {
		$likes = Array(
			"likesNb" => 0,
			"currentUserLike" => FALSE
		);

		$addLikeQuery = "INSERT INTO `Like` (`id_image`, `id_user`)
				VALUES (:idImage, :idUser)";

		$deleteLikeQuery = "DELETE FROM `Like`
				WHERE `id_image` = :idImage AND `id_user` = :idUser";

		$params = array(
			':idImage' => array( (int)$idImage, PDO::PARAM_INT ),
			':idUser' => array( (int)$idUser, PDO::PARAM_INT )
		);

		try {
			if (self::getLikeByImageByUser($idImage, $idUser) === FALSE)
			{
				$query = Database::newQuery($addLikeQuery, $params);
				$likes['currentUserLike'] = TRUE;
			}
			else
				$query = Database::newQuery($deleteLikeQuery, $params);

			$likes['likesNb'] = self::getLikesNb($idImage);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return ($likes);
	}

	public static function getLikesNb($idImage) {
		$query = "SELECT * FROM `Like`
				WHERE `id_image` = :idImage";

		$params = array(
			':idImage' => array( (int)$idImage, PDO::PARAM_INT )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		$queryResult = $query->fetchAll();
		return (count($queryResult));
	}

	public static function getLikeByImageByUser($idImage, $idUser) {
		$checkLikeQuery = "SELECT * FROM `Like`
					INNER JOIN `Image` ON `Like`.`id_image` = `Image`.`id_image`
					INNER JOIN `User` ON `Like`.`id_user` = `User`.`id_user`
				WHERE `Like`.`id_image` = :idImage AND `Like`.`id_user` = :idUser";

		$params = array(
			':idImage' => array( (int)$idImage, PDO::PARAM_INT ),
			':idUser' => array( (int)$idUser, PDO::PARAM_INT )
		);

		try {
			$checkLikeQuery = Database::newQuery($checkLikeQuery, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

		$checkLikeResult = $checkLikeQuery->fetch();
		if ($checkLikeResult === FALSE)
			return (FALSE);
		else
			return (TRUE);
	}

	public static function getLikesByImage($idImage, $idUser) {
		$likes = Array();

		$query = "SELECT * FROM `Like`
					INNER JOIN `image` ON `like`.`id_image` = `image`.`id_image`
				WHERE `like`.`id_image` = :idImage";

		$params = array(
			':idImage' => array( (int)$idImage, PDO::PARAM_INT )
		);
		
		try {
			$query = Database::newQuery($query, $params);
			if ($idUser === null || self::getLikeByImageByUser($idImage, $idUser) === FALSE)
				$likes['currentUserLike'] = FALSE;
			else
				$likes['currentUserLike'] = TRUE;
			$likes['likesNb'] = self::getLikesNb($idImage);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

		return ($likes);
	}

}
