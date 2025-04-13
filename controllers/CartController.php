<?php
require_once __DIR__ . '/../models/Product.php';

function initCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function showCart() {
    global $pdo;
    $productModel = new Product($pdo);
    $cartItems = [];
    $total = 0;
    
    initCart();
    
    // Get full product details for cart items
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
    
    $pageTitle = "Your Cart - The Scent";
    require_once __DIR__ . '/../views/cart.php';
}

function addToCart() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        return;
    }
    
    $productId = $_POST['product_id'] ?? null;
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if (!$productId || $quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
        return;
    }
    
    initCart();
    
    // Check stock availability
    global $pdo;
    $productModel = new Product($pdo);
    if (!$productModel->isInStock($productId, $quantity)) {
        echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
        return;
    }
    
    // Add or update quantity
    if (isset($_SESSION['cart'][$productId])) {
        $newQuantity = $_SESSION['cart'][$productId] + $quantity;
        // Recheck stock for total quantity
        if (!$productModel->isInStock($productId, $newQuantity)) {
            echo json_encode(['success' => false, 'message' => 'Insufficient stock for requested quantity']);
            return;
        }
        $_SESSION['cart'][$productId] = $newQuantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart',
        'cartCount' => array_sum($_SESSION['cart'])
    ]);
}

function updateCart() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        return;
    }
    
    $updates = $_POST['updates'] ?? [];
    global $pdo;
    $productModel = new Product($pdo);
    $stockErrors = [];
    
    foreach ($updates as $productId => $quantity) {
        $quantity = (int)$quantity;
        if ($quantity > 0) {
            // Validate stock
            if (!$productModel->isInStock($productId, $quantity)) {
                $product = $productModel->getById($productId);
                $stockErrors[] = "{$product['name']} has insufficient stock";
                continue;
            }
            $_SESSION['cart'][$productId] = $quantity;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
    }
    
    $response = [
        'success' => empty($stockErrors),
        'message' => empty($stockErrors) ? 'Cart updated' : 'Some items have insufficient stock',
        'cartCount' => array_sum($_SESSION['cart']),
        'errors' => $stockErrors
    ];
    
    echo json_encode($response);
}

function removeFromCart() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        return;
    }
    
    $productId = $_POST['product_id'] ?? null;
    
    if (!$productId || !isset($_SESSION['cart'][$productId])) {
        echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
        return;
    }
    
    unset($_SESSION['cart'][$productId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Product removed from cart',
        'cartCount' => array_sum($_SESSION['cart'])
    ]);
}

function clearCart() {
    $_SESSION['cart'] = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Cart cleared',
            'cartCount' => 0
        ]);
    } else {
        header('Location: index.php?page=cart');
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