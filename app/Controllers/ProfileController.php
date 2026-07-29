<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\UserPreference;
use App\Models\UserPaymentMethod;
use App\Models\UserConnectedAccount;
use App\Models\UserLoginHistory;
use App\Models\UserDevice;
use App\Models\Wishlist;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Province;

class ProfileController extends Controller {
    private $userModel;
    private $orderModel;
    private $addressModel;
    private $preferenceModel;
    private $paymentMethodModel;
    private $connectedAccountModel;
    private $loginHistoryModel;
    private $deviceModel;
    private $wishlistModel;
    private $couponModel;

    public function __construct() {
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->addressModel = new UserAddress();
        $this->preferenceModel = new UserPreference();
        $this->paymentMethodModel = new UserPaymentMethod();
        $this->connectedAccountModel = new UserConnectedAccount();
        $this->loginHistoryModel = new UserLoginHistory();
        $this->deviceModel = new UserDevice();
        $this->wishlistModel = new Wishlist();
        $this->couponModel = new Coupon();
    }

    // --- Legacy redirect ---
    public function show() {
        $this->redirect('account/dashboard');
    }

    // ===== PROFILE =====
    public function profile() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        $this->render('customer/account/profile', [
            'title' => t('edit_profile') . ' - ' . t('my_account'),
            'user' => $user
        ]);
    }

    public function update() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (!empty($_POST['ajax'])) {
                $this->json(['success' => false, 'message' => 'Invalid request']);
            }
            $this->redirect('account/dashboard');
        }

        $userId = Session::getUserId();
        $isAjax = !empty($_POST['ajax']);
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Fallback for legacy forms that send a single "name" field
        if (empty($first_name) && !empty($_POST['name'])) {
            $parts = preg_split('/\s+/', trim($_POST['name']), 2);
            $first_name = $parts[0] ?? '';
            $last_name = $parts[1] ?? '';
        }

        if (empty($first_name) || empty($last_name) || empty($email)) {
            $msg = 'First name, last name and email are required';
            if ($isAjax) { $this->json(['success' => false, 'message' => $msg]); return; }
            Session::setFlash('error', $msg);
            $this->redirect('account/dashboard');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = t('invalid_email');
            if ($isAjax) { $this->json(['success' => false, 'message' => $msg]); return; }
            Session::setFlash('error', $msg);
            $this->redirect('account/dashboard');
        }

        $user = $this->userModel->findById($userId);
        if ($email !== $user['email'] && $this->userModel->emailExists($email)) {
            $msg = t('email_in_use');
            if ($isAjax) { $this->json(['success' => false, 'message' => $msg]); return; }
            Session::setFlash('error', $msg);
            $this->redirect('account/dashboard');
        }

        $avatar = $user['avatar'];
        $removeAvatar = !empty($_POST['remove_avatar']);

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH;
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $fileName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array(strtolower($ext), $allowed)) {
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $fileName)) {
                    if ($avatar && file_exists($uploadDir . $avatar)) unlink($uploadDir . $avatar);
                    $avatar = $fileName;
                }
            }
        } elseif ($removeAvatar && $avatar) {
            if (file_exists(UPLOAD_PATH . $avatar)) unlink(UPLOAD_PATH . $avatar);
            $avatar = null;
        }

        $name = trim("$first_name $last_name");
        $data = [
            'name' => $name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'gender' => trim($_POST['gender'] ?? ''),
            'date_of_birth' => trim($_POST['date_of_birth'] ?? '') ?: null,
            'company' => trim($_POST['company'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'email' => $email,
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];

        $this->userModel->updateAvatar($userId, $avatar);
        $this->userModel->updateProfile($userId, $data);
        Session::set('customer_user_name', $name);
        Session::set('customer_user_email', $email);

        if ($isAjax) {
            $this->json(['success' => true, 'message' => t('profile_updated')]);
            return;
        }
        Session::setFlash('success', t('profile_updated'));
        $this->redirect('account/dashboard');
    }

    // ===== ORDERS =====
    public function orders() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $status = $_GET['status'] ?? 'all';
        $allOrders = $this->orderModel->findByUserId($userId);
        if ($status !== 'all') {
            $allOrders = array_filter($allOrders ?? [], fn($o) => $o['status'] === $status);
        }
        $this->render('customer/account/orders', [
            'title' => t('my_orders') . ' - ' . t('my_account'),
            'orders' => $allOrders,
            'currentStatus' => $status
        ]);
    }

    public function orderDetail() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) $this->redirect('account/orders');

        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['user_id'] != $userId) $this->redirect('account/orders');

        $items = $this->orderModel->getItems($orderId);
        $this->render('customer/account/order_detail', [
            'title' => t('order') . ' #' . htmlspecialchars($order['invoice_number']),
            'order' => $order,
            'items' => $items
        ]);
    }

    public function cancelOrder() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $orderId = $_POST['id'] ?? null;
        if (!$orderId) $this->redirect('account/orders');

        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['user_id'] != $userId) $this->redirect('account/orders');
        if ($order['status'] !== 'pending') $this->redirect('account/order?id=' . $orderId);

        $this->orderModel->updateStatus($orderId, 'cancelled');

        $productModel = new Product();
        $items = $this->orderModel->getItems($orderId);
        foreach ($items as $item) {
            $productModel->addStock($item['product_id'], $item['quantity']);
        }

        Session::setFlash('success', t('order_cancelled'));
        $this->redirect('account/order?id=' . $orderId);
    }

    // ===== WISHLIST =====
    public function wishlist() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $items = $this->wishlistModel->byUser($userId);
        $this->render('customer/account/wishlist', [
            'title' => t('my_wishlist') . ' - ' . t('my_account'),
            'wishlist' => $items
        ]);
    }

    public function wishlistRemove() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $id = $_GET['id'] ?? null;
        if ($id) $this->wishlistModel->remove($id, $userId);
        $this->redirect('account/wishlist');
    }

    // ===== USERNAME =====
    public function username() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        $this->render('customer/account/username', [
            'title' => t('username') . ' - ' . t('my_account'),
            'user' => $user
        ]);
    }

    public function usernameSave() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (!empty($_POST['ajax'])) {
                $this->json(['success' => false, 'message' => 'Invalid request']);
            }
            $this->redirect('account/dashboard');
        }
        $userId = Session::getUserId();
        $isAjax = !empty($_POST['ajax']);
        $username = trim($_POST['username'] ?? '');
        if (empty($username)) {
            $msg = 'Username is required.';
            if ($isAjax) { $this->json(['success' => false, 'message' => $msg]); return; }
            Session::setFlash('error', $msg);
            $this->redirect('account/dashboard');
        }
        $this->userModel->updateProfile($userId, ['username' => $username]);
        Session::set('customer_user_name', $username);
        if ($isAjax) {
            $this->json(['success' => true, 'message' => 'Username updated.']);
            return;
        }
        Session::setFlash('success', 'Username updated.');
        $this->redirect('account/dashboard');
    }

    // ===== DEVICES =====
    public function devices() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $devices = $this->deviceModel->byUser($userId);
        $this->render('customer/account/devices', [
            'title' => t('devices') . ' - ' . t('my_account'),
            'devices' => $devices
        ]);
    }

    // ===== PASSKEYS =====
    public function passkeys() {
        $this->requireLogin();
        $this->render('customer/account/passkeys', [
            'title' => t('passkeys') . ' - ' . t('my_account')
        ]);
    }

    // ===== MEMBERSHIP =====
    public function membership() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        $orderCount = $this->orderModel->getCountByUser($userId);
        $totalSpent = $this->orderModel->getTotalSpentByUser($userId);
        $this->render('customer/account/membership', [
            'title' => t('membership') . ' - ' . t('my_account'),
            'user' => $user,
            'orderCount' => $orderCount,
            'totalSpent' => $totalSpent
        ]);
    }

    // ===== ADDRESSES =====
    public function addresses() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $addresses = $this->addressModel->byUser($userId);
        $provinces = (new Province())->all();
        $this->render('customer/account/addresses', [
            'title' => t('my_addresses') . ' - ' . t('my_account'),
            'addresses' => $addresses,
            'provinces' => $provinces,
            'addressModel' => $this->addressModel
        ]);
    }

    public function addressSave() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('account/addresses');

        $userId = Session::getUserId();
        $id = $_POST['id'] ?? null;
        $data = [
            'user_id' => $userId,
            'label' => $_POST['label'] ?? 'Home',
            'full_name' => trim($_POST['full_name'] ?? ''),
            'company' => trim($_POST['company'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'tax_id' => trim($_POST['tax_id'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'province_code' => $_POST['province_code'] ?? null,
            'district_code' => $_POST['district_code'] ?? null,
            'commune_code' => $_POST['commune_code'] ?? null,
            'village_code' => $_POST['village_code'] ?? null,
            'street' => trim($_POST['street'] ?? ''),
            'zip_code' => trim($_POST['zip_code'] ?? ''),
            'latitude' => $_POST['latitude'] ?? null,
            'longitude' => $_POST['longitude'] ?? null,
            'is_default' => !empty($_POST['is_default']) ? 1 : 0,
        ];

        if ($id) {
            $this->addressModel->update($id, $data);
            Session::setFlash('success', t('address_updated'));
        } else {
            $this->addressModel->create($data);
            Session::setFlash('success', t('address_added'));
        }
        $this->redirect('account/addresses');
    }

    public function addressDelete() {
        $this->requireLogin();
        $id = $_GET['id'] ?? null;
        if ($id) $this->addressModel->delete($id);
        Session::setFlash('success', t('address_deleted'));
        $this->redirect('account/addresses');
    }

    // ===== PAYMENT METHODS =====
    public function paymentMethods() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $methods = $this->paymentMethodModel->byUser($userId);
        $this->render('customer/account/payment_methods', [
            'title' => t('payment_methods') . ' - ' . t('my_account'),
            'methods' => $methods
        ]);
    }

    public function paymentMethodSave() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('account/payment-methods');

        $userId = Session::getUserId();
        $this->paymentMethodModel->create([
            'user_id' => $userId,
            'type' => $_POST['type'] ?? 'cod',
            'token' => $_POST['token'] ?? null,
            'last_four' => $_POST['last_four'] ?? null,
            'cardholder_name' => $_POST['cardholder_name'] ?? null,
            'expiry_month' => $_POST['expiry_month'] ?? null,
            'expiry_year' => $_POST['expiry_year'] ?? null,
            'is_default' => !empty($_POST['is_default']) ? 1 : 0,
        ]);
        Session::setFlash('success', t('payment_method_added'));
        $this->redirect('account/payment-methods');
    }

    public function paymentMethodDelete() {
        $this->requireLogin();
        $id = $_GET['id'] ?? null;
        if ($id) $this->paymentMethodModel->delete($id);
        Session::setFlash('success', t('payment_method_deleted'));
        $this->redirect('account/payment-methods');
    }

    public function paymentMethodSetDefault() {
        $this->requireLogin();
        $id = $_GET['id'] ?? null;
        $userId = Session::getUserId();
        if ($id) $this->paymentMethodModel->setDefault($id, $userId);
        $this->redirect('account/payment-methods');
    }

    // ===== SECURITY =====
    public function security() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        $loginHistory = $this->loginHistoryModel->byUser($userId, 10);
        $devices = $this->deviceModel->byUser($userId);
        $this->render('customer/account/security', [
            'title' => t('security') . ' - ' . t('my_account'),
            'user' => $user,
            'loginHistory' => $loginHistory,
            'devices' => $devices
        ]);
    }

    public function password() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (!empty($_POST['ajax'])) {
                $this->json(['success' => false, 'message' => 'Invalid request']);
            }
            $this->redirect('account/dashboard');
        }

        $userId = Session::getUserId();
        $isAjax = !empty($_POST['ajax']);
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        $user = $this->userModel->findById($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            $msg = t('incorrect_current_password');
            if ($isAjax) { $this->json(['success' => false, 'message' => $msg]); return; }
            Session::setFlash('error', $msg);
            $this->redirect('account/dashboard');
        }
        if ($newPassword !== $confirmPassword) {
            $msg = t('passwords_do_not_match');
            if ($isAjax) { $this->json(['success' => false, 'message' => $msg]); return; }
            Session::setFlash('error', $msg);
            $this->redirect('account/dashboard');
        }
        if (strlen($newPassword) < 6) {
            $msg = t('password_min_length');
            if ($isAjax) { $this->json(['success' => false, 'message' => $msg]); return; }
            Session::setFlash('error', $msg);
            $this->redirect('account/dashboard');
        }

        $this->userModel->changePassword($userId, $newPassword);
        if ($isAjax) {
            $this->json(['success' => true, 'message' => t('password_changed')]);
            return;
        }
        Session::setFlash('success', t('password_changed'));
        $this->redirect('account/dashboard');
    }

    // ===== NOTIFICATIONS =====
    public function notifications() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $prefs = $this->preferenceModel->findByUserId($userId);
        $this->render('customer/account/notifications', [
            'title' => t('notifications') . ' - ' . t('my_account'),
            'prefs' => $prefs
        ]);
    }

    public function notificationsSave() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('account/notifications');

        $userId = Session::getUserId();
        $this->preferenceModel->createOrUpdate($userId, [
            'email_notifications' => !empty($_POST['email_notifications']) ? 1 : 0,
            'sms_notifications' => !empty($_POST['sms_notifications']) ? 1 : 0,
            'order_updates' => !empty($_POST['order_updates']) ? 1 : 0,
            'promotions' => !empty($_POST['promotions']) ? 1 : 0,
            'newsletter' => !empty($_POST['newsletter']) ? 1 : 0,
            'price_drop_alerts' => !empty($_POST['price_drop_alerts']) ? 1 : 0,
            'back_in_stock' => !empty($_POST['back_in_stock']) ? 1 : 0,
        ]);
        Session::setFlash('success', t('notification_prefs_saved'));
        $this->redirect('account/notifications');
    }

    // ===== CONNECTED ACCOUNTS =====
    public function connectedAccounts() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $accounts = $this->connectedAccountModel->byUser($userId);
        $this->render('customer/account/connected_accounts', [
            'title' => t('connected_accounts') . ' - ' . t('my_account'),
            'connectedAccounts' => $accounts
        ]);
    }

    public function connectedAccountDelete() {
        $this->requireLogin();
        $id = $_GET['id'] ?? null;
        if ($id) $this->connectedAccountModel->delete($id);
        Session::setFlash('success', t('account_disconnected'));
        $this->redirect('account/connected');
    }

    // ===== APPEARANCE =====
    public function appearance() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $prefs = $this->preferenceModel->findByUserId($userId);
        if (!$prefs) {
            $prefs = ['theme' => 'system', 'language' => 'en', 'currency' => 'USD'];
        }
        $this->render('customer/account/appearance', [
            'title' => t('appearance') . ' - ' . t('my_account'),
            'prefs' => $prefs
        ]);
    }

    public function appearanceSave() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('account/appearance');

        $userId = Session::getUserId();
        $theme = $_POST['theme'] ?? 'system';
        $language = $_POST['language'] ?? 'en';
        $currency = $_POST['currency'] ?? 'USD';

        if (!in_array($theme, ['light', 'dark', 'system'])) $theme = 'system';
        if (!in_array($language, ['en', 'km'])) $language = 'en';
        if (!in_array($currency, ['USD', 'KHR'])) $currency = 'USD';

        $this->preferenceModel->createOrUpdate($userId, [
            'theme' => $theme,
            'language' => $language,
            'currency' => $currency,
        ]);

        Session::setFlash('success', t('appearance_saved'));
        $this->redirect('account/appearance');
    }

    // ===== PRIVACY =====
    public function privacy() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        $this->render('customer/account/privacy', [
            'title' => t('privacy') . ' - ' . t('my_account'),
            'user' => $user
        ]);
    }

    public function downloadData() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        $orders = $this->orderModel->findByUserId($userId);
        $addresses = $this->addressModel->byUser($userId);

        $data = [
            'user' => $user,
            'orders' => $orders,
            'addresses' => $addresses,
            'exported_at' => date('Y-m-d H:i:s')
        ];

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="my-data-' . date('Y-m-d') . '.json"');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    public function deleteAccount() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('account/dashboard');
        }

        $userId = Session::getUserId();
        $isAjax = !empty($_POST['ajax']);

        $user = $this->userModel->findById($userId);
        if (!$user) {
            if ($isAjax) { $this->json(['success' => false, 'message' => 'User not found']); return; }
            $this->redirect('account/dashboard');
        }

        $this->userModel->delete($userId);
        Session::logout('customer');

        if ($isAjax) {
            $this->json(['success' => true, 'message' => t('account_deleted')]);
            return;
        }
        Session::setFlash('success', t('account_deleted'));
        $this->redirect('');
    }

    // ===== PREFERENCE SAVE (AJAX) =====
    public function preferenceSave() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $key = $_POST['key'] ?? '';
        $value = $_POST['value'] ?? '';

        if (empty($key)) {
            $this->json(['success' => false, 'message' => 'Key is required']);
            return;
        }

        // Handle special keys
        if ($key === 'theme') {
            $this->preferenceModel->createOrUpdate($userId, ['theme' => $value]);
        } elseif ($key === 'auto_update_avatar') {
            $this->preferenceModel->createOrUpdate($userId, ['auto_update_avatar' => (int)$value]);
        } else {
            $this->preferenceModel->createOrUpdate($userId, [$key => $value]);
        }

        $this->json(['success' => true, 'message' => 'Preference saved']);
    }

    // ===== CONNECTED ACCOUNT TOGGLE (AJAX) =====
    public function connectedAccountToggle() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $provider = $_POST['provider'] ?? '';

        if (empty($provider)) {
            $this->json(['success' => false, 'message' => 'Provider is required']);
            return;
        }

        $existing = $this->connectedAccountModel->byUser($userId);
        $found = false;
        foreach ($existing as $acc) {
            if ($acc['provider'] === $provider) {
                $found = true;
                $this->connectedAccountModel->delete($acc['id']);
                $this->json(['success' => true, 'connected' => false, 'message' => 'Disconnected ' . ucfirst($provider)]);
                return;
            }
        }

        if (!$found) {
            $this->connectedAccountModel->create([
                'user_id' => $userId,
                'provider' => $provider,
                'connected' => 1,
                'connected_at' => date('Y-m-d H:i:s')
            ]);
            $this->json(['success' => true, 'connected' => true, 'message' => 'Connected ' . ucfirst($provider)]);
        }
    }

    // ===== DEVICE REVOKE (AJAX) =====
    public function deviceRevoke() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $deviceId = $_GET['id'] ?? null;

        if (!$deviceId) {
            $this->json(['success' => false, 'message' => 'Device ID required']);
            return;
        }

        $device = $this->deviceModel->byUser($userId);
        $found = false;
        foreach ($device as $d) {
            if ((string)$d['id'] === (string)$deviceId) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->json(['success' => false, 'message' => 'Device not found']);
            return;
        }

        $this->deviceModel->delete($deviceId);
        $this->json(['success' => true, 'message' => 'Device session revoked']);
    }

    // ===== DASHBOARD DATA (used by the main settings view) =====
    public function dashboard() {
        $this->requireLogin();
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        $orderCount = $this->orderModel->getCountByUser($userId);
        $totalSpent = $this->orderModel->getTotalSpentByUser($userId);
        $recentOrders = $this->orderModel->findByUserId($userId);
        $recentOrders = array_slice($recentOrders ?? [], 0, 5);
        $wishlistCount = $this->wishlistModel->getcount($userId);
        $addressCount = count($this->addressModel->byUser($userId));
        $coupons = $this->couponModel->all();
        $lastLogin = $this->loginHistoryModel->byUser($userId, 1);
        $lastLogin = $lastLogin ? $lastLogin[0] : null;
        $connectedAccounts = $this->connectedAccountModel->byUser($userId);
        $devicesList = $this->deviceModel->byUser($userId);
        $prefs = $this->preferenceModel->findByUserId($userId);
        $provinces = (new Province())->all();

        $this->render('customer/account/dashboard', [
            'title' => t('my_account'),
            'user' => $user,
            'provinces' => $provinces,
            'orderCount' => $orderCount,
            'totalSpent' => $totalSpent,
            'recentOrders' => $recentOrders,
            'wishlistCount' => $wishlistCount,
            'addressCount' => $addressCount,
            'coupons' => $coupons,
            'lastLogin' => $lastLogin,
            'connectedAccounts' => $connectedAccounts,
            'devicesList' => $devicesList,
            'prefs' => $prefs
        ]);
    }

    // --- Helper ---
    private function requireLogin() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', t('please_login'));
            $this->redirect('login');
        }
    }
}
