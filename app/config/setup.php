<?php

include_once "./config.php";
include_once CLASSES_D . '/Database.class.php';
Database::setDBConnection('mysql:host=' . $DB_HOST . ';charset=utf8', $DB_USER, $DB_PASSWORD);

echo 'Trying to create camagru database, please wait...<br>';

$query = <<<SQL
	SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
	SET AUTOCOMMIT = 0;
	START TRANSACTION;
	SET time_zone = "+00:00";

	-- DROP DATABASE IF EXISTS `$DB_DATABASE`;
	-- CREATE DATABASE IF NOT EXISTS `$DB_DATABASE`;
	USE `$DB_DATABASE`;

	CREATE TABLE `comment` (
	  `id_comment` int(11) NOT NULL,
	  `text` varchar(255) NOT NULL,
	  `id_image` int(11) NOT NULL,
	  `id_user` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `filter` (
	  `id_filter` int(11) NOT NULL,
	  `type` enum('frame','object','classic') NOT NULL,
	  `path` varchar(127) NOT NULL,
	  `full_name` varchar(127) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	INSERT INTO `filter` (`id_filter`, `type`, `path`, `full_name`) VALUES
	(1, 'classic', 'invert(1)', 'Negative'),
	(2, 'object', 'claude-francois', 'Claude François'),
	(3, 'object', 'big-brown-beard', 'Big brown beard'),
	(4, 'object', 'brown-mustache', 'Brown mustache'),
	(5, 'object', 'elvis-hair', 'Elvis hair'),
	(6, 'object', 'marx-beard', 'Marx beard'),
	(7, 'object', 'skyrim-helmet', 'Skyrim helmet'),
	(8, 'object', 'trump-hair', 'Trump hair'),
	(9, 'frame', 'hands-1', 'Head on hands'),
	(10, 'frame', 'hands-2', 'Frame with hands'),
	(11, 'frame', 'grunge-frame', 'Grunge frame'),
	(12, 'object', 'sunglasses', 'Sunglasses'),
	(13, 'classic', 'contrast(2)', 'High contrast'),
	(14, 'classic', 'contrast(0.3)', 'Low contrast'),
	(15, 'classic', 'grayscale(1)', 'Grayscale');

	CREATE TABLE `image` (
	  `id_image` int(11) NOT NULL,
	  `creation_date` datetime NOT NULL,
	  `id_user` int(11) NOT NULL,
	  `path` varchar(127) NOT NULL,
	  `description` varchar(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `like` (
	  `id_like` int(11) NOT NULL,
	  `id_image` int(11) NOT NULL,
	  `id_user` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `token` (
	  `id_token` int(11) NOT NULL,
	  `id_user` int(11) NOT NULL,
	  `token` varchar(255) DEFAULT NULL,
	  `purpose` enum('reset-password','confirm-account') NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE TABLE `user` (
	  `id_user` int(11) NOT NULL,
	  `mail` varchar(254) NOT NULL,
	  `username` varchar(63) NOT NULL,
	  `password` varchar(255) NOT NULL,
	  `firstname` varchar(63) NOT NULL,
	  `lastname` varchar(63) NOT NULL,
	  `profile_img` bit(1) NOT NULL DEFAULT b'0',
	  `account_confirmed` bit(1) NOT NULL DEFAULT b'0',
	  `mail_preference` bit(1) NOT NULL DEFAULT b'1'
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	ALTER TABLE `comment`
	  ADD PRIMARY KEY (`id_comment`),
	  ADD KEY `Comment_fk0` (`id_image`),
	  ADD KEY `Comment_fk1` (`id_user`);

	ALTER TABLE `filter`
	  ADD PRIMARY KEY (`id_filter`);

	ALTER TABLE `image`
	  ADD PRIMARY KEY (`id_image`),
	  ADD KEY `Image_fk0` (`id_user`);

	ALTER TABLE `like`
	  ADD PRIMARY KEY (`id_like`),
	  ADD KEY `Like_fk0` (`id_image`),
	  ADD KEY `Like_fk1` (`id_user`);

	ALTER TABLE `token`
	  ADD PRIMARY KEY (`id_token`),
	  ADD UNIQUE KEY `token` (`token`),
	  ADD KEY `Token_fk0` (`id_user`);

	ALTER TABLE `user`
	  ADD PRIMARY KEY (`id_user`),
	  ADD UNIQUE KEY `mail` (`mail`),
	  ADD UNIQUE KEY `username` (`username`);

	ALTER TABLE `comment`
	  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT;

	ALTER TABLE `filter`
	  MODIFY `id_filter` int(11) NOT NULL AUTO_INCREMENT;

	ALTER TABLE `image`
	  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT;

	ALTER TABLE `like`
	  MODIFY `id_like` int(11) NOT NULL AUTO_INCREMENT;

	ALTER TABLE `token`
	  MODIFY `id_token` int(11) NOT NULL AUTO_INCREMENT;

	ALTER TABLE `user`
	  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

	ALTER TABLE `comment`
	  ADD CONSTRAINT `Comment_fk0` FOREIGN KEY (`id_image`) REFERENCES `image` (`id_image`) ON DELETE CASCADE,
	  ADD CONSTRAINT `Comment_fk1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

	ALTER TABLE `image`
	  ADD CONSTRAINT `Image_fk0` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

	ALTER TABLE `like`
	  ADD CONSTRAINT `Like_fk0` FOREIGN KEY (`id_image`) REFERENCES `image` (`id_image`) ON DELETE CASCADE,
	  ADD CONSTRAINT `Like_fk1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

	ALTER TABLE `token`
	  ADD CONSTRAINT `Token_fk0` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

	COMMIT;

SQL;

try {
	$query = Database::newQuery($query, null);
} catch (Exception $e) {
	echo 'Error: ' . $e->getMessage();
}

echo 'Database camagru successfully created';