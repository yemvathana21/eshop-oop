<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Village {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byCommune($communeCode) {
        $stmt = $this->db->prepare("SELECT * FROM villages WHERE commune_code = ? ORDER BY name_en ASC");
        $stmt->execute([$communeCode]);
        return $stmt->fetchAll();
    }

    public function findByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM villages WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }
}
