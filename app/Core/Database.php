<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            // First connect without database name to ensure the database exists
            $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Create database if not exists
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            
            // Connect to the specific database
            $this->connection = new PDO($dsn . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Check if tables are empty or missing, if so, seed from database.sql
            $this->checkAndInitializeDatabase();
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function checkAndInitializeDatabase() {
        // 1. Check if categories table exists
        $result = $this->connection->query("SHOW TABLES LIKE 'categories'")->rowCount();
        if ($result === 0) {
            $sqlFile = ROOT_PATH . 'config' . DIRECTORY_SEPARATOR . 'database.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                $this->connection->exec($sql);
                $this->seedInitialData();
                // NOTE: No early return — continue to run ALTER TABLE migrations below
            }
        }

        // 2. Force fix for missing category_id in products table (the error you are seeing)
        try {
            $this->connection->query("SELECT category_id FROM products LIMIT 1");
        } catch (PDOException $e) {
            // Column doesn't exist, let's add it
            $this->connection->exec("ALTER TABLE products ADD COLUMN category_id INT NULL AFTER stock");
            $this->connection->exec("ALTER TABLE products ADD CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL");
        }

        // 3. Add gallery_images column if missing
        try {
            $this->connection->query("SELECT gallery_images FROM products LIMIT 1");
        } catch (PDOException $e) {
            $this->connection->exec("ALTER TABLE products ADD COLUMN gallery_images TEXT NULL AFTER image");
        }

        // 3b. Add specifications column if missing
        try {
            $this->connection->query("SELECT specifications FROM products LIMIT 1");
        } catch (PDOException $e) {
            $this->connection->exec("ALTER TABLE products ADD COLUMN specifications TEXT NULL AFTER gallery_images");
        }

        // 3c. Add parent_id column to categories if missing
        try {
            $this->connection->query("SELECT parent_id FROM categories LIMIT 1");
        } catch (PDOException $e) {
            $this->connection->exec("ALTER TABLE categories ADD COLUMN parent_id INT DEFAULT NULL AFTER sort_order");
            try {
                $this->connection->exec("ALTER TABLE categories ADD CONSTRAINT fk_category_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL");
            } catch (PDOException $e2) {}
        }

        // 3d. Create user_addresses table if missing
        $uaCheck = $this->connection->query("SHOW TABLES LIKE 'user_addresses'")->rowCount();
        if ($uaCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `user_addresses` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `label` VARCHAR(100) DEFAULT 'Billing',
                `full_name` VARCHAR(255) DEFAULT NULL,
                `company` VARCHAR(255) DEFAULT NULL,
                `email` VARCHAR(100) DEFAULT NULL,
                `tax_id` VARCHAR(50) DEFAULT NULL,
                `phone` VARCHAR(20) DEFAULT NULL,
                `province_code` VARCHAR(20) DEFAULT NULL,
                `district_code` VARCHAR(20) DEFAULT NULL,
                `commune_code` VARCHAR(20) DEFAULT NULL,
                `village_code` VARCHAR(20) DEFAULT NULL,
                `street` TEXT DEFAULT NULL,
                `zip_code` VARCHAR(20) DEFAULT NULL,
                `latitude` VARCHAR(50) DEFAULT NULL,
                `longitude` VARCHAR(50) DEFAULT NULL,
                `is_default` TINYINT(1) DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 3f. Create Cambodia address tables if missing
        $addrCheck = $this->connection->query("SHOW TABLES LIKE 'provinces'")->rowCount();
        if ($addrCheck === 0) {
            $addrFile = ROOT_PATH . 'database' . DIRECTORY_SEPARATOR . 'seeds' . DIRECTORY_SEPARATOR . 'cambodia_addresses.sql';
            if (file_exists($addrFile)) {
                $this->connection->exec(file_get_contents($addrFile));
            }
        }

        // 3g. Add columns to users if missing
        $userColumns = [
            'phone' => "VARCHAR(20) DEFAULT NULL AFTER email",
            'address' => "TEXT DEFAULT NULL AFTER phone",
            'avatar' => "VARCHAR(255) DEFAULT NULL AFTER address",
            'username' => "VARCHAR(100) DEFAULT NULL AFTER avatar",
            'first_name' => "VARCHAR(100) DEFAULT NULL AFTER username",
            'last_name' => "VARCHAR(100) DEFAULT NULL AFTER first_name",
            'gender' => "VARCHAR(20) DEFAULT NULL AFTER last_name",
            'date_of_birth' => "DATE DEFAULT NULL AFTER gender",
            'company' => "VARCHAR(255) DEFAULT NULL AFTER date_of_birth",
            'location' => "VARCHAR(255) DEFAULT NULL AFTER company",
        ];
        foreach ($userColumns as $col => $def) {
            try {
                $this->connection->query("SELECT $col FROM users LIMIT 1");
            } catch (PDOException $e) {
                $this->connection->exec("ALTER TABLE users ADD COLUMN $col $def");
            }
        }

        // 3h. Add columns to user_addresses if missing
        $addrColumns = ['company', 'email', 'tax_id'];
        $addrDefs = [
            'company' => "VARCHAR(255) DEFAULT NULL AFTER full_name",
            'email' => "VARCHAR(100) DEFAULT NULL AFTER company",
            'tax_id' => "VARCHAR(50) DEFAULT NULL AFTER email",
        ];
        foreach ($addrColumns as $col) {
            try {
                $this->connection->query("SELECT $col FROM user_addresses LIMIT 1");
            } catch (PDOException $e) {
                $this->connection->exec("ALTER TABLE user_addresses ADD COLUMN $col {$addrDefs[$col]}");
            }
        }

        // 4. Create reviews table if missing
        $reviewsCheck = $this->connection->query("SHOW TABLES LIKE 'reviews'")->rowCount();
        if ($reviewsCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `reviews` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `product_id` INT NOT NULL,
                `user_id` INT NOT NULL,
                `rating` TINYINT NOT NULL,
                `comment` TEXT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
                FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
                UNIQUE KEY `unique_review` (`product_id`, `user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 4b. Drop unique constraint on reviews to allow multiple reviews per user per product
        try {
            $this->connection->exec("ALTER TABLE reviews DROP FOREIGN KEY reviews_ibfk_1");
        } catch (PDOException $e) {}
        try {
            $this->connection->exec("ALTER TABLE reviews DROP FOREIGN KEY reviews_ibfk_2");
        } catch (PDOException $e) {}
        try {
            $this->connection->exec("ALTER TABLE reviews DROP INDEX unique_review");
        } catch (PDOException $e) {}
        try {
            $this->connection->exec("ALTER TABLE reviews ADD CONSTRAINT reviews_ibfk_1 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE");
        } catch (PDOException $e) {}
        try {
            $this->connection->exec("ALTER TABLE reviews ADD CONSTRAINT reviews_ibfk_2 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
        } catch (PDOException $e) {}

        // 5. Create wishlist table if missing
        $wishlistCheck = $this->connection->query("SHOW TABLES LIKE 'wishlist'")->rowCount();
        if ($wishlistCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `wishlist` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT NOT NULL,
                `product_id` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
                FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
                UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 5b. Add columns to orders if missing
        $orderCols = ['shipping_name', 'shipping_phone', 'shipping_address', 'shipping_method', 'shipping_cost', 'payment_method'];
        $orderDefs = [
            'shipping_name' => "VARCHAR(255) DEFAULT NULL AFTER status",
            'shipping_phone' => "VARCHAR(20) DEFAULT NULL AFTER shipping_name",
            'shipping_address' => "TEXT DEFAULT NULL AFTER shipping_phone",
            'shipping_method' => "VARCHAR(50) DEFAULT NULL AFTER shipping_address",
            'shipping_cost' => "DECIMAL(10,2) DEFAULT 0.00 AFTER shipping_method",
            'payment_method' => "VARCHAR(50) DEFAULT NULL AFTER shipping_cost",
        ];
        foreach ($orderCols as $col) {
            try {
                $this->connection->query("SELECT $col FROM orders LIMIT 1");
            } catch (PDOException $e) {
                $this->connection->exec("ALTER TABLE orders ADD COLUMN $col {$orderDefs[$col]}");
            }
        }

        // 5c. Update orders.status ENUM to include new statuses
        try {
            $checkStatus = $this->connection->query("SHOW COLUMNS FROM orders WHERE Field = 'status'")->fetch();
            if ($checkStatus && strpos($checkStatus['Type'], 'confirmed') === false) {
                $this->connection->exec("ALTER TABLE orders MODIFY status ENUM('pending','confirmed','shipping','delivery','delivered','completed','cancelled') DEFAULT 'pending'");
            }
        } catch (PDOException $e) {
            // Table may not exist yet
        }

        // 6. Create product_size pivot table if missing
        $sizeCheck = $this->connection->query("SHOW TABLES LIKE 'product_size'")->rowCount();
        if ($sizeCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `product_size` (
                `product_id` INT NOT NULL,
                `size_id` INT NOT NULL,
                PRIMARY KEY (`product_id`, `size_id`),
                FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
                FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 7. Create product_color pivot table if missing
        $colorCheck = $this->connection->query("SHOW TABLES LIKE 'product_color'")->rowCount();
        if ($colorCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `product_color` (
                `product_id` INT NOT NULL,
                `color_id` INT NOT NULL,
                PRIMARY KEY (`product_id`, `color_id`),
                FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
                FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 8b. Add variant columns to order_items if missing
        $oiCols = ['size_name', 'color_name'];
        $oiDefs = [
            'size_name' => "VARCHAR(100) DEFAULT NULL AFTER quantity",
            'color_name' => "VARCHAR(100) DEFAULT NULL AFTER size_name",
        ];
        foreach ($oiCols as $col) {
            try {
                $this->connection->query("SELECT $col FROM order_items LIMIT 1");
            } catch (PDOException $e) {
                $this->connection->exec("ALTER TABLE order_items ADD COLUMN $col {$oiDefs[$col]}");
            }
        }

        // 9. Create shipping_methods table if missing
        $smCheck = $this->connection->query("SHOW TABLES LIKE 'shipping_methods'")->rowCount();
        if ($smCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `shipping_methods` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `code` VARCHAR(50) NOT NULL UNIQUE,
                `label` VARCHAR(100) NOT NULL,
                `days` VARCHAR(100) DEFAULT NULL,
                `cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                `is_active` TINYINT(1) DEFAULT 1,
                `sort_order` INT DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            // Seed 3 default shipping methods
            $this->connection->exec("INSERT INTO shipping_methods (code, label, days, cost, is_active, sort_order) VALUES
                ('standard', 'Standard Shipping', '5-7 business days', 0.00, 1, 1),
                ('express', 'Express Shipping', '2-3 business days', 4.99, 1, 2),
                ('nextday', 'Next Day Delivery', 'Tomorrow', 9.99, 1, 3)
            ");
        }

        // 10. Create settings table if missing
        $settingsCheck = $this->connection->query("SHOW TABLES LIKE 'settings'")->rowCount();
        if ($settingsCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `settings` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `setting_key` VARCHAR(100) NOT NULL UNIQUE,
                `setting_value` TEXT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            // Seed default settings
            $stmt = $this->connection->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            $defaultSettings = [
                ['store_name', 'General Online Store'],
                ['store_email', 'contact@generalstore.com'],
                ['store_phone', '+855 12 345 678'],
                ['store_address', '123 Street, Phnom Penh, Cambodia'],
                ['store_map', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3908.770663730876!2d104.9192!3d11.5621!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTHCsDMzJzQzLjYiTiAxMDTCsDU1JzA5LjEiRQ!5e0!3m2!1sen!2skh!4v1625000000000!5m2!1sen!2skh'],
                ['facebook_url', 'https://facebook.com/generalstore'],
                ['telegram_url', 'https://t.me/generalstore'],
                ['tiktok_url', 'https://tiktok.com/@generalstore']
            ];
            foreach ($defaultSettings as $s) {
                $stmt->execute($s);
            }
        }

        // 11. Create contact_messages table if missing
        $cmCheck = $this->connection->query("SHOW TABLES LIKE 'contact_messages'")->rowCount();
        if ($cmCheck === 0) {
            $this->connection->exec("CREATE TABLE IF NOT EXISTS `contact_messages` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT DEFAULT NULL,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `subject` VARCHAR(255) DEFAULT NULL,
                `message` TEXT NOT NULL,
                `status` ENUM('unread', 'read', 'replied') DEFAULT 'unread',
                `reply_message` TEXT DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        } else {
            // Check if user_id column exists
            try {
                $this->connection->query("SELECT user_id FROM contact_messages LIMIT 1");
            } catch (\PDOException $e) {
                $this->connection->exec("ALTER TABLE contact_messages ADD COLUMN user_id INT DEFAULT NULL AFTER id");
                $this->connection->exec("ALTER TABLE contact_messages ADD CONSTRAINT fk_cm_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
            }
        }

        // 11b. Add is_customer_viewed column to contact_messages if missing
        try {
            $this->connection->query("SELECT is_customer_viewed FROM contact_messages LIMIT 1");
        } catch (PDOException $e) {
            $this->connection->exec("ALTER TABLE contact_messages ADD COLUMN is_customer_viewed TINYINT(1) DEFAULT 0 AFTER reply_message");
        }
    }

    private function seedInitialData() {
        // Seed an admin user and some default products
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute(['admin@store.com']);
        if ($stmt->rowCount() === 0) {
            // Create admin (password: admin123)
            $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
            $stmtInsert = $this->connection->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmtInsert->execute(['System Admin', 'admin@store.com', $adminPass, 'admin']);

            // Create customer (password: customer123)
            $customerPass = password_hash('customer123', PASSWORD_BCRYPT);
            $stmtInsert->execute(['John Doe', 'john@gmail.com', $customerPass, 'customer']);

            // Seed default products
            $products = [
                [
                    'name' => 'Premium Leather Backpack',
                    'description' => 'A stylish and durable premium leather backpack.',
                    'price' => 79.99,
                    'stock' => 15,
                    'image' => 'backpack.jpg',
                    'category_id' => 1
                ],
                [
                    'name' => 'Wireless Headphones',
                    'description' => 'High-fidelity sound, active noise cancellation.',
                    'price' => 129.50,
                    'stock' => 8,
                    'image' => 'headphones.jpg',
                    'category_id' => 2
                ]
            ];

            $stmtProd = $this->connection->prepare("INSERT INTO products (name, description, price, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($products as $p) {
                $stmtProd->execute([$p['name'], $p['description'], $p['price'], $p['stock'], $p['image'], $p['category_id']]);
            }
        }
    }
}
