<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.sort_order ASC");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function create($name, $slug, $icon, $sortOrder) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, slug, icon, sort_order) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $slug, $icon, $sortOrder]);
    }

    public function update($id, $name, $slug, $icon, $sortOrder) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, slug = ?, icon = ?, sort_order = ? WHERE id = ?");
        return $stmt->execute([$name, $slug, $icon, $sortOrder, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE products SET category_id = NULL WHERE category_id = ?");
        $stmt->execute([$id]);
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function slugExists($slug, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
