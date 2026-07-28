<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class UserAddress {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function byUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM user_addresses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getDefault($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user_addresses WHERE user_id = ? AND is_default = 1 LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function create($data) {
        $this->clearDefaultIfNeeded($data['user_id'], $data['is_default'] ?? false);
        $stmt = $this->db->prepare("INSERT INTO user_addresses (user_id, label, full_name, phone, province_code, district_code, commune_code, village_code, street, zip_code, latitude, longitude, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'], $data['label'] ?? 'Billing', $data['full_name'] ?? null,
            $data['phone'] ?? null, $data['province_code'] ?? null, $data['district_code'] ?? null,
            $data['commune_code'] ?? null, $data['village_code'] ?? null, $data['street'] ?? null,
            $data['zip_code'] ?? null, $data['latitude'] ?? null, $data['longitude'] ?? null,
            !empty($data['is_default']) ? 1 : 0
        ]);
    }

    public function update($id, $data) {
        $addr = $this->findById($id);
        if (!$addr) return false;
        $this->clearDefaultIfNeeded($addr['user_id'], $data['is_default'] ?? false, $id);
        $stmt = $this->db->prepare("UPDATE user_addresses SET label=?, full_name=?, phone=?, province_code=?, district_code=?, commune_code=?, village_code=?, street=?, zip_code=?, latitude=?, longitude=?, is_default=? WHERE id=?");
        return $stmt->execute([
            $data['label'] ?? $addr['label'], $data['full_name'] ?? $addr['full_name'],
            $data['phone'] ?? $addr['phone'], $data['province_code'] ?? $addr['province_code'],
            $data['district_code'] ?? $addr['district_code'], $data['commune_code'] ?? $addr['commune_code'],
            $data['village_code'] ?? $addr['village_code'], $data['street'] ?? $addr['street'],
            $data['zip_code'] ?? $addr['zip_code'], $data['latitude'] ?? $addr['latitude'],
            $data['longitude'] ?? $addr['longitude'], !empty($data['is_default']) ? 1 : 0, $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM user_addresses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getFullAddress($addr) {
        if (!$addr) return '';
        $parts = [];
        $province = (new Province())->findByCode($addr['province_code'] ?? '');
        $district = (new District())->findByCode($addr['district_code'] ?? '');
        $commune = (new Commune())->findByCode($addr['commune_code'] ?? '');
        $village = (new Village())->findByCode($addr['village_code'] ?? '');
        if ($village) $parts[] = $village['name_en'];
        if ($commune) $parts[] = $commune['name_en'];
        if ($district) $parts[] = $district['name_en'];
        if ($province) $parts[] = $province['name_en'];
        if (!empty($addr['street'])) array_unshift($parts, $addr['street']);
        return implode(', ', $parts);
    }

    private function clearDefaultIfNeeded($userId, $isDefault, $excludeId = null) {
        if ($isDefault) {
            if ($excludeId) {
                $stmt = $this->db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ? AND id != ?");
                $stmt->execute([$userId, $excludeId]);
            } else {
                $stmt = $this->db->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
                $stmt->execute([$userId]);
            }
        }
    }

    public function all() {
        $stmt = $this->db->query("SELECT ua.*, u.name as user_name, u.email as user_email FROM user_addresses ua JOIN users u ON ua.user_id = u.id ORDER BY ua.id DESC");
        return $stmt->fetchAll();
    }
}
