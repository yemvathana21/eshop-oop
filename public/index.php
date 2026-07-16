<?php
// Custom PSR-4 style autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    // App\Core\Database -> App/Core/Database.php
    $prefix = 'App\\';
    $baseDir = realpath(__DIR__ . '/../app') . DIRECTORY_SEPARATOR;

    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

// Load configuration
require_once realpath(__DIR__ . '/../config/config.php');

// Init Language
use App\Core\Lang\Language;
Language::init();

// Shorthand for views
function t($key) { return Language::get($key); }

// Use the Router
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\AdminController;

$router = new Router();

// --- Customer Routes ---
$router->get('/', [HomeController::class, 'index']);
$router->get('/shop', [HomeController::class, 'shop']);
$router->get('/product', [HomeController::class, 'productDetail']);

// --- Auth Routes ---
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/lang', function() {
    $lang = $_GET['lang'] ?? 'en';
    Language::set($lang);
    $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
    header('Location: ' . $referer);
    exit;
});

// --- Cart Routes ---
$router->get('/cart', [CartController::class, 'view']);
$router->get('/cart/add', [CartController::class, 'add']);
$router->post('/cart/add', [CartController::class, 'add']);
$router->post('/cart/update', [CartController::class, 'update']);
$router->get('/cart/remove', [CartController::class, 'remove']);
$router->get('/cart/clear', [CartController::class, 'clear']);

// --- Checkout Routes ---
$router->get('/checkout', [CheckoutController::class, 'index']);
$router->post('/checkout/process', [CheckoutController::class, 'process']);
$router->get('/invoice', [CheckoutController::class, 'invoice']);
$router->get('/my-orders', [CheckoutController::class, 'myOrders']);

// --- Admin Routes ---
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/products', [AdminController::class, 'products']);
$router->get('/admin/product/create', [AdminController::class, 'productCreate']);
$router->post('/admin/product/save', [AdminController::class, 'productSave']);
$router->get('/admin/product/edit', [AdminController::class, 'productEdit']);
$router->post('/admin/product/update', [AdminController::class, 'productUpdate']);
$router->get('/admin/product/delete', [AdminController::class, 'productDelete']);
$router->get('/admin/categories', [AdminController::class, 'categories']);
$router->get('/admin/category/create', [AdminController::class, 'categoryCreate']);
$router->post('/admin/category/save', [AdminController::class, 'categorySave']);
$router->get('/admin/category/edit', [AdminController::class, 'categoryEdit']);
$router->post('/admin/category/update', [AdminController::class, 'categoryUpdate']);
$router->get('/admin/category/delete', [AdminController::class, 'categoryDelete']);
$router->get('/admin/inventory', [AdminController::class, 'inventory']);
$router->post('/admin/inventory/adjust', [AdminController::class, 'inventoryAdjust']);
$router->get('/admin/orders', [AdminController::class, 'orders']);
$router->get('/admin/order', [AdminController::class, 'orderDetail']);
$router->get('/admin/order/invoice', [AdminController::class, 'orderInvoice']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/user/create', [AdminController::class, 'userCreate']);
$router->post('/admin/user/save', [AdminController::class, 'userSave']);
$router->get('/admin/user/edit', [AdminController::class, 'userEdit']);
$router->post('/admin/user/update', [AdminController::class, 'userUpdate']);
$router->get('/admin/user/delete', [AdminController::class, 'userDelete']);
$router->get('/admin/search', [AdminController::class, 'search']);

// Dispatch
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($url, $method);
