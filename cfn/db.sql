/* db setup */
DROP DATABASE IF EXISTS `flickrer`;
CREATE DATABASE `flickrer` DEFAULT CHARACTER SET = `utf8`;
USE `flickrer`;

DROP TABLE IF EXISTS `recent_searches`;

CREATE TABLE `recent_searches` (
  `id` bigint(32) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(32) NOT NULL,
  `search_params` varchar(500) NOT NULL DEFAULT '',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `updated_at` timestamp DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(32) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL DEFAULT '',
  `passhash` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(250) DEFAULT '',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `updated_at` timestamp DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `passhash` (`passhash`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

grant all on flickrer.* to dev@localhost identified by 'pass123';
