<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class UserConnectedAccount {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byUser($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_connected_accounts WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) { return []; }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_connected_accounts WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) { return null; }
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO user_connected_accounts (user_id, provider, provider_id, email, avatar_url) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['user_id'], $data['provider'], $data['provider_id'] ?? null,
                $data['email'] ?? null, $data['avatar_url'] ?? null
            ]);
        } catch (\PDOException $e) { return false; }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_connected_accounts WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) { return false; }
    }
}
