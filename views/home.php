<?php require_once __DIR__ . '/layout/header.php'; ?>

<section class="hero">
    <div class="container">
        <h1>Discover Your Perfect Scent</h1>
        <p class="hero-subtitle">Premium aromatherapy products for wellness and relaxation</p>
        <div class="hero-cta">
            <a href="index.php?page=quiz" class="btn-primary">Take the Scent Quiz</a>
            <a href="index.php?page=products" class="btn-secondary">Browse Products</a>
        </div>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card" data-aos="fade-up">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php if ($product['highlight_text']): ?>
                            <span class="product-badge"><?= htmlspecialchars($product['highlight_text']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                        <div class="product-actions">
                            <a href="index.php?page=products&id=<?= $product['id'] ?>" class="btn-secondary">View Details</a>
                            <button class="btn-primary add-to-cart" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="quiz-promo">
    <div class="container">
        <div class="quiz-promo-content">
            <h2>Not Sure Where to Start?</h2>
            <p>Take our quick quiz to discover your perfect scent match based on your preferences and lifestyle.</p>
            <a href="index.php?page=quiz" class="btn-primary">Start Quiz</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>