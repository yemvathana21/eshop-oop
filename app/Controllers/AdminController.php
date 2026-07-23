<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\User;

class AdminController extends Controller {
    private $productModel;
    private $orderModel;
    private $categoryModel;
    private $userModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->categoryModel = new Category();
        $this->userModel = new User();

        if (!Session::isAdmin()) {
            Session::setFlash('error', 'Access denied. Admin only.');
            $this->redirect('admin/login');
        }
    }

    public function dashboard() {
        $totalSales = $this->orderModel->getTotalSales();
        $ordersCount = $this->orderModel->getOrdersCount();
        $recentOrders = $this->orderModel->getRecentOrders(5);
        $lowStockProducts = $this->productModel->getLowStockProducts(10);
        $totalProducts = count($this->productModel->all());
        $totalUsers = count($this->userModel->all());

        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard - E-Shop',
            'totalSales' => $totalSales,
            'ordersCount' => $ordersCount,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers
        ], 'admin');
    }

    // Products
    public function products() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 10;

        $products = $this->productModel->paginate($page, $limit);
        $totalProducts = $this->productModel->count();
        $totalPages = ceil($totalProducts / $limit);

        $this->render('admin/products', [
            'title' => 'Manage Products - E-Shop',
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ], 'admin');
    }

    public function productCreate() {
        $categories = $this->categoryModel->all();
        $this->render('admin/product_form', [
            'title' => 'Add Product - E-Shop',
            'product' => null,
            'categories' => $categories
        ], 'admin');
    }

    public function productSave() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/products');
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $comparePrice = !empty($_POST['compare_price']) ? (float)$_POST['compare_price'] : null;
        $stock = (int)($_POST['stock'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0) ?: null;
        $image = null;

        if (empty($name) || $price <= 0 || $stock < 0) {
            Session::setFlash('error', 'Please fill all required fields correctly.');
            $this->redirect('admin/product/create');
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            chmod($uploadDir, 0777);
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $filePath = $uploadDir . $fileName;
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                    $image = $fileName;
                }
            }
        }

        if ($this->productModel->create($name, $description, $price, $stock, $image, $categoryId, $comparePrice)) {
            Session::setFlash('success', 'Product created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create product.');
        }

        $this->redirect('admin/products');
    }

    public function productEdit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/products');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('admin/products');
        }

        $categories = $this->categoryModel->all();
        $this->render('admin/product_form', [
            'title' => 'Edit Product - E-Shop',
            'product' => $product,
            'categories' => $categories
        ], 'admin');
    }

    public function productUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/products');
        }

        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $comparePrice = !empty($_POST['compare_price']) ? (float)$_POST['compare_price'] : null;
        $stock = (int)($_POST['stock'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0) ?: null;
        $image = null;

        if (!$id || empty($name) || $price <= 0 || $stock < 0) {
            Session::setFlash('error', 'Please fill all required fields correctly.');
            $this->redirect('admin/product/edit?id=' . $id);
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . str_replace(' ', '_', basename($_FILES['image']['name']));
            $filePath = $uploadDir . $fileName;
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                    // Delete old image if exists
                    $oldProduct = $this->productModel->find($id);
                    if ($oldProduct && $oldProduct['image']) {
                        if (file_exists(UPLOAD_PATH . $oldProduct['image'])) {
                            unlink(UPLOAD_PATH . $oldProduct['image']);
                        }
                        if (file_exists(IMAGES_PATH . $oldProduct['image'])) {
                            unlink(IMAGES_PATH . $oldProduct['image']);
                        }
                    }
                    $image = $fileName;
                } else {
                    Session::setFlash('error', 'Failed to move uploaded file. Check folder permissions.');
                }
            } else {
                Session::setFlash('error', 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.');
            }
        }

        if ($this->productModel->update($id, $name, $description, $price, $stock, $image, $categoryId, $comparePrice)) {
            Session::setFlash('success', 'Product updated successfully.');
        } else {
            Session::setFlash('error', 'Failed to update product.');
        }

        $this->redirect('admin/products');
    }

    public function productDelete() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/products');
        }

        $product = $this->productModel->find($id);
        if ($product) {
            if ($product['image']) {
                if (file_exists(UPLOAD_PATH . $product['image'])) {
                    unlink(UPLOAD_PATH . $product['image']);
                }
                if (file_exists(IMAGES_PATH . $product['image'])) {
                    unlink(IMAGES_PATH . $product['image']);
                }
            }
            $this->productModel->delete($id);
            Session::setFlash('success', 'Product deleted.');
        } else {
            Session::setFlash('error', 'Product not found.');
        }

        $this->redirect('admin/products');
    }

    // Orders
    public function orders() {
        $orders = $this->orderModel->all();
        $this->render('admin/orders', [
            'title' => 'Manage Orders - E-Shop',
            'orders' => $orders
        ], 'admin');
    }

    public function orderDetail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/orders');
        }

        $order = $this->orderModel->findById($id);
        if (!$order) {
            Session::setFlash('error', 'Order not found.');
            $this->redirect('admin/orders');
        }

        $items = $this->orderModel->getItems($order['id']);

        $this->render('admin/order_detail', [
            'title' => 'Order #' . $order['invoice_number'] . ' - E-Shop',
            'order' => $order,
            'items' => $items
        ], 'admin');
    }

    // Inventory
    public function inventory() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 10;

        $products = $this->productModel->paginate($page, $limit);
        $totalProducts = $this->productModel->count();
        $totalPages = ceil($totalProducts / $limit);

        // Stats should still be based on all products for accuracy
        $allProducts = $this->productModel->all();
        $lowStock = $this->productModel->getLowStockProducts(10);
        $outOfStock = $this->productModel->getLowStockProducts(0);
        $totalStock = array_sum(array_column($allProducts, 'stock'));
        $totalValue = array_sum(array_map(fn($p) => $p['price'] * $p['stock'], $allProducts));

        $this->render('admin/inventory', [
            'title' => 'Inventory Management - E-Shop',
            'products' => $products,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'totalStock' => $totalStock,
            'totalValue' => $totalValue,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ], 'admin');
    }

    public function inventoryAdjust() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/inventory');
        }

        $id = $_POST['product_id'] ?? null;
        $action = $_POST['adjust_action'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 0);

        if (!$id || !$action || $quantity <= 0) {
            Session::setFlash('error', 'Invalid adjustment data.');
            $this->redirect('admin/inventory');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('admin/inventory');
        }

        if ($action === 'add') {
            $this->productModel->addStock($id, $quantity);
            Session::setFlash('success', "Added {$quantity} units to '{$product['name']}'. New stock: " . ($product['stock'] + $quantity));
        } elseif ($action === 'remove') {
            if ($product['stock'] < $quantity) {
                Session::setFlash('error', "Cannot remove {$quantity} units. Only {$product['stock']} in stock.");
                $this->redirect('admin/inventory');
            }
            $newStock = $product['stock'] - $quantity;
            $stmt = \App\Core\Database::getInstance()->getConnection()->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $stmt->execute([$newStock, $id]);
            Session::setFlash('success', "Removed {$quantity} units from '{$product['name']}'. New stock: {$newStock}");
        } elseif ($action === 'set') {
            $stmt = \App\Core\Database::getInstance()->getConnection()->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $stmt->execute([$quantity, $id]);
            Session::setFlash('success', "Set stock for '{$product['name']}' to {$quantity}.");
        }

        $this->redirect('admin/inventory');
    }

    public function orderInvoice() {
        $invoiceNumber = $_GET['inv'] ?? null;
        if (!$invoiceNumber) {
            $this->redirect('admin/orders');
        }

        $order = $this->orderModel->findByInvoiceNumber($invoiceNumber);
        if (!$order) {
            Session::setFlash('error', 'Invoice not found.');
            $this->redirect('admin/orders');
        }

        $items = $this->orderModel->getItems($order['id']);

        $this->render('customer/invoice', [
            'title' => 'Invoice ' . $invoiceNumber . ' - E-Shop',
            'order' => $order,
            'items' => $items,
            'isAdmin' => true
        ], 'admin');
    }

    // Categories
    public function categories() {
        $categories = $this->categoryModel->all();
        $this->render('admin/categories', [
            'title' => 'Manage Categories - E-Shop',
            'categories' => $categories
        ], 'admin');
    }

    public function categoryCreate() {
        $this->render('admin/category_form', [
            'title' => 'Add Category - E-Shop',
            'category' => null
        ], 'admin');
    }

    public function categorySave() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/categories');
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-tag');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if (empty($name) || empty($slug)) {
            Session::setFlash('error', 'Name and slug are required.');
            $this->redirect('admin/category/create');
        }

        $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));

        if ($this->categoryModel->slugExists($slug)) {
            Session::setFlash('error', 'A category with this slug already exists.');
            $this->redirect('admin/category/create');
        }

        if ($this->categoryModel->create($name, $slug, $icon, $sortOrder)) {
            Session::setFlash('success', 'Category created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create category.');
        }

        $this->redirect('admin/categories');
    }

    public function categoryEdit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/categories');
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            Session::setFlash('error', 'Category not found.');
            $this->redirect('admin/categories');
        }

        $this->render('admin/category_form', [
            'title' => 'Edit Category - E-Shop',
            'category' => $category
        ], 'admin');
    }

    public function categoryUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/categories');
        }

        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-tag');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if (!$id || empty($name) || empty($slug)) {
            Session::setFlash('error', 'Name and slug are required.');
            $this->redirect('admin/categories');
        }

        $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));

        if ($this->categoryModel->slugExists($slug, $id)) {
            Session::setFlash('error', 'A category with this slug already exists.');
            $this->redirect('admin/category/edit?id=' . $id);
        }

        if ($this->categoryModel->update($id, $name, $slug, $icon, $sortOrder)) {
            Session::setFlash('success', 'Category updated successfully.');
        } else {
            Session::setFlash('error', 'Failed to update category.');
        }

        $this->redirect('admin/categories');
    }

    public function categoryDelete() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/categories');
        }

        $category = $this->categoryModel->find($id);
        if ($category) {
            $this->categoryModel->delete($id);
            Session::setFlash('success', 'Category deleted. Products in this category have been unassigned.');
        } else {
            Session::setFlash('error', 'Category not found.');
        }

        $this->redirect('admin/categories');
    }

    // Users
    public function users() {
        $users = $this->userModel->all();
        foreach ($users as &$u) {
            $u['order_count'] = $this->userModel->getOrderCount($u['id']);
        }
        $this->render('admin/users', [
            'title' => 'Manage Users - E-Shop',
            'users' => $users
        ], 'admin');
    }

    public function userCreate() {
        $this->render('admin/user_form', [
            'title' => 'Add User - E-Shop',
            'user' => null
        ], 'admin');
    }

    public function userSave() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/users');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'customer';

        if (empty($name) || empty($email) || empty($password)) {
            Session::setFlash('error', 'All fields are required.');
            $this->redirect('admin/user/create');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('error', 'Invalid email address.');
            $this->redirect('admin/user/create');
        }

        if ($this->userModel->emailExists($email)) {
            Session::setFlash('error', 'Email already exists.');
            $this->redirect('admin/user/create');
        }

        if (strlen($password) < 6) {
            Session::setFlash('error', 'Password must be at least 6 characters.');
            $this->redirect('admin/user/create');
        }

        if ($this->userModel->create($name, $email, $password, $role)) {
            Session::setFlash('success', 'User created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create user.');
        }

        $this->redirect('admin/users');
    }

    public function userEdit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/users');
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('admin/users');
        }

        $this->render('admin/user_form', [
            'title' => 'Edit User - E-Shop',
            'user' => $user
        ], 'admin');
    }

    public function userUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/users');
        }

        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'customer';

        if (!$id || empty($name) || empty($email)) {
            Session::setFlash('error', 'Name and email are required.');
            $this->redirect('admin/users');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('error', 'Invalid email address.');
            $this->redirect('admin/user/edit?id=' . $id);
        }

        // Check email uniqueness (exclude current user)
        $existing = $this->userModel->findByEmail($email);
        if ($existing && $existing['id'] != $id) {
            Session::setFlash('error', 'Email already in use by another user.');
            $this->redirect('admin/user/edit?id=' . $id);
        }

        if ($password && strlen($password) < 6) {
            Session::setFlash('error', 'Password must be at least 6 characters.');
            $this->redirect('admin/user/edit?id=' . $id);
        }

        if ($this->userModel->update($id, $name, $email, $role, $password ?: null)) {
            Session::setFlash('success', 'User updated successfully.');
        } else {
            Session::setFlash('error', 'Failed to update user.');
        }

        $this->redirect('admin/users');
    }

    public function userDelete() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/users');
        }

        // Prevent deleting yourself
        if ($id == Session::get('user_id')) {
            Session::setFlash('error', 'You cannot delete your own account.');
            $this->redirect('admin/users');
        }

        $user = $this->userModel->findById($id);
        if ($user) {
            $this->userModel->delete($id);
            Session::setFlash('success', 'User deleted.');
        } else {
            Session::setFlash('error', 'User not found.');
        }

        $this->redirect('admin/users');
    }

    public function search() {
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            $this->json([]);
        }

        $results = [];
        $db = \App\Core\Database::getInstance()->getConnection();

        // Search products
        $stmt = $db->prepare("SELECT id, name, price, stock FROM products WHERE name LIKE ? OR description LIKE ? LIMIT 5");
        $like = "%{$q}%";
        $stmt->execute([$like, $like]);
        foreach ($stmt->fetchAll() as $row) {
            $results[] = [
                'title' => $row['name'],
                'subtitle' => '$' . number_format($row['price'], 2) . ' — Stock: ' . $row['stock'],
                'type' => 'Product',
                'icon' => 'box',
                'url' => BASE_URL . 'admin/product/edit?id=' . $row['id']
            ];
        }

        // Search orders
        $stmt = $db->prepare("
            SELECT o.id, o.invoice_number, o.total_price, o.status, u.name 
            FROM orders o JOIN users u ON o.user_id = u.id 
            WHERE o.invoice_number LIKE ? OR u.name LIKE ? OR u.email LIKE ? LIMIT 5
        ");
        $stmt->execute([$like, $like, $like]);
        foreach ($stmt->fetchAll() as $row) {
            $results[] = [
                'title' => '#' . $row['invoice_number'],
                'subtitle' => $row['name'] . ' — $' . number_format($row['total_price'], 2) . ' (' . ucfirst($row['status']) . ')',
                'type' => 'Order',
                'icon' => 'file-invoice',
                'url' => BASE_URL . 'admin/order?id=' . $row['id']
            ];
        }

        // Search users
        $stmt = $db->prepare("SELECT id, name, email, role FROM users WHERE name LIKE ? OR email LIKE ? LIMIT 5");
        $stmt->execute([$like, $like]);
        foreach ($stmt->fetchAll() as $row) {
            $results[] = [
                'title' => $row['name'],
                'subtitle' => $row['email'] . ' (' . ucfirst($row['role']) . ')',
                'type' => 'User',
                'icon' => 'user',
                'url' => BASE_URL . 'admin/orders'
            ];
        }

        $this->json($results);
    }
}
