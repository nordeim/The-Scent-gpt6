<think>

Thank you! Now that the full e-commerce platform for *The Scent* is complete, the next logical step is to **document it fully** for both **technical maintainability** and **deployment readiness**.

We will now proceed to create two critical documents:

1. 📘 `detailed_technical_design_specification.md`  
   → A thorough technical blueprint for developers and maintainers.

2. 🚀 `detailed_deployment_guide.md`  
   → A step-by-step guide for deploying the platform on a production-ready environment (e.g., LAMP server, or Docker).

---

## 🧠 Step-by-Step Planning

We’ll begin by **systematically planning the structure and contents** of both documents before generating them.

---

## 📘 1. Technical Design Specification Plan

### 🎯 Purpose

Provide developers with a **comprehensive understanding** of:

- System architecture
- Core components
- Data models
- Flow diagrams
- APIs / routes
- Security practices
- Extensibility

### 📑 Document Structure Plan

```markdown
# The Scent - Technical Design Specification

## 1. Overview
- Project description
- Tech stack
- Goals

## 2. System Architecture
- High-level diagram
- Folder structure
- Component roles

## 3. Database Design
- ERD diagram
- Table descriptions
- Relationships

## 4. Backend System
- MVC structure
- Routing (.htaccess)
- Controllers & Models
- Input validation
- Session handling

## 5. Authentication
- Login/Registration flow
- Password hashing
- Role management

## 6. Cart & Checkout
- Session-based cart
- Order processing logic
- Payment extension points

## 7. Scent Quiz Logic
- Quiz flow
- Answer mapping
- DB storage
- Product recommendation

## 8. Frontend Features
- Landing page layout
- Component breakdown
- AOS.js & Particles.js
- Tailwind responsiveness

## 9. Security Measures
- Input sanitization
- CSRF protection
- SQL injection prevention

## 10. Extensibility
- Adding new products
- New quiz questions
- Admin panel extension

## 11. Sample API Routes / URLs
- `index.php?page=product&id=1`
- `index.php?page=quiz`
- `admin/index.php`

## 12. Appendix
- Code snippets
- Sample queries
```

---

## 🚀 2. Deployment Guide Plan

### 🎯 Purpose

Enable a developer or DevOps team to **replicate and deploy the application** easily in a secure and production-ready way.

### 📑 Document Structure Plan

```markdown
# The Scent - Deployment Guide

## 1. Requirements
- Server (VPS or local)
- LAMP stack (Linux, Apache, MySQL, PHP)
- Git, Composer (optional)

## 2. File Structure
- Public root
- Secured folders
- Permissions

## 3. Installation Steps
### 3.1 Clone the repository
### 3.2 Create MySQL database
### 3.3 Import SQL schema
### 3.4 Configure `/config.php`

## 4. Apache Setup
- Setup virtual host
- Enable mod_rewrite
- .htaccess rules

## 5. File Permissions
- uploads/ folder
- config.php

## 6. Environment Configuration
- DB credentials
- BASE_URL

## 7. Testing the Application
- Access via browser
- Confirm routes

## 8. Optional: Docker Deployment
- Dockerfile
- docker-compose.yml
- Environment variables

## 9. Optional: SSL with Let's Encrypt
- Certbot instructions

## 10. Troubleshooting
- Common issues & fixes

## 11. Appendix
- Sample `.env`
- Sample `config.php`
```

---

## ✅ Implementation Strategy

1. Begin with `detailed_technical_design_specification.md`  
   → Logical, detailed, with examples.

2. Then generate `detailed_deployment_guide.md`  
   → With code snippets, command line steps, and tips.

---

</think>

---

## 📘 Now Creating: `detailed_technical_design_specification.md`

```markdown
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
```

---

## ✅ Next: Creating `detailed_deployment_guide.md`

```markdown
# The Scent - Deployment Guide

## 1. Requirements

- Ubuntu 20.04 or newer (recommended)
- Apache2
- PHP 8.0+
- MySQL 5.7+
- Git
- Composer (optional)

---

## 2. Folder Structure

- Place project in `/var/www/the-scent/`
- Public root should be `/public/`
- Secure `/includes/`, `/models/`, `/

Based on the previous planning phase, we’ve already completed the `detailed_technical_design_specification.md`. Now we will proceed to **complete the second document** in our plan: the **`detailed_deployment_guide.md`**.

The goal of this deployment guide is to ensure that:

- Any developer or DevOps engineer can **set up and deploy the full platform** (frontend + backend + database).
- It supports **traditional LAMP stack environments** and optionally supports **Docker-based deployments**.
- It is clear, structured, and includes **copy-paste-ready commands**, **config examples**, and **troubleshooting tips**.

This guide should be suitable for:
- Local development
- VPS deployment (e.g., DigitalOcean, Linode)
- Cloud environments (e.g., AWS, GCP)
- Dockerized environments (optional section)

---

## ✅ Let's Continue: `detailed_deployment_guide.md`

```markdown
# The Scent - Deployment Guide

This document provides a step-by-step procedure to install, configure, and deploy **The Scent** e-commerce platform on a LAMP stack (Linux + Apache + MySQL + PHP) server.

---

## ⚙️ 1. System Requirements

| Component | Minimum Version |
|----------|----------------|
| PHP      | 8.0+           |
| MySQL    | 5.7+           |
| Apache   | 2.4+           |
| Git      | Latest         |
| Linux    | Ubuntu 20.04+  |

---

## 📁 2. Directory Structure

```
/var/www/the-scent/
│
├── public/             # Web root (DocumentRoot)
├── includes/           # Shared scripts
├── controllers/        # Logic layer
├── models/             # DB access
├── views/              # HTML templates
├── admin/              # Admin dashboard
├── config.php          # DB config
├── .htaccess           # Clean URLs
```

> 🔒 Ensure `includes/`, `models/`, and `config.php` are **not directly accessible** via the web.

---

## 🧩 3. Installation Steps

### ✅ Step 3.1: Clone the Repository

```bash
cd /var/www/
git clone https://github.com/your-org/the-scent.git
cd the-scent
```

---

### ✅ Step 3.2: Set Permissions

```bash
# Allow Apache to write to uploads/ if needed
sudo chown -R www-data:www-data public/uploads
sudo chmod -R 755 public/uploads
```

---

### ✅ Step 3.3: Create MySQL Database

Log into MySQL:

```bash
mysql -u root -p
```

Then run:

```sql
CREATE DATABASE the_scent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'scent_user'@'localhost' IDENTIFIED BY 'StrongPassword123';
GRANT ALL PRIVILEGES ON the_scent.* TO 'scent_user'@'localhost';
FLUSH PRIVILEGES;
```

---

### ✅ Step 3.4: Import the Database Schema

```bash
mysql -u scent_user -p the_scent < database/schema.sql
```

---

### ✅ Step 3.5: Configure PHP

Edit `/etc/php/8.0/apache2/php.ini` and ensure:

```ini
file_uploads = On
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 50M
```

Restart Apache:

```bash
sudo systemctl restart apache2
```

---

## 🌐 4. Apache Configuration

### 🔧 Virtual Host Setup

Edit or create:

```bash
sudo nano /etc/apache2/sites-available/the-scent.conf
```

Paste:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/the-scent/public

    <Directory /var/www/the-scent/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/scent_error.log
    CustomLog ${APACHE_LOG_DIR}/scent_access.log combined
</VirtualHost>
```

Enable site and rewrite:

```bash
sudo a2ensite the-scent.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

---

### 🔁 .htaccess Setup

Ensure `/public/.htaccess` contains:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?page=$1 [QSA,L]
```

---

## 🔐 5. Secure File Permissions

```bash
chmod 640 config.php
chown www-data:www-data config.php
```

Do not store credentials in version control (see `.gitignore`).

---

## ⚙️ 6. Environment Configuration

Edit `/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'the_scent');
define('DB_USER', 'scent_user');
define('DB_PASS', 'StrongPassword123');
define('BASE_URL', '/'); // Or '/the-scent/public/' if in subfolder
```

---

## ✅ 7. Testing the Application

### Open in browser:

```
http://yourdomain.com/
```

Test:

- Home page loads ✔️
- Product links work ✔️
- Quiz works and stores results ✔️
- Admin panel accessible (if logged in) ✔️

---

## 🐳 8. Optional: Docker Deployment

### 🐳 Create `Dockerfile`

```Dockerfile
FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html/
WORKDIR /var/www/html/public
EXPOSE 80
```

---

### 🐋 `docker-compose.yml`

```yaml
version: '3.8'
services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db

  db:
    image: mysql:5.7
    restart: always
    environment:
      MYSQL_DATABASE: the_scent
      MYSQL_USER: scent_user
      MYSQL_PASSWORD: StrongPassword123
      MYSQL_ROOT_PASSWORD: rootpass
    volumes:
      - dbdata:/var/lib/mysql

volumes:
  dbdata:
```

---

To run:

```bash
docker-compose up -d
```

Access at: `http://localhost:8080/`

---

## 🔐 9. SSL with Let’s Encrypt (Production)

```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com
```

Auto-renew:

```bash
sudo crontab -e
# Add:
0 3 * * * /usr/bin/certbot renew --quiet
```

---

## 🛠️ 10. Troubleshooting Tips

| Issue | Fix |
|-------|-----|
| 404 errors | Ensure `.htaccess` and `AllowOverride All` |
| DB not connecting | Check `config.php`, DB user privileges |
| CSS not loading | Use absolute `BASE_URL` or relative paths |
| Uploads not working | Check `uploads/` folder permissions |

---

## 📄 11. Appendix

### Sample `.env` (if using dotenv or custom config)
```bash
DB_HOST=localhost
DB_NAME=the_scent
DB_USER=scent_user
DB_PASS=StrongPassword123
```

### Sample `config.php`
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'the_scent');
define('DB_USER', 'scent_user');
define('DB_PASS', 'StrongPassword123');
define('BASE_URL', '/');
```

---

## ✅ End of Deployment Guide
```

---

### ✅ Both Documents Generated Successfully

1. 📘 `detailed_technical_design_specification.md` – covers architecture, logic, DB, and more
2. 🚀 `detailed_deployment_guide.md` – a step-by-step LAMP + optional Docker deployment manual

---

## 🧩 Next Steps (Optional)

- 📦 Package both files into a downloadable zip?
- 📘 Generate printable PDFs of the documents?
- 🚀 Create a GitHub `README.md` for the repo?
- 🛡️ Add security hardening checklist?
