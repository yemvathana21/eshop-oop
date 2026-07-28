<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class UserDevice {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byUser($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_devices WHERE user_id = ? ORDER BY is_current DESC, last_active DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) { return []; }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_devices WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) { return null; }
    }

    public function createOrUpdate($data) {
        try {
            $existing = $this->findByDeviceFingerprint($data['user_id'], $data['device_name']);
            if ($existing) {
                $stmt = $this->db->prepare("UPDATE user_devices SET last_active = NOW(), is_current = 1, ip_address = ? WHERE id = ?");
                return $stmt->execute([$data['ip_address'] ?? null, $existing['id']]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO user_devices (user_id, device_name, device_type, browser, os, ip_address, is_current) VALUES (?, ?, ?, ?, ?, ?, 1)");
                return $stmt->execute([
                    $data['user_id'], $data['device_name'] ?? 'Unknown',
                    $data['device_type'] ?? null, $data['browser'] ?? null,
                    $data['os'] ?? null, $data['ip_address'] ?? null
                ]);
            }
        } catch (\PDOException $e) { return false; }
    }

    private function findByDeviceFingerprint($userId, $deviceName) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_devices WHERE user_id = ? AND device_name = ? LIMIT 1");
            $stmt->execute([$userId, $deviceName]);
            return $stmt->fetch();
        } catch (\PDOException $e) { return null; }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user_devices WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (\PDOException $e) { return false; }
    }
}
