<?php
namespace App\Models\Product;

use App\Core\Database;
use PDO;

class Size {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        return $this->db->query("SELECT * FROM sizes ORDER BY id ASC")->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM sizes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO sizes (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function update($id, $name) {
        $stmt = $this->db->prepare("UPDATE sizes SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM sizes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
