<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Review {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByProduct($productId) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.name as user_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getAvgRating($productId) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public function getRatingDistribution($productId) {
        $stmt = $this->db->prepare("SELECT rating, COUNT(*) as count FROM reviews WHERE product_id = ? GROUP BY rating ORDER BY rating DESC");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function hasUserReviewed($productId, $userId) {
        if (!$userId) return false;
        $stmt = $this->db->prepare("SELECT id FROM reviews WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$productId, $userId]);
        return $stmt->fetch() !== false;
    }

    public function create($productId, $userId, $rating, $comment) {
        $stmt = $this->db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$productId, $userId, $rating, $comment]);
    }

    public function update($id, $rating, $comment) {
        $stmt = $this->db->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
        return $stmt->execute([$rating, $comment, $id]);
    }

    public function getByUserAndProduct($userId, $productId) {
        $stmt = $this->db->prepare("SELECT * FROM reviews WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        return $stmt->fetch();
    }

    public function delete($id, $userId = null) {
        if ($userId) {
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
            return $stmt->execute([$id, $userId]);
        }
        $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countByProduct($productId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reviews WHERE product_id = ?");
        $stmt->execute([$productId]);
        return (int)$stmt->fetchColumn();
    }

    public function countAll() {
        return (int)$this->db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    }
}
