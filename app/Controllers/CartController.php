<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;

class CartController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    private function getCart() {
        return Session::get('cart', []);
    }

    private function saveCart($cart) {
        Session::set('cart', $cart);
    }

    public function view() {
        $cart = $this->getCart();
        $this->render('customer/cart', [
            'title' => 'Shopping Cart - E-Shop',
            'cart' => $cart
        ]);
    }

    public function add() {
        $productId = $_GET['id'] ?? $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$productId) {
            Session::setFlash('error', 'Invalid product.');
            $this->redirect('');
        }

        $product = $this->productModel->find($productId);
        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('');
        }

        if (!$this->productModel->hasSufficientStock($productId, $quantity)) {
            Session::setFlash('error', 'Not enough stock available.');
            $this->redirect('product?id=' . $productId);
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $quantity;
            if (!$this->productModel->hasSufficientStock($productId, $newQty)) {
                Session::setFlash('error', 'Not enough stock available. Current in cart: ' . $cart[$productId]['quantity']);
                $this->redirect('product?id=' . $productId);
            }
            $cart[$productId]['quantity'] = $newQty;
        } else {
            $cart[$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }

        $this->saveCart($cart);
        Session::setFlash('success', $product['name'] . ' added to cart.');

        // Stay on the same page (shop or product detail)
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (!empty($referer) && parse_url($referer, PHP_URL_HOST) === parse_url(BASE_URL, PHP_URL_HOST)) {
            $path = parse_url($referer, PHP_URL_PATH);
            $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '\\/');
            $baseUrlPath = parse_url(rtrim(BASE_URL, '/'), PHP_URL_PATH);
            foreach ([$scriptDir, $baseUrlPath] as $prefix) {
                if (!empty($prefix) && strpos($path, $prefix) === 0) {
                    $path = substr($path, strlen($prefix));
                    break;
                }
            }
            $this->redirect(ltrim($path, '/'));
        } else {
            $this->redirect('');
        }
    }

    public function update() {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 0);

        $cart = $this->getCart();

        if (!$productId || !isset($cart[$productId])) {
            Session::setFlash('error', 'Product not in cart.');
            $this->redirect('cart');
        }

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            if (!$this->productModel->hasSufficientStock($productId, $quantity)) {
                Session::setFlash('error', 'Not enough stock available.');
                $this->redirect('cart');
            }
            $cart[$productId]['quantity'] = $quantity;
        }

        $this->saveCart($cart);
        Session::setFlash('success', 'Cart updated.');
        $this->redirect('cart');
    }

    public function remove() {
        $productId = $_GET['id'] ?? $_POST['product_id'] ?? null;

        $cart = $this->getCart();

        if ($productId && isset($cart[$productId])) {
            $name = $cart[$productId]['name'];
            unset($cart[$productId]);
            $this->saveCart($cart);
            Session::setFlash('success', $name . ' removed from cart.');
        }

        $this->redirect('cart');
    }

    public function clear() {
        Session::remove('cart');
        Session::setFlash('success', 'Cart cleared.');
        $this->redirect('cart');
    }

    private function calculateTotal($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
