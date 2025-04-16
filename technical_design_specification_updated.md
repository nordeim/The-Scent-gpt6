# The Scent - Technical Design Specification (Updated)

## 1. Overview

**Project Name:** The Scent  
**Purpose:** Modular, secure e-commerce platform for premium aromatherapy products.

**Tech Stack:**
- Apache2 (Web server)
- PHP 8+ (Backend, MVC-style)
- MySQL 5.7+ (Database)
- HTML5, Tailwind CSS, custom CSS, JS (Frontend)
- AOS.js, Particles.js (Animations)

---

## 2. System Architecture

### High-Level Diagram
```
[Browser]
   ↓
[Apache2 Server]
   ↓
[public/index.php] → [Router] → [Controllers] → [Models] → [MySQL DB]
            ↑             ↓         ↓
         [Views]     [Includes]   [Sessions]
```

### Folder Structure
```
/the-scent/
├── public/             # Web root (index.php, assets)
├── includes/           # Shared PHP (header, footer, db, auth, security)
├── controllers/        # Business logic (Product, Cart, User, Quiz, etc.)
├── models/             # DB abstraction (Product, User, Order, Quiz, etc.)
├── views/              # HTML templates (home, product, cart, quiz, etc.)
├── admin/              # Admin dashboard
├── config.php          # DB & app config
├── .htaccess           # Routing
└── ...
```

---

## 3. Database Design

- Normalized (3NF) schema with foreign keys and indexes
- Main tables: users, products, categories, orders, order_items, cart_items, newsletter_subscribers, quiz_results
- See `/db/schema.sql` for full DDL

**Example Table:**
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 4. Backend System

### Routing & MVC Flow
- `.htaccess` rewrites all requests to `public/index.php?page=...`
- `index.php` dispatches to controllers based on `page` param
- Controllers call models for DB, then render views

**Example:**
```
/public/index.php?page=product&id=1
→ controllers/ProductController.php → models/Product.php
→ views/product.php
```

---

## 5. Authentication & Session Management

- Passwords hashed with `password_hash()` (bcrypt)
- Login with `password_verify()`
- Secure session cookies (`httponly`, `secure`, `samesite`)
- Session ID regeneration, session integrity checks
- CSRF tokens for all forms (see `SecurityMiddleware.php`)
- Rate limiting on login/register/reset endpoints
- Audit logging for auth events

**Session Example:**
```php
$_SESSION['user']['id']
$_SESSION['user']['role']
```

---

## 6. Cart, Checkout, and Orders

- Cart stored in `cart_items` (DB) or session (for guests)
- Checkout flow: Cart → Checkout → Order → Order Items
- Payment integration (Stripe) via `PaymentController`
- Inventory and stock validation
- Tax and coupon logic via dedicated controllers

---

## 7. Scent Quiz Logic

- Multi-step quiz UI (JS, AOS.js)
- Answers mapped to product/category IDs (see `quiz_mappings.php`)
- Results stored in `quiz_results` (user or anonymous)
- Recommendations displayed and can be emailed

---

## 8. Frontend Features

- **Tailwind CSS** for layout, grid, and responsive design
- **AOS.js** for scroll-triggered animations
- **Particles.js** for ambient background effects
- **Hero video** background in landing page
- Featured products, testimonials, quiz CTA, newsletter, and more
- Mobile-first, accessible, SEO-friendly

---

## 9. Security Measures

- Input sanitization (`htmlspecialchars`, `filter_input`)
- Prepared statements (PDO)
- CSRF protection (tokens)
- Rate limiting, session hardening, security headers
- File permissions (config.php 640, uploads restricted)
- Audit and error logging

---

## 10. Extensibility

- Add products/categories via admin
- Extend quiz logic via `quiz_mappings.php`
- Add new controllers/models/views for features
- REST API and SPA frontend possible in future

---

## 11. Sample Routes

| Page      | URL Example                      |
|-----------|----------------------------------|
| Home      | `/` or `/?page=home`             |
| Product   | `/?page=product&id=3`            |
| Cart      | `/?page=cart`                    |
| Quiz      | `/?page=quiz`                    |
| Admin     | `/admin/index.php`               |

---

## 12. Appendix

- See `/db/schema.sql` for full schema
- See `/config.php` for environment and security settings
- See `/public/css/style.css` for custom styles
- See `/views/home.php` for landing page structure

---

**End of Technical Design Specification**
