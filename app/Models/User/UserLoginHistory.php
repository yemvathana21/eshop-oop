<?php
namespace App\Models\User;

use App\Core\Database;
use PDO;

class UserLoginHistory {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byUser($userId, $limit = 20) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_login_history WHERE user_id = ? ORDER BY logged_at DESC LIMIT ?");
            $stmt->bindValue(1, $userId, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) { return []; }
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO user_login_history (user_id, ip_address, user_agent, device_type, browser, os, location, success) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['user_id'], $data['ip_address'] ?? null, $data['user_agent'] ?? null,
                $data['device_type'] ?? null, $data['browser'] ?? null, $data['os'] ?? null,
                $data['location'] ?? null, !empty($data['success']) ? 1 : 0
            ]);
        } catch (\PDOException $e) { return false; }
    }

    public function deleteOlderThan($days = 90) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_login_history WHERE logged_at < NOW() - INTERVAL ? DAY");
            return $stmt->execute([$days]);
        } catch (\PDOException $e) { return false; }
    }
}
