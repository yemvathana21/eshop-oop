<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function generateInvoiceNumber() {
        $prefix = "INV-" . date('Ymd');
        // Retrieve count of orders today to create a sequence
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM orders WHERE invoice_number LIKE ?");
        $stmt->execute([$prefix . "%"]);
        $row = $stmt->fetch();
        $sequence = str_pad($row['count'] + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . "-" . $sequence;
    }

    // Business Logic: Create order and deduct stock in a single transaction
    public function createOrder($userId, $totalPrice, $cartItems, $options = []) {
        try {
            $this->db->beginTransaction();

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();

            $shippingName = $options['shipping_name'] ?? null;
            $shippingAddress = $options['shipping_address'] ?? null;
            $shippingMethod = $options['shipping_method'] ?? null;
            $shippingCost = $options['shipping_cost'] ?? 0.00;
            $paymentMethod = $options['payment_method'] ?? 'cod';

            // Create main order record
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price, status, invoice_number, shipping_name, shipping_address, shipping_method, shipping_cost, payment_method) VALUES (?, ?, 'pending', ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $totalPrice, $invoiceNumber, $shippingName, $shippingAddress, $shippingMethod, $shippingCost, $paymentMethod]);
            $orderId = $this->db->lastInsertId();

            $productModel = new Product();

            // Create order items and deduct stock
            $stmtItem = $this->db->prepare("INSERT INTO order_items (order_id, product_id, price, quantity, size_name, color_name) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($cartItems as $itemKey => $item) {
                $pid = $item['product_id'] ?? $itemKey;
                // Deduct stock in real-time
                $deducted = $productModel->deductStock($pid, $item['quantity']);
                if (!$deducted) {
                    throw new Exception("Error: Insufficient stock for product '{$item['name']}'. Please check your cart.");
                }

                // Insert into order_items
                $sizeName = $item['size_name'] ?? null;
                $colorName = $item['color_name'] ?? null;
                $stmtItem->execute([$orderId, $pid, $item['price'], $item['quantity'], $sizeName, $colorName]);
            }

            $this->db->commit();
            return [
                'success' => true,
                'order_id' => $orderId,
                'invoice_number' => $invoiceNumber
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function all() {
        $stmt = $this->db->query("
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.id DESC
        ");
        return $stmt->fetchAll();
    }

    public function findByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByInvoiceNumber($invoiceNumber) {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name as user_name, u.email as user_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.invoice_number = ?
        ");
        $stmt->execute([$invoiceNumber]);
        return $stmt->fetch();
    }

    public function getItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getItemsForOrders($orderIds) {
        if (empty($orderIds)) return [];
        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id IN ($placeholders)
            ORDER BY oi.id ASC
        ");
        $stmt->execute($orderIds);
        $rows = $stmt->fetchAll();
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['order_id']][] = $row;
        }
        return $grouped;
    }

    public function getCountByUser($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    public function getTotalSpentByUser($userId) {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_price), 0) FROM orders WHERE user_id = ? AND status IN ('delivered', 'completed')");
        $stmt->execute([$userId]);
        return (float)$stmt->fetchColumn();
    }

    // Analytics Methods
    public function getTotalSales() {
        $stmt = $this->db->query("SELECT SUM(total_price) as total FROM orders WHERE status IN ('delivered', 'completed')");
        $row = $stmt->fetch();
        return $row['total'] ?? 0.00;
    }

    public function getOrdersCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM orders");
        $row = $stmt->fetch();
        return $row['count'] ?? 0;
    }

    public function getRecentOrders($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name as user_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.id DESC LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
