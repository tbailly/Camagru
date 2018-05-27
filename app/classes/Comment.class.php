<?php

Class Comment {

	public static function addComment($comment, $idImage, $idUser) {
		$query = "INSERT INTO `comment` (`text`, `id_image`, `id_user`)
				VALUES (:comment, :idImage, :idUser)";

		$comment = htmlspecialchars($comment);

		$params = array(
			':comment' 	=> array( $comment, PDO::PARAM_STR ),
			':idImage' 	=> array( (int)$idImage, PDO::PARAM_INT ),
			':idUser' 	=> array( (int)$idUser, PDO::PARAM_INT )
		);

		try {
			if (strlen($comment) > 255)
				throw new Exception("Comment is too long !");
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return ($comment);
	}

	public static function getCommentsByImage($idImage) {
		$query = "SELECT `comment`.*, `image`.*, `user`.`id_user`, `user`.`username`,
				`user`.`firstname`, `user`.`lastname`, `user`.`profile_img` FROM `comment`
					INNER JOIN `image` ON `comment`.`id_image` = `image`.`id_image`
					INNER JOIN `user` ON `comment`.`id_user` = `user`.`id_user`
				WHERE `comment`.`id_image` = :idImage ORDER BY `id_comment`";

		$params = array(
			':idImage' 	=> array( (int)$idImage, PDO::PARAM_INT )
		);

		try {
			$query = Database::newQuery($query, $params);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		$queryResult = $query->fetchAll();
		return ($queryResult);
	}

}
