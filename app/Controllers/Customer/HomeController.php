<?php
namespace App\Controllers\Customer;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Product\Review;
use App\Models\User\Wishlist;
use App\Models\Contact\ContactMessage;

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
        $featured = $this->productModel->featured(8);
        $latest = $this->productModel->latest(8);
        $popular = $this->productModel->popular(8);
        $allProducts = $this->productModel->all();
        $wishlistedIds = $this->wishlistModel->getProductIds(Session::getUserId());

        $this->render('customer/home', [
            'title' => 'General Online Store - Premium Store',
            'categories' => $categories,
            'categoryTree' => $categoryTree,
            'featured' => $featured,
            'latest' => $latest,
            'popular' => $popular,
            'allProducts' => $allProducts,
            'wishlistedIds' => $wishlistedIds
        ]);
    }

    public function shop() {
        $categories = $this->categoryModel->all();
        $categoryTree = $this->categoryModel->getTree();
        $slug = $_GET['category'] ?? null;
        $priceFilter = $_GET['price'] ?? null;
        $search = trim($_GET['search'] ?? '');

        $currentCategory = null;
        $categoryPath = [];
        if ($slug) {
            $currentCategory = $this->categoryModel->findBySlug($slug);
            if ($currentCategory) {
                $categoryPath = $this->categoryModel->getPath($currentCategory['id']);
            }
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
        $wishlistedIds = $this->wishlistModel->getProductIds(Session::getUserId());

        $this->render('customer/shop', [
            'title' => ($currentCategory ? $currentCategory['name'] . ' - ' : 'Shop - ') . 'General Online Store',
            'products' => $products,
            'categories' => $categories,
            'categoryTree' => $categoryTree,
            'currentCategory' => $currentCategory,
            'categoryPath' => $categoryPath,
            'totalProducts' => $totalProducts,
            'wishlistedIds' => $wishlistedIds
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

        $sizes = $this->productModel->getSizesWithNames($product['id']);
        $colors = $this->productModel->getColorsWithNames($product['id']);

        $this->render('customer/product_detail', [
            'title' => $product['name'] . ' - General Online Store',
            'product' => $product,
            'reviews' => $reviews,
            'ratingData' => $ratingData,
            'distribution' => $distribution,
            'isWishlisted' => $isWishlisted,
            'relatedProducts' => $relatedProducts,
            'galleryImages' => $galleryImages,
            'sizes' => $sizes,
            'colors' => $colors
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

    public function about() {
        $this->render('customer/about', [
            'title' => 'About Us - General Online Store'
        ]);
    }

    public function contact() {
        $this->render('customer/contact', [
            'title' => 'Contact Us - General Online Store'
        ]);
    }

    public function faq() {
        $this->render('customer/faq', [
            'title' => 'FAQ - General Online Store'
        ]);
    }

    public function contactSubmit() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            Session::setFlash('error', 'Please fill in all required fields.');
            $this->redirect('contact');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('error', 'Please provide a valid email address.');
            $this->redirect('contact');
        }

        $cmModel = new ContactMessage();
        if ($cmModel->create([
            'user_id' => Session::getUserId(),
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ])) {
            Session::setFlash('success', 'Your message has been sent successfully. We will get back to you soon!');
        } else {
            Session::setFlash('error', 'Failed to send message. Please try again later.');
        }

        $this->redirect('contact');
    }
}
