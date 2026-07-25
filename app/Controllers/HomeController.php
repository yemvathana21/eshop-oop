<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Wishlist;

class HomeController extends Controller {
    private $productModel;
    private $categoryModel;
    private $reviewModel;
    private $wishlistModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->reviewModel = new Review();
        $this->wishlistModel = new Wishlist();
    }

    public function index() {
        $categories = $this->categoryModel->all();
        $categoryTree = $this->categoryModel->getTree();
        $featured = $this->productModel->featured(4);
        $allProducts = $this->productModel->all();

        $this->render('customer/home', [
            'title' => 'E-Shop - Premium Store',
            'categories' => $categories,
            'categoryTree' => $categoryTree,
            'featured' => $featured,
            'allProducts' => $allProducts
        ]);
    }

    public function shop() {
        $categories = $this->categoryModel->all();
        $categoryTree = $this->categoryModel->getTree();
        $slug = $_GET['category'] ?? null;
        $priceFilter = $_GET['price'] ?? null;
        $search = trim($_GET['search'] ?? '');

        $currentCategory = null;
        if ($slug) {
            $currentCategory = $this->categoryModel->findBySlug($slug);
        }

        if ($currentCategory) {
            $categoryIds = [$currentCategory['id']];
            $children = $this->categoryModel->getChildIdsRecursive($currentCategory['id']);
            $categoryIds = array_merge($categoryIds, $children);
            $products = $this->productModel->byCategories($categoryIds);
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
            'categoryTree' => $categoryTree,
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

        $userId = Session::getUserId();
        $categoryId = $product['category_id'];

        $reviews = $this->reviewModel->getByProduct($product['id']);
        $ratingData = $this->reviewModel->getAvgRating($product['id']);
        $distribution = $this->reviewModel->getRatingDistribution($product['id']);
        $isWishlisted = $this->wishlistModel->isWishlisted($product['id'], $userId);
        $relatedProducts = $this->productModel->relatedByCategory($categoryId, 4);

        $galleryImages = [];
        if (!empty($product['gallery_images'])) {
            $galleryImages = json_decode($product['gallery_images'], true) ?? [];
        }

        $this->render('customer/product_detail', [
            'title' => $product['name'] . ' - E-Shop',
            'product' => $product,
            'reviews' => $reviews,
            'ratingData' => $ratingData,
            'distribution' => $distribution,
            'isWishlisted' => $isWishlisted,
            'relatedProducts' => $relatedProducts,
            'galleryImages' => $galleryImages
        ]);
    }

    public function submitReview() {
        $userId = Session::getUserId();
        if (!$userId) {
            Session::setFlash('error', 'Please login to write a review.');
            $this->redirect('login');
        }

        $productId = $_POST['product_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = trim($_POST['comment'] ?? '');

        if (!$productId || !$rating || $rating < 1 || $rating > 5) {
            Session::setFlash('error', 'Invalid review data.');
            $this->redirect('product?id=' . $productId);
        }

        $this->reviewModel->create($productId, $userId, (int)$rating, $comment);
        Session::setFlash('success', 'Review submitted!');
        $this->redirect('product?id=' . $productId);
    }

    public function deleteReview() {
        $userId = Session::getUserId();
        if (!$userId) {
            $this->redirect('login');
        }

        $reviewId = $_GET['id'] ?? null;
        $productId = $_GET['product_id'] ?? null;

        if ($reviewId) {
            $this->reviewModel->delete($reviewId, $userId);
            Session::setFlash('success', 'Review deleted.');
        }

        $this->redirect('product?id=' . $productId);
    }

    public function toggleWishlist() {
        $userId = Session::getUserId();
        if (!$userId) {
            $this->json(['success' => false, 'message' => 'Please login first.']);
        }

        $productId = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Invalid product.']);
        }

        $added = $this->wishlistModel->toggle($productId, $userId);
        $this->json([
            'success' => true, 
            'wishlisted' => $added,
            'message' => $added ? 'Added to wishlist!' : 'Removed from wishlist.'
        ]);
    }
}
