смотри у меня вот такая вот база данных
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.0.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: shoes_store
-- ------------------------------------------------------
-- Server version	12.0.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`,`size`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_order` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `order_items` VALUES
(1,2,6,'36',1,5490.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('ordered','processing','shipped','delivered') DEFAULT 'ordered',
  `delivery_address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `orders` VALUES
(1,3,0.00,'ordered','Г бор улица фрунзе д 26 кв 60','89101335645','встретит собака','2025-11-24 04:27:45'),
(2,3,5490.00,'ordered','Г бор улица фрунзе д 26 кв 60','9101335645','встретит собака','2025-11-24 04:39:35');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `products` VALUES
(1,'Кроссовки Nike Air Max','Nike',6990.00,'Удобные и стильные кроссовки для повседневной носки.','uploads/products/1.jpg',10,'2025-11-10 11:21:10'),
(2,'Кеды Adidas Superstar','Adidas',4990.00,'Классика уличной моды.','uploads/products/2.jpg',5,'2025-11-10 11:21:10'),
(3,'Ботинки Skechers D\'lites','Skechers',5490.00,'Стильные и удобные ботинки для города.','uploads/products/3.jpg',7,'2025-11-10 11:21:10'),
(4,'Кроссовки Nike Air Max','Nike',6990.00,'Удобные и стильные кроссовки для повседневной носки.','uploads/products/1.jpg',10,'2025-11-19 11:50:22'),
(5,'Кеды Adidas Superstar','Adidas',4990.00,'Классика уличной моды.','uploads/products/2.jpg',5,'2025-11-19 11:50:22'),
(6,'Ботинки Skechers D\'lites','Skechers',5490.00,'Стильные и удобные ботинки для города.','uploads/products/3.jpg',7,'2025-11-19 11:50:22'),
(7,'Кроссовки Puma RS-X','Puma',7990.00,'Модные кроссовки с ярким дизайном.','uploads/products/4.jpg',12,'2025-11-19 11:50:22'),
(8,'Кеды Converse Chuck Taylor','Converse',3990.00,'Классические высокие кеды.','uploads/products/5.jpg',15,'2025-11-19 11:50:22'),
(9,'Кроссовки New Balance 574','New Balance',8990.00,'Комфортные кроссовки для повседневной носки.','uploads/products/6.jpg',8,'2025-11-19 11:50:22'),
(10,'Ботинки Timberland 6-inch','Timberland',12990.00,'Зимние ботинки премиум-класса.','uploads/products/1.jpg',3,'2025-11-19 11:50:22'),
(11,'Кроссовки Reebok Classic','Reebok',5990.00,'Классический дизайн для активного образа жизни.','uploads/products/2.jpg',9,'2025-11-19 11:50:22'),
(12,'Сникеры Vans Old Skool','Vans',4490.00,'Стильные сникеры для улицы.','uploads/products/3.jpg',11,'2025-11-19 11:50:22'),
(13,'Кроссовки Asics Gel-Kayano','Asics',10990.00,'Кроссовки для бега с амортизацией.','uploads/products/4.jpg',6,'2025-11-19 11:50:22'),
(14,'Кеды DC Shoes Court Graffik','DC Shoes',5290.00,'Скейтбординг и уличный стиль.','uploads/products/5.jpg',4,'2025-11-19 11:50:22'),
(15,'Кроссовки Fila Disruptor','Fila',6490.00,'Модная модель с массивной подошвой.','uploads/products/6.jpg',7,'2025-11-19 11:50:22'),
(16,'Ботинки Timberland 6-inch','Timberland',12990.00,'Зимние ботинки премиум-класса.','uploads/products/7.jpg',3,'2025-11-19 11:55:15'),
(17,'Кроссовки Reebok Classic','Reebok',5990.00,'Классический дизайн для активного образа жизни.','uploads/products/8.jpg',9,'2025-11-19 11:55:15'),
(18,'Сникеры Vans Old Skool','Vans',4490.00,'Стильные сникеры для улицы.','uploads/products/9.jpg',11,'2025-11-19 11:55:15'),
(19,'Кроссовки Asics Gel-Kayano','Asics',10990.00,'Кроссовки для бега с амортизацией.','uploads/products/10.jpg',6,'2025-11-19 11:55:15'),
(20,'Кеды DC Shoes Court Graffik','DC Shoes',5290.00,'Скейтбординг и уличный стиль.','uploads/products/11.jpg',4,'2025-11-19 11:55:15'),
(21,'Кроссовки Fila Disruptor','Fila',6490.00,'Модная модель с массивной подошвой.','uploads/products/12.jpg',5,'2025-11-19 11:55:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Admin','admin@admin.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,NULL,'admin','2025-11-10 11:21:10'),
(2,'Яшин Дмитрий Иванович','dima10yashin@gmail.com','$2y$12$dgJuFh7BqGwIbDrvxkcsFu9ukhR5i5QVgp0cR0eEH8deLNK2A1wCK',NULL,NULL,'user','2025-11-15 08:43:29'),
(3,'Дмитрий','dima1010yashin@gmail.com','$2y$12$ihotZ3JtK8/19EXAXD4v3Oxn6RZ3.SENkr0X2bs7yh4zhIeRlYMfu',NULL,NULL,'user','2025-11-22 13:04:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-11-26 11:19:19
