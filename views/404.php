<?php require_once __DIR__ . '/layout/header.php'; ?>

<section class="error-page">
    <div class="container">
        <div class="error-content" data-aos="fade-up">
            <h1>404 - Page Not Found</h1>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            <div class="error-actions">
                <a href="index.php" class="btn-primary">Return Home</a>
                <a href="index.php?page=products" class="btn-secondary">Browse Products</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>