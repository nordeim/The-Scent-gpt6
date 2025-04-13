<?php
require_once __DIR__ . '/../models/Product.php';

function showCheckout() {
    if (empty($_SESSION['cart'])) {
        header('Location: index.php?page=cart');
        exit;
    }
    
    global $pdo;
    $productModel = new Product($pdo);
    $cartItems = [];
    $total = 0;
    
    // Get cart items
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $productModel->getById($productId);
        if ($product) {
            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity
            ];
            $total += $product['price'] * $quantity;
        }
    }
    
    $pageTitle = "Checkout - The Scent";
    require_once __DIR__ . '/../views/checkout.php';
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
        
        // Create order
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                user_id, total_amount, shipping_name, shipping_email,
                shipping_address, shipping_city, shipping_state,
                shipping_zip, shipping_country, status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        
        $userId = getCurrentUser()['id'];
        $total = calculateOrderTotal();
        
        $stmt->execute([
            $userId,
            $total,
            $_POST['shipping_name'],
            $_POST['shipping_email'],
            $_POST['shipping_address'],
            $_POST['shipping_city'],
            $_POST['shipping_state'],
            $_POST['shipping_zip'],
            $_POST['shipping_country']
        ]);
        
        $orderId = $pdo->lastInsertId();
        
        // Create order items
        $stmt = $pdo->prepare("
            INSERT INTO order_items (
                order_id, product_id, quantity, price
            ) VALUES (?, ?, ?, ?)
        ");
        
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = (new Product($pdo))->getById($productId);
            if ($product) {
                $stmt->execute([
                    $orderId,
                    $productId,
                    $quantity,
                    $product['price']
                ]);
                
                // Update product inventory (if implementing inventory tracking)
                // updateInventory($productId, $quantity);
            }
        }
        
        $pdo->commit();
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        // Store order ID for confirmation page
        $_SESSION['last_order_id'] = $orderId;
        
        // Redirect to confirmation page
        header('Location: index.php?page=order_confirmation');
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log($e->getMessage());
        
        $_SESSION['flash_message'] = 'An error occurred while processing your order. Please try again.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php?page=checkout');
        exit;
    }
}

function calculateOrderTotal() {
    global $pdo;
    $total = 0;
    $productModel = new Product($pdo);
    
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $productModel->getById($productId);
        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }
    
    return $total;
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