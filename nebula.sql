/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : localhost:3306
 Source Schema         : nebula

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 28/07/2022 08:57:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for nebula_metas
-- ----------------------------
DROP TABLE IF EXISTS `nebula_metas`;
CREATE TABLE `nebula_metas`  (
  `mid` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`mid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nebula_metas
-- ----------------------------
INSERT INTO `nebula_metas` VALUES (0, '默认分类');

-- ----------------------------
-- Table structure for nebula_options
-- ----------------------------
DROP TABLE IF EXISTS `nebula_options`;
CREATE TABLE `nebula_options`  (
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nebula_options
-- ----------------------------
INSERT INTO `nebula_options` VALUES ('allowRegister', '1');
INSERT INTO `nebula_options` VALUES ('description', '又一个博客网站诞生了');
INSERT INTO `nebula_options` VALUES ('smtp', 'a:5:{s:4:\"host\";s:11:\"smtp.qq.com\";s:8:\"username\";s:13:\"226582@qq.com\";s:8:\"password\";s:16:\"revpqsbyoyvucaig\";s:4:\"port\";i:465;s:4:\"name\";s:6:\"Nebula\";}');
INSERT INTO `nebula_options` VALUES ('title', 'Nebula');

-- ----------------------------
-- Table structure for nebula_posts
-- ----------------------------
DROP TABLE IF EXISTS `nebula_posts`;
CREATE TABLE `nebula_posts`  (
  `pid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mid` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nebula_posts
-- ----------------------------

-- ----------------------------
-- Table structure for nebula_users
-- ----------------------------
DROP TABLE IF EXISTS `nebula_users`;
CREATE TABLE `nebula_users`  (
  `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `nickname` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nebula_users
-- ----------------------------
INSERT INTO `nebula_users` VALUES (1, 1, 'admin', 'admin', 'ciybkc88f05meqaulgc1mzlwg9nohkewff9346ae29a24f9f05c0953839cd5933', '226582@qq.com', 'xaf5f7ys9oihiaorpvr2zc4z0kmipacp');

SET FOREIGN_KEY_CHECKS = 1;
