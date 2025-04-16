Crtical error when loading the main landing page in browser:
```
Oops! Something went wrong
We apologize for the inconvenience. Please try again later.

Return Home
Go Back
```

```
[Wed Apr 16 18:56:00.474230 2025] [php:warn] [pid 3746799] [client 127.0.0.1:49282] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.474241 2025] [php:notice] [pid 3746799] [client 127.0.0.1:49282] [2025-04-16 10:56:00] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 14\nContext: {"url":"\\/public\\/videos\\/hero.mp4","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:00"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.474249 2025] [php:warn] [pid 3746799] [client 127.0.0.1:49282] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497568 2025] [php:notice] [pid 3746800] [client 127.0.0.1:49292] [2025-04-16 10:56:00] Warning: mkdir(): Permission denied in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 19\nContext: {"url":"\\/public\\/particles.json","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:00"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497646 2025] [php:warn] [pid 3746800] [client 127.0.0.1:49292] PHP Warning:  file_put_contents(/cdrom/project/The-Scent-gpt6/includes/../logs/security.log): Failed to open stream: No such file or directory in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 266, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497893 2025] [php:notice] [pid 3746800] [client 127.0.0.1:49292] [2025-04-16 10:56:00] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 10\nContext: {"url":"\\/public\\/particles.json","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:00"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497911 2025] [php:warn] [pid 3746800] [client 127.0.0.1:49292] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497925 2025] [php:notice] [pid 3746800] [client 127.0.0.1:49292] [2025-04-16 10:56:00] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 11\nContext: {"url":"\\/public\\/particles.json","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:00"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497935 2025] [php:warn] [pid 3746800] [client 127.0.0.1:49292] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497948 2025] [php:notice] [pid 3746800] [client 127.0.0.1:49292] [2025-04-16 10:56:00] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 12\nContext: {"url":"\\/public\\/particles.json","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:00"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497957 2025] [php:warn] [pid 3746800] [client 127.0.0.1:49292] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497969 2025] [php:notice] [pid 3746800] [client 127.0.0.1:49292] [2025-04-16 10:56:00] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 13\nContext: {"url":"\\/public\\/particles.json","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:00"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.497978 2025] [php:warn] [pid 3746800] [client 127.0.0.1:49292] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.498001 2025] [php:notice] [pid 3746800] [client 127.0.0.1:49292] [2025-04-16 10:56:00] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 14\nContext: {"url":"\\/public\\/particles.json","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:00"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:00.498010 2025] [php:warn] [pid 3746800] [client 127.0.0.1:49292] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417640 2025] [php:notice] [pid 3746798] [client 127.0.0.1:49308] [2025-04-16 10:56:01] Warning: mkdir(): Permission denied in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 19\nContext: {"url":"\\/favicon.ico","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:01"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417690 2025] [php:warn] [pid 3746798] [client 127.0.0.1:49308] PHP Warning:  file_put_contents(/cdrom/project/The-Scent-gpt6/includes/../logs/security.log): Failed to open stream: No such file or directory in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 266, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417857 2025] [php:notice] [pid 3746798] [client 127.0.0.1:49308] [2025-04-16 10:56:01] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 10\nContext: {"url":"\\/favicon.ico","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:01"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417873 2025] [php:warn] [pid 3746798] [client 127.0.0.1:49308] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417887 2025] [php:notice] [pid 3746798] [client 127.0.0.1:49308] [2025-04-16 10:56:01] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 11\nContext: {"url":"\\/favicon.ico","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:01"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417897 2025] [php:warn] [pid 3746798] [client 127.0.0.1:49308] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417908 2025] [php:notice] [pid 3746798] [client 127.0.0.1:49308] [2025-04-16 10:56:01] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 12\nContext: {"url":"\\/favicon.ico","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:01"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417917 2025] [php:warn] [pid 3746798] [client 127.0.0.1:49308] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417929 2025] [php:notice] [pid 3746798] [client 127.0.0.1:49308] [2025-04-16 10:56:01] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 13\nContext: {"url":"\\/favicon.ico","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:01"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417951 2025] [php:warn] [pid 3746798] [client 127.0.0.1:49308] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417964 2025] [php:notice] [pid 3746798] [client 127.0.0.1:49308] [2025-04-16 10:56:01] Warning: Cannot modify header information - headers already sent by (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/SecurityMiddleware.php on line 14\nContext: {"url":"\\/favicon.ico","method":"GET","ip":"127.0.0.1","timestamp":"2025-04-16 10:56:01"}, referer: https://the-scent.com/
[Wed Apr 16 18:56:01.417973 2025] [php:warn] [pid 3746798] [client 127.0.0.1:49308] PHP Warning:  http_response_code(): Cannot set response code - headers already sent (output started at /cdrom/project/The-Scent-gpt6/views/layout/header.php:9) in /cdrom/project/The-Scent-gpt6/includes/ErrorHandler.php on line 225, referer: https://the-scent.com/
```

---

To address the critical mismatches between the current implementation (the current public/css/style.css, views/home.php, views/layout/header.php and views/layout/footer.php files) and the sample UI for the main landing page as provided by the index.html and style.css inside the "sample_landing_page_design/" folder, you will need to focus on the following areas:

1. **Header/Nav**:  
   - Icon layout: Add missing search/account/cart icons and ensure their placement matches the sample.
   - Nav link structure: Ensure the order and presence of links match the sample (add "Home", ensure all sections are present).
   - Sticky behavior: Refine styles for sticky header, especially background transition and text color.

2. **Featured Products**:  
   - Card style: Adjust border-radius, shadow, padding, image sizing to match sample.
   - CTA: Move "Shop All Products" CTA below the product grid, style to match sample.
   - Badges: Remove or restyle badges to match sample (or hide if not present in sample).
   - Product info: Add short description if available.

3. **Newsletter**:  
   - Form style: Refine input and button styles (border-radius, color, font, padding).
   - Consent note: Add consent text below form.

4. **Footer**:  
   - Column layout: Expand to 4 columns (About, Shop, Help, Contact).
   - Content: Add About, Help, and Contact sections, and fill with proper content.
   - Payment icons: Add FontAwesome payment method icons in the footer bottom.
   - Social icons: Ensure all sample social icons are present and styled.

**Constraints:**
- Do not break PHP logic or dynamic content.
- All changes must be implemented in the PHP template files (`views/layout/header.php`, `views/home.php`, `views/layout/footer.php`) and the main stylesheet (`public/css/style.css`).
- Use semantic, accessible HTML.
- Only add/adjust inline PHP in templates where absolutely necessary (e.g., for product descriptions).

** Use the following methon to come up with your own execution plan:**

1. **Header/Nav**  
   - Edit `views/layout/header.php`:
     - Adjust nav link order, add missing links/icons.
     - Refactor the icon layout for search, account, cart.
     - Improve sticky header classes and JS if needed.

2. **Featured Products Section**  
   - Edit `views/home.php`:
     - Update product card markup to match sample: adjust card class, add short description, hide badges.
     - Add "Shop All Products" CTA below grid.
   - Edit `public/css/style.css`:
     - Add/adjust relevant CSS classes (border-radius, box-shadow, card padding, CTA style).

   2.1 Update the home page (views/home.php):
   - Featured product card: Merge improved markup (border-radius, shadow, padding, img sizing).
   - Remove product badges if not in sample.
   - Show short description if available.
   - Move "Shop All Products" CTA below grid.
   - Merge newsletter section improvements.

3. **Newsletter**  
   - Edit `views/home.php` and `views/layout/footer.php`:
     - Update newsletter form markup for input/button style.
     - Add consent note below form.
   - Edit `public/css/style.css`:
     - Refine form element CSS.

4. **Footer**  
   - Edit `views/layout/footer.php`:
     - Expand to 4 columns as per sample, add About, Shop, Help, Contact sections.
     - Add payment method icons (FontAwesome).
     - Ensure full set of social icons.
   - Edit `public/css/style.css`:
     - Adjust footer grid and icon styles.

5. **Test and Verify**  
   - Ensure no PHP logic is broken.
   - Review on desktop and mobile for visual match.


IMPORTANT NOTE: take care not to merge the suggested changes below to the current project files instead of replacing them because we don't want to lose other features and functionalities while fixing the UI mismatch against the sample UI design issue:

# Use the following steps to merge the suggested changes below to the actual project files:
1. Update `views/layout/header.php` for nav links and icons; ensure sticky header classes/JS are correct.
2. Update `views/home.php` for featured products grid markup; move/add CTA, remove badges, add short description.
3. Update newsletter form markup and consent note in both `views/home.php` and `views/layout/footer.php`.
4. Update `views/layout/footer.php` for footer columns, content, payment icons, social icons.
5. Refactor/add CSS rules in `public/css/style.css` for header, product cards, newsletter form, footer.
6. Double-check all changes for PHP compatibility.
</plan>

Use the following as suggested changes only. You should do your own validation of the following suggested code fixes against the original file to apply / merge the relevant sections. Again DO NOT replace the original files as is!!!

````php name=views/layout/header.php
<?php
require_once __DIR__ . '/../../includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'The Scent - Premium Aromatherapy Products' ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@400;500;600&family=Raleway:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1A4D5A',
                        'primary-dark': '#164249',
                        secondary: '#A0C1B1',
                        accent: '#D4A76A'
                    },
                    fontFamily: {
                        heading: ['Cormorant Garamond', 'serif'],
                        body: ['Montserrat', 'sans-serif'],
                        accent: ['Raleway', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="/public/css/style.css">
    
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Initialize mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            let isMenuOpen = false;

            function toggleMenu() {
                isMenuOpen = !isMenuOpen;
                mobileMenu.classList.toggle('active');
                document.body.classList.toggle('menu-open');
                mobileMenuToggle.innerHTML = isMenuOpen ? 
                    '<i class="fas fa-times"></i>' : 
                    '<i class="fas fa-bars"></i>';
            }

            function closeMenu() {
                if (isMenuOpen) {
                    isMenuOpen = false;
                    mobileMenu.classList.remove('active');
                    document.body.classList.remove('menu-open');
                    mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            }

            mobileMenuToggle?.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMenu();
            });

            document.addEventListener('click', function(e) {
                if (isMenuOpen && !e.target.closest('.main-nav')) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isMenuOpen) {
                    closeMenu();
                }
            });

            mobileMenu?.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', closeMenu);
            });
        });
    </script>
</head>
<body>
    <header>
        <nav class="main-nav sample-header">
            <div class="container header-container">
                <div class="logo">
                    <a href="index.php" style="text-transform:uppercase; letter-spacing:1px;">The Scent</a>
                    <span style="display:block; font-family:'Raleway',sans-serif; font-size:0.7rem; letter-spacing:2px; text-transform:uppercase; color:#A0C1B1; margin-top:-5px; opacity:0.8;">AROMATHERAPY</span>
                </div>
                <div class="nav-links" id="mobile-menu">
                    <a href="index.php">Home</a>
                    <a href="index.php?page=products">Shop</a>
                    <a href="index.php?page=quiz">Scent Finder</a>
                    <a href="index.php?page=about">About</a>
                    <a href="index.php?page=contact">Contact</a>
                </div>
                <div class="header-icons">
                    <a href="#" aria-label="Search"><i class="fas fa-search"></i></a>
                    <?php if (isLoggedIn()): ?>
                        <a href="index.php?page=account" aria-label="Account"><i class="fas fa-user"></i></a>
                    <?php else: ?>
                        <a href="index.php?page=login" aria-label="Login"><i class="fas fa-user"></i></a>
                    <?php endif; ?>
                    <a href="index.php?page=cart" class="cart-link" aria-label="Cart">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-count"><?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0 ?></span>
                    </a>
                </div>
                <button class="mobile-menu-toggle md:hidden" aria-label="Toggle Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>
    <main>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message <?= $_SESSION['flash_type'] ?? 'info' ?>">
                <?= $_SESSION['flash_message'] ?>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>
````

````php name=views/home.php
<?php 
require_once __DIR__ . '/layout/header.php';
echo '<!-- DEBUG: home.php loaded -->';
$delay = 0; // Initialize delay counter for animations
?>

<!-- Hero Section with Video Background -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    <div id="particles-js" class="absolute inset-0 z-0"></div>
    <video class="absolute inset-0 w-full h-full object-cover z-[-1]" autoplay muted loop playsinline poster="/public/images/scent5.jpg">
        <source src="/public/videos/hero.mp4" type="video/mp4">
        <img src="/public/images/scent5.jpg" alt="Calming Nature" class="w-full h-full object-cover" />
    </video>
    <div class="absolute inset-0 bg-gradient-to-br from-primary/70 to-primary-dark/80 z-10"></div>
    <div class="container relative z-20 flex flex-col items-center justify-center text-center text-white px-6">
        <div data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 font-heading" style="text-shadow: 0 2px 4px rgba(0,0,0,0.7);">Find Your Moment of Calm</h1>
            <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto font-body">Experience premium, natural aromatherapy crafted to enhance well-being and restore balance.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#featured-products" class="btn btn-primary">Explore Our Collections</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-section py-16 bg-light" id="featured-products">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-12" data-aos="fade-up">Featured Collections</h2>
        <div class="featured-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 px-6">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card sample-card" data-aos="zoom-in" style="border-radius:8px; box-shadow:0 4px 15px rgba(0,0,0,0.05); overflow:hidden;">
                        <img src="<?= htmlspecialchars($product['image'] ?? '/public/images/placeholder.jpg') ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-64 object-cover" loading="lazy">
                        <div class="product-info" style="padding:1.5rem; text-align:center;">
                            <h3 style="margin-bottom:0.5rem; font-size:1.3rem;"><?= htmlspecialchars($product['name']) ?></h3>
                            <?php if (!empty($product['short_description'])): ?>
                                <p style="font-size:0.9rem; color:#666; margin-bottom:1rem;"><?= htmlspecialchars($product['short_description']) ?></p>
                            <?php elseif (!empty($product['category_name'])): ?>
                                <p style="font-size:0.9rem; color:#666; margin-bottom:1rem;"><?= htmlspecialchars($product['category_name']) ?></p>
                            <?php endif; ?>
                            <a href="index.php?page=product&id=<?= $product['id'] ?>" class="product-link" style="display:inline-block;font-family:'Raleway',sans-serif;font-weight:500;color:#D4A76A;text-transform:uppercase;font-size:0.85rem;letter-spacing:0.5px;position:relative;padding-bottom:3px;">View Product</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-gray-600">
                    <p>Discover our curated collection of premium aromatherapy products.</p>
                    <a href="index.php?page=products" class="inline-block mt-4 text-primary hover:underline">Browse All Products</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="view-all-cta" style="text-align:center; margin-top:3rem;">
            <a href="index.php?page=products" class="btn btn-primary">Shop All Products</a>
        </div>
    </div>
</section>

<!-- ... rest of the file remains unchanged ... -->

<!-- Newsletter Section -->
<section class="newsletter-section py-20 bg-light">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-6">Stay Connected</h2>
            <p class="mb-8">Subscribe to receive updates, exclusive offers, and aromatherapy tips.</p>
            <form id="newsletter-form" class="newsletter-form flex flex-col sm:flex-row gap-4 justify-center">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="email" name="email" placeholder="Enter your email" required class="newsletter-input flex-1 px-4 py-2 rounded-full border border-gray-300 focus:border-primary">
                <button type="submit" class="btn btn-primary newsletter-btn">Subscribe</button>
            </form>
            <p class="newsletter-consent" style="font-size:0.8rem;opacity:0.7; margin-top:1rem;">By subscribing, you agree to our <a href="index.php?page=privacy" style="color:#A0C1B1;text-decoration:underline;">Privacy Policy</a>.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
````

````php name=views/layout/footer.php
</main>
    <footer>
        <div class="container">
            <div class="footer-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:3rem;margin-bottom:3rem;">
                <div class="footer-about">
                    <h3>About The Scent</h3>
                    <p>Creating premium aromatherapy products to enhance mental and physical well-being through the power of nature.</p>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="footer-links">
                    <h3>Shop</h3>
                    <ul>
                        <li><a href="index.php?page=products">Essential Oils</a></li>
                        <li><a href="index.php?page=products">Natural Soaps</a></li>
                        <li><a href="index.php?page=products">Gift Sets</a></li>
                        <li><a href="index.php?page=products">New Arrivals</a></li>
                        <li><a href="index.php?page=products">Bestsellers</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h3>Help</h3>
                    <ul>
                        <li><a href="index.php?page=contact">Contact Us</a></li>
                        <li><a href="index.php?page=faq">FAQs</a></li>
                        <li><a href="index.php?page=shipping">Shipping & Returns</a></li>
                        <li><a href="index.php?page=order-tracking">Track Your Order</a></li>
                        <li><a href="index.php?page=privacy">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Aromatherapy Lane, Wellness City, WB 12345</p>
                    <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> hello@thescent.com</p>
                    <form id="newsletter-form-footer" class="newsletter-form" style="margin-top:1rem;">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        <input type="email" name="email" placeholder="Enter your email" required class="newsletter-input">
                        <button type="submit" class="btn btn-primary newsletter-btn">Subscribe</button>
                    </form>
                    <p class="newsletter-consent" style="font-size:0.8rem;opacity:0.7; margin-top:1rem;">By subscribing, you agree to our <a href="index.php?page=privacy" style="color:#A0C1B1; text-decoration:underline;">Privacy Policy</a>.</p>
                </div>
            </div>
            <div class="footer-bottom" style="background-color:#222b2e; padding:1.5rem 0; margin-top:2rem;">
                <div class="container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;font-size:0.85rem;">
                    <p>&copy; <?= date('Y') ?> The Scent. All rights reserved.</p>
                    <div class="payment-methods" style="display:flex;align-items:center;gap:0.8rem;">
                        <span>Accepted Payments:</span>
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-paypal"></i>
                        <i class="fab fa-cc-amex"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                offset: 100,
                once: true
            });

            if (document.getElementById('particles-js')) {
                particlesJS.load('particles-js', '/particles.json');
            }
        });

        // Newsletter Form
        document.getElementById('newsletter-form')?.addEventListener('submit', handleNewsletterSubmit);
        document.getElementById('newsletter-form-footer')?.addEventListener('submit', handleNewsletterSubmit);

        function handleNewsletterSubmit(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('index.php?page=newsletter&action=subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<p class="success">Thank you for subscribing!</p>';
                } else {
                    alert(data.message || 'Subscription failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Subscription failed. Please try again.');
            });
        }

        // Add to Cart functionality with CSRF protection
        document.querySelectorAll('.add-to-cart')?.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', '1');
                formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

                fetch('index.php?page=cart&action=add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cartCount;
                        }
                        alert('Product added to cart!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to add product to cart. Please try again.');
                });
            });
        });
    </script>
</body>
</html>
````

````css name=public/css/style.css
/* --- Additions/Overrides for Sample UI Match --- */

/* HEADER/NAV FIXES */
.sample-header {
    position: absolute;
    top: 0; left: 0; width: 100%;
    z-index: 1000;
    padding: 1.5rem 0;
    background: transparent;
    transition: background-color 0.3s, box-shadow 0.3s, padding 0.3s;
}
.sample-header.sticky {
    position: fixed;
    background-color: rgba(255,255,255,0.95);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 0.8rem 0;
}
.sample-header .logo a,
.sample-header .logo span,
.sample-header .nav-links a,
.sample-header .header-icons a {
    color: #1A4D5A;
}

/* Nav link styling (sample) */
.nav-links a {
    font-family: 'Raleway',sans-serif;
    font-weight: 500;
    color: #1A4D5A;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 5px 0;
    position: relative;
    margin-left: 2rem;
    transition: color 0.2s;
}
.nav-links a:first-child { margin-left: 0; }
.nav-links a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: #D4A76A;
    transition: width 0.3s;
}
.nav-links a:hover::after, .nav-links a:focus::after { width: 100%; }
.header-icons { display: flex; gap: 1.2rem; }
.header-icons a { color: #1A4D5A; font-size: 1.2rem; }

/* --- PRODUCT CARD --- */
.sample-card {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}
.sample-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
.sample-card img { height: 250px; object-fit: cover; transition: opacity 0.3s; }
.sample-card:hover img { opacity: 0.85; }
.product-info { padding: 1.5rem; text-align: center; }
.product-info h3 { margin-bottom: 0.5rem; font-size: 1.3rem; }
.product-info p { font-size: 0.9rem; color: #666; margin-bottom: 1rem; }
.product-link {
    font-family: 'Raleway',sans-serif;
    font-weight: 500;
    color: #D4A76A;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    position: relative;
    padding-bottom: 3px;
    display: inline-block;
}
.product-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 1px;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: #D4A76A;
    transition: width 0.3s;
}
.sample-card:hover .product-link::after { width: 50%; }
.view-all-cta { text-align: center; margin-top: 3rem; }

/* --- NEWSLETTER --- */
.newsletter-form {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.newsletter-input {
    padding: 0.8rem;
    border: 1px solid #A0C1B1;
    border-radius: 50px;
    font-family: 'Montserrat', sans-serif;
    min-width: 300px;
    flex-grow: 1;
}
.newsletter-btn {
    background-color: #D4A76A;
    color: #1A4D5A;
    border-color: #D4A76A;
    border-radius: 50px;
    font-family: 'Raleway',sans-serif;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.8rem 2rem;
    transition: background-color 0.3s, color 0.3s;
}
.newsletter-btn:hover {
    background-color: #A0C1B1;
    border-color: #A0C1B1;
    color: #1A4D5A;
}
.newsletter-consent {
    font-size: 0.8rem;
    opacity: 0.7;
    margin-bottom: 0;
}
.newsletter-consent a {
    color: #A0C1B1;
    text-decoration: underline;
}
.newsletter-consent a:hover {
    color: #fff;
}

/* --- FOOTER --- */
.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap: 3rem;
    margin-bottom: 3rem;
}
.footer-about h3, .footer-links h3, .footer-contact h3 {
    font-family: 'Raleway',sans-serif;
    color: #fff;
    font-weight: 600;
    margin-bottom: 1.2rem;
    font-size: 1.1rem;
}
.footer-about p { line-height: 1.6; margin-bottom: 1rem; }
.social-icons { display: flex; gap: 1rem; }
.social-icons a { color: #ccc; font-size: 1.2rem; transition: color 0.3s, transform 0.3s; }
.social-icons a:hover { color: #D4A76A; transform: scale(1.1); }
.footer-links ul li { margin-bottom: 0.5rem; }
.footer-links a { color: #ccc; }
.footer-links a:hover { color: #fff; text-decoration: underline; }
.footer-contact p { margin-bottom: 0.6rem; display: flex; align-items: center; gap: 0.5rem; }
.footer-contact i { color: #A0C1B1; width: 16px; text-align: center; }
.footer-bottom { background-color: #222b2e; padding: 1.5rem 0; margin-top: 2rem; }
.footer-bottom .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.85rem;
}
.payment-methods { display: flex; align-items: center; gap: 0.8rem; }
.payment-methods span { margin-right: 0.5rem; }
.payment-methods i { font-size: 1.8rem; color: #aaa; }

/* Responsive Adjustments */
@media (max-width: 992px) {
    .header-container { padding: 0 1.5rem; }
    .nav-links, .header-icons { display: none; }
    .mobile-menu-toggle { display: block; }
    .footer-grid { grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); }
}
@media (max-width: 576px) {
    .footer-grid { grid-template-columns: 1fr; text-align: center; }
    .social-icons { justify-content: center; margin-top: 1rem; }
    .footer-bottom .container { flex-direction: column; text-align: center; }
    .payment-methods { margin-top: 1rem; }
}
````
