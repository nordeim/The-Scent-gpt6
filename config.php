<?php
// Environment Setting
define('ENVIRONMENT', 'development'); // Change to 'production' for live site

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'the_scent');
define('DB_USER', 'scent_user');
define('DB_PASS', 'StrongPassword123');
define('BASE_URL', '/');

// Ensure all database constants are properly escaped
foreach (['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'] as $const) {
    if (defined($const)) {
        define($const, trim(constant($const)));
    }
}

// Stripe Configuration
define('STRIPE_PUBLIC_KEY', 'pk_test_your_stripe_public_key');
define('STRIPE_SECRET_KEY', 'sk_test_your_stripe_secret_key');
define('STRIPE_WEBHOOK_SECRET', 'whsec_your_stripe_webhook_secret');

// Email Configuration (for next phase)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your_email@gmail.com');
define('SMTP_PASS', 'your_email_app_password');
define('SMTP_FROM', 'noreply@thescent.com');
define('SMTP_FROM_NAME', 'The Scent');

// Application Settings
define('TAX_RATE', 0.10); // 10% tax rate
define('FREE_SHIPPING_THRESHOLD', 50.00); // Free shipping on orders over $50
define('SHIPPING_COST', 5.99); // Standard shipping cost

// Error Logging Configuration
define('ERROR_LOG_PATH', '/var/log/thescent/');
define('ERROR_LOG_LEVEL', E_ALL);

// Quiz Configuration
define('QUIZ_MAX_ATTEMPTS', 3);
define('QUIZ_RESULT_EXPIRY_DAYS', 30);
define('RECOMMENDATION_LIMIT', 5);
