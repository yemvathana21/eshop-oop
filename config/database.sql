CREATE DATABASE IF NOT EXISTS `eshop_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `eshop_db`;

-- =============================================================================
-- Drop all tables in reverse dependency order (to satisfy FK constraints)
-- =============================================================================
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `user_addresses`;
DROP TABLE IF EXISTS `villages`;
DROP TABLE IF EXISTS `communes`;
DROP TABLE IF EXISTS `districts`;
DROP TABLE IF EXISTS `provinces`;
DROP TABLE IF EXISTS `shipping_costs`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `product_color`;
DROP TABLE IF EXISTS `colors`;
DROP TABLE IF EXISTS `product_size`;
DROP TABLE IF EXISTS `sizes`;
DROP TABLE IF EXISTS `wishlist`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `categories`;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- 1. Categories (with parent_id for hierarchy)
-- =============================================================================
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
-- Level 1: Top Level Categories
(1, 'Men', 'men', 'fa-person', 10, NULL),
(2, 'Women', 'women', 'fa-person-dress', 20, NULL),
(3, 'Kids', 'kids', 'fa-child', 30, NULL),
(4, 'Electronics', 'electronics', 'fa-laptop', 40, NULL),
(5, 'Health & Household', 'health-household', 'fa-house-medical', 50, NULL),

-- Level 2: Mid Level Categories (Parent: Men - ID: 1)
(6, 'Men Accessories', 'men-accessories', 'fa-circle-o', 1, 1),
(7, 'Men\'s Shoes', 'men-shoes', 'fa-circle-o', 2, 1),
(8, 'Bottoms', 'bottoms-men', 'fa-circle-o', 3, 1),
(9, 'T-shirts & Shirts', 't-shirts-shirts-men', 'fa-circle-o', 4, 1),

-- Level 3: End Level Categories (Parent: Men Accessories - ID: 6)
(10, 'Headwear', 'headwear-men', 'fa-circle-o', 1, 6),
(11, 'Sunglasses', 'sunglasses-men', 'fa-circle-o', 2, 6),
(12, 'Watches', 'watches-men', 'fa-circle-o', 3, 6),
(13, 'Belts', 'belts-men', 'fa-circle-o', 4, 6),
-- Level 3: End Level Categories (Parent: Men's Shoes - ID: 7)
(14, 'Sandals', 'sandals-men', 'fa-circle-o', 1, 7),
(15, 'Boots', 'boots-men', 'fa-circle-o', 2, 7),
(16, 'Sports Shoes', 'sports-shoes-men', 'fa-circle-o', 3, 7),
(17, 'Casual Shoes', 'casual-shoes-men', 'fa-circle-o', 4, 7),
-- Level 3: End Level Categories (Parent: Bottoms - ID: 8)
(18, 'Pants', 'pants-men', 'fa-circle-o', 1, 8),
(19, 'Jeans', 'jeans-men', 'fa-circle-o', 2, 8),
(20, 'Joggers', 'joggers-men', 'fa-circle-o', 3, 8),
-- Level 3: End Level Categories (Parent: T-shirts & Shirts - ID: 9)
(21, 'T-shirts', 't-shirts-men', 'fa-circle-o', 1, 9),
(22, 'Casual Shirts', 'casual-shirts-men', 'fa-circle-o', 2, 9),
(23, 'Formal Shirts', 'formal-shirts-men', 'fa-circle-o', 3, 9),

-- Level 2: Mid Level Categories (Parent: Women - ID: 2)
(24, 'Beauty Products', 'beauty-products', 'fa-circle-o', 1, 2),
(25, 'Accessories', 'accessories-women', 'fa-circle-o', 2, 2),
(26, 'Shoes', 'shoes-women', 'fa-circle-o', 3, 2),
(27, 'Clothing', 'clothing-women', 'fa-circle-o', 4, 2),

-- Level 3: End Level Categories (Parent: Beauty Products - ID: 24)
(28, 'Fragrance', 'fragrance-beauty', 'fa-circle-o', 1, 24),
(29, 'Skincare', 'skincare-beauty', 'fa-circle-o', 2, 24),
(30, 'Hair Care', 'hair-care-beauty', 'fa-circle-o', 3, 24),
(31, 'Lips', 'lips-beauty', 'fa-circle-o', 4, 24),
-- Level 3: End Level Categories (Parent: Accessories - ID: 25)
(32, 'Watches', 'watches-women', 'fa-circle-o', 1, 25),
(33, 'Jewellery', 'jewellery-women', 'fa-circle-o', 2, 25),
(34, 'Bags', 'bags-women', 'fa-circle-o', 3, 25),
-- Level 3: End Level Categories (Parent: Shoes - ID: 26)
(35, 'Sandals', 'sandals-women', 'fa-circle-o', 1, 26),
(36, 'Pumps', 'pumps-women', 'fa-circle-o', 2, 26),
(37, 'Sneakers', 'sneakers-women', 'fa-circle-o', 3, 26),
-- Level 3: End Level Categories (Parent: Clothing - ID: 27)
(38, 'Dresses', 'dresses-women', 'fa-circle-o', 1, 27),
(39, 'Tops', 'tops-women', 'fa-circle-o', 2, 27),
(40, 'Pants & Leggings', 'pants-leggings-women', 'fa-circle-o', 3, 27),

-- Level 2: Mid Level Categories (Parent: Kids - ID: 3)
(41, 'Clothing', 'kids-clothing', 'fa-circle-o', 1, 3),
(42, 'Shoes', 'kids-shoes', 'fa-circle-o', 2, 3),
-- Level 3: End Level Categories (Parent: Clothing - ID: 41)
(43, 'Boys', 'boys-clothing', 'fa-circle-o', 1, 41),
(44, 'Girls', 'girls-clothing', 'fa-circle-o', 2, 41),

-- Level 2: Mid Level Categories (Parent: Electronics - ID: 4)
(45, 'Electronic Items', 'electronic-items', 'fa-circle-o', 1, 4),
(46, 'Computers', 'computers', 'fa-circle-o', 2, 4),
-- Level 3: End Level Categories (Parent: Electronic Items - ID: 45)
(47, 'Phones', 'phones-accessories', 'fa-circle-o', 1, 45),
(48, 'Headphones', 'headphones-electronics', 'fa-circle-o', 2, 45),
(49, 'TV & Video', 'tv-video-electronics', 'fa-circle-o', 3, 45),
-- Level 3: End Level Categories (Parent: Computers - ID: 46)
(50, 'Laptops', 'laptops-computers', 'fa-circle-o', 1, 46),
(51, 'Accessories', 'laptop-accessories', 'fa-circle-o', 2, 46),

-- Level 2: Mid Level Categories (Parent: Health & Household - ID: 5)
(52, 'Health', 'health-mid', 'fa-circle-o', 1, 5),
(53, 'Household', 'household-mid', 'fa-circle-o', 2, 5),
-- Level 3: End Level Categories (Parent: Health - ID: 52)
(54, 'Medical Supplies', 'medical-supplies', 'fa-circle-o', 1, 52),
(55, 'Oral Care', 'oral-care', 'fa-circle-o', 2, 52),
-- Level 3: End Level Categories (Parent: Household - ID: 53)
(56, 'Baby Care', 'baby-care', 'fa-circle-o', 1, 53),
(57, 'Supplies', 'household-supplies', 'fa-circle-o', 2, 53);

-- =============================================================================
-- 2. Users
-- =============================================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'customer') DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'System Admin', 'admin@eshop.com', '$2y$10$EVg9jzPfKLprhFbc3ho6WeRFTtY7ge8gLY4MwuzDUer4Wwku478cK', 'admin'),
(2, 'John Doe', 'john@gmail.com', '$2y$10$7OoPWE1OZrXsRtKQpB/jdeKEI4cmJhPrc3HeHoOgIrMXERUjY8jau', 'customer');

-- =============================================================================
-- 3. Products
-- =============================================================================
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
(1, 'LED Desk Lamp', 'Adjustable LED desk lamp with multiple brightness levels and touch control.', 25.00, 35.00, 50, 57, 'LED Desk Lamp.jpg'),
(2, 'Canvas Sneakers', 'Comfortable and stylish canvas sneakers suitable for everyday wear.', 45.00, 60.00, 100, 17, 'Canvas Sneakers.jpg'),
(3, 'Minimalist Wallet', 'Slim and durable leather wallet with multiple card slots and RFID protection.', 20.00, 30.00, 80, 13, 'Minimalist Wallet.jpg'),
(4, 'USB-C Hub Adapter', '7-in-1 USB-C hub with 4K HDMI, USB 3.0 ports, and SD/TF card reader.', 35.00, 45.00, 60, 51, 'USB-C Hub Adapter.jpg'),
(5, 'Classic Sunglasses', 'Polarized classic sunglasses with UV400 protection and stylish frame.', 15.00, 25.00, 120, 11, 'Classic Sunglasses.jpg'),
(6, 'Monitor Stand Riser', 'Ergonomic monitor stand with built-in storage drawers and phone holder.', 29.00, 39.00, 40, 51, 'Monitor Stand Riser.jpg'),
(7, 'Cable Management Box', 'High-quality box to organize and hide messy cables and power strips.', 18.00, 25.00, 70, 51, 'Cable Management Box.jpg'),
(8, 'True Wireless Earbuds', 'Bluetooth 5.0 earbuds with high-fidelity sound and 24-hour battery life.', 55.00, 75.00, 90, 48, 'True Wireless Earbuds.jpg'),
(9, 'Wireless Charging Pad', 'Ultra-slim 15W fast wireless charging pad for all Qi-enabled devices.', 22.00, 30.00, 110, 47, 'Wireless Charging Pad.jpg'),
(10, 'Bluetooth Speaker Mini', 'Portable mini Bluetooth speaker with 360-degree sound and waterproof design.', 30.00, 40.00, 55, 45, 'Bluetooth Speaker Mini.jpg'),
(11, 'Studio Monitor Headphones', 'Professional over-ear studio headphones for recording and mixing.', 85.00, 110.00, 30, 48, 'Studio Monitor Headphones.jpg'),
(12, 'Portable Power Bank 20000mAh', 'High-capacity power bank with fast charging and multiple output ports.', 40.00, 50.00, 45, 47, 'Portable Power Bank 20000mAh.jpg'),
(13, 'Premium Laptop Bag', 'Water-resistant laptop bag with padded compartment and adjustable strap.', 45.00, 65.00, 25, 34, 'bag.png'),
(14, 'Wireless Gaming Headset', 'Immersive 7.1 surround sound headset with noise-canceling microphone.', 65.00, 89.00, 35, 48, 'kas.jpg'),
(15, 'Ergonomic Wireless Mouse', 'High-precision 2.4G wireless mouse with comfortable ergonomic design.', 25.00, 35.00, 100, 51, 'mouse.jpg'),
(16, 'Mechanical Gaming Keyboard', 'Tactile mechanical keyboard with RGB backlit and programmable keys.', 75.00, 95.00, 20, 51, 'key.png'),
(17, 'Full HD 1080p Webcam', 'Crystal clear video quality for professional meetings and live streaming.', 45.00, 55.00, 30, 51, 'webcam.jpg'),
(18, 'Laptop Protective Sleeve', 'Soft and shockproof protective sleeve for 15-inch laptops and tablets.', 20.00, 28.00, 60, 51, 'case.jpg'),
(19, 'Gaming Controller', 'Wireless Bluetooth controller compatible with PC, consoles, and mobile.', 49.00, 59.00, 40, 45, 'game.jpg'),
(20, 'Ergonomic Office Chair', 'High-back mesh office chair with lumbar support and adjustable headrest.', 149.00, 189.00, 15, 53, 'chair.png'),
(21, 'MacBook Pro Carrying Case', 'Slim and stylish carrying case designed specifically for MacBook Pro.', 35.00, 45.00, 30, 34, 'macbag.jpg'),
(22, 'Water-Repellent Laptop Sleeve', 'High-quality polyester laptop sleeve for 14-15 inch laptops.', 25.00, 35.00, 50, 51, 'Mosiso-Laptop-Sleeve-for-15-Inch-New-MacBook-Pro-Touch-Bar-A1990-A1707-14-Inch-ThinkPad-Chromebook-Water-Repellent-Polyester-Tablet-Bag-Case-Gray_ce9dbd20-8fc7-4131-99a8-c9b432899cef.98f5031a0d84e6bd009d4e72d0711d20.avif');

-- =============================================================================
-- 4. Sizes
-- =============================================================================
CREATE TABLE IF NOT EXISTS `sizes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `sizes` (`id`, `name`) VALUES
(1, 'XS'), (2, 'S'), (3, 'M'), (4, 'L'), (5, 'XL'), (6, 'XXL'),
(7, '38'), (8, '39'), (9, '40'), (10, '41'), (11, '42'), (12, '44');

-- =============================================================================
-- 5. Product Sizes (Pivot table)
-- =============================================================================
CREATE TABLE IF NOT EXISTS `product_size` (
  `product_id` INT NOT NULL,
  `size_id` INT NOT NULL,
  PRIMARY KEY (`product_id`, `size_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- 6. Colors
-- =============================================================================
CREATE TABLE IF NOT EXISTS `colors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `colors` (`id`, `name`) VALUES
(1, 'Red'), (2, 'Blue'), (3, 'Black'), (4, 'White'), (5, 'Green'), (6, 'Yellow'), (7, 'Grey');

-- =============================================================================
-- 7. Product Colors (Pivot table)
-- =============================================================================
CREATE TABLE IF NOT EXISTS `product_color` (
  `product_id` INT NOT NULL,
  `color_id` INT NOT NULL,
  PRIMARY KEY (`product_id`, `color_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- 8. Orders
-- =============================================================================
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `total_price` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('pending', 'completed', 'cancelled') DEFAULT 'completed',
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `invoice_number`) VALUES
(1, 2, 125.00, 'completed', 'INV-2023-001'),
(2, 2, 55.00, 'completed', 'INV-2023-002'),
(3, 2, 235.00, 'pending', 'INV-2023-003');

-- =============================================================================
-- 9. Order Items
-- =============================================================================
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `quantity` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `order_items` (`order_id`, `product_id`, `price`, `quantity`) VALUES
(1, 1, 25.00, 1),
(1, 2, 45.00, 1),
(1, 8, 55.00, 1),
(2, 8, 55.00, 1),
(3, 11, 85.00, 2),
(3, 14, 65.00, 1);

-- =============================================================================
-- 10. Reviews
-- =============================================================================
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

INSERT INTO `reviews` (`product_id`, `user_id`, `rating`, `comment`) VALUES
(1, 2, 5, 'Great lamp! Very bright and easy to use.'),
(8, 2, 4, 'Good sound quality for the price.'),
(13, 2, 5, 'Perfect bag for my laptop, very well made.');

-- =============================================================================
-- 11. Wishlist
-- =============================================================================
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- 12. Countries
-- =============================================================================
CREATE TABLE IF NOT EXISTS `countries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `countries` (`id`, `name`) VALUES
(1, 'United States'), (2, 'Cambodia'), (3, 'Thailand'), (4, 'Vietnam'), (5, 'United Kingdom');

-- =============================================================================
-- 13. Shipping Costs
-- =============================================================================
CREATE TABLE IF NOT EXISTS `shipping_costs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `country_id` INT NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `shipping_costs` (`country_id`, `amount`) VALUES
(1, 20.00), (2, 2.00), (3, 10.00), (4, 8.00), (5, 15.00);
