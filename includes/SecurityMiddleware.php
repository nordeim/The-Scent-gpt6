<?php

class SecurityMiddleware {
    public static function apply() {
        // Set security headers
        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Content-Security-Policy: default-src 'self'; script-src 'self' https://js.stripe.com 'unsafe-inline'; style-src 'self' 'unsafe-inline'; frame-src https://js.stripe.com; img-src 'self' data: https:; connect-src 'self' https://api.stripe.com");
        
        // Set secure cookie parameters
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $cookieParams['lifetime'],
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 3600) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
    
    public static function validateInput($input, $type, $options = []) {
        if ($input === null) {
            return null;
        }
        
        // Basic sanitization for all inputs
        if (is_string($input)) {
            $input = trim($input);
        }
        
        switch ($type) {
            case 'email':
                $email = filter_var($input, FILTER_VALIDATE_EMAIL);
                if ($email && strlen($email) <= 254) { // RFC 5321
                    return $email;
                }
                return false;
                
            case 'int':
                $min = $options['min'] ?? null;
                $max = $options['max'] ?? null;
                $int = filter_var($input, FILTER_VALIDATE_INT);
                if ($int === false) return false;
                if ($min !== null && $int < $min) return false;
                if ($max !== null && $int > $max) return false;
                return $int;
                
            case 'float':
                $min = $options['min'] ?? null;
                $max = $options['max'] ?? null;
                $float = filter_var($input, FILTER_VALIDATE_FLOAT);
                if ($float === false) return false;
                if ($min !== null && $float < $min) return false;
                if ($max !== null && $float > $max) return false;
                return $float;
                
            case 'url':
                return filter_var($input, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
                
            case 'string':
                $min = $options['min'] ?? 0;
                $max = $options['max'] ?? 65535;
                $allowedTags = $options['allowTags'] ?? [];
                
                // Remove any tags not specifically allowed
                $cleaned = strip_tags($input, $allowedTags);
                $cleaned = htmlspecialchars($cleaned, ENT_QUOTES, 'UTF-8');
                
                if (strlen($cleaned) < $min || strlen($cleaned) > $max) {
                    return false;
                }
                return $cleaned;
                
            case 'password':
                $minLength = $options['minLength'] ?? 8;
                if (strlen($input) < $minLength) return false;
                
                // Check password strength
                $hasUpper = preg_match('/[A-Z]/', $input);
                $hasLower = preg_match('/[a-z]/', $input);
                $hasNumber = preg_match('/[0-9]/', $input);
                $hasSpecial = preg_match('/[^A-Za-z0-9]/', $input);
                
                return $hasUpper && $hasLower && $hasNumber && $hasSpecial;
                
            case 'date':
                $format = $options['format'] ?? 'Y-m-d';
                $date = DateTime::createFromFormat($format, $input);
                return $date && $date->format($format) === $input;
                
            case 'array':
                if (!is_array($input)) return false;
                $validItems = [];
                foreach ($input as $item) {
                    $validated = self::validateInput($item, $options['itemType'] ?? 'string');
                    if ($validated !== false) {
                        $validItems[] = $validated;
                    }
                }
                return $validItems;
                
            case 'filename':
                // Remove potentially dangerous characters
                $safe = preg_replace('/[^a-zA-Z0-9._-]/', '', $input);
                // Ensure no double extensions
                $parts = explode('.', $safe);
                if (count($parts) > 2) {
                    return false;
                }
                return $safe;
                
            default:
                return false;
        }
    }
    
    public static function validateCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                http_response_code(403);
                throw new Exception('CSRF token validation failed');
            }
        }
    }
    
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function preventSQLInjection($value) {
        if (is_array($value)) {
            return array_map([self::class, 'preventSQLInjection'], $value);
        }
        
        if (is_string($value)) {
            // Remove common SQL injection patterns
            $patterns = [
                '/\bUNION\b/i',
                '/\bSELECT\b/i',
                '/\bINSERT\b/i',
                '/\bUPDATE\b/i',
                '/\bDELETE\b/i',
                '/\bDROP\b/i',
                '/\bTRUNCATE\b/i',
                '/\bOR\b\s+\d+\s*[=<>]/i',
                '/\bAND\b\s+\d+\s*[=<>]/i'
            ];
            
            $value = preg_replace($patterns, '', $value);
            return addslashes($value);
        }
        
        return $value;
    }
    
    public static function validateFileUpload($file, $allowedTypes, $maxSize = 5242880) {
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
        
        // Scan file for malware (if ClamAV is installed)
        if (function_exists('clamav_scan')) {
            $scan = clamav_scan($file['tmp_name']);
            if ($scan !== true) {
                throw new Exception('File may be infected');
            }
        }

        return true;
    }
    
    public static function sanitizeFileName($filename) {
        // Remove any directory components
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Ensure single extension
        $parts = explode('.', $filename);
        if (count($parts) > 2) {
            $ext = array_pop($parts);
            $filename = implode('_', $parts) . '.' . $ext;
        }
        
        return $filename;
    }
    
    public static function generateSecurePassword($length = 16) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $password = '';
        
        try {
            for ($i = 0; $i < $length; $i++) {
                $password .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } catch (Exception $e) {
            // Fallback to less secure but still usable method
            for ($i = 0; $i < $length; $i++) {
                $password .= $chars[mt_rand(0, strlen($chars) - 1)];
            }
        }
        
        return $password;
    }
    
    private static function isBlacklisted($ip) {
        // Add IP blacklist check implementation
        $blacklist = [
            // Known bad IPs would go here
        ];
        
        return in_array($ip, $blacklist);
    }
}