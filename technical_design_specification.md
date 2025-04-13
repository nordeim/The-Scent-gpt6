# The Scent - Technical Design Specification

## 1. Overview

**Project Name:** The Scent  
**Purpose:** A full-stack, modular e-commerce platform for natural aromatherapy products.

**Tech Stack:**
- Apache2 (Web server)
- PHP 8+ (Backend)
- MySQL 5.7+ (Database)
- HTML5, CSS3 / Tailwind CSS / JS (Frontend)
- AOS.js + Particles.js (Animation & Interactivity)

---

## 2. System Architecture

### High-Level Diagram:

```
[Browser]
   ↓
[Apache2 Server]
   ↓
[public/index.php] → [Router] → [Controllers] → [Models] → [MySQL DB]
            ↑             ↓         ↓
         [Views]     [Includes]   [Sessions]
```

### Folder Structure:

```
/the-scent/
│
├── public/             # Web root
├── includes/           # Shared PHP (header, footer, db)
├── controllers/        # Logic layer
├── models/             # DB abstraction
├── views/              # HTML templates
├── admin/              # Admin dashboard
├── config.php          # DB config
└── .htaccess           # Routing
```

---

## 3. Database Design

### Entity Relationship Diagram (ERD):

- Users (1) ←→ (∞) Orders
- Orders (1) ←→ (∞) Order Items
- Products (∞) ←→ (1) Categories
- Users (1) ←→ (∞) Quiz Results

### Table Summary:

| Table               | Purpose |
|---------------------|---------|
| `users`             | Auth & roles |
| `products`          | Product catalog |
| `categories`        | Product grouping |
| `orders`            | Order headers |
| `order_items`       | Line items |
| `cart_items`        | User/guest cart |
| `quiz_results`      | Personalization data |
| `newsletter_subscribers` | Email capture |

---

## 4. Backend System

### MVC-style Flow:

```
/public/index.php → page=product&id=1
→ controllers/ProductController.php → models/Product.php
→ views/product.php
```

### Routing:

- Apache `.htaccess` rewrites URLs:
```apache
RewriteRule ^(.*)$ index.php?page=$1 [QSA,L]
```

### Session Handling:

- `session_start()` in `auth.php`
- Cart tracked by session ID or user ID

---

## 5. Authentication

- Passwords stored with `password_hash()`
- Login verifies with `password_verify()`
- Session variables:
```php
$_SESSION['user']['id']
$_SESSION['user']['role'] = 'admin'
```

---

## 6. Cart & Checkout

- Cart stored in `cart_items` (DB) or session
- Checkout flow:
```text
Cart → Checkout → Order Created → Order Items Inserted
```

- Future: Payment gateway (Stripe/PayPal) integration point

---

## 7. Scent Quiz Logic

### Flow:

1. User selects mood (e.g., “Relaxation”)
2. Mapped to product IDs
3. Result stored in `quiz_results`
4. Matching products displayed

### Example mapping:
```php
'relaxation' => [1, 3]
```

---

## 8. Frontend Features

- **Tailwind CSS** grid & responsive classes
- **AOS.js** fade-in on scroll:
```html
<div data-aos="fade-up">...</div>
```
- **Particles.js**:
```js
particlesJS.load('particles-js', 'particles.json');
```
- **Hero video**:
```html
<video autoplay muted loop>
  <source src="/videos/hero.mp4" type="video/mp4">
</video>
```

---

## 9. Security Measures

- Input sanitization with `htmlspecialchars()` and `filter_input()`
- Prepared statements via PDO
- Password hashed with bcrypt
- Session checking for admin routes

---

## 10. Extensibility

- Add a new product: insert into `products` table or via admin form
- Add quiz steps: extend `quiz_mappings.php`
- Add admin features: extend `admin/` and `controllers/`

---

## 11. Sample Routes

| Page | URL |
|------|-----|
| Home | `/` or `/index.php?page=home` |
| Product | `/index.php?page=product&id=3` |
| Cart | `/index.php?page=cart` |
| Quiz | `/index.php?page=quiz` |
| Admin | `/admin/index.php` |

---

## 12. Appendix

### Sample: Product Query

```php
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
```

---

## ✅ End of Document
