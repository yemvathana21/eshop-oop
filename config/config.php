<?php
// Database Configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'eshop_db');

// Dynamic Base URL detection
if (php_sapi_name() === 'cli') {
    $base_url = 'http://localhost';
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = dirname($scriptName);
    $dir = ($dir === '\\' || $dir === '/') ? '' : $dir;
    // Ensure we handle subdirectory setups (like localhost/eshop-oop) or virtual hosts (like generalstore.test)
    if (strpos($dir, '/public') !== false) {
        $base_url = $protocol . $domainName . str_replace('/public', '', $dir);
    } else {
        $base_url = $protocol . $domainName . $dir;
    }
}
define('BASE_URL', rtrim($base_url, '/') . '/');

// Absolute Paths
define('ROOT_PATH', realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR);
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads' . DIRECTORY_SEPARATOR);
define('IMAGES_PATH', ROOT_PATH . 'images' . DIRECTORY_SEPARATOR);

// Session Configuration
if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_start();
}
