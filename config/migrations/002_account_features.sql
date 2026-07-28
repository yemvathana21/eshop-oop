-- Migration 002: Account Center Features
-- Adds new tables for the redesigned customer account center.

SET FOREIGN_KEY_CHECKS = 0;

-- User Preferences (appearance, language, currency, notification settings)
CREATE TABLE IF NOT EXISTS `user_preferences` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `theme` ENUM('light', 'dark', 'system') DEFAULT 'system',
    `language` VARCHAR(5) DEFAULT 'en',
    `currency` VARCHAR(3) DEFAULT 'USD',
    `email_notifications` TINYINT(1) DEFAULT 1,
    `sms_notifications` TINYINT(1) DEFAULT 1,
    `order_updates` TINYINT(1) DEFAULT 1,
    `promotions` TINYINT(1) DEFAULT 0,
    `newsletter` TINYINT(1) DEFAULT 0,
    `price_drop_alerts` TINYINT(1) DEFAULT 1,
    `back_in_stock` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment Methods (tokenized, never store raw card numbers)
CREATE TABLE IF NOT EXISTS `user_payment_methods` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `type` ENUM('cod', 'aba', 'acleda', 'khqr', 'visa', 'mastercard') NOT NULL,
    `token` VARCHAR(255) DEFAULT NULL,
    `last_four` VARCHAR(4) DEFAULT NULL,
    `cardholder_name` VARCHAR(100) DEFAULT NULL,
    `expiry_month` VARCHAR(2) DEFAULT NULL,
    `expiry_year` VARCHAR(4) DEFAULT NULL,
    `is_default` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Connected Accounts (OAuth social logins)
CREATE TABLE IF NOT EXISTS `user_connected_accounts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `provider` ENUM('google', 'facebook', 'telegram', 'apple', 'github') NOT NULL,
    `provider_id` VARCHAR(255) DEFAULT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `avatar_url` VARCHAR(255) DEFAULT NULL,
    `connected_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_provider` (`user_id`, `provider`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Login History
CREATE TABLE IF NOT EXISTS `user_login_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `device_type` VARCHAR(50) DEFAULT NULL,
    `browser` VARCHAR(100) DEFAULT NULL,
    `os` VARCHAR(100) DEFAULT NULL,
    `location` VARCHAR(255) DEFAULT NULL,
    `success` TINYINT(1) DEFAULT 1,
    `logged_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Active User Devices/Sessions
CREATE TABLE IF NOT EXISTS `user_devices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `device_name` VARCHAR(255) DEFAULT NULL,
    `device_type` VARCHAR(50) DEFAULT NULL,
    `browser` VARCHAR(100) DEFAULT NULL,
    `os` VARCHAR(100) DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `is_current` TINYINT(1) DEFAULT 0,
    `last_active` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Coupons table (for dashboard)
CREATE TABLE IF NOT EXISTS `coupons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `discount` DECIMAL(10,2) NOT NULL,
    `type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
    `usage_limit` INT DEFAULT 100,
    `used_count` INT DEFAULT 0,
    `expires_at` DATE DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
