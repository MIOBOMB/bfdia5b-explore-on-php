-- Adminer 5.2.1 MySQL 5.7.44 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `authorId` int(11) NOT NULL DEFAULT '0',
  `levelData` varchar(15000) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `difficulty` int(11) NOT NULL DEFAULT '0',
  `featured` int(11) NOT NULL DEFAULT '0',
  `plays` int(11) NOT NULL DEFAULT '0',
  `thumbnail` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `unlisted` int(11) NOT NULL DEFAULT '0',
  `createDate` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `updated` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


DROP TABLE IF EXISTS `packs`;
CREATE TABLE `packs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `authorId` int(11) NOT NULL DEFAULT '0',
  `levels` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `featured` int(11) NOT NULL DEFAULT '0',
  `plays` int(11) NOT NULL DEFAULT '0',
  `stars` int(11) NOT NULL DEFAULT '0',
  `createDate` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `updated` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `isActive` int(11) NOT NULL DEFAULT '0',
  `regDate` int(11) NOT NULL DEFAULT '0',
  `token` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


-- 2025-06-23 09:00:21 UTC
