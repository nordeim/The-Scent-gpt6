<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

class PaymentController {
    private $stripe;
    
    public function __construct() {
        $this->stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
    }
    
    public function createPaymentIntent($amount, $currency = 'usd') {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true]
            ]);
            
            return [
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log($e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment processing failed'
            ];
        }
    }
    
    public function processPayment($orderId) {
        try {
            // Get order details
            global $pdo;
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$orderId]);
            $order = $stmt->fetch();
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Create payment intent
            $result = $this->createPaymentIntent($order['total_amount']);
            if (!$result['success']) {
                throw new Exception($result['error']);
            }
            
            // Update order with payment intent
            $stmt = $pdo->prepare("UPDATE orders SET payment_intent_id = ? WHERE id = ?");
            $stmt->execute([$result['clientSecret'], $orderId]);
            
            return $result;
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment processing failed'
            ];
        }
    }
    
    public function handleWebhook() {
        $payload = @file_get_contents('php://input');
        $event = null;
        
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
            
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handleSuccessfulPayment($event->data->object);
                    break;
                    
                case 'payment_intent.payment_failed':
                    $this->handleFailedPayment($event->data->object);
                    break;
            }
            
            http_response_code(200);
            return ['success' => true];
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(400);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    private function handleSuccessfulPayment($paymentIntent) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = 'paid', 
                payment_status = 'completed',
                paid_at = NOW()
            WHERE payment_intent_id = ?
        ");
        $stmt->execute([$paymentIntent->client_secret]);
    }
    
    private function handleFailedPayment($paymentIntent) {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = 'payment_failed',
                payment_status = 'failed'
            WHERE payment_intent_id = ?
        ");
        $stmt->execute([$paymentIntent->client_secret]);
    }
}