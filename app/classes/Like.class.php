<?php

Class Like {

	public static function toggleLike($idImage, $idUser) {
		$likes = Array(
			"likesNb" => 0,
			"currentUserLike" => FALSE
		);

		$addLikeQuery = "INSERT INTO `like` (`id_image`, `id_user`)
				VALUES (:idImage, :idUser)";

		$deleteLikeQuery = "DELETE FROM `like`
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
		$query = "SELECT * FROM `like`
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
		$checkLikeQuery = "SELECT * FROM `like`
					INNER JOIN `image` ON `like`.`id_image` = `image`.`id_image`
					INNER JOIN `user` ON `like`.`id_user` = `user`.`id_user`
				WHERE `like`.`id_image` = :idImage AND `like`.`id_user` = :idUser";

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

		$query = "SELECT * FROM `like`
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
