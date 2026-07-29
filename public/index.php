<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
use App\Controllers\ProfileController;
use App\Controllers\LocationController;

$router = new Router();

// --- Customer Routes ---
$router->get('/', [HomeController::class, 'index']);
$router->get('/shop', [HomeController::class, 'shop']);
$router->get('/product', [HomeController::class, 'productDetail']);
$router->post('/review/submit', [HomeController::class, 'submitReview']);
$router->get('/review/delete', [HomeController::class, 'deleteReview']);
$router->post('/wishlist/toggle', [HomeController::class, 'toggleWishlist']);
$router->get('/wishlist/toggle', [HomeController::class, 'toggleWishlist']);

// --- Profile Routes (Legacy, keeps old /profile links working) ---
$router->get('/profile', [ProfileController::class, 'show']);
$router->post('/profile/update', [ProfileController::class, 'update']);
$router->post('/profile/password', [ProfileController::class, 'password']);
$router->post('/profile/address/save', [ProfileController::class, 'addressSave']);
$router->get('/profile/address/delete', [ProfileController::class, 'addressDelete']);
$router->post('/profile/delete-account', [ProfileController::class, 'deleteAccount']);

// --- Account Center Routes ---
$router->get('/account', function() {
    header('Location: ' . BASE_URL . 'account/dashboard');
    exit;
});
$router->get('/account/dashboard', [ProfileController::class, 'dashboard']);
$router->get('/account/profile', [ProfileController::class, 'profile']);
$router->post('/account/profile/update', [ProfileController::class, 'update']);
$router->get('/account/orders', [ProfileController::class, 'orders']);
$router->get('/account/order', [ProfileController::class, 'orderDetail']);
$router->post('/account/order/cancel', [ProfileController::class, 'cancelOrder']);
$router->get('/account/wishlist', [ProfileController::class, 'wishlist']);
$router->get('/account/wishlist/remove', [ProfileController::class, 'wishlistRemove']);
$router->get('/account/addresses', [ProfileController::class, 'addresses']);
$router->post('/account/address/save', [ProfileController::class, 'addressSave']);
$router->get('/account/address/delete', [ProfileController::class, 'addressDelete']);
$router->get('/account/payment-methods', [ProfileController::class, 'paymentMethods']);
$router->post('/account/payment-method/save', [ProfileController::class, 'paymentMethodSave']);
$router->get('/account/payment-method/delete', [ProfileController::class, 'paymentMethodDelete']);
$router->get('/account/payment-method/default', [ProfileController::class, 'paymentMethodSetDefault']);
$router->get('/account/security', [ProfileController::class, 'security']);
$router->post('/account/security/password', [ProfileController::class, 'password']);
$router->get('/account/notifications', [ProfileController::class, 'notifications']);
$router->post('/account/notifications/save', [ProfileController::class, 'notificationsSave']);
$router->get('/account/connected', [ProfileController::class, 'connectedAccounts']);
$router->get('/account/connected/delete', [ProfileController::class, 'connectedAccountDelete']);
$router->get('/account/appearance', [ProfileController::class, 'appearance']);
$router->post('/account/appearance/save', [ProfileController::class, 'appearanceSave']);
$router->get('/account/privacy', [ProfileController::class, 'privacy']);
$router->post('/account/delete-account', [ProfileController::class, 'deleteAccount']);
$router->get('/account/username', [ProfileController::class, 'username']);
$router->post('/account/username/save', [ProfileController::class, 'usernameSave']);
$router->get('/account/devices', [ProfileController::class, 'devices']);
$router->get('/account/passkeys', [ProfileController::class, 'passkeys']);
$router->get('/account/membership', [ProfileController::class, 'membership']);
$router->get('/account/download-data', [ProfileController::class, 'downloadData']);
$router->post('/account/preference/save', [ProfileController::class, 'preferenceSave']);
$router->post('/account/connected/toggle', [ProfileController::class, 'connectedAccountToggle']);
$router->post('/account/device/revoke', [ProfileController::class, 'deviceRevoke']);

// --- Location (AJAX) Routes ---
$router->get('/api/provinces', [LocationController::class, 'provinces']);
$router->get('/api/districts', [LocationController::class, 'districts']);
$router->get('/api/communes', [LocationController::class, 'communes']);
$router->get('/api/villages', [LocationController::class, 'villages']);

// --- Auth Routes ---
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/admin/login', [AuthController::class, 'showAdminLogin']);
$router->post('/admin/login', [AuthController::class, 'adminLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/admin/logout', [AuthController::class, 'adminLogout']);
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
$router->post('/cart/checkout-selected', [CartController::class, 'checkoutSelected']);

// --- Checkout Routes ---
$router->get('/checkout', [CheckoutController::class, 'index']);
$router->post('/checkout/save-step', [CheckoutController::class, 'saveStep']);
$router->post('/checkout/process', [CheckoutController::class, 'process']);
$router->get('/order-confirmation', [CheckoutController::class, 'orderConfirmation']);
$router->get('/invoice', [CheckoutController::class, 'invoice']);
$router->get('/my-orders', [CheckoutController::class, 'myOrders']);

// --- Admin Routes ---
$router->get('/admin', function() {
    header('Location: ' . BASE_URL . 'admin/dashboard');
    exit;
});
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
$router->post('/admin/order/update-status', [AdminController::class, 'orderUpdateStatus']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/user/create', [AdminController::class, 'userCreate']);
$router->post('/admin/user/save', [AdminController::class, 'userSave']);
$router->get('/admin/user/edit', [AdminController::class, 'userEdit']);
$router->post('/admin/user/update', [AdminController::class, 'userUpdate']);
$router->get('/admin/user/delete', [AdminController::class, 'userDelete']);
$router->get('/admin/reviews', [AdminController::class, 'reviews']);
$router->get('/admin/review/delete', [AdminController::class, 'reviewDelete']);
$router->get('/admin/search', [AdminController::class, 'search']);
$router->get('/admin/get-subcategories', [AdminController::class, 'getSubcategories']);

// --- Shop Settings Routes ---
$router->get('/admin/sizes', [AdminController::class, 'sizes']);
$router->post('/admin/size/save', [AdminController::class, 'sizeSave']);
$router->get('/admin/size/delete', [AdminController::class, 'sizeDelete']);

$router->get('/admin/colors', [AdminController::class, 'colors']);
$router->post('/admin/color/save', [AdminController::class, 'colorSave']);
$router->get('/admin/color/delete', [AdminController::class, 'colorDelete']);

$router->get('/admin/countries', [AdminController::class, 'countries']);
$router->post('/admin/country/save', [AdminController::class, 'countrySave']);
$router->get('/admin/country/delete', [AdminController::class, 'countryDelete']);

$router->get('/admin/shipping-costs', [AdminController::class, 'shippingCosts']);
$router->post('/admin/shipping-cost/save', [AdminController::class, 'shippingCostSave']);
$router->get('/admin/shipping-cost/delete', [AdminController::class, 'shippingCostDelete']);

$router->get('/admin/shipping-methods', [AdminController::class, 'shippingMethods']);
$router->post('/admin/shipping-method/save', [AdminController::class, 'shippingMethodSave']);
$router->get('/admin/shipping-method/delete', [AdminController::class, 'shippingMethodDelete']);

$router->get('/admin/top-categories', [AdminController::class, 'topCategories']);
$router->post('/admin/top-category/save', [AdminController::class, 'topCategorySave']);
$router->get('/admin/top-category/delete', [AdminController::class, 'topCategoryDelete']);

$router->get('/admin/mid-categories', [AdminController::class, 'midCategories']);
$router->post('/admin/mid-category/save', [AdminController::class, 'midCategorySave']);
$router->get('/admin/mid-category/delete', [AdminController::class, 'midCategoryDelete']);

$router->get('/admin/end-categories', [AdminController::class, 'endCategories']);
$router->post('/admin/end-category/save', [AdminController::class, 'endCategorySave']);
$router->get('/admin/end-category/delete', [AdminController::class, 'endCategoryDelete']);

// Dispatch
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($url, $method);
