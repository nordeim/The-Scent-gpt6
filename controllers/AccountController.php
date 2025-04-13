<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/EmailService.php';

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

function requestPasswordReset() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_message'] = 'Please enter a valid email address.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php?page=forgot_password');
        exit;
    }

    // Generate a secure random token
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $this->db->prepare('UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?');
    $stmt->execute([$token, $expiry, $email]);

    if ($stmt->rowCount() > 0) {
        // Get user details for the email
        $stmt = $this->db->prepare('SELECT name FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        $resetLink = "https://" . $_SERVER['HTTP_HOST'] . "/index.php?page=reset_password&token=" . $token;

        // Send password reset email
        $emailService = new EmailService();
        $emailService->sendPasswordResetEmail($email, [
            'name' => $user['name'],
            'resetLink' => $resetLink,
            'user' => ['email' => $email]
        ]);
    }

    // Always show the same message to prevent email enumeration
    $_SESSION['flash_message'] = 'If an account exists with that email, we have sent password reset instructions.';
    $_SESSION['flash_type'] = 'success';
    header('Location: index.php?page=forgot_password');
    exit;
}

function resetPassword() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$token) {
        $_SESSION['flash_message'] = 'Invalid password reset token.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php?page=login');
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['flash_message'] = 'Passwords do not match.';
        $_SESSION['flash_type'] = 'error';
        header("Location: index.php?page=reset_password&token=$token");
        exit;
    }

    // Validate password strength
    if (strlen($password) < 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[^A-Za-z0-9]/', $password)) {
        $_SESSION['flash_message'] = 'Password does not meet the requirements.';
        $_SESSION['flash_type'] = 'error';
        header("Location: index.php?page=reset_password&token=$token");
        exit;
    }

    // Check if token exists and hasn't expired
    $stmt = $this->db->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['flash_message'] = 'This password reset link has expired or is invalid.';
        $_SESSION['flash_type'] = 'error';
        header('Location: index.php?page=forgot_password');
        exit;
    }

    // Update password and clear reset token
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $this->db->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?');
    $stmt->execute([$hashedPassword, $user['id']]);

    $_SESSION['flash_message'] = 'Your password has been successfully reset. Please log in with your new password.';
    $_SESSION['flash_type'] = 'success';
    header('Location: index.php?page=login');
    exit;
}

function updateNewsletterPreferences() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=account&section=profile');
        exit;
    }

    $emailMarketing = isset($_POST['email_marketing']);
    $emailOrders = isset($_POST['email_orders']);
    $emailNewsletter = isset($_POST['email_newsletter']);

    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE users 
        SET email_marketing = ?,
            email_orders = ?,
            email_newsletter = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $emailMarketing ? 1 : 0,
        $emailOrders ? 1 : 0,
        $emailNewsletter ? 1 : 0,
        getCurrentUser()['id']
    ]);

    $_SESSION['flash_message'] = 'Communication preferences updated successfully.';
    $_SESSION['flash_type'] = 'success';
    header('Location: index.php?page=account&section=profile');
    exit;
}