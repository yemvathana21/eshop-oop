<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Commune {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byDistrict($districtCode) {
        $stmt = $this->db->prepare("SELECT * FROM communes WHERE district_code = ? ORDER BY name_en ASC");
        $stmt->execute([$districtCode]);
        return $stmt->fetchAll();
    }

    public function findByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM communes WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }
}
