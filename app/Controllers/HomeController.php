<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index() {
        $categories = $this->categoryModel->all();
        $featured = $this->productModel->featured(4);
        $allProducts = $this->productModel->all();

        $this->render('customer/home', [
            'title' => 'E-Shop - Premium Store',
            'categories' => $categories,
            'featured' => $featured,
            'allProducts' => $allProducts
        ]);
    }

    public function shop() {
        $categories = $this->categoryModel->all();
        $slug = $_GET['category'] ?? null;
        $priceFilter = $_GET['price'] ?? null;
        $search = trim($_GET['search'] ?? '');

        $currentCategory = null;
        if ($slug) {
            $currentCategory = $this->categoryModel->findBySlug($slug);
        }

        if ($currentCategory) {
            $products = $this->productModel->byCategory($currentCategory['id']);
        } else {
            $products = $this->productModel->all();
        }

        // Price filter
        if ($priceFilter) {
            $products = array_filter($products, function($p) use ($priceFilter) {
                $price = (float)$p['price'];
                switch ($priceFilter) {
                    case 'under50': return $price < 50;
                    case '50to100': return $price >= 50 && $price <= 100;
                    case 'over100': return $price > 100;
                    default: return true;
                }
            });
            $products = array_values($products);
        }

        // Search filter
        if ($search) {
            $products = array_filter($products, function($p) use ($search) {
                $term = strtolower($search);
                return stripos($p['name'], $term) !== false || stripos($p['description'] ?? '', $term) !== false;
            });
            $products = array_values($products);
        }

        $totalProducts = count($this->productModel->all());

        $this->render('customer/shop', [
            'title' => ($currentCategory ? $currentCategory['name'] . ' - ' : 'Shop - ') . 'E-Shop',
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'totalProducts' => $totalProducts
        ]);
    }

    public function productDetail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('shop');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            $this->redirect('shop');
        }

        $this->render('customer/product_detail', [
            'title' => $product['name'] . ' - E-Shop',
            'product' => $product
        ]);
    }
}
