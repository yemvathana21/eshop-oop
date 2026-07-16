<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Order;
use App\Models\Product;

class CheckoutController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function index() {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Please login to checkout.');
            $this->redirect('login');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            Session::setFlash('error', 'Your cart is empty.');
            $this->redirect('cart');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->render('customer/checkout', [
            'title' => 'Checkout - E-Shop',
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function process() {
        if (!Session::isLoggedIn()) {
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('checkout');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            Session::setFlash('error', 'Your cart is empty.');
            $this->redirect('cart');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $userId = Session::getUserId();
        $result = $this->orderModel->createOrder($userId, $total, $cart);

        if ($result['success']) {
            Session::remove('cart');
            Session::setFlash('success', 'Order placed successfully! Invoice: ' . $result['invoice_number']);
            $this->redirect('invoice?inv=' . $result['invoice_number']);
        } else {
            Session::setFlash('error', $result['message']);
            $this->redirect('checkout');
        }
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

        // Customers can only view their own invoices
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
