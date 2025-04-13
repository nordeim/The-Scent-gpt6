<?php
class CouponController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function validateCoupon($code, $subtotal, $userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM coupons 
            WHERE code = ? 
            AND is_active = TRUE
            AND (start_date IS NULL OR start_date <= NOW())
            AND (end_date IS NULL OR end_date >= NOW())
            AND (usage_limit IS NULL OR usage_count < usage_limit)
            AND min_purchase_amount <= ?
        ");
        $stmt->execute([$code, $subtotal]);
        $coupon = $stmt->fetch();
        
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Invalid or expired coupon code'
            ];
        }
        
        // Check if user has already used this coupon
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM coupon_usage 
            WHERE coupon_id = ? AND user_id = ?
        ");
        $stmt->execute([$coupon['id'], $userId]);
        $usageCount = $stmt->fetchColumn();
        
        if ($usageCount > 0) {
            return [
                'valid' => false,
                'message' => 'You have already used this coupon'
            ];
        }
        
        // Calculate discount
        $discountAmount = $this->calculateDiscount($coupon, $subtotal);
        
        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount_amount' => $discountAmount,
            'message' => 'Coupon applied successfully'
        ];
    }
    
    private function calculateDiscount($coupon, $subtotal) {
        $discountAmount = 0;
        
        if ($coupon['discount_type'] === 'percentage') {
            $discountAmount = $subtotal * ($coupon['discount_value'] / 100);
        } else { // fixed amount
            $discountAmount = $coupon['discount_value'];
        }
        
        // Apply maximum discount limit if set
        if ($coupon['max_discount_amount'] !== null) {
            $discountAmount = min($discountAmount, $coupon['max_discount_amount']);
        }
        
        return round($discountAmount, 2);
    }
    
    public function applyCoupon($couponId, $orderId, $userId, $discountAmount) {
        try {
            $this->pdo->beginTransaction();
            
            // Record coupon usage
            $stmt = $this->pdo->prepare("
                INSERT INTO coupon_usage (coupon_id, order_id, user_id, discount_amount)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$couponId, $orderId, $userId, $discountAmount]);
            
            // Update coupon usage count
            $stmt = $this->pdo->prepare("
                UPDATE coupons 
                SET usage_count = usage_count + 1
                WHERE id = ?
            ");
            $stmt->execute([$couponId]);
            
            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function getAllCoupons() {
        $stmt = $this->pdo->query("
            SELECT 
                c.*,
                COUNT(cu.id) as total_uses,
                SUM(cu.discount_amount) as total_discount_given
            FROM coupons c
            LEFT JOIN coupon_usage cu ON c.id = cu.coupon_id
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function createCoupon($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO coupons (
                code, description, discount_type, discount_value,
                min_purchase_amount, max_discount_amount,
                start_date, end_date, usage_limit, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['code'],
            $data['description'],
            $data['discount_type'],
            $data['discount_value'],
            $data['min_purchase_amount'] ?? 0,
            $data['max_discount_amount'] ?? null,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['usage_limit'] ?? null,
            $data['is_active'] ?? true
        ]);
    }
}