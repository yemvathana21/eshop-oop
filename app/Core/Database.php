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
