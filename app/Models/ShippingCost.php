<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class ShippingCost {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        return $this->db->query("
            SELECT sc.*, c.name as country_name
            FROM shipping_costs sc
            JOIN countries c ON sc.country_id = c.id
            ORDER BY c.name ASC
        ")->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM shipping_costs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($countryId, $amount) {
        $stmt = $this->db->prepare("INSERT INTO shipping_costs (country_id, amount) VALUES (?, ?)");
        return $stmt->execute([$countryId, $amount]);
    }

    public function update($id, $countryId, $amount) {
        $stmt = $this->db->prepare("UPDATE shipping_costs SET country_id = ?, amount = ? WHERE id = ?");
        return $stmt->execute([$countryId, $amount, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM shipping_costs WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countryExists($countryId, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM shipping_costs WHERE country_id = ? AND id != ?");
            $stmt->execute([$countryId, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM shipping_costs WHERE country_id = ?");
            $stmt->execute([$countryId]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
