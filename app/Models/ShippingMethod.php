<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class ShippingMethod {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        return $this->db->query("SELECT * FROM shipping_methods ORDER BY sort_order ASC, id ASC")->fetchAll();
    }

    public function active() {
        $stmt = $this->db->query("SELECT * FROM shipping_methods WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        return $stmt->fetchAll();
    }

    public function findByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM shipping_methods WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM shipping_methods WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($code, $label, $days, $cost, $isActive = 1, $sortOrder = 0) {
        $stmt = $this->db->prepare("INSERT INTO shipping_methods (code, label, days, cost, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$code, $label, $days, $cost, $isActive, $sortOrder]);
    }

    public function update($id, $code, $label, $days, $cost, $isActive, $sortOrder) {
        $stmt = $this->db->prepare("UPDATE shipping_methods SET code = ?, label = ?, days = ?, cost = ?, is_active = ?, sort_order = ? WHERE id = ?");
        return $stmt->execute([$code, $label, $days, $cost, $isActive, $sortOrder, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM shipping_methods WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function codeExists($code, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM shipping_methods WHERE code = ? AND id != ?");
            $stmt->execute([$code, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM shipping_methods WHERE code = ?");
            $stmt->execute([$code]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
