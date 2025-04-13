<?php require_once __DIR__ . '/layout/header.php'; ?>

<section class="error-page">
    <div class="container">
        <div class="error-content" data-aos="fade-up">
            <h1>Oops! Something went wrong</h1>
            <p>We apologize for the inconvenience. Please try again later.</p>
            <div class="error-actions">
                <a href="index.php" class="btn-primary">Return Home</a>
                <a href="javascript:history.back()" class="btn-secondary">Go Back</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>