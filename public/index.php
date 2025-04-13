<?php
session_start();

// Load configuration and core files
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Load controllers
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/QuizController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/CouponController.php';
require_once __DIR__ . '/../controllers/TaxController.php';

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
            
            switch ($action) {
                case 'apply-coupon':
                    header('Content-Type: application/json');
                    $data = json_decode(file_get_contents('php://input'), true);
                    $couponController = new CouponController($pdo);
                    $validationResult = $couponController->validateCoupon(
                        $data['code'],
                        calculateCartSubtotal(),
                        getCurrentUser()['id']
                    );
                    
                    if ($validationResult['valid']) {
                        $_SESSION['coupon'] = [
                            'id' => $validationResult['coupon']['id'],
                            'code' => $data['code'],
                            'discount_amount' => $validationResult['discount_amount']
                        ];
                        
                        // Recalculate totals
                        $subtotal = calculateCartSubtotal();
                        $shipping = $subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
                        $total = $subtotal + $shipping - $validationResult['discount_amount'];
                        
                        echo json_encode([
                            'success' => true,
                            'message' => $validationResult['message'],
                            'discount_amount' => number_format($validationResult['discount_amount'], 2),
                            'total' => number_format($total, 2)
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => $validationResult['message']
                        ]);
                    }
                    break;
                    
                case 'calculate-tax':
                    // ...existing tax calculation code...
                    break;
                    
                default:
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        processCheckout();
                    } else {
                        showCheckout();
                    }
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
            
        case 'payment':
            $paymentController = new PaymentController();
            switch ($action) {
                case 'create-intent':
                    header('Content-Type: application/json');
                    $data = json_decode(file_get_contents('php://input'), true);
                    $result = $paymentController->createPaymentIntent(
                        $data['amount'],
                        $data['currency'] ?? 'usd'
                    );
                    echo json_encode($result);
                    break;
                    
                case 'webhook':
                    $result = $paymentController->handleWebhook();
                    http_response_code($result['success'] ? 200 : 400);
                    echo json_encode($result);
                    break;
                    
                default:
                    http_response_code(404);
                    require_once __DIR__ . '/../views/404.php';
            }
            break;
            
        case 'admin':
            if (!isAdmin()) {
                header('Location: index.php?page=login');
                exit;
            }
            
            switch ($action) {
                case 'coupons':
                    $couponController = new CouponController($pdo);
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $result = $couponController->createCoupon($_POST);
                        header('Location: index.php?page=admin&action=coupons&success=' . ($result ? '1' : '0'));
                    } else {
                        $coupons = $couponController->getAllCoupons();
                        require_once __DIR__ . '/../views/admin/coupons.php';
                    }
                    break;
                    
                // ...other admin routes...
            }
            break;
            
        case 'newsletter':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newsletterController = new NewsletterController($pdo);
                $email = $_POST['email'] ?? '';
                $source = $_POST['source'] ?? 'footer';
                
                header('Content-Type: application/json');
                echo json_encode($newsletterController->subscribe($email));
                exit;
            }
            break;
            
        case 'unsubscribe':
            $email = $_GET['email'] ?? '';
            $token = $_GET['token'] ?? '';
            
            if ($email && $token) {
                $newsletterController = new NewsletterController($pdo);
                $result = $newsletterController->unsubscribe($email, $token);
                
                if ($result['success']) {
                    $_SESSION['flash_message'] = $result['message'];
                    $_SESSION['flash_type'] = 'success';
                } else {
                    $_SESSION['flash_message'] = $result['message'];
                    $_SESSION['flash_type'] = 'error';
                }
            }
            
            header('Location: index.php?page=home');
            exit;
            break;
            
        case 'forgot-password':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                requestPasswordReset();
            } else {
                $pageTitle = "Forgot Password - The Scent";
                require_once __DIR__ . '/../views/forgot_password.php';
            }
            break;
            
        case 'reset-password':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                resetPassword();
            } else {
                $token = $_GET['token'] ?? '';
                if (empty($token)) {
                    header('Location: index.php?page=login');
                    exit;
                }
                $pageTitle = "Reset Password - The Scent";
                require_once __DIR__ . '/../views/reset_password.php';
            }
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