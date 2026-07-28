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
                return; // Everything created fresh
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
    }

    private function seedInitialData() {
        // Seed an admin user and some default products
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute(['admin@eshop.com']);
        if ($stmt->rowCount() === 0) {
            // Create admin (password: admin123)
            $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
            $stmtInsert = $this->connection->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmtInsert->execute(['System Admin', 'admin@eshop.com', $adminPass, 'admin']);

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
