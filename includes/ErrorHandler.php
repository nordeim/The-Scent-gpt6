<?php

class ErrorHandler {
    private static $logger;
    
    public static function init($logger = null) {
        self::$logger = $logger;
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }
    
    public static function handleError($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return false;
        }
        
        $error = [
            'type' => self::getErrorType($errno),
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ];
        
        self::logError($error);
        
        if (ENVIRONMENT === 'development') {
            self::displayError($error);
        } else {
            self::displayProductionError();
        }
        
        return true;
    }
    
    public static function handleException($exception) {
        $error = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];
        
        self::logError($error);
        
        if (ENVIRONMENT === 'development') {
            self::displayError($error);
        } else {
            self::displayProductionError();
        }
    }
    
    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleError(
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line']
            );
        }
    }
    
    private static function getErrorType($errno) {
        switch ($errno) {
            case E_ERROR:
                return 'Fatal Error';
            case E_WARNING:
                return 'Warning';
            case E_PARSE:
                return 'Parse Error';
            case E_NOTICE:
                return 'Notice';
            case E_CORE_ERROR:
                return 'Core Error';
            case E_CORE_WARNING:
                return 'Core Warning';
            case E_COMPILE_ERROR:
                return 'Compile Error';
            case E_COMPILE_WARNING:
                return 'Compile Warning';
            case E_USER_ERROR:
                return 'User Error';
            case E_USER_WARNING:
                return 'User Warning';
            case E_USER_NOTICE:
                return 'User Notice';
            case E_STRICT:
                return 'Strict Notice';
            case E_RECOVERABLE_ERROR:
                return 'Recoverable Error';
            case E_DEPRECATED:
                return 'Deprecated';
            case E_USER_DEPRECATED:
                return 'User Deprecated';
            default:
                return 'Unknown Error';
        }
    }
    
    private static function logError($error) {
        $message = sprintf(
            "[%s] %s: %s in %s on line %d",
            date('Y-m-d H:i:s'),
            $error['type'],
            $error['message'],
            $error['file'],
            $error['line']
        );
        
        if (isset($error['trace'])) {
            $message .= "\nStack trace:\n" . $error['trace'];
        }
        
        if (self::$logger) {
            self::$logger->error($message);
        } else {
            error_log($message);
        }
    }
    
    private static function displayError($error) {
        http_response_code(500);
        if (php_sapi_name() === 'cli') {
            echo "\nError: {$error['message']}\n";
            echo "Type: {$error['type']}\n";
            echo "File: {$error['file']}\n";
            echo "Line: {$error['line']}\n";
            if (isset($error['trace'])) {
                echo "\nStack trace:\n{$error['trace']}\n";
            }
        } else {
            require_once __DIR__ . '/../views/error.php';
        }
    }
    
    private static function displayProductionError() {
        http_response_code(500);
        if (php_sapi_name() === 'cli') {
            echo "\nAn error occurred. Please check the error logs for details.\n";
        } else {
            require_once __DIR__ . '/../views/error.php';
        }
    }
}