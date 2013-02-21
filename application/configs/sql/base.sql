/*
 Navicat MySQL Data Transfer

 Source Server         : Localhost
 Source Server Version : 50157
 Source Host           : localhost
 Source Database       : fa_bdd2

 Target Server Version : 50157
 File Encoding         : utf-8

 Date: 09/18/2011 17:05:20 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `categories`
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `position` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `drawing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  `value` text NOT NULL,
  `note` int(11) NOT NULL,
  `state_id` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `drawing_id` (`drawing_id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `user_id_3` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`drawing_id`) REFERENCES `drawings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1934 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `drawings`
-- ----------------------------
DROP TABLE IF EXISTS `drawings`;
CREATE TABLE `drawings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `view` int(11) NOT NULL DEFAULT '0',
  `trash` tinyint(1) NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `drawings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1857 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `files`
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `fk_name` varchar(255) NOT NULL,
  `fk_id` varchar(40) NOT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`guid`),
  KEY `fk_name` (`fk_name`),
  KEY `fk_id` (`fk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `subcategories`
-- ----------------------------
DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE `subcategories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `name` text NOT NULL,
  `name_url` text NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4034 DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- ----------------------------
--  Table structure for `trackings`
-- ----------------------------
DROP TABLE IF EXISTS `trackings`;
CREATE TABLE `trackings` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `system_version` varchar(255) NOT NULL,
  `localized_model` varchar(255) NOT NULL,
  `locale` varchar(6) NOT NULL,
  `network` varchar(255) NOT NULL,
  `launch` int(11) NOT NULL,
  `last_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(45) NOT NULL,
  `civility` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(5) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `telephone` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL DEFAULT '',
  `birthday` date DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `description` text,
  `login_url` varchar(255) NOT NULL,
  `validate` varchar(45) NOT NULL,
  `last_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=397 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

SET FOREIGN_KEY_CHECKS = 1;
