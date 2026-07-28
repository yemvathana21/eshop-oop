<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class UserPaymentMethod {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byUser($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_payment_methods WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) { return []; }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_payment_methods WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) { return null; }
    }

    public function create($data) {
        try {
            $this->clearDefault($data['user_id']);
            $stmt = $this->db->prepare("INSERT INTO user_payment_methods (user_id, type, token, last_four, cardholder_name, expiry_month, expiry_year, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['user_id'], $data['type'], $data['token'] ?? null,
                $data['last_four'] ?? null, $data['cardholder_name'] ?? null,
                $data['expiry_month'] ?? null, $data['expiry_year'] ?? null,
                !empty($data['is_default']) ? 1 : 0
            ]);
        } catch (\PDOException $e) { return false; }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_payment_methods WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) { return false; }
    }

    public function setDefault($id, $userId) {
        try {
            $this->clearDefault($userId);
            $stmt = $this->db->prepare("UPDATE user_payment_methods SET is_default = 1 WHERE id = ? AND user_id = ?");
            return $stmt->execute([$id, $userId]);
        } catch (\PDOException $e) { return false; }
    }

    private function clearDefault($userId) {
        $stmt = $this->db->prepare("UPDATE user_payment_methods SET is_default = 0 WHERE user_id = ?");
        $stmt->execute([$userId]);
    }

    public function getDefault($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_payment_methods WHERE user_id = ? AND is_default = 1 LIMIT 1");
            $stmt->execute([$userId]);
            return $stmt->fetch();
        } catch (\PDOException $e) { return null; }
    }
}
