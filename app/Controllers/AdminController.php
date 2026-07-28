<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\User;
use App\Models\Review;
use App\Models\Size;
use App\Models\Color;
use App\Models\Country;
use App\Models\ShippingCost;

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

        // Additional stats for enhanced dashboard
        $db = \App\Core\Database::getInstance()->getConnection();
        $reviewModel = new Review();
        $totalReviews = $reviewModel->countAll();

        // Orders by status
        $stmt = $db->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
        $ordersByStatus = [];
        foreach ($stmt->fetchAll() as $row) {
            $ordersByStatus[$row['status']] = $row['count'];
        }

        // Daily sales (last 7 days)
        $stmt = $db->query("SELECT DATE(created_at) as day, SUM(total_price) as total FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY day ASC");
        $dailySales = $stmt->fetchAll();

        // Fill missing days with 0
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $label = date('D', strtotime($date));
            $amount = 0;
            foreach ($dailySales as $ds) {
                if ($ds['day'] === $date) {
                    $amount = (float)$ds['total'];
                    break;
                }
            }
            $salesChart[] = ['label' => $label, 'amount' => $amount, 'date' => $date];
        }

        $maxSales = max(array_column($salesChart, 'amount')) ?: 1;

        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard - E-Shop',
            'totalSales' => $totalSales,
            'ordersCount' => $ordersCount,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalReviews' => $totalReviews,
            'ordersByStatus' => $ordersByStatus,
            'salesChart' => $salesChart,
            'maxSales' => $maxSales
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
            'title' => 'Product Management - E-Shop',
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ], 'admin');
    }

    public function productCreate() {
        $categoryModel = new Category();
        $sizeModel = new Size();
        $colorModel = new Color();

        $topCategories = $categoryModel->parentCategories();
        $this->render('admin/product_form', [
            'title' => 'Add Product - E-Shop',
            'product' => null,
            'topCategories' => $topCategories,
            'sizes' => $sizeModel->all(),
            'colors' => $colorModel->all()
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

        // Handle 3-level categories: Use the deepest selected one
        $categoryId = (int)($_POST['ecat_id'] ?? 0) ?: (int)($_POST['mcat_id'] ?? 0) ?: (int)($_POST['tcat_id'] ?? 0) ?: null;

        $image = null;
        $galleryImages = null;

        if (empty($name) || $price <= 0 || $stock < 0) {
            Session::setFlash('error', 'Please fill all required fields correctly.');
            $this->redirect('admin/product/create');
        }

        // Handle main image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $image = $fileName;
            }
        }

        // Handle gallery images
        $galleryFiles = [];
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['name'] as $idx => $gName) {
                if ($_FILES['gallery_images']['error'][$idx] === UPLOAD_ERR_OK) {
                    $gFileName = time() . '_' . $idx . '_' . basename($gName);
                    if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$idx], UPLOAD_PATH . $gFileName)) {
                        $galleryFiles[] = $gFileName;
                    }
                }
            }
        }
        if (!empty($galleryFiles)) $galleryImages = json_encode($galleryFiles);

        $productId = $this->productModel->create($name, $description, $price, $stock, $image, $categoryId, $comparePrice, $galleryImages);

        if ($productId) {
            // Handle Sizes and Colors
            $this->productModel->setSizes($productId, $_POST['size'] ?? []);
            $this->productModel->setColors($productId, $_POST['color'] ?? []);
            Session::setFlash('success', 'Product created successfully.');
        } else {
            Session::setFlash('error', 'Failed to create product.');
        }

        $this->redirect('admin/products');
    }

    public function productEdit() {
        $id = $_GET['id'] ?? null;
        if (!$id) $this->redirect('admin/products');

        $product = $this->productModel->find($id);
        if (!$product) {
            Session::setFlash('error', 'Product not found.');
            $this->redirect('admin/products');
        }

        $categoryModel = new Category();
        $sizeModel = new Size();
        $colorModel = new Color();

        $topCategories = $categoryModel->parentCategories();

        // Find category hierarchy for this product
        $catPath = [];
        if ($product['category_id']) {
            $catPath = $categoryModel->getPath($product['category_id']);
        }

        $tcat_id = isset($catPath[0]) ? $catPath[0]['id'] : null;
        $mcat_id = isset($catPath[1]) ? $catPath[1]['id'] : null;
        $ecat_id = isset($catPath[2]) ? $catPath[2]['id'] : null;

        $midCategories = $tcat_id ? $categoryModel->childrenOf($tcat_id) : [];
        $endCategories = $mcat_id ? $categoryModel->childrenOf($mcat_id) : [];

        $this->render('admin/product_form', [
            'title' => 'Edit Product - E-Shop',
            'product' => $product,
            'topCategories' => $topCategories,
            'midCategories' => $midCategories,
            'endCategories' => $endCategories,
            'tcat_id' => $tcat_id,
            'mcat_id' => $mcat_id,
            'ecat_id' => $ecat_id,
            'sizes' => $sizeModel->all(),
            'colors' => $colorModel->all(),
            'productSizes' => $this->productModel->getSizes($id),
            'productColors' => $this->productModel->getColors($id)
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

        $categoryId = (int)($_POST['ecat_id'] ?? 0) ?: (int)($_POST['mcat_id'] ?? 0) ?: (int)($_POST['tcat_id'] ?? 0) ?: null;

        if (!$id || empty($name) || $price <= 0 || $stock < 0) {
            Session::setFlash('error', 'Please fill all required fields correctly.');
            $this->redirect('admin/product/edit?id=' . $id);
        }

        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $fileName)) {
                $image = $fileName;
            }
        }

        // Gallery logic
        $existingGallery = $_POST['existing_gallery'] ?? '';
        $existingGalleryArr = $existingGallery ? json_decode($existingGallery, true) : [];
        if (!empty($_POST['remove_gallery'])) {
            foreach ($_POST['remove_gallery'] as $ridx) unset($existingGalleryArr[$ridx]);
            $existingGalleryArr = array_values($existingGalleryArr);
        }
        $newGalleryFiles = [];
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['name'] as $idx => $gName) {
                if ($_FILES['gallery_images']['error'][$idx] === UPLOAD_ERR_OK) {
                    $gFileName = time() . '_' . $idx . '_' . basename($gName);
                    if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$idx], UPLOAD_PATH . $gFileName)) {
                        $newGalleryFiles[] = $gFileName;
                    }
                }
            }
        }
        $galleryImages = json_encode(array_merge($existingGalleryArr, $newGalleryFiles));

        if ($this->productModel->update($id, $name, $description, $price, $stock, $image, $categoryId, $comparePrice, $galleryImages)) {
            $this->productModel->setSizes($id, $_POST['size'] ?? []);
            $this->productModel->setColors($id, $_POST['color'] ?? []);
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
        $statusFilter = $_GET['status'] ?? null;
        $search = trim($_GET['search'] ?? '');

        $allOrders = $this->orderModel->all();
        $statusCounts = [];
        foreach ($allOrders as $o) {
            $statusCounts[$o['status']] = ($statusCounts[$o['status']] ?? 0) + 1;
        }

        $orders = $allOrders;

        if ($statusFilter) {
            $orders = array_filter($orders, fn($o) => $o['status'] === $statusFilter);
            $orders = array_values($orders);
        }

        if ($search) {
            $orders = array_filter($orders, function($o) use ($search) {
                $term = strtolower($search);
                return stripos($o['invoice_number'], $term) !== false ||
                       stripos($o['user_name'], $term) !== false ||
                       stripos($o['user_email'], $term) !== false;
            });
            $orders = array_values($orders);
        }

        $this->render('admin/orders', [
            'title' => 'Order Management - E-Shop',
            'orders' => $orders,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'totalOrders' => count($allOrders),
            'statusCounts' => $statusCounts
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
        $categoryTree = $this->categoryModel->getTree();
        $this->render('admin/categories', [
            'title' => 'Category Management - E-Shop',
            'categories' => $categories,
            'categoryTree' => $categoryTree
        ], 'admin');
    }

    public function categoryCreate() {
        $parentCategories = $this->categoryModel->parentCategories();
        $this->render('admin/category_form', [
            'title' => 'Add Category - E-Shop',
            'category' => null,
            'parentCategories' => $parentCategories
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
        $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        if (empty($name) || empty($slug)) {
            Session::setFlash('error', 'Name and slug are required.');
            $this->redirect('admin/category/create');
        }

        $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));

        if ($this->categoryModel->slugExists($slug)) {
            Session::setFlash('error', 'A category with this slug already exists.');
            $this->redirect('admin/category/create');
        }

        if ($this->categoryModel->create($name, $slug, $icon, $sortOrder, $parentId)) {
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

        $parentCategories = $this->categoryModel->parentCategories();
        $this->render('admin/category_form', [
            'title' => 'Edit Category - E-Shop',
            'category' => $category,
            'parentCategories' => $parentCategories
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
        $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

        if (!$id || empty($name) || empty($slug)) {
            Session::setFlash('error', 'Name and slug are required.');
            $this->redirect('admin/categories');
        }

        if ($parentId == $id) {
            Session::setFlash('error', 'A category cannot be its own parent.');
            $this->redirect('admin/category/edit?id=' . $id);
        }

        $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));

        if ($this->categoryModel->slugExists($slug, $id)) {
            Session::setFlash('error', 'A category with this slug already exists.');
            $this->redirect('admin/category/edit?id=' . $id);
        }

        if ($this->categoryModel->update($id, $name, $slug, $icon, $sortOrder, $parentId)) {
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
            'title' => 'Registered Customer - E-Shop',
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

    // Reviews Management
    public function reviews() {
        $reviewModel = new Review();
        $db = \App\Core\Database::getInstance()->getConnection();

        $stmt = $db->query("
            SELECT r.*, u.name as user_name, u.email as user_email, p.name as product_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            JOIN products p ON r.product_id = p.id 
            ORDER BY r.created_at DESC
        ");
        $reviews = $stmt->fetchAll();

        $this->render('admin/reviews', [
            'title' => 'Customer Reviews - E-Shop',
            'reviews' => $reviews
        ], 'admin');
    }

    public function reviewDelete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $reviewModel = new Review();
            $reviewModel->delete($id);
            Session::setFlash('success', 'Review deleted.');
        }
        $this->redirect('admin/reviews');
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

    // Sizes
    public function sizes() {
        $model = new Size();
        $this->render('admin/settings/sizes', [
            'title' => 'Manage Sizes - E-Shop',
            'sizes' => $model->all()
        ], 'admin');
    }

    public function sizeSave() {
        $model = new Size();
        $name = trim($_POST['name'] ?? '');
        $id = $_POST['id'] ?? null;

        if (empty($name)) {
            Session::setFlash('error', 'Size name is required.');
        } else {
            if ($id) {
                $model->update($id, $name);
                Session::setFlash('success', 'Size updated.');
            } else {
                $model->create($name);
                Session::setFlash('success', 'Size added.');
            }
        }
        $this->redirect('admin/sizes');
    }

    public function sizeDelete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new Size())->delete($id);
            Session::setFlash('success', 'Size deleted.');
        }
        $this->redirect('admin/sizes');
    }

    // Colors
    public function colors() {
        $model = new Color();
        $this->render('admin/settings/colors', [
            'title' => 'Manage Colors - E-Shop',
            'colors' => $model->all()
        ], 'admin');
    }

    public function colorSave() {
        $model = new Color();
        $name = trim($_POST['name'] ?? '');
        $id = $_POST['id'] ?? null;

        if (empty($name)) {
            Session::setFlash('error', 'Color name is required.');
        } else {
            if ($id) {
                $model->update($id, $name);
                Session::setFlash('success', 'Color updated.');
            } else {
                $model->create($name);
                Session::setFlash('success', 'Color added.');
            }
        }
        $this->redirect('admin/colors');
    }

    public function colorDelete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new Color())->delete($id);
            Session::setFlash('success', 'Color deleted.');
        }
        $this->redirect('admin/colors');
    }

    // Countries
    public function countries() {
        $model = new Country();
        $this->render('admin/settings/countries', [
            'title' => 'Manage Countries - E-Shop',
            'countries' => $model->all()
        ], 'admin');
    }

    public function countrySave() {
        $model = new Country();
        $name = trim($_POST['name'] ?? '');
        $id = $_POST['id'] ?? null;

        if (empty($name)) {
            Session::setFlash('error', 'Country name is required.');
        } else {
            if ($id) {
                $model->update($id, $name);
                Session::setFlash('success', 'Country updated.');
            } else {
                $model->create($name);
                Session::setFlash('success', 'Country added.');
            }
        }
        $this->redirect('admin/countries');
    }

    public function countryDelete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new Country())->delete($id);
            Session::setFlash('success', 'Country deleted.');
        }
        $this->redirect('admin/countries');
    }

    // Shipping Costs
    public function shippingCosts() {
        $scModel = new ShippingCost();
        $cModel = new Country();
        $this->render('admin/settings/shipping_costs', [
            'title' => 'Manage Shipping Costs - E-Shop',
            'shippingCosts' => $scModel->all(),
            'countries' => $cModel->all()
        ], 'admin');
    }

    public function shippingCostSave() {
        $model = new ShippingCost();
        $countryId = $_POST['country_id'] ?? null;
        $amount = (float)($_POST['amount'] ?? 0);
        $id = $_POST['id'] ?? null;

        if (!$countryId) {
            Session::setFlash('error', 'Country is required.');
        } else {
            if ($model->countryExists($countryId, $id)) {
                Session::setFlash('error', 'Shipping cost for this country already exists.');
            } else {
                if ($id) {
                    $model->update($id, $countryId, $amount);
                    Session::setFlash('success', 'Shipping cost updated.');
                } else {
                    $model->create($countryId, $amount);
                    Session::setFlash('success', 'Shipping cost added.');
                }
            }
        }
        $this->redirect('admin/shipping-costs');
    }

    public function shippingCostDelete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new ShippingCost())->delete($id);
            Session::setFlash('success', 'Shipping cost deleted.');
        }
        $this->redirect('admin/shipping-costs');
    }

    // Top Level Categories
    public function topCategories() {
        $this->render('admin/settings/top_categories', [
            'title' => 'Top Level Categories - E-Shop',
            'categories' => $this->categoryModel->parentCategories()
        ], 'admin');
    }

    public function topCategorySave() {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-tag');
        $id = $_POST['id'] ?? null;

        if (empty($name) || empty($slug)) {
            Session::setFlash('error', 'Name and Slug are required.');
        } else {
            $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));
            if ($id) {
                $this->categoryModel->update($id, $name, $slug, $icon, 0, null);
                Session::setFlash('success', 'Top category updated.');
            } else {
                if ($this->categoryModel->slugExists($slug)) {
                    Session::setFlash('error', 'Slug already exists.');
                } else {
                    $this->categoryModel->create($name, $slug, $icon, 0, null);
                    Session::setFlash('success', 'Top category added.');
                }
            }
        }
        $this->redirect('admin/top-categories');
    }

    public function topCategoryDelete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->categoryModel->delete($id);
            Session::setFlash('success', 'Top category deleted.');
        }
        $this->redirect('admin/top-categories');
    }

    // Mid Level Categories
    public function midCategories() {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT c.*, p.name as parent_name
            FROM categories c
            JOIN categories p ON c.parent_id = p.id
            WHERE p.parent_id IS NULL
            ORDER BY p.name ASC, c.name ASC
        ");
        $midCategories = $stmt->fetchAll();

        $this->render('admin/settings/mid_categories', [
            'title' => 'Mid Level Categories - E-Shop',
            'categories' => $midCategories,
            'topCategories' => $this->categoryModel->parentCategories()
        ], 'admin');
    }

    public function midCategorySave() {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $parentId = $_POST['parent_id'] ?? null;
        $id = $_POST['id'] ?? null;

        if (empty($name) || empty($slug) || !$parentId) {
            Session::setFlash('error', 'All fields are required.');
        } else {
            $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));
            if ($id) {
                $this->categoryModel->update($id, $name, $slug, 'fa-circle-o', 0, $parentId);
                Session::setFlash('success', 'Mid category updated.');
            } else {
                $this->categoryModel->create($name, $slug, 'fa-circle-o', 0, $parentId);
                Session::setFlash('success', 'Mid category added.');
            }
        }
        $this->redirect('admin/mid-categories');
    }

    public function midCategoryDelete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->categoryModel->delete($id);
            Session::setFlash('success', 'Mid category deleted.');
        }
        $this->redirect('admin/mid-categories');
    }

    // End Level Categories
    public function endCategories() {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("
            SELECT c.*, m.name as mid_name, t.name as top_name
            FROM categories c
            JOIN categories m ON c.parent_id = m.id
            JOIN categories t ON m.parent_id = t.id
            ORDER BY t.name ASC, m.name ASC, c.name ASC
        ");
        $endCategories = $stmt->fetchAll();

        // Get mid categories for dropdown
        $stmt = $db->query("
            SELECT c.*, p.name as parent_name
            FROM categories c
            JOIN categories p ON c.parent_id = p.id
            WHERE p.parent_id IS NULL
        ");
        $midCategories = $stmt->fetchAll();

        $this->render('admin/settings/end_categories', [
            'title' => 'End Level Categories - E-Shop',
            'categories' => $endCategories,
            'midCategories' => $midCategories
        ], 'admin');
    }

    public function endCategorySave() {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $parentId = $_POST['parent_id'] ?? null;
        $id = $_POST['id'] ?? null;

        if (empty($name) || empty($slug) || !$parentId) {
            Session::setFlash('error', 'All fields are required.');
        } else {
            $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $slug));
            if ($id) {
                $this->categoryModel->update($id, $name, $slug, 'fa-circle-o', 0, $parentId);
                Session::setFlash('success', 'End category updated.');
            } else {
                $this->categoryModel->create($name, $slug, 'fa-circle-o', 0, $parentId);
                Session::setFlash('success', 'End category added.');
            }
        }
        $this->redirect('admin/end-categories');
    }

    public function getSubcategories() {
        $parentId = $_GET['parent_id'] ?? null;
        if (!$parentId) $this->json([]);

        $categoryModel = new Category();
        $subs = $categoryModel->childrenOf($parentId);
        $this->json($subs);
    }
}
