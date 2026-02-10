/*
 Navicat Premium Data Transfer

 Source Server         : 00 - local
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3306
 Source Schema         : db_araklangkir

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 09/02/2026 16:54:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `fullname` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` smallint NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `createdby` bigint NULL DEFAULT NULL,
  `updated` datetime NULL DEFAULT NULL,
  `updatedby` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Admin', 'admin', '771def87d33cda670ee19cfeebbcdda161f8fdf455ad99b2a37f7c82676646e0a80865df529b87114e29bdd96fe04f243005d64caa52238115671ba7c4ae444arpa3mTGjUe4wzjgcnsx2R0qryetqu6aXvo+Q0KLrISc=', 1, '2026-02-07 09:03:22', NULL, '2026-02-07 14:27:57', 1);


-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `product_code` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `product_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `uom` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `price` decimal(18, 0) NULL DEFAULT NULL,
  `status` smallint NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `createdby` bigint NULL DEFAULT NULL,
  `updated` datetime NULL DEFAULT NULL,
  `updatedby` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES (1, 'AB15', 'Arak Bali 15%', '600 ML', 30000, 1, '2026-02-07 15:05:06', 1, '2026-02-07 15:05:15', 1);
INSERT INTO `product` VALUES (2, 'AB25', 'Arak Bali 25%', '600 ML', 35000, 1, '2026-02-07 15:07:00', 1, NULL, NULL);

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ip_address` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `timestamp` int NOT NULL,
  `data` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('0193a4ea5063bbd9d4a94b8234ab097e0d721066', '::1', 1770630799, 'auth|a:10:{s:2:\"id\";s:1:\"1\";s:8:\"fullname\";s:5:\"Admin\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:172:\"771def87d33cda670ee19cfeebbcdda161f8fdf455ad99b2a37f7c82676646e0a80865df529b87114e29bdd96fe04f243005d64caa52238115671ba7c4ae444arpa3mTGjUe4wzjgcnsx2R0qryetqu6aXvo+Q0KLrISc=\";s:6:\"status\";s:1:\"1\";s:7:\"created\";s:19:\"2026-02-07 09:03:22\";s:9:\"createdby\";N;s:7:\"updated\";s:19:\"2026-02-07 14:27:57\";s:9:\"updatedby\";s:1:\"1\";s:7:\"expired\";i:1770634399;}');

-- ----------------------------
-- Table structure for stock
-- ----------------------------
DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `product_id` bigint NOT NULL,
  `stock_year` int NOT NULL,
  `stock_month` int NOT NULL,
  `ending_stock` decimal(18, 0) NULL DEFAULT NULL,
  `created` datetime NULL DEFAULT NULL,
  `createdby` bigint NULL DEFAULT NULL,
  `updated` datetime NULL DEFAULT NULL,
  `updatedby` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of stock
-- ----------------------------
INSERT INTO `stock` VALUES (1, 1, 2026, 2, 500, '2026-02-09 14:19:08', 1, '2026-02-09 16:21:31', 1);

-- ----------------------------
-- Table structure for tr_detail
-- ----------------------------
DROP TABLE IF EXISTS `tr_detail`;
CREATE TABLE `tr_detail`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `header_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `qty` decimal(18, 0) NOT NULL,
  `price` decimal(12, 0) NOT NULL DEFAULT 0,
  `status` smallint NOT NULL,
  `created` datetime NULL DEFAULT NULL,
  `updated` datetime NULL DEFAULT NULL,
  `createdby` bigint NOT NULL,
  `updatedby` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Fk_product`(`product_id`) USING BTREE,
  CONSTRAINT `Fk_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_detail
-- ----------------------------
INSERT INTO `tr_detail` VALUES (1, 1, 1, 1000, 30000, 3, '2026-02-09 14:19:00', '2026-02-09 16:18:13', 1, 1);
INSERT INTO `tr_detail` VALUES (2, 2, 1, 500, 30000, 3, '2026-02-09 16:21:17', '2026-02-09 16:21:31', 1, 1);

-- ----------------------------
-- Table structure for tr_header
-- ----------------------------
DROP TABLE IF EXISTS `tr_header`;
CREATE TABLE `tr_header`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tr_id` smallint NOT NULL,
  `tr_date` date NOT NULL,
  `tr_number` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `status` smallint NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NULL DEFAULT NULL,
  `createdby` bigint NULL DEFAULT NULL,
  `updatedby` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Fk_users`(`createdby`) USING BTREE,
  CONSTRAINT `Fk_users` FOREIGN KEY (`createdby`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_header
-- ----------------------------
INSERT INTO `tr_header` VALUES (1, 1, '2026-02-09', '260209010001', 'Barang Masuk', 3, '2026-02-09 14:19:00', '2026-02-09 16:18:13', 1, 1);
INSERT INTO `tr_header` VALUES (2, 2, '2026-02-09', '260209020001', 'Keluar', 3, '2026-02-09 16:21:17', '2026-02-09 16:21:31', 1, 1);

SET FOREIGN_KEY_CHECKS = 1;
