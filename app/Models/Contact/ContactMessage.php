<?php
namespace App\Models\Contact;

use App\Core\Database;
use PDO;

class ContactMessage {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO contact_messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'] ?? null,
            $data['name'],
            $data['email'],
            $data['subject'] ?? null,
            $data['message']
        ]);
    }

    public function findByUser($userId, $email = null) {
        if ($email) {
            $stmt = $this->db->prepare("SELECT * FROM contact_messages WHERE user_id = ? OR email = ? ORDER BY created_at DESC");
            $stmt->execute([$userId, $email]);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM contact_messages WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
        }
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function saveReply($id, $reply) {
        $stmt = $this->db->prepare("UPDATE contact_messages SET reply_message = ?, status = 'replied', is_customer_viewed = 0 WHERE id = ?");
        return $stmt->execute([$reply, $id]);
    }

    public function markAsViewedByCustomer($userId, $email = null) {
        if ($email) {
            $stmt = $this->db->prepare("UPDATE contact_messages SET is_customer_viewed = 1 WHERE (user_id = ? OR email = ?) AND status = 'replied'");
            return $stmt->execute([$userId, $email]);
        } else {
            $stmt = $this->db->prepare("UPDATE contact_messages SET is_customer_viewed = 1 WHERE user_id = ? AND status = 'replied'");
            return $stmt->execute([$userId]);
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM contact_messages WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countUnread() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'");
        return (int)$stmt->fetchColumn();
    }

    public function countUnreadRepliesForCustomer($userId, $email = null) {
        if ($email) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM contact_messages WHERE (user_id = ? OR email = ?) AND status = 'replied' AND is_customer_viewed = 0");
            $stmt->execute([$userId, $email]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM contact_messages WHERE user_id = ? AND status = 'replied' AND is_customer_viewed = 0");
            $stmt->execute([$userId]);
        }
        return (int)$stmt->fetchColumn();
    }
}
