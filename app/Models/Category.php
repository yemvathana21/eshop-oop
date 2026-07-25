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

    public function parentCategories() {
        $stmt = $this->db->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id WHERE c.parent_id IS NULL GROUP BY c.id ORDER BY c.sort_order ASC");
        return $stmt->fetchAll();
    }

    public function childrenOf($parentId) {
        $stmt = $this->db->prepare("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id WHERE c.parent_id = ? GROUP BY c.id ORDER BY c.sort_order ASC");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll();
    }

    public function getChildrenIds($parentId) {
        $children = $this->childrenOf($parentId);
        return array_column($children, 'id');
    }

    public function getChildIdsRecursive($parentId) {
        $ids = [];
        $children = $this->childrenOf($parentId);
        foreach ($children as $child) {
            $ids[] = $child['id'];
            $ids = array_merge($ids, $this->getChildIdsRecursive($child['id']));
        }
        return $ids;
    }

    public function getTree() {
        $all = $this->all();
        $tree = [];
        $lookup = [];
        foreach ($all as $cat) {
            $cat['children'] = [];
            $lookup[$cat['id']] = $cat;
        }
        foreach ($all as $cat) {
            if ($cat['parent_id'] && isset($lookup[$cat['parent_id']])) {
                $lookup[$cat['parent_id']]['children'][] = &$lookup[$cat['id']];
            } else {
                $tree[] = &$lookup[$cat['id']];
            }
        }
        return $tree;
    }

    public function getPath($categoryId) {
        $path = [];
        $cat = $this->find($categoryId);
        while ($cat) {
            array_unshift($path, $cat);
            $cat = $cat['parent_id'] ? $this->find($cat['parent_id']) : null;
        }
        return $path;
    }

    public function create($name, $slug, $icon, $sortOrder, $parentId = null) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, slug, icon, sort_order, parent_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $slug, $icon, $sortOrder, $parentId]);
    }

    public function update($id, $name, $slug, $icon, $sortOrder, $parentId = null) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ?, slug = ?, icon = ?, sort_order = ?, parent_id = ? WHERE id = ?");
        return $stmt->execute([$name, $slug, $icon, $sortOrder, $parentId, $id]);
    }

    public function delete($id) {
        $children = $this->childrenOf($id);
        foreach ($children as $child) {
            $this->update($child['id'], $child['name'], $child['slug'], $child['icon'], $child['sort_order'], null);
        }
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
