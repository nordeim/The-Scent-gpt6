<?php
session_start();

// Load configuration and core files
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Load controllers
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/QuizController.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Handle routing
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Route to appropriate controller/action
try {
    switch ($page) {
        case 'home':
            showHomePage();
            break;
            
        case 'products':
            if ($id) {
                showProduct($id);
            } else {
                showProductList();
            }
            break;
            
        case 'quiz':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                handleQuizSubmission();
            } else {
                showQuiz();
            }
            break;
            
        case 'quiz_results':
            showQuizResults();
            break;
            
        case 'cart':
            switch ($action) {
                case 'add':
                    addToCart();
                    break;
                case 'update':
                    updateCart();
                    break;
                case 'remove':
                    removeFromCart();
                    break;
                case 'clear':
                    clearCart();
                    break;
                default:
                    showCart();
            }
            break;
            
        case 'checkout':
            if (!isLoggedIn()) {
                $_SESSION['redirect_after_login'] = 'checkout';
                header('Location: index.php?page=login');
                exit;
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                processCheckout();
            } else {
                showCheckout();
            }
            break;
            
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                
                if (login($email, $password)) {
                    $redirect = $_SESSION['redirect_after_login'] ?? 'home';
                    unset($_SESSION['redirect_after_login']);
                    header("Location: index.php?page=$redirect");
                    exit;
                } else {
                    $_SESSION['flash_message'] = 'Invalid email or password';
                    $_SESSION['flash_type'] = 'error';
                    header('Location: index.php?page=login');
                    exit;
                }
            } else {
                require_once __DIR__ . '/../views/login.php';
            }
            break;
            
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $name = $_POST['name'] ?? '';
                
                if (register($email, $password, $name)) {
                    login($email, $password);
                    header('Location: index.php?page=home');
                    exit;
                } else {
                    $_SESSION['flash_message'] = 'Registration failed. Email may already be in use.';
                    $_SESSION['flash_type'] = 'error';
                    header('Location: index.php?page=register');
                    exit;
                }
            } else {
                require_once __DIR__ . '/../views/register.php';
            }
            break;
            
        case 'logout':
            logout();
            header('Location: index.php?page=home');
            exit;
            break;
            
        default:
            // 404 Not Found
            http_response_code(404);
            require_once __DIR__ . '/../views/404.php';
    }
} catch (Exception $e) {
    // Log error and show error page
    error_log($e->getMessage());
    http_response_code(500);
    require_once __DIR__ . '/../views/error.php';
}