-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `user_id` bigint(20) NOT NULL,
  `state` smallint(3) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `state` (`state`),
  CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` int(255) DEFAULT NULL,
  `username` varchar(72) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `eth_address` varchar(255) DEFAULT NULL,
  `joined_at` datetime DEFAULT NULL,
  `started_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `eth_address` (`eth_address`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `joined_at` (`joined_at`),
  KEY `email` (`email`),
  KEY `username` (`username`),
  KEY `started_at` (`started_at`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `web_admin`;
CREATE TABLE `web_admin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `web_admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Admin',  'admin',  '$argon2i$v=19$m=1024,t=2,p=2$dW9XcndZTXFyaUd6Rm9jWg$3Z8ww/FZN/eHaH/AKrHsiX5JX3/zufenWAwsQQx0f9o');

-- 2018-10-28 13:53:00
