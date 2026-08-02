<?php
namespace App\Models\Location;

use App\Core\Database;
use PDO;

class District {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byProvince($provinceCode) {
        $stmt = $this->db->prepare("SELECT * FROM districts WHERE province_code = ? ORDER BY name_en ASC");
        $stmt->execute([$provinceCode]);
        return $stmt->fetchAll();
    }

    public function findByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM districts WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM districts ORDER BY province_code, name_en ASC");
        return $stmt->fetchAll();
    }
}
