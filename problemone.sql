/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : problemone

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 18/08/2024 09:10:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for form_fields
-- ----------------------------
DROP TABLE IF EXISTS `form_fields`;
CREATE TABLE `form_fields`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `form_id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `validation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `email_send` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `form_id`(`form_id` ASC) USING BTREE,
  CONSTRAINT `form_fields_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of form_fields
-- ----------------------------
INSERT INTO `form_fields` VALUES (1, 1, 'input', 'Name', '{\"required\":\"1\",\"minlength\":\"2\",\"maxlength\":\"6\",\"pattern\":\"\"}', 1);
INSERT INTO `form_fields` VALUES (2, 1, 'textarea', 'Address', '{\"required\":\"1\",\"minlength\":\"\",\"maxlength\":\"\",\"pattern\":\"\"}', 0);

-- ----------------------------
-- Table structure for forms
-- ----------------------------
DROP TABLE IF EXISTS `forms`;
CREATE TABLE `forms`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of forms
-- ----------------------------
INSERT INTO `forms` VALUES (1, 'Test Form');

-- ----------------------------
-- Table structure for submissions
-- ----------------------------
DROP TABLE IF EXISTS `submissions`;
CREATE TABLE `submissions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `form_id` int NOT NULL,
  `submission_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `form_id`(`form_id` ASC) USING BTREE,
  CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of submissions
-- ----------------------------
INSERT INTO `submissions` VALUES (1, 1, '{\"form_id\":\"1\",\"Name\":\"Asim\",\"Address\":\"qewqw\"}');

SET FOREIGN_KEY_CHECKS = 1;
