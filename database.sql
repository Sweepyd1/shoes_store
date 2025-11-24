-- --------------------------------------------------------
-- База данных: `shoes_store`
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `shoes_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shoes_store`;

-- --------------------------------------------------------
-- Таблица `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20),
  `address` text,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
-- Таблица `products`
-- --------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image` varchar(255),
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
-- Таблица `orders`
-- --------------------------------------------------------

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('ordered','processing','shipped','delivered') DEFAULT 'ordered',
  `delivery_address` text,
  `phone` varchar(20),
  `comment` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Таблица `order_items`
-- --------------------------------------------------------

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_order` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Добавление администратора (логин: admin@admin.com, пароль: admin123)
-- --------------------------------------------------------

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- --------------------------------------------------------
-- Добавление тестовых товаров
-- --------------------------------------------------------

INSERT INTO `products` (`name`, `brand`, `price`, `description`, `stock`) VALUES
('Кроссовки Nike Air Max', 'Nike', 6990.00, 'Удобные и стильные кроссовки для повседневной носки.', 10),
('Кеды Adidas Superstar', 'Adidas', 4990.00, 'Классика уличной моды.', 5),
('Ботинки Skechers D\'lites', 'Skechers', 5490.00, 'Стильные и удобные ботинки для города.', 7);