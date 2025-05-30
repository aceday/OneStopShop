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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `categories`;
INSERT INTO `categories` (`idCategory`, `category_name`, `category_description`) VALUES
	(2, 'Food', 'Desc'),
	(3, 'Electronics', ''),
	(4, 'Beauty', ''),
	(5, 'Appliances', 'ii'),
	(7, 'Dairy', 'Good'),
	(8, 'Dress', 'Clothes for dress people'),
	(11, 'Power', 'Use for power your life');

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `idInventory` int NOT NULL AUTO_INCREMENT,
  `fromStock` int DEFAULT '0',
  `toStock` int DEFAULT '0',
  `comment` text,
  PRIMARY KEY (`idInventory`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
	(12, 1, 1, 'ADDED: 1x Redmi Note 13'),
	(13, 500, 500, 'ADDED: 500x Sample 0001'),
	(14, 45, 45, 'ADDED: 45x Sample 101'),
	(15, 400, 400, 'ADDED: 400x Queen Pantera Tee'),
	(16, 400, 400, 'ADDED: 400x Queen Pantera Tee'),
	(17, 400, 400, 'ADDED: 400x Queen Pantera Tee'),
	(18, 100, 100, 'ADDED: 100x Shock Pants'),
	(19, 500, 500, 'ADDED: 500x Sample 0001'),
	(20, 500, 500, 'ADDED: 500x Sample 0001'),
	(21, 500, 500, 'ADDED: 500x Sample 0001'),
	(22, 500, 500, 'ADDED: 500x Sample 0001'),
	(23, 500, 500, 'ADDED: 500x Sample 0001'),
	(24, 500, 500, 'ADDED: 500x Sample 0001'),
	(25, 500, 500, 'ADDED: 500x Sample 0001'),
	(26, 500, 500, 'ADDED: 500x Sample 0001'),
	(27, 500, 500, 'ADDED: 500x Sample 0001'),
	(28, 500, 500, 'ADDED: 500x Sample 0001'),
	(29, 500, 500, 'ADDED: 500x Sample 0001'),
	(30, 500, 500, 'ADDED: 500x Sample 0001'),
	(31, 10, 10, 'ADDED: 10x Buds'),
	(32, 10, 10, 'ADDED: 10x Bud2'),
	(33, 7, 7, 'ADDED: 7x Buds0003'),
	(34, 500, 500, 'ADDED: 500x Sample 0001'),
	(35, 500, 500, 'ADDED: 500x Sample 0002'),
	(36, 5, 5, 'ADDED: 5x Trial');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `idOrder` int NOT NULL AUTO_INCREMENT,
  `product_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT 'The multiple entry will be sperated by semicolon',
  `cart_ids` text,
  `status` enum('completed','pending','cancelled','return') DEFAULT NULL,
  `reason` text,
  `customer_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `customer_address` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deliver_type` enum('pickup','deliver') DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `payment_type` enum('cod','card','gcash') DEFAULT NULL,
  `customer_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `product_quantity` int NOT NULL DEFAULT (0),
  `claim` tinyint NOT NULL DEFAULT (0),
  `created_at` datetime DEFAULT (now()),
  `checkout_type` enum('buy','cart') DEFAULT NULL,
  `total_payment` decimal(20,2) DEFAULT '0.00',
  PRIMARY KEY (`idOrder`) USING BTREE,
  KEY `idx_transactions_payment_type` (`payment_type`) USING BTREE,
  KEY `fk_orders_user_id` (`user_id`),
  KEY `idx_transactions_name` (`customer_name`) USING BTREE,
  CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `orders`;
INSERT INTO `orders` (`idOrder`, `product_ids`, `cart_ids`, `status`, `reason`, `customer_name`, `customer_address`, `deliver_type`, `user_id`, `payment_type`, `customer_phone`, `product_quantity`, `claim`, `created_at`, `checkout_type`, `total_payment`) VALUES
	(48, '6', NULL, 'pending', NULL, 'Lester', 'Les City', 'pickup', 6, 'cod', '09123456789', 1, 0, '2025-05-30 12:02:41', 'buy', 350.00),
	(49, '12', NULL, 'cancelled', NULL, 'Lester', 'Les', 'pickup', 6, 'cod', '090912334566', 1, 0, '2025-05-30 13:58:15', 'buy', 500.00),
	(50, '12', NULL, 'pending', NULL, 'Les1', 'BMX', 'pickup', 6, 'card', '0909123', 1, 0, '2025-05-30 15:48:09', 'buy', 500.00),
	(51, '12', NULL, 'pending', NULL, 'Les1', 'BMX', 'pickup', 6, 'card', '0909123', 1, 0, '2025-05-30 15:48:40', 'buy', 500.00),
	(52, '12', NULL, 'pending', NULL, '32', 'NX', 'pickup', 6, 'cod', '09023124321', 1, 0, '2025-05-30 15:55:37', 'buy', 500.00),
	(53, '19', NULL, 'pending', NULL, 'Mic2', 'PPC', 'pickup', 6, 'cod', '0909123', 2, 0, '2025-05-30 15:57:09', 'buy', 600.00),
	(54, '12', NULL, 'pending', NULL, 'Lester', 'BM', 'pickup', 6, 'card', '09123456', 1, 0, '2025-05-30 15:58:16', 'buy', 500.00),
	(55, '12', NULL, 'pending', NULL, 'MX', '1234', 'pickup', 6, 'cod', '11', 1, 0, '2025-05-30 16:01:49', 'buy', 500.00),
	(56, '12', NULL, 'pending', NULL, 'Mas', 'BMX', 'pickup', 6, 'cod', '092134', 5, 0, '2025-05-30 16:03:50', 'buy', 2500.00),
	(57, '14', NULL, 'pending', NULL, 'Sir Web', 'San Pedro', 'pickup', 18, 'cod', '09123456789', 18, 0, '2025-05-30 16:24:46', 'buy', 5400.00),
	(58, '10', NULL, 'pending', NULL, 'Intel', 'AMD', 'pickup', 19, 'cod', '09123456789', 8, 0, '2025-05-30 16:41:30', 'buy', 6000.00);

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
  `product_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`idProduct`) USING BTREE,
  UNIQUE KEY `Index 4` (`product_code`),
  KEY `fk_prod_cate_category_id` (`product_category`),
  CONSTRAINT `fk_prod_cate_category_id` FOREIGN KEY (`product_category`) REFERENCES `categories` (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `products`;
INSERT INTO `products` (`idProduct`, `product_code`, `product_name`, `product_category`, `product_price_now`, `product_price_original`, `product_description`, `product_quantity`, `product_status`, `product_image`) VALUES
	(3, 'SAM-0002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 'BW-0001', 'Middle Ground Tanktop', 2, 550.00, 1000.00, 'Type: Tank \r\nColor: Black\r\nSize: Small, Medium, Larg, XL\r\nDescription:\r\n- Silkscreen\r\n- Material: Cotton and Polyester Blend\r\n- Made in the Philippines', 0, NULL, '/data/image/5/1.jpg'),
	(6, 'BW-0002', 'Range Shorts', 8, 350.00, 400.00, 'Type:  Short\r\nColor: White\r\nSize: Small, Medium, Larg, XL\r\nDescription: Fullmax Fabric', 14, NULL, '/data/image/6/2.jpg'),
	(10, 'BW-0003', 'Shock Hoodle', 8, 750.00, 1000.00, 'Type:  Hoodie\r\nColor: Black\r\nSize: Small, Medium, Larg, XL\r\nDescription: Fleece Material', 2, NULL, '/data/image/10/3.jpg'),
	(11, 'BW-0004', 'Lock Loaded Tee', 8, 750.00, 800.00, 'Type:  T-shirt \r\nColor: White\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 15, NULL, '/data/image/11/4.jpg'),
	(12, 'BW-0005', 'Oval Standard Tee', 8, 500.00, 1500.00, 'Type:  T-shirt \r\nColor: Black\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 489, NULL, '/data/image/12/5.jpg'),
	(13, 'BW-0007', 'Dark Route Shorts', 8, 300.00, 500.00, 'Type:  Short\r\nColor: White\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 39, NULL, '/data/image/13/6.jpg'),
	(14, 'BW-0008', 'Outlined Tee', 8, 300.00, 500.00, 'Type:  T-shirt \r\nColor: White, Black\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 12, NULL, '/data/image/14/7.jpg'),
	(15, 'BW-0009', 'Queen Pantera Tee', 8, 400.00, 600.00, 'Type:  T-shirt \r\nColor: Black \r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 300, NULL, '/data/image/15/8.jpg'),
	(18, 'BW-0011', 'Shock Pants', 8, 500.00, 600.00, 'Type:   Pants\r\nColor: Black\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 40, NULL, '/data/image/18/9.jpg'),
	(19, 'BW-0012', 'Skull Race Tee', 8, 300.00, 500.00, 'Type:  T-shirt \r\nColor: Black\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 58, NULL, '/data/image/19/10.jpg'),
	(20, 'BW-0013', 'Natural Blossom Tee', 8, 350.00, 500.00, 'Type:  T-shirt \r\nColor: Black\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 30, NULL, '/data/image/20/11.jpg'),
	(21, 'BW-0014', 'Hope Worship Tee', 8, 550.00, 1000.00, 'Type:  T-shirt \r\nColor: Black\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton', 5, NULL, '/data/image/21/12.jpg'),
	(22, 'BW-0015', ' Majestic Valiant Tee ', 8, 450.00, 500.00, 'Type:  T-shirt \r\nColor: Black\r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 5, NULL, '/data/image/22/13.jpg'),
	(23, 'BW-0016', 'Spiral Vision Tee', 8, 300.00, 500.00, 'Type:  T-shirt \r\nColor: White, Black \r\nSize: Small, Medium, Larg, XL\r\nDescription: Cotton', 10, NULL, '/data/image/23/14.jpg'),
	(24, 'BW-0017', 'Weaponized Shorts', 7, 200.00, 550.00, 'Type:  Short\r\nColor: Black\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton', 15, NULL, '/data/image/24/15.jpg'),
	(25, 'BW-0018', 'Middle ground TankTop', 8, 400.00, 600.00, 'Type:  Tank\r\nColor: Black\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton', 20, NULL, '/data/image/25/16.jpg'),
	(26, 'BW-0019', 'Ebony Long sleeve ', 8, 750.00, 800.00, 'Type:  Long sleeve \r\nColor: Black\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton', 45, NULL, '/data/image/26/17.jpg'),
	(27, 'BW-0020', '13th Blitz Type:  Shirt ', 8, 500.00, 700.00, 'Type:  Shirt \r\nColor: Black Gray\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton', 50, NULL, '/data/image/27/18.jpg'),
	(28, 'BW-0021', 'Whitex Sleeve', 8, 399.00, 500.00, 'Type:  Long Sleeve\r\nColor: White\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton', 35, NULL, '/data/image/28/19.jpg'),
	(29, 'BW-0022', 'Cranium Polo', 8, 499.00, 799.00, 'Type:  Long Sleeve\r\nColor: Black\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton\r\n', 10, NULL, '/data/image/29/20.jpg'),
	(30, 'BW-0023', 'Triple Band Tee', 8, 299.00, 499.00, 'Type:  T-shirt \r\nColor: Black, White, Gray\r\nSize: Small, Medium, Large, XL\r\nDescription: Cotton\r\n', 50, NULL, '/data/image/30/21.jpg'),
	(31, 'B-0001', 'Buds1', 8, 1.00, 1.00, 'Developer', 10000, NULL, '/data/image/31/6.jpg');

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
  `role_type` enum('admin','employee','standard') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'standard',
  `comment` text,
  `user_image` text,
  PRIMARY KEY (`idUser`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DELETE FROM `users`;
INSERT INTO `users` (`idUser`, `username`, `email`, `password`, `status`, `role_type`, `comment`, `user_image`) VALUES
	(1, 'marc', 'master@localhost', '$2y$10$YLzX1VI4HlWail6s1TWv5extNUzo/WrlL5kwlq0/mNU05146nD9KG', 1, 'admin', NULL, '/data/image/user/14.jpg'),
	(2, 'lester', 'webmaster1@local', '$2y$10$U/ziX5DeqkxqvakL9TeN/.CXrLhU1QhwPI5ugyiUHhchiVWCln8Ti', 1, 'employee', NULL, '/data/image/user/14.jpg'),
	(6, 'mic', 'mic@localhost', '$2y$10$F3Sq9uLGyVaMXIqQGws5Qu8G5N.F60dKoWvLmcb8NDYBykwPtu37W', 1, 'standard', NULL, '/data/image/user/5.jpg'),
	(18, 'sir', 'mic@localhost1', '$2y$10$GsCStuZvFzKH3EgwYqfgLeuCDjEmVx/E5PZ1mLZLHbYpMWOSNh146', 1, 'standard', NULL, NULL),
	(19, 'system32', 'system32@localhost', '$2y$10$uS1z1qBK7t6LGIBdwNd6vuw2guvQCT4qLAtIKY6FgvVEXJ7SkEaDq', 1, 'standard', NULL, NULL);

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
