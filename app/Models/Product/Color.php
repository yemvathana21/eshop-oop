<?php
namespace App\Models\Product;

use App\Core\Database;
use PDO;

class Color {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        return $this->db->query("SELECT * FROM colors ORDER BY id ASC")->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM colors WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO colors (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function update($id, $name) {
        $stmt = $this->db->prepare("UPDATE colors SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM colors WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
