<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Wishlist {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function isWishlisted($productId, $userId) {
        if (!$userId) return false;
        $stmt = $this->db->prepare("SELECT id FROM wishlist WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$productId, $userId]);
        return $stmt->fetch() !== false;
    }

    public function toggle($productId, $userId) {
        if ($this->isWishlisted($productId, $userId)) {
            $stmt = $this->db->prepare("DELETE FROM wishlist WHERE product_id = ? AND user_id = ?");
            $stmt->execute([$productId, $userId]);
            return false;
        } else {
            $stmt = $this->db->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$userId, $productId]);
            return true;
        }
    }

    public function getcount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }
}
