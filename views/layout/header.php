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

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (isMenuOpen && !e.target.closest('.main-nav')) {
                    closeMenu();
                }
            });

            // Close menu when pressing escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isMenuOpen) {
                    closeMenu();
                }
            });

            // Handle menu links
            mobileMenu?.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', closeMenu);
            });
        });
    </script>
</head>
<body>
    <header>
        <nav class="main-nav">
            <div class="container">
                <a href="index.php" class="logo">
                    The Scent
                    <span>Premium Aromatherapy</span>
                </a>
                <button class="mobile-menu-toggle md:hidden" aria-label="Toggle Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="nav-links" id="mobile-menu">
                    <a href="index.php?page=products">Shop</a>
                    <a href="index.php?page=quiz">Find Your Scent</a>
                    <a href="index.php?page=about">About</a>
                    <a href="index.php?page=contact">Contact</a>
                </div>
                <div class="nav-actions">
                    <?php if (isLoggedIn()): ?>
                        <a href="index.php?page=account" class="account-link">
                            <i class="fas fa-user"></i>
                            Account
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=login">Login</a>
                    <?php endif; ?>
                    <a href="index.php?page=cart" class="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0 ?></span>
                    </a>
                </div>
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