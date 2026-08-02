<?php
namespace App\Controllers\Customer;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product\Product;

class CartController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    private function getCartKey() {
        $userId = Session::getUserId();
        return $userId ? 'cart_' . $userId : 'cart_guest';
    }

    private function getCart() {
        return Session::get($this->getCartKey(), []);
    }

    private function saveCart($cart) {
        Session::set($this->getCartKey(), $cart);
    }

    public function view() {
        Session::remove('buy_now_item');
        Session::remove('checkout_selected');
        $cart = $this->getCart();
        $this->render('customer/cart', [
            'title' => 'Shopping Cart - General Online Store',
            'cart' => $cart
        ]);
    }

    public function add() {
        $productId = $_GET['id'] ?? $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);
        $sizeName = trim($_POST['size_name'] ?? '');
        $colorName = trim($_POST['color_name'] ?? '');

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
        $itemKey = $productId;
        if ($sizeName || $colorName) {
            $itemKey .= '|' . $sizeName . '|' . $colorName;
        }

        if (isset($cart[$itemKey])) {
            $newQty = $cart[$itemKey]['quantity'] + $quantity;
            if (!$this->productModel->hasSufficientStock($productId, $newQty)) {
                Session::setFlash('error', 'Not enough stock available. Current in cart: ' . $cart[$itemKey]['quantity']);
                $this->redirect('product?id=' . $productId);
            }
            $cart[$itemKey]['quantity'] = $newQty;
        } else {
            $cart[$itemKey] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'size_name' => $sizeName,
                'color_name' => $colorName
            ];
        }

        if (!empty($_POST['buy_now'])) {
            Session::set('buy_now_item', [$itemKey => [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'size_name' => $sizeName,
                'color_name' => $colorName
            ]]);
            $this->redirect('checkout');
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
        $itemKey = $_POST['item_key'] ?? $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 0);

        $cart = $this->getCart();

        if (!$itemKey || !isset($cart[$itemKey])) {
            Session::setFlash('error', 'Product not in cart.');
            $this->redirect('cart');
        }

        if ($quantity <= 0) {
            unset($cart[$itemKey]);
        } else {
            $pid = $cart[$itemKey]['product_id'] ?? $itemKey;
            if (!$this->productModel->hasSufficientStock($pid, $quantity)) {
                Session::setFlash('error', 'Not enough stock available.');
                $this->redirect('cart');
            }
            $cart[$itemKey]['quantity'] = $quantity;
        }

        $this->saveCart($cart);
        Session::setFlash('success', 'Cart updated.');
        $this->redirect('cart');
    }

    public function remove() {
        $itemKey = $_GET['id'] ?? $_POST['item_key'] ?? $_POST['product_id'] ?? null;

        $cart = $this->getCart();

        if ($itemKey && isset($cart[$itemKey])) {
            $name = $cart[$itemKey]['name'];
            unset($cart[$itemKey]);
            $this->saveCart($cart);
            Session::setFlash('success', $name . ' removed from cart.');
        }

        $this->redirect('cart');
    }

    public function clear() {
        Session::remove($this->getCartKey());
        Session::setFlash('success', 'Cart cleared.');
        $this->redirect('cart');
    }

    public function checkoutSelected() {
        $selected = $_POST['selected'] ?? [];
        if (empty($selected)) {
            Session::setFlash('error', 'Please select at least one item.');
            $this->redirect('cart');
        }
        $cart = $this->getCart();
        $valid = [];
        foreach ($selected as $key) {
            if (isset($cart[$key])) {
                $valid[] = $key;
            }
        }
        if (empty($valid)) {
            Session::setFlash('error', 'Selected items are no longer in your cart.');
            $this->redirect('cart');
        }
        Session::set('checkout_selected', $valid);
        $this->redirect('checkout');
    }

    private function calculateTotal($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
