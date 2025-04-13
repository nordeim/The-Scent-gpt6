<?php
require_once __DIR__ . '/../models/Product.php';

class InventoryController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function updateStock($productId, $quantity, $type = 'adjustment', $referenceId = null, $notes = null) {
        try {
            $this->pdo->beginTransaction();
            
            // Get current stock
            $stmt = $this->pdo->prepare("
                SELECT stock_quantity, backorder_allowed 
                FROM products 
                WHERE id = ? 
                FOR UPDATE
            ");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            
            if (!$product) {
                throw new Exception('Product not found');
            }
            
            // Check if we have enough stock for reduction
            if ($quantity < 0 && !$product['backorder_allowed'] && ($product['stock_quantity'] + $quantity) < 0) {
                throw new Exception('Insufficient stock');
            }
            
            // Update product stock
            $stmt = $this->pdo->prepare("
                UPDATE products 
                SET stock_quantity = stock_quantity + ? 
                WHERE id = ?
            ");
            $stmt->execute([$quantity, $productId]);
            
            // Record movement
            $stmt = $this->pdo->prepare("
                INSERT INTO inventory_movements (
                    product_id, quantity_change, type, reference_id, notes, created_by
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $productId,
                $quantity,
                $type,
                $referenceId,
                $notes,
                $_SESSION['user']['id'] ?? null
            ]);
            
            $this->pdo->commit();
            
            // Check if we need to send low stock notification
            $this->checkLowStockAlert($productId);
            
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function checkLowStockAlert($productId) {
        $stmt = $this->pdo->prepare("
            SELECT name, stock_quantity, low_stock_threshold 
            FROM products 
            WHERE id = ? AND stock_quantity <= low_stock_threshold
        ");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if ($product) {
            // Log low stock alert
            error_log("Low stock alert: {$product['name']} has only {$product['stock_quantity']} units left");
            
            // TODO: Send email notification to admin (will be implemented with email system)
        }
    }
    
    public function getInventoryMovements($productId, $limit = 50) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.name as user_name 
            FROM inventory_movements m
            LEFT JOIN users u ON m.created_by = u.id
            WHERE m.product_id = ?
            ORDER BY m.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$productId, $limit]);
        return $stmt->fetchAll();
    }
}