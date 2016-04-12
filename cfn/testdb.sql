/* db setup */
CREATE DATABASE `flickrer_test` DEFAULT CHARACTER SET = `utf8`;
USE `flickrer_test`;

DROP TABLE IF EXISTS `recent_searches`;
CREATE TABLE `recent_searches` (
  `id` bigint(32) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(32) NOT NULL,
  `search_params` varchar(500) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(32) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL DEFAULT '',
  `passhash` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(250) DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

grant all on flickrer_test.* to dev@localhost identified by 'pass123';
