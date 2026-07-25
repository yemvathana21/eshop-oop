CREATE DATABASE IF NOT EXISTS `eshop_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `eshop_db`;

-- 1. Categories (with parent_id for hierarchy)
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `icon` VARCHAR(50) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `parent_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`, `parent_id`) VALUES
(1, 'Electronics', 'electronics', 'fa-laptop', 10, NULL),
(2, 'Smartphones', 'smartphones', 'fa-mobile-screen', 11, 1),
(3, 'Tablets', 'tablets', 'fa-tablet-screen-button', 12, 1),
(4, 'Audio', 'audio', 'fa-headphones', 13, 1),
(5, 'Fashion', 'fashion', 'fa-shirt', 20, NULL),
(6, 'Men', 'men', 'fa-user', 21, 5),
(7, 'Women', 'women', 'fa-user', 22, 5),
(8, 'Kids', 'kids', 'fa-child', 23, 5),
(9, 'Home & Office', 'home-office', 'fa-couch', 30, NULL),
(10, 'Furniture', 'furniture', 'fa-chair', 31, 9),
(11, 'Lighting', 'lighting', 'fa-lightbulb', 32, 9),
(12, 'Gaming', 'gaming', 'fa-gamepad', 40, NULL),
(13, 'Consoles', 'consoles', 'fa-tv', 41, 12),
(14, 'PC Gaming', 'pc-gaming', 'fa-desktop', 42, 12),
(15, 'Accessories', 'accessories', 'fa-bag-shopping', 50, NULL),
(16, 'Bags & Cases', 'bags-cases', 'fa-suitcase', 51, 15),
(17, 'Peripherals', 'peripherals', 'fa-keyboard', 52, 15);

-- 2. Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'customer') DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'System Admin', 'admin@eshop.com', '$2y$10$EVg9jzPfKLprhFbc3ho6WeRFTtY7ge8gLY4MwuzDUer4Wwku478cK', 'admin'),
(2, 'John Doe', 'john@gmail.com', '$2y$10$7OoPWE1OZrXsRtKQpB/jdeKEI4cmJhPrc3HeHoOgIrMXERUjY8jau', 'customer');

-- 3. Products (now using subcategory IDs)
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `compare_price` DECIMAL(10, 2) DEFAULT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `category_id` INT NULL,
  `image` VARCHAR(255) NULL,
  `gallery_images` TEXT NULL,
  `specifications` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`id`, `name`, `description`, `price`, `compare_price`, `stock`, `category_id`, `image`) VALUES
(1, 'Webcam HD 1080p', 'High definition webcam for video calls.', 49.99, 59.99, 30, 17, 'webcam.jpg'),
(2, 'Macbook Bag', 'Premium water-repellent laptop bag.', 29.99, 39.99, 50, 16, 'macbag.jpg'),
(3, 'Professional Headphones', 'High-quality studio monitor headphones.', 149.00, 199.00, 15, 4, 'kas.jpg'),
(4, 'Studio Monitor Headphones', 'Over-ear studio monitor headphones for professional audio work.', 199.00, 249.00, 10, 4, 'Studio Monitor Headphones.jpg'),
(5, 'Minimalist Wallet', 'Slim and durable minimalist wallet.', 25.00, 35.00, 100, 16, 'Minimalist Wallet.jpg'),
(6, 'Classic Sunglasses', 'Stylish sunglasses for all occasions.', 15.00, 25.00, 80, 6, 'Classic Sunglasses.jpg'),
(7, 'Ergonomic Office Chair', 'Comfortable chair for long work hours.', 189.00, 250.00, 10, 10, 'chair.png'),
(8, 'LED Desk Lamp', 'Adjustable brightness desk lamp.', 35.00, 45.00, 25, 11, 'LED Desk Lamp.jpg'),
(9, 'Gaming Controller', 'Wireless controller for PlayStation and PC.', 59.00, 69.00, 20, 13, 'game.jpg'),
(10, 'Mechanical Keyboard', 'RGB backlit mechanical keyboard.', 79.00, 99.00, 12, 14, 'key.png'),
(11, 'Wireless Mouse', 'High precision wireless optical mouse.', 19.00, 29.00, 50, 17, 'mouse.jpg'),
(12, 'USB-C Hub Adapter', '7-in-1 multi-port adapter.', 39.00, 49.00, 35, 17, 'USB-C Hub Adapter.jpg'),
(13, 'Portable Power Bank', '20000mAh fast charging power bank.', 45.00, 55.00, 60, 2, 'Portable Power Bank 20000mAh.jpg'),
(14, 'Cable Management Box', 'Keep your desk tidy and organized.', 19.99, 25.00, 50, 16, 'Cable Management Box.jpg');

-- 4. Orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `total_price` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('pending', 'completed', 'cancelled') DEFAULT 'completed',
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Order Items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `rating` TINYINT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Wishlist
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
