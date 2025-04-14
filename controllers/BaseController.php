<?php

abstract class BaseController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    protected function requireLogin() {
        if (!isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: index.php?page=login');
            exit;
        }
    }
    
    protected function requireAdmin() {
        $this->requireLogin();
        if (!isset($_SESSION['user']['is_admin']) || !$_SESSION['user']['is_admin']) {
            throw new Exception('Unauthorized access');
        }
    }
    
    protected function validateInput($input, $type) {
        return SecurityMiddleware::validateInput($input, $type);
    }
    
    protected function getCsrfToken() {
        return SecurityMiddleware::generateCSRFToken();
    }
    
    protected function validateCSRF() {
        SecurityMiddleware::validateCSRF();
    }
    
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($page, $params = []) {
        $url = 'index.php?page=' . urlencode($page);
        foreach ($params as $key => $value) {
            $url .= '&' . urlencode($key) . '=' . urlencode($value);
        }
        header('Location: ' . $url);
        exit;
    }
    
    protected function setFlashMessage($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    
    protected function beginTransaction() {
        $this->pdo->beginTransaction();
    }
    
    protected function commit() {
        $this->pdo->commit();
    }
    
    protected function rollback() {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
    
    protected function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
    
    protected function getUserId() {
        return $_SESSION['user']['id'] ?? null;
    }
    
    protected function validateAjax() {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            $this->jsonResponse(['error' => 'Invalid request'], 400);
        }
    }
    
    protected function isRateLimited($key, $maxAttempts, $timeWindow) {
        $rateLimitKey = "rate_limit:{$key}:" . $_SERVER['REMOTE_ADDR'];
        $attempts = $_SESSION[$rateLimitKey] ?? ['count' => 0, 'first_attempt' => time()];
        
        if (time() - $attempts['first_attempt'] > $timeWindow) {
            // Reset if time window has passed
            $attempts = ['count' => 1, 'first_attempt' => time()];
        } else {
            $attempts['count']++;
        }
        
        $_SESSION[$rateLimitKey] = $attempts;
        
        return $attempts['count'] > $maxAttempts;
    }
    
    protected function renderView($viewPath, $data = []) {
        // Extract data to make it available in view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: {$viewPath}");
        }
        
        require $viewFile;
        
        return ob_get_clean();
    }
    
    protected function validateFileUpload($file, $allowedTypes, $maxSize = 5242880) { // 5MB default
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new Exception('Invalid file upload');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception('File too large');
            case UPLOAD_ERR_PARTIAL:
                throw new Exception('File upload interrupted');
            default:
                throw new Exception('Unknown upload error');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('File too large');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid file type');
        }

        return true;
    }
    
    protected function log($message, $level = 'info') {
        $logFile = __DIR__ . '/../logs/' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        
        error_log($formattedMessage, 3, $logFile);
    }
}