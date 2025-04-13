<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'The Scent - Premium Aromatherapy Products' ?></title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="main-nav">
            <div class="container">
                <a href="index.php" class="logo">The Scent</a>
                <div class="nav-links">
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