# The Scent - Deployment Guide (Updated)

## 1. Requirements
- Ubuntu 20.04+ (recommended)
- Apache2
- PHP 8.0+
- MySQL 5.7+
- Git
- Composer (optional)

---

## 2. Directory Structure
```
/var/www/the-scent/
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

## 3. Installation Steps

### 3.1: Clone the Repository
```bash
cd /var/www/
git clone https://github.com/your-org/the-scent.git
cd the-scent
```

### 3.2: Set Permissions
```bash
sudo chown -R www-data:www-data public/uploads
sudo chmod -R 755 public/uploads
```

### 3.3: Create MySQL Database
```bash
mysql -u root -p
```
```sql
CREATE DATABASE the_scent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'scent_user'@'localhost' IDENTIFIED BY 'StrongPassword123';
GRANT ALL PRIVILEGES ON the_scent.* TO 'scent_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3.4: Import the Database Schema
```bash
mysql -u scent_user -p the_scent < db/schema.sql
```

### 3.5: Configure PHP
Edit `/etc/php/8.0/apache2/php.ini`:
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

## 4. Apache Configuration

### Virtual Host Setup
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

### .htaccess Setup
Ensure `/public/.htaccess` contains:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?page=$1 [QSA,L]
```

---

## 5. Secure File Permissions
```bash
chmod 640 config.php
chown www-data:www-data config.php
```
> Do not store credentials in version control (see `.gitignore`).

---

## 6. Environment Configuration
Edit `/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'the_scent');
define('DB_USER', 'scent_user');
define('DB_PASS', 'StrongPassword123');
define('BASE_URL', '/');
```

---

## 7. Testing the Application
Open in browser:
```
http://yourdomain.com/
```
Test:
- Home page loads ✔️
- Product links work ✔️
- Quiz works and stores results ✔️
- Admin panel accessible (if logged in) ✔️

---

## 8. Optional: Docker Deployment

### Dockerfile
```Dockerfile
FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html/
WORKDIR /var/www/html/public
EXPOSE 80
```

### docker-compose.yml
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
To run:
```bash
docker-compose up -d
```
Access at: `http://localhost:8080/`

---

## 9. SSL with Let’s Encrypt (Production)
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

## 10. Troubleshooting Tips
| Issue | Fix |
|-------|-----|
| 404 errors | Ensure `.htaccess` and `AllowOverride All` |
| DB not connecting | Check `config.php`, DB user privileges |
| CSS not loading | Use absolute `BASE_URL` or relative paths |
| Uploads not working | Check `uploads/` folder permissions |

---

## 11. Appendix

### Sample `.env` (if using dotenv)
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

**End of Deployment Guide**
