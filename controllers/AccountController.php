<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Quiz.php';

function showDashboard() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    global $pdo;
    $user = getCurrentUser();
    
    // Get recent orders
    $orderModel = new Order($pdo);
    $recentOrders = $orderModel->getRecentByUserId($user['id'], 5);
    
    // Get saved quiz results
    $quizModel = new Quiz($pdo);
    $quizResults = $quizModel->getResultsByUserId($user['id']);
    
    $pageTitle = "My Account - The Scent";
    require_once __DIR__ . '/../views/account/dashboard.php';
}

function showOrders() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    global $pdo;
    $user = getCurrentUser();
    $orderModel = new Order($pdo);
    
    // Get all orders with pagination
    $page = $_GET['p'] ?? 1;
    $perPage = 10;
    $orders = $orderModel->getAllByUserId($user['id'], $page, $perPage);
    $totalOrders = $orderModel->getTotalOrdersByUserId($user['id']);
    $totalPages = ceil($totalOrders / $perPage);
    
    $pageTitle = "My Orders - The Scent";
    require_once __DIR__ . '/../views/account/orders.php';
}

function showOrderDetails($orderId) {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    global $pdo;
    $user = getCurrentUser();
    $orderModel = new Order($pdo);
    
    // Get order details
    $order = $orderModel->getByIdAndUserId($orderId, $user['id']);
    if (!$order) {
        http_response_code(404);
        require_once __DIR__ . '/../views/404.php';
        return;
    }
    
    $pageTitle = "Order #" . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . " - The Scent";
    require_once __DIR__ . '/../views/account/order_details.php';
}

function showProfile() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $user = getCurrentUser();
    $pageTitle = "My Profile - The Scent";
    require_once __DIR__ . '/../views/account/profile.php';
}

function updateProfile() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=account&section=profile');
        exit;
    }
    
    global $pdo;
    $user = getCurrentUser();
    
    // Validate input
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    
    if (empty($name) || empty($email)) {
        $_SESSION['flash_message'] = 'Name and email are required.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php?page=account&section=profile');
        exit;
    }
    
    // Check if email is changing
    if ($email !== $user['email']) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user['id']]);
        if ($stmt->fetch()) {
            $_SESSION['flash_message'] = 'Email already in use.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?page=account&section=profile');
            exit;
        }
    }
    
    // Update basic info
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $user['id']]);
    
    // Update password if provided
    if ($newPassword) {
        if (!password_verify($currentPassword, $user['password'])) {
            $_SESSION['flash_message'] = 'Current password is incorrect.';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?page=account&section=profile');
            exit;
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $user['id']]);
    }
    
    // Update session
    $_SESSION['user'] = array_merge($user, ['name' => $name, 'email' => $email]);
    
    $_SESSION['flash_message'] = 'Profile updated successfully.';
    $_SESSION['flash_type'] = 'success';
    header('Location: index.php?page=account&section=profile');
    exit;
}

function showQuizHistory() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    global $pdo;
    $user = getCurrentUser();
    $quizModel = new Quiz($pdo);
    $quizResults = $quizModel->getResultsByUserId($user['id']);
    
    $pageTitle = "Quiz History - The Scent";
    require_once __DIR__ . '/../views/account/quiz_history.php';
}