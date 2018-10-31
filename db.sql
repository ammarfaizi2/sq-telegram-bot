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


DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `point` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `point` (`point`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tasks` (`id`, `name`, `point`) VALUES
(1, 'Join Group', 15000),
(2, 'Join Channel', 15000),
(3, 'Follow and Retweet Twitter', 15000),
(4, 'Follow and Like Facebook', 15000),
(5, 'Follow Medium',  10000);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(72) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `wallet` varchar(255) DEFAULT NULL,
  `balance` double DEFAULT '0',
  `joined_at` datetime DEFAULT NULL,
  `started_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `eth_address` (`wallet`),
  KEY `first_name` (`name`),
  KEY `joined_at` (`joined_at`),
  KEY `email` (`email`),
  KEY `username` (`username`),
  KEY `started_at` (`started_at`),
  KEY `balance` (`balance`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users_task`;
CREATE TABLE `users_task` (
  `user_id` bigint(20) NOT NULL,
  `task_id` int(11) NOT NULL,
  `taskhash` varchar(128) NOT NULL,
  `point` double NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `users_task_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_task_ibfk_4` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `web_admin`;
CREATE TABLE `web_admin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `web_admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Admin',  'admin',  '$argon2i$v=19$m=1024,t=2,p=2$dW9XcndZTXFyaUd6Rm9jWg$3Z8ww/FZN/eHaH/AKrHsiX5JX3/zufenWAwsQQx0f9o');

-- 2018-10-31 11:09:50
