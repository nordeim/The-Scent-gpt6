# Issues Found and Resolution Steps: Landing Page & Platform Stability

## 1. Landing Page Not Rendering / Incomplete Output
- **Symptoms:** Only header/nav loaded, rest of the page blank. Browser showed generic error message.
- **Root Cause:** Fatal PHP error due to undefined function `isLoggedIn()` in `views/layout/header.php`.
- **Resolution:** Ensured `includes/auth.php` is required at the top of `views/layout/header.php` so all authentication helpers are available.

## 2. Database Connection Errors / Fatal PDO Exception
- **Symptoms:** Fatal error: `PDO::__construct() expects at least 1 argument, 0 given` in `BaseController.php`.
- **Root Cause:** `$pdo` was not initialized or not passed to controllers due to missing or misordered `require_once` for `includes/db.php` in `public/index.php`.
- **Resolution:** Required `includes/db.php` before any controller instantiation in `public/index.php` and ensured `$pdo` is set in `includes/db.php`.

## 3. Missing Database Columns (stock_quantity, etc.)
- **Symptoms:** SQL error: `Unknown column 'p.stock_quantity' in 'field list'`.
- **Root Cause:** The `products` table was missing the `stock_quantity` column (and others used in code).
- **Resolution:** Updated the database schema to add all required columns (`stock_quantity`, `low_stock_threshold`, `highlight_text`, etc.) to the `products` table.

## 4. Undefined Array Key 'image_url' in home.php
- **Symptoms:** PHP warning: `Undefined array key "image_url"` and deprecation warning for `htmlspecialchars()`.
- **Root Cause:** Code referenced `$product['image_url']` but the DB and code use `$product['image']`.
- **Resolution:** Updated `views/home.php` to use `$product['image']` with a fallback placeholder.

## 5. Session Warnings: session_set_cookie_params()
- **Symptoms:** PHP warning: `session_set_cookie_params(): Session cookie parameters cannot be changed when a session is active`.
- **Root Cause:** `session_set_cookie_params()` was called after `session_start()`.
- **Resolution:** Updated `includes/auth.php` and `includes/SecurityMiddleware.php` to only call `session_set_cookie_params()` if the session is not already active.

## 6. Class Not Found: EmailService
- **Symptoms:** Fatal error: `Class "EmailService" not found` in `BaseController.php`.
- **Root Cause:** `EmailService.php` was not required in `BaseController.php`.
- **Resolution:** Added `require_once __DIR__ . '/../includes/EmailService.php';` at the top of `BaseController.php`.

## 7. Log Directory Missing
- **Symptoms:** PHP warning: `file_put_contents(.../logs/security.log): Failed to open stream: No such file or directory`.
- **Root Cause:** The `logs/` directory did not exist or had wrong permissions.
- **Resolution:** Created the `logs/` directory and set correct permissions.

## 8. Constant Already Defined Warnings
- **Symptoms:** PHP warning: `Constant DB_HOST already defined`.
- **Root Cause:** Redefinition of DB constants in `config.php`.
- **Resolution:** Removed unnecessary redefinition block from `config.php`.

---

# Summary of Fixes
- All fatal errors and warnings resolved.
- Landing page now renders fully and correctly.
- Database schema and code are in sync.
- Session and security warnings eliminated.
- Documentation and codebase are up to date.
