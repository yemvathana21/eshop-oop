<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

try {
    $db = \App\Core\Database::getInstance()->getConnection();

    $sql = "
    CREATE TABLE IF NOT EXISTS `sizes` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `colors` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `countries` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `shipping_costs` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `country_id` INT NOT NULL,
      `amount` DECIMAL(10, 2) NOT NULL,
      FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    $db->exec($sql);
    echo "<h1 style='color:green;'>Success! Shop Settings tables created successfully.</h1>";
    echo "<p>You can now delete this file: <b>public/install_settings.php</b></p>";
    echo "<a href='admin/sizes'>Go to Size Management</a>";

} catch (Exception $e) {
    echo "<h1 style='color:red;'>Error: " . $e->getMessage() . "</h1>";
}
