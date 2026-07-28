<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Province {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM provinces ORDER BY name_en ASC");
        return $stmt->fetchAll();
    }

    public function findByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM provinces WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public function findByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM provinces WHERE name_en LIKE ? OR name_km LIKE ?");
        $like = "%{$name}%";
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }
}
