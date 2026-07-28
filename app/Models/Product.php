<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
        return $stmt->fetchAll();
    }

    public function paginate($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug
                                    FROM products p
                                    LEFT JOIN categories c ON p.category_id = c.id
                                    ORDER BY p.id DESC
                                    LIMIT ? OFFSET ?");
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM products");
        return (int)$stmt->fetchColumn();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function byCategory($categoryId) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? ORDER BY p.id DESC");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function byCategories($categoryIds) {
        if (empty($categoryIds)) return [];
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id IN ($placeholders) ORDER BY p.id DESC");
        $stmt->execute($categoryIds);
        return $stmt->fetchAll();
    }

    public function featured($limit = 4) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock > 0 ORDER BY p.created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function create($name, $description, $price, $stock, $image, $categoryId = null, $comparePrice = null, $galleryImages = null) {
        $stmt = $this->db->prepare("INSERT INTO products (name, description, price, compare_price, stock, image, category_id, gallery_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$name, $description, $price, $comparePrice, $stock, $image, $categoryId, $galleryImages]);
        return $success ? $this->db->lastInsertId() : false;
    }

    public function update($id, $name, $description, $price, $stock, $image = null, $categoryId = null, $comparePrice = null, $galleryImages = null) {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE products SET name = ?, description = ?, price = ?, compare_price = ?, stock = ?, image = ?, category_id = ?, gallery_images = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $price, $comparePrice, $stock, $image, $categoryId, $galleryImages, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE products SET name = ?, description = ?, price = ?, compare_price = ?, stock = ?, category_id = ?, gallery_images = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $price, $comparePrice, $stock, $categoryId, $galleryImages, $id]);
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function hasSufficientStock($id, $quantity) {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }
        return $product['stock'] >= $quantity;
    }

    public function deductStock($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $success = $stmt->execute([$quantity, $id, $quantity]);
        return $success && $stmt->rowCount() > 0;
    }

    public function addStock($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        return $stmt->execute([$quantity, $id]);
    }

    public function getLowStockProducts($threshold = 10) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE stock <= ? ORDER BY stock ASC");
        $stmt->execute([$threshold]);
        return $stmt->fetchAll();
    }

    public function latest($limit = 8) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock > 0 ORDER BY p.id DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function popular($limit = 8) {
        // In a real app, join with order_items. For now, random or by price.
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock > 0 ORDER BY RAND() LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    // Pivot tables management
    public function setSizes($productId, $sizeIds) {
        $stmt = $this->db->prepare("DELETE FROM product_size WHERE product_id = ?");
        $stmt->execute([$productId]);
        if (!empty($sizeIds)) {
            $stmt = $this->db->prepare("INSERT INTO product_size (product_id, size_id) VALUES (?, ?)");
            foreach ($sizeIds as $sid) {
                $stmt->execute([$productId, $sid]);
            }
        }
    }

    public function setColors($productId, $colorIds) {
        $stmt = $this->db->prepare("DELETE FROM product_color WHERE product_id = ?");
        $stmt->execute([$productId]);
        if (!empty($colorIds)) {
            $stmt = $this->db->prepare("INSERT INTO product_color (product_id, color_id) VALUES (?, ?)");
            foreach ($colorIds as $cid) {
                $stmt->execute([$productId, $cid]);
            }
        }
    }

    public function getSizes($productId) {
        $stmt = $this->db->prepare("SELECT size_id FROM product_size WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getColors($productId) {
        $stmt = $this->db->prepare("SELECT color_id FROM product_color WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function relatedByCategory($categoryId, $limit = 4) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? ORDER BY RAND() LIMIT ?");
        $stmt->execute([$categoryId, $limit]);
        return $stmt->fetchAll();
    }
}
