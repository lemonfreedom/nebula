/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80029 (8.0.29)
 Source Host           : localhost:3306
 Source Schema         : nebula

 Target Server Type    : MySQL
 Target Server Version : 80029 (8.0.29)
 File Encoding         : 65001

 Date: 31/07/2022 14:59:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for nebula_options
-- ----------------------------
DROP TABLE IF EXISTS `nebula_options`;
CREATE TABLE `nebula_options` (
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of nebula_options
-- ----------------------------
BEGIN;
INSERT INTO `nebula_options` (`name`, `value`) VALUES ('allowRegister', '1');
INSERT INTO `nebula_options` (`name`, `value`) VALUES ('description', '又一个博客网站诞生了');
INSERT INTO `nebula_options` (`name`, `value`) VALUES ('smtp', 'a:5:{s:4:\"host\";s:11:\"smtp.qq.com\";s:8:\"username\";s:13:\"226582@qq.com\";s:8:\"password\";s:16:\"revpqsbyoyvucaig\";s:4:\"port\";i:465;s:4:\"name\";s:6:\"Nebula\";}');
INSERT INTO `nebula_options` (`name`, `value`) VALUES ('title', 'Nebula');
COMMIT;

-- ----------------------------
-- Table structure for nebula_posts
-- ----------------------------
DROP TABLE IF EXISTS `nebula_posts`;
CREATE TABLE `nebula_posts` (
  `pid` int unsigned NOT NULL AUTO_INCREMENT,
  `tid` tinyint unsigned NOT NULL,
  `title` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of nebula_posts
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for nebula_terms
-- ----------------------------
DROP TABLE IF EXISTS `nebula_terms`;
CREATE TABLE `nebula_terms` (
  `tid` tinyint unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of nebula_terms
-- ----------------------------
BEGIN;
INSERT INTO `nebula_terms` (`tid`, `name`) VALUES (0, '默认分类');
COMMIT;

-- ----------------------------
-- Table structure for nebula_users
-- ----------------------------
DROP TABLE IF EXISTS `nebula_users`;
CREATE TABLE `nebula_users` (
  `uid` int unsigned NOT NULL AUTO_INCREMENT,
  `role` tinyint unsigned NOT NULL DEFAULT '1',
  `nickname` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of nebula_users
-- ----------------------------
BEGIN;
INSERT INTO `nebula_users` (`uid`, `role`, `nickname`, `username`, `password`, `email`, `token`) VALUES (1, 0, 'admin', 'admin', 'n0wuobcl68hrcd4k3nicqqzfn5ocze0f1105e53a2698e9035fbbb2a160475e32', '226582@qq.com', '7zsew0yp44e2xohcliccy8nhtpa9bcmi');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
