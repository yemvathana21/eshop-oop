<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Coupon {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        try {
            $stmt = $this->db->query("SELECT * FROM coupons WHERE is_active = 1 AND (expires_at IS NULL OR expires_at >= CURDATE()) ORDER BY id DESC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function findByCode($code) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1 AND (expires_at IS NULL OR expires_at >= CURDATE()) LIMIT 1");
            $stmt->execute([$code]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return null;
        }
    }
}
