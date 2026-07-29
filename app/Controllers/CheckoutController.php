<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Order;
use App\Models\Product;
use App\Models\Province;
use App\Models\UserAddress;
use App\Models\ShippingMethod;

class CheckoutController extends Controller {
    private $orderModel;
    private $productModel;
    private $addressModel;

    public function __construct() {
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->addressModel = new UserAddress();
    }

    public static function shippingMethods() {
        $methods = (new ShippingMethod())->active();
        $result = [];
        foreach ($methods as $m) {
            $result[$m['code']] = [
                'label' => $m['label'],
                'days' => $m['days'] ?? '',
                'cost' => (float)$m['cost'],
            ];
        }
        return $result;
    }

    private function filterSelectedCart($cart) {
        $selectedKeys = Session::get('checkout_selected', []);
        if (!empty($selectedKeys)) {
            $filtered = [];
            foreach ($selectedKeys as $key) {
                if (isset($cart[$key])) {
                    $filtered[$key] = $cart[$key];
                }
            }
            return $filtered;
        }
        return $cart;
    }

    public function index() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to checkout.');
            $this->redirect('login');
        }

        $cart = Session::get('cart', []);
        $cart = $this->filterSelectedCart($cart);
        if (empty($cart)) {
            Session::setFlash('error', 'Your cart is empty.');
            $this->redirect('cart');
        }

        $userId = Session::getUserId();
        $addresses = $this->addressModel->byUser($userId);
        $savedAddress = Session::get('checkout_address', []);
        $savedShipping = Session::get('checkout_shipping', []);
        $savedPayment = Session::get('checkout_payment', 'cod');

        $provinces = (new Province())->all();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $this->render('customer/checkout', [
            'title' => 'Checkout - E-Shop',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'addresses' => $addresses,
            'savedAddress' => $savedAddress,
            'savedShipping' => $savedShipping,
            'savedPayment' => $savedPayment,
            'shippingMethods' => self::shippingMethods(),
            'provinces' => $provinces,
        ]);
    }

    public function saveStep() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request.']);
        }

        $step = $_POST['step'] ?? null;

        switch ($step) {
            case 'address':
                $addressId = $_POST['address_id'] ?? null;
                if ($addressId) {
                    $address = $this->addressModel->findById($addressId);
                    if (!$address || $address['user_id'] != Session::getUserId()) {
                        $this->json(['success' => false, 'message' => 'Invalid address.']);
                    }
                    Session::set('checkout_address', $address);
                } else {
                    $fullName = trim($_POST['full_name'] ?? '');
                    $phone = trim($_POST['phone'] ?? '');
                    $street = trim($_POST['street'] ?? '');
                    $provinceCode = trim($_POST['province_code'] ?? '');
                    $districtCode = trim($_POST['district_code'] ?? '');
                    $communeCode = trim($_POST['commune_code'] ?? '');
                    $villageCode = trim($_POST['village_code'] ?? '');
                    $latitude = trim($_POST['latitude'] ?? '');
                    $longitude = trim($_POST['longitude'] ?? '');

                    if (empty($fullName) || empty($phone) || empty($street)) {
                        $this->json(['success' => false, 'message' => 'Please fill in name, phone, and street address.']);
                    }

                    $address = [
                        'full_name' => $fullName,
                        'phone' => $phone,
                        'street' => $street,
                        'province_code' => $provinceCode,
                        'district_code' => $districtCode,
                        'commune_code' => $communeCode,
                        'village_code' => $villageCode,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ];
                    Session::set('checkout_address', $address);
                }
                $formatted = $this->addressModel->getFullAddress($address);
                $nameText = $address['full_name'] ?? '';
                $phoneText = $address['phone'] ?? '';
                $fullText = $nameText;
                if ($phoneText) $fullText .= ' (' . $phoneText . ')';
                $fullText .= ' - ' . $formatted;
                Session::set('checkout_address_text', $fullText);
                $this->json(['success' => true, 'address_text' => $fullText]);
                break;

            case 'shipping':
                $method = $_POST['method'] ?? null;
                $methods = self::shippingMethods();
                if (!$method || !isset($methods[$method])) {
                    $this->json(['success' => false, 'message' => 'Invalid shipping method.']);
                }
                Session::set('checkout_shipping', array_merge(['key' => $method], $methods[$method]));
                $this->json(['success' => true]);
                break;

            case 'payment':
                $method = $_POST['method'] ?? null;
                if (!in_array($method, ['cod', 'card'])) {
                    $this->json(['success' => false, 'message' => 'Invalid payment method.']);
                }
                Session::set('checkout_payment', $method);
                $this->json(['success' => true]);
                break;

            default:
                $this->json(['success' => false, 'message' => 'Unknown step.']);
        }
    }

    public function process() {
        if (!Session::isLoggedIn()) {
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('checkout');
        }

        $cart = Session::get('cart', []);
        $cart = $this->filterSelectedCart($cart);
        if (empty($cart)) {
            Session::setFlash('error', 'Your cart is empty.');
            $this->redirect('cart');
        }

        // Validate cart hash to prevent tampering / double-submit
        $expectedHash = md5(json_encode($cart));
        $submittedHash = $_POST['cart_hash'] ?? '';
        if ($submittedHash !== $expectedHash) {
            Session::setFlash('error', 'Cart has changed. Please review your order.');
            $this->redirect('checkout');
        }

        $userId = Session::getUserId();
        $address = Session::get('checkout_address', []);
        $shipping = Session::get('checkout_shipping', []);
        $payment = Session::get('checkout_payment', 'cod');

        if (empty($address)) {
            Session::setFlash('error', 'Please provide a shipping address.');
            $this->redirect('checkout');
        }

        if (empty($shipping)) {
            Session::setFlash('error', 'Please select a shipping method.');
            $this->redirect('checkout');
        }

        // Re-verify stock for all cart items using current DB prices
        $subtotal = 0;
        foreach ($cart as $itemKey => $item) {
            $pid = $item['product_id'] ?? $itemKey;
            $product = $this->productModel->find($pid);
            if (!$product) {
                Session::setFlash('error', "Product '{$item['name']}' is no longer available.");
                $this->redirect('cart');
            }
            if (!$this->productModel->hasSufficientStock($pid, $item['quantity'])) {
                Session::setFlash('error', "Insufficient stock for '{$item['name']}'. Please adjust your cart.");
                $this->redirect('cart');
            }
            $subtotal += $product['price'] * $item['quantity'];
        }
        $shippingCost = $shipping['cost'] ?? 0;
        $total = $subtotal + $shippingCost;

        // Build shipping address text
        $province = '';
        $district = '';
        $commune = '';
        $village = '';
        if (!empty($address['province_code'])) {
            $p = (new \App\Models\Province())->findByCode($address['province_code']);
            $province = $p ? $p['name_en'] : '';
        }
        if (!empty($address['district_code'])) {
            $d = (new \App\Models\District())->findByCode($address['district_code']);
            $district = $d ? $d['name_en'] : '';
        }
        if (!empty($address['commune_code'])) {
            $c = (new \App\Models\Commune())->findByCode($address['commune_code']);
            $commune = $c ? $c['name_en'] : '';
        }
        if (!empty($address['village_code'])) {
            $v = (new \App\Models\Village())->findByCode($address['village_code']);
            $village = $v ? $v['name_en'] : '';
        }

        $addrParts = array_filter([$address['street'] ?? '', $village, $commune, $district, $province]);
        $addressText = implode(', ', $addrParts);
        $nameText = $address['full_name'] ?? '';

        $result = $this->orderModel->createOrder($userId, $total, $cart, [
            'shipping_name' => $nameText,
            'shipping_address' => $addressText,
            'shipping_method' => $shipping['label'] ?? '',
            'shipping_cost' => $shippingCost,
            'payment_method' => $payment,
        ]);

        if ($result['success']) {
            Session::remove('cart');
            Session::remove('checkout_address');
            Session::remove('checkout_shipping');
            Session::remove('checkout_payment');
            Session::remove('checkout_selected');
            Session::set('last_order', [
                'invoice_number' => $result['invoice_number'],
                'order_id' => $result['order_id'],
            ]);
            $this->redirect('order-confirmation');
        } else {
            Session::setFlash('error', $result['message']);
            $this->redirect('checkout');
        }
    }

    public function orderConfirmation() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to view your order.');
            $this->redirect('login');
        }

        $lastOrder = Session::get('last_order', []);
        if (empty($lastOrder)) {
            $this->redirect('');
        }

        $order = $this->orderModel->findById($lastOrder['order_id']);
        if (!$order || $order['user_id'] != Session::getUserId()) {
            Session::remove('last_order');
            $this->redirect('');
        }

        $items = $this->orderModel->getItems($order['id']);

        $this->render('customer/order_confirmation', [
            'title' => 'Order Placed - E-Shop',
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function invoice() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to view invoice.');
            $this->redirect('login');
        }

        $invoiceNumber = $_GET['inv'] ?? null;
        if (!$invoiceNumber) {
            $this->redirect('');
        }

        $order = $this->orderModel->findByInvoiceNumber($invoiceNumber);
        if (!$order) {
            Session::setFlash('error', 'Invoice not found.');
            $this->redirect('');
        }

        if (!Session::isAdmin() && $order['status'] === 'pending') {
            $this->redirect('order-confirmation');
        }

        if (!Session::isAdmin() && $order['user_id'] != Session::getUserId()) {
            Session::setFlash('error', 'Unauthorized access.');
            $this->redirect('');
        }

        $items = $this->orderModel->getItems($order['id']);

        $this->render('customer/invoice', [
            'title' => 'Invoice ' . $invoiceNumber . ' - E-Shop',
            'order' => $order,
            'items' => $items
        ]);
    }

    public function myOrders() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to view your orders.');
            $this->redirect('login');
        }

        $orders = $this->orderModel->findByUserId(Session::getUserId());
        
        $enriched = [];
        foreach ($orders as $order) {
            $order['items'] = $this->orderModel->getItems($order['id']);
            $enriched[] = $order;
        }

        $this->render('customer/my_orders', [
            'title' => 'My Orders - E-Shop',
            'orders' => $enriched
        ]);
    }
}
