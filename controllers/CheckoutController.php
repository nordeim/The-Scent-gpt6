<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/InventoryController.php';
require_once __DIR__ . '/../controllers/TaxController.php';
require_once __DIR__ . '/../includes/EmailService.php';

function showCheckout() {
    if (empty($_SESSION['cart'])) {
        header('Location: index.php?page=cart');
        exit;
    }
    
    global $pdo;
    $productModel = new Product($pdo);
    $cartItems = [];
    $subtotal = 0;
    
    // Get cart items
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $productModel->getById($productId);
        if ($product) {
            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity
            ];
            $subtotal += $product['price'] * $quantity;
        }
    }
    
    // Calculate initial tax (0% until country is selected)
    $tax_rate_formatted = '0%';
    $tax_amount = 0;
    
    // Calculate shipping cost
    $shipping_cost = $subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
    
    // Calculate total
    $total = $subtotal + $shipping_cost + $tax_amount;
    
    $pageTitle = "Checkout - The Scent";
    require_once __DIR__ . '/../views/checkout.php';
}

function calculateTax() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        return;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $country = $data['country'] ?? '';
    $state = $data['state'] ?? '';
    
    if (empty($country)) {
        echo json_encode(['success' => false, 'error' => 'Country is required']);
        return;
    }
    
    global $pdo;
    $subtotal = calculateCartSubtotal();
    $shipping_cost = $subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
    
    $taxController = new TaxController($pdo);
    $tax_amount = $taxController->calculateTax($subtotal, $country, $state);
    $tax_rate = $taxController->getTaxRate($country, $state);
    
    $total = $subtotal + $shipping_cost + $tax_amount;
    
    echo json_encode([
        'success' => true,
        'tax_rate_formatted' => $taxController->formatTaxRate($tax_rate),
        'tax_amount' => number_format($tax_amount, 2),
        'total' => number_format($total, 2)
    ]);
}

function calculateCartSubtotal() {
    global $pdo;
    $subtotal = 0;
    $productModel = new Product($pdo);
    
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $productModel->getById($productId);
        if ($product) {
            $subtotal += $product['price'] * $quantity;
        }
    }
    
    return $subtotal;
}

function processCheckout() {
    if (empty($_SESSION['cart'])) {
        header('Location: index.php?page=cart');
        exit;
    }
    
    // Validate form data
    $required = ['shipping_name', 'shipping_email', 'shipping_address', 'shipping_city', 
                'shipping_state', 'shipping_zip', 'shipping_country'];
                
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['flash_message'] = 'Please fill in all required fields.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?page=checkout');
            exit;
        }
    }
    
    global $pdo;
    try {
        $pdo->beginTransaction();
        
        // Validate stock levels before proceeding
        $stockErrors = validateCartStock();
        if (!empty($stockErrors)) {
            throw new Exception('Some items are out of stock: ' . implode(', ', $stockErrors));
        }
        
        $subtotal = calculateCartSubtotal();
        $shipping_cost = $subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
        
        // Calculate tax
        $taxController = new TaxController($pdo);
        $tax_amount = $taxController->calculateTax(
            $subtotal,
            $_POST['shipping_country'],
            $_POST['shipping_state']
        );
        
        $total = $subtotal + $shipping_cost + $tax_amount;
        
        // Create order
        $orderModel = new Order($pdo);
        $userId = getCurrentUser()['id'];
        
        $orderData = [
            'user_id' => $userId,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'tax_amount' => $tax_amount,
            'total_amount' => $total,
            'shipping_name' => $_POST['shipping_name'],
            'shipping_email' => $_POST['shipping_email'],
            'shipping_address' => $_POST['shipping_address'],
            'shipping_city' => $_POST['shipping_city'],
            'shipping_state' => $_POST['shipping_state'],
            'shipping_zip' => $_POST['shipping_zip'],
            'shipping_country' => $_POST['shipping_country']
        ];
        
        $orderId = $orderModel->create($orderData);
        
        // Create order items and update inventory
        $inventoryController = new InventoryController($pdo);
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = (new Product($pdo))->getById($productId);
            if ($product) {
                // Add order item
                $stmt->execute([
                    $orderId,
                    $productId,
                    $quantity,
                    $product['price']
                ]);
                
                // Update inventory
                if (!$inventoryController->updateStock(
                    $productId,
                    -$quantity,
                    'order',
                    $orderId,
                    "Order #{$orderId}"
                )) {
                    throw new Exception("Failed to update inventory for product {$product['name']}");
                }
            }
        }
        
        // Process payment
        $paymentController = new PaymentController();
        $paymentResult = $paymentController->processPayment($orderId);
        
        if (!$paymentResult['success']) {
            throw new Exception($paymentResult['error']);
        }
        
        $pdo->commit();
        
        // Send order confirmation email
        $emailService = new EmailService();
        $user = getCurrentUser();
        $order = (new Order($pdo))->getById($orderId);
        
        $emailService->sendOrderConfirmation($order, $user);
        
        // Store order ID for confirmation
        $_SESSION['last_order_id'] = $orderId;
        $_SESSION['cart'] = [];
        
        // Return payment intent client secret for Stripe.js
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'orderId' => $orderId,
            'clientSecret' => $paymentResult['clientSecret']
        ]);
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log($e->getMessage());
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'An error occurred while processing your order. Please try again.'
        ]);
        exit;
    }
}

function validateCartStock() {
    global $pdo;
    $productModel = new Product($pdo);
    $errors = [];
    
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        if (!$productModel->isInStock($productId, $quantity)) {
            $product = $productModel->getById($productId);
            $errors[] = "{$product['name']} has insufficient stock";
        }
    }
    
    return $errors;
}

function showOrderConfirmation() {
    if (!isset($_SESSION['last_order_id'])) {
        header('Location: index.php?page=products');
        exit;
    }
    
    global $pdo;
    
    // Get order details
    $stmt = $pdo->prepare("
        SELECT o.*, oi.product_id, oi.quantity, oi.price, p.name as product_name
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ?
    ");
    
    $stmt->execute([$_SESSION['last_order_id']]);
    $orderItems = $stmt->fetchAll();
    
    if (empty($orderItems)) {
        header('Location: index.php?page=products');
        exit;
    }
    
    $order = [
        'id' => $orderItems[0]['id'],
        'total_amount' => $orderItems[0]['total_amount'],
        'shipping_name' => $orderItems[0]['shipping_name'],
        'shipping_email' => $orderItems[0]['shipping_email'],
        'shipping_address' => $orderItems[0]['shipping_address'],
        'shipping_city' => $orderItems[0]['shipping_city'],
        'shipping_state' => $orderItems[0]['shipping_state'],
        'shipping_zip' => $orderItems[0]['shipping_zip'],
        'shipping_country' => $orderItems[0]['shipping_country'],
        'created_at' => $orderItems[0]['created_at'],
        'items' => $orderItems
    ];
    
    // Clear the stored order ID
    unset($_SESSION['last_order_id']);
    
    $pageTitle = "Order Confirmation - The Scent";
    require_once __DIR__ . '/../views/order_confirmation.php';
}

function updateOrderStatus($orderId, $status, $trackingInfo = null) {
    if (!isAdmin()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }
    
    global $pdo;
    $orderModel = new Order($pdo);
    $order = $orderModel->getById($orderId);
    
    if (!$order) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Order not found']);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Update order status
        $orderModel->updateStatus($orderId, $status);
        
        // If order is shipped and tracking info provided, update tracking
        if ($status === 'shipped' && $trackingInfo) {
            $orderModel->updateTracking(
                $orderId,
                $trackingInfo['number'],
                $trackingInfo['carrier'],
                $trackingInfo['url']
            );
            
            // Send shipping notification email
            $user = (new User($pdo))->getById($order['user_id']);
            $emailService = new EmailService();
            $emailService->sendShippingUpdate(
                $order,
                $user,
                $trackingInfo['number'],
                $trackingInfo['carrier']
            );
        }
        
        $pdo->commit();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}