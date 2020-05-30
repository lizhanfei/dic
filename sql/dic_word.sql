/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50646
 Source Host           : localhost:3306
 Source Schema         : dic

 Target Server Type    : MySQL
 Target Server Version : 50646
 File Encoding         : 65001

 Date: 30/05/2020 15:29:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for dic_word
-- ----------------------------
DROP TABLE IF EXISTS `dic_word`;
CREATE TABLE `dic_word` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(50) NOT NULL COMMENT '词',
  `from_system` varchar(50) NOT NULL COMMENT '来源系统标识',
  `type` varchar(50) NOT NULL COMMENT '词语类型',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`from_system`,`type`,`word`) USING BTREE COMMENT '一个来源系统的同一种类型的一个词只可以有一条'
) ENGINE=InnoDB AUTO_INCREMENT=425989 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
