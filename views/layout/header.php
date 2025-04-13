<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'The Scent - Premium Aromatherapy Products' ?></title>
    <meta name="description" content="Discover premium natural aromatherapy products at The Scent. Find your moment of calm with our essential oils and natural soaps.">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
</head>
<body>
    <!-- Particles Background -->
    <div id="particles-js" class="particles-container"></div>

    <!-- Header -->
    <header class="main-header">
        <nav class="nav-container">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="The Scent Logo">
                    <span class="logo-text">The Scent</span>
                </a>
            </div>
            
            <ul class="nav-links">
                <li><a href="index.php?page=products">Shop</a></li>
                <li><a href="index.php?page=quiz">Scent Quiz</a></li>
                <li><a href="index.php?page=about">About</a></li>
                <li><a href="index.php?page=contact">Contact</a></li>
                <?php if (isLoggedIn()): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            <i class="fas fa-user"></i>
                            <?= htmlspecialchars(getCurrentUser()['name']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?page=account">My Account</a></li>
                            <li><a href="index.php?page=orders">Orders</a></li>
                            <li><a href="index.php?page=logout">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Login</a></li>
                <?php endif; ?>
                <li class="cart-icon">
                    <a href="index.php?page=cart">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
            
            <button class="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>

    <!-- Mobile Navigation -->
    <div class="mobile-nav">
        <ul>
            <li><a href="index.php?page=products">Shop</a></li>
            <li><a href="index.php?page=quiz">Scent Quiz</a></li>
            <li><a href="index.php?page=about">About</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="index.php?page=account">My Account</a></li>
                <li><a href="index.php?page=orders">Orders</a></li>
                <li><a href="index.php?page=logout">Logout</a></li>
            <?php else: ?>
                <li><a href="index.php?page=login">Login</a></li>
                <li><a href="index.php?page=register">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <main>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message <?= $_SESSION['flash_type'] ?? 'info' ?>">
                <?= $_SESSION['flash_message'] ?>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>