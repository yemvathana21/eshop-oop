<?php
namespace App\Models\User;

use App\Core\Database;
use PDO;

class UserPreference {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUserId($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_preferences WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function createOrUpdate($userId, $data) {
        try {
            $existing = $this->findByUserId($userId);
            if ($existing) {
                $sets = [];
                $params = [];
                foreach ($data as $key => $value) {
                    $sets[] = "`$key` = ?";
                    $params[] = $value;
                }
                $params[] = $userId;
                $stmt = $this->db->prepare("UPDATE user_preferences SET " . implode(', ', $sets) . " WHERE user_id = ?");
                return $stmt->execute($params);
            } else {
                $keys = array_keys($data);
                $values = array_values($data);
                $placeholders = rtrim(str_repeat('?,', count($values)), ',');
                $stmt = $this->db->prepare("INSERT INTO user_preferences (user_id, " . implode(', ', $keys) . ") VALUES ($userId, $placeholders)");
                return $stmt->execute($values);
            }
        } catch (\PDOException $e) {
            return false;
        }
    }
}
