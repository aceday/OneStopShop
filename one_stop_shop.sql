/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `one_stop_shop`;
CREATE DATABASE IF NOT EXISTS `one_stop_shop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `one_stop_shop`;

DROP TABLE IF EXISTS `cart_data`;
CREATE TABLE IF NOT EXISTS `cart_data` (
  `idCart` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT '0',
  `user_id` int DEFAULT '0',
  `quantity` int DEFAULT '0',
  `checkout` tinyint DEFAULT '0',
  `added_datetime` datetime DEFAULT (now()),
  PRIMARY KEY (`idCart`) USING BTREE,
  KEY `fk_cart_prod_id` (`product_id`),
  KEY `fk_cart_user_id` (`user_id`),
  CONSTRAINT `fk_cart_prod_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`idProduct`),
  CONSTRAINT `fk_cart_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`idUser`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `cart_data`;
INSERT INTO `cart_data` (`idCart`, `product_id`, `user_id`, `quantity`, `checkout`, `added_datetime`) VALUES
	(1, 11, 6, 1, 0, '2025-05-19 08:19:26'),
	(3, 11, 6, 1, 0, '2025-05-19 09:06:17'),
	(4, 11, 6, 1, 0, '2025-05-19 09:06:19'),
	(5, 11, 6, 1, 0, '2025-05-19 09:06:20'),
	(6, 11, 6, 1, 0, '2025-05-19 09:06:22'),
	(7, 11, 6, 1, 0, '2025-05-19 09:06:23'),
	(8, 11, 6, 1, 0, '2025-05-19 09:06:25'),
	(9, 11, 6, 1, 0, '2025-05-19 09:42:04'),
	(10, 11, 6, 1, 0, '2025-05-19 09:43:27'),
	(11, 11, 6, 1, 0, '2025-05-19 09:43:36'),
	(12, 11, 6, 1, 0, '2025-05-19 17:29:46'),
	(13, 6, 6, 15, 0, '2025-05-19 17:29:59'),
	(14, 11, 6, 1, 0, '2025-05-19 17:39:10'),
	(15, 12, 1, 1, 0, '2025-05-19 18:02:24'),
	(16, 12, 6, 1, 0, '2025-05-20 14:38:34');

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `idCategory` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) DEFAULT NULL,
  `category_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idCategory`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `categories`;
INSERT INTO `categories` (`idCategory`, `category_name`, `category_description`) VALUES
	(2, 'Food', 'Desc'),
	(3, 'Electronics', ''),
	(4, 'Beauty', ''),
	(5, 'Appliances', 'ii'),
	(7, 'Dairy', 'Good');

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `idInventory` int NOT NULL AUTO_INCREMENT,
  `fromStock` int DEFAULT '0',
  `toStock` int DEFAULT '0',
  `comment` text,
  PRIMARY KEY (`idInventory`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `inventory`;
INSERT INTO `inventory` (`idInventory`, `fromStock`, `toStock`, `comment`) VALUES
	(3, NULL, NULL, NULL),
	(4, 150, 150, 'Product "Sample 3" is added to inventory with quantity of 150'),
	(5, 500, 500, 'ADDED: 500x Sample'),
	(6, 500, 500, 'ADDED: 500x Sample'),
	(7, 1, 1, 'ADDED: 1x Sample 100'),
	(8, 1, 1, 'ADDED: 1x Sample 101'),
	(9, 1, 1, 'ADDED: 1x Sample 102'),
	(10, 1, 1, 'ADDED: 1x Sam 103'),
	(11, 1, 1, 'ADDED: 1x Samsung A12'),
	(12, 1, 1, 'ADDED: 1x Redmi Note 13');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `idOrder` int NOT NULL AUTO_INCREMENT,
  `product_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT 'The multiple entry will be sperated by semicolon',
  `cart_ids` text,
  `status` enum('completed','pending','cancelled','return') DEFAULT NULL,
  `reason` text,
  `name` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `deliver_type` enum('pickup','deliver') DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `payment_type` enum('cod','card','gcash') DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT (now()),
  PRIMARY KEY (`idOrder`) USING BTREE,
  KEY `idx_transactions_name` (`name`) USING BTREE,
  KEY `idx_transactions_payment_type` (`payment_type`) USING BTREE,
  KEY `fk_orders_user_id` (`user_id`),
  CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `orders`;
INSERT INTO `orders` (`idOrder`, `product_ids`, `cart_ids`, `status`, `reason`, `name`, `address`, `deliver_type`, `user_id`, `payment_type`, `contact_no`, `created_at`) VALUES
	(3, '11', NULL, 'pending', NULL, 'Marc', 'Parkway', 'pickup', NULL, 'cod', '0909', '2025-05-24 17:37:23'),
	(4, '11', NULL, 'pending', NULL, 'Alsaki', 'BM', 'pickup', NULL, 'cod', '123', '2025-05-24 17:37:23'),
	(5, '11', NULL, 'pending', NULL, 'Alsaki', 'BM', 'pickup', NULL, 'card', '0909', '2025-05-24 17:37:23'),
	(6, '11', NULL, 'pending', NULL, 'Alsaki', 'BM', 'pickup', NULL, 'cod', '0909', '2025-05-24 17:37:23'),
	(7, '11', NULL, 'pending', NULL, 'aLSAKI', 'KJ', 'pickup', NULL, 'cod', '99', '2025-05-24 17:37:23'),
	(8, '11', NULL, 'pending', NULL, 'Marc', 'Parkway', 'pickup', NULL, 'cod', '0909', '2025-05-24 17:37:23'),
	(9, '11', NULL, 'pending', NULL, 'aLSAKI', 'KJ', 'pickup', NULL, 'cod', '99', '2025-05-24 17:37:23'),
	(10, '11', NULL, 'pending', NULL, 'aLSAKI', 'KJ', 'pickup', NULL, 'cod', '99', '2025-05-24 17:37:23'),
	(11, '11', NULL, 'pending', NULL, 'aLSAKI', 'KJ', 'pickup', NULL, 'cod', '99', '2025-05-24 17:37:23'),
	(12, '11', NULL, 'pending', NULL, 'aLSAKI', 'KJ', 'pickup', NULL, 'cod', '99', '2025-05-24 17:37:23'),
	(13, '11', NULL, 'pending', NULL, 'sam', 'wawe', 'pickup', NULL, 'cod', '09', '2025-05-24 17:37:23'),
	(14, '11', NULL, 'pending', NULL, 'Sample', 'BM', 'pickup', NULL, 'cod', '09088', '2025-05-24 17:37:23'),
	(15, '11', NULL, 'pending', NULL, 'Legit', 'BM', 'pickup', NULL, 'cod', '9898', '2025-05-24 17:37:23'),
	(16, '11', NULL, 'pending', NULL, 'Goods', '09', 'pickup', NULL, 'cod', '09', '2025-05-24 17:37:23'),
	(17, '11', NULL, 'pending', NULL, 'Marc', 'Parkway', 'pickup', 6, 'cod', '0909', '2025-05-24 17:37:23'),
	(18, '12', NULL, 'pending', NULL, 'Marc', 'PPC', 'pickup', NULL, 'cod', '0909123', '2025-05-24 22:05:32'),
	(19, '11', NULL, 'pending', NULL, 'Sam', 'BM', 'pickup', NULL, 'card', '090912', '2025-05-25 11:32:12'),
	(20, '11', NULL, 'pending', NULL, 'Sam', 'M', 'deliver', NULL, 'card', '09', '2025-05-25 11:33:05'),
	(21, '11', NULL, 'pending', NULL, 'Haaha', '98', 'pickup', NULL, 'card', '98', '2025-05-25 11:34:27'),
	(22, '11', NULL, 'pending', NULL, 'Haaha', '98', 'pickup', NULL, 'card', '98', '2025-05-25 11:34:38'),
	(23, '11', NULL, 'pending', NULL, 'Sam', '98', 'pickup', 6, 'cod', '88', '2025-05-25 11:38:34'),
	(24, '11', NULL, 'pending', NULL, 'Sam', '98', 'pickup', 6, 'cod', '88', '2025-05-25 11:38:48');

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `idProduct` int NOT NULL AUTO_INCREMENT,
  `product_code` varchar(50) DEFAULT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `product_category` int DEFAULT NULL,
  `product_price_now` decimal(10,2) DEFAULT NULL,
  `product_price_original` decimal(10,2) DEFAULT NULL,
  `product_description` text,
  `product_quantity` int DEFAULT NULL,
  `product_status` enum('sale','out','preview') DEFAULT NULL,
  `product_default_image` int DEFAULT NULL,
  PRIMARY KEY (`idProduct`) USING BTREE,
  UNIQUE KEY `Index 4` (`product_code`),
  KEY `fk_prod_cate_category_id` (`product_category`),
  KEY `fk_prod_prodImgId` (`product_default_image`),
  CONSTRAINT `fk_prod_cate_category_id` FOREIGN KEY (`product_category`) REFERENCES `categories` (`idCategory`),
  CONSTRAINT `fk_prod_prodImgId` FOREIGN KEY (`product_default_image`) REFERENCES `products_image` (`idProductImg`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `products`;
INSERT INTO `products` (`idProduct`, `product_code`, `product_name`, `product_category`, `product_price_now`, `product_price_original`, `product_description`, `product_quantity`, `product_status`, `product_default_image`) VALUES
	(2, 'SAM-0001', 'Sample', 2, 1.00, 1.00, 'Sample Description', 500, NULL, NULL),
	(3, 'SAM-0002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'SAM-0003', 'Sample 3', NULL, NULL, NULL, NULL, 150, NULL, NULL),
	(5, 'SAM-0005', 'Sample', 2, 1.00, 1.00, 'Sample Description', 500, NULL, NULL),
	(6, 'SAM-0005  ', 'Sample', 2, 1.00, 1.00, 'Sample Description', 500, NULL, NULL),
	(10, 'SAM-0103', 'Sam 103', 2, 1.00, 500.00, 'l;;l', 1, NULL, NULL),
	(11, 'SM-A125F', 'Samsung A12', 3, 8000.00, 8000.00, 'Sale self', 979, NULL, NULL),
	(12, 'RM-2213', 'Redmi Note 13 NFC', 3, 8000.00, 11000.00, '8GB/256GB', 0, NULL, NULL);

DROP TABLE IF EXISTS `products_image`;
CREATE TABLE IF NOT EXISTS `products_image` (
  `idProductImg` int NOT NULL AUTO_INCREMENT,
  `product_image` mediumblob,
  `product_id` int DEFAULT NULL,
  PRIMARY KEY (`idProductImg`) USING BTREE,
  KEY `fk_prodImg_prod_id` (`product_id`),
  CONSTRAINT `fk_prodImg_prod_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`idProduct`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `products_image`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `role_type` enum('admin','employee','standard') DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`idUser`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `users`;
INSERT INTO `users` (`idUser`, `username`, `email`, `password`, `status`, `role_type`, `comment`) VALUES
	(1, 'marc', 'master@localhost', '$2y$10$6Z5vbis0UsP5FvaIWgcpleREMKy3.PsALWVC0imK4Vzlune3oHBA.', 1, 'admin', NULL),
	(2, 'marc', 'user1@localhost', '$2y$10$GU.wkZ3iXb4PgvlTLFGJce9IDwHxxLLFDa6x6z6Avu2VByp12ztJ2', 1, NULL, NULL),
	(3, 'marx', 'user1@localhost', '$2y$10$xYeL0.FD0lgJpLimFH9wH.0ineQVuD6wfTobkGCCc4hV938V3Bho6', 1, NULL, NULL),
	(4, 'marx1', 'marx@marx.com', '$2y$10$p35NZPHzFaFlJpixHj3vvOupZMXVBcIlg1txVtlKKJ9YJsRxAtE7C', 1, NULL, NULL),
	(6, 'mic', 'mic@mic.com', '$2y$10$fvHsa.b/4fuaACkN3NCpD.CApJnlkm3w5CpaszuGTxP6cFk53l5Ou', 1, 'standard', NULL);

DROP TRIGGER IF EXISTS `AFTER_INSERT_PRODUCT`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `AFTER_INSERT_PRODUCT` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    INSERT INTO inventory (idInventory, fromStock, toStock, comment)
    VALUES (NEW.idProduct, NEW.product_quantity, NEW.product_quantity, CONCAT('ADDED: ',NEW.product_quantity,'x ', NEW.product_name));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

DROP TRIGGER IF EXISTS `AFTER_UPDATE_PRODUCT`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `AFTER_UPDATE_PRODUCT` BEFORE INSERT ON `cart_data` FOR EACH ROW BEGIN

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
