<?php require_once __DIR__ . '/layout/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section relative h-screen overflow-hidden">
    <!-- Particles Background -->
    <div id="particles-js" class="absolute inset-0 z-0"></div>
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/50 to-primary-dark/50 z-10"></div>
    
    <!-- Hero Content -->
    <div class="container relative z-20 h-full flex items-center justify-center text-center text-white">
        <div data-aos="fade-up">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Find Your Moment of Calm</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">Experience premium, natural aromatherapy crafted to enhance well-being and restore balance.</p>
            <div class="flex gap-4 justify-center">
                <a href="index.php?page=quiz" class="btn-primary">Take the Scent Quiz</a>
                <a href="index.php?page=products" class="btn-secondary">Shop Collection</a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section py-20 bg-white" data-aos="fade-up">
    <div class="container">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="about-content">
                <h2 class="text-3xl font-bold mb-6">Our Story</h2>
                <p class="mb-6">The Scent produces a whole range of aroma therapeutic products where high quality raw materials from all over the world are imported and our finished products are exported back to these countries.</p>
                <p class="mb-6">This is possible due to our unique and creative product formulations and our knowledge for the various applications, to create harmonious, balanced and well rounded aromatherapy products.</p>
                <a href="index.php?page=about" class="btn-secondary">Learn More</a>
            </div>
            <div class="about-image" data-aos="fade-left">
                <img src="/images/about.jpg" alt="About The Scent" class="rounded-lg shadow-xl">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products py-20 bg-gray-50">
    <div class="container">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Featured Collection</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card bg-white rounded-lg shadow-lg" data-aos="fade-up">
                    <div class="product-image relative overflow-hidden">
                        <img src="<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-64 object-cover">
                        <?php if ($product['highlight_text']): ?>
                            <span class="product-badge absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full">
                                <?= htmlspecialchars($product['highlight_text']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info p-6">
                        <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="text-gray-600 mb-4">$<?= number_format($product['price'], 2) ?></p>
                        <div class="flex gap-2">
                            <a href="index.php?page=products&id=<?= $product['id'] ?>" 
                               class="btn-secondary flex-1">View Details</a>
                            <button class="btn-primary flex-1 add-to-cart" 
                                    data-product-id="<?= $product['id'] ?>">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quiz Promo Section -->
<section class="quiz-promo py-20 bg-gradient-to-r from-primary to-primary-dark text-white">
    <div class="container">
        <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-6">Discover Your Perfect Scent</h2>
            <p class="text-xl mb-8">Take our interactive quiz to find your ideal aromatherapy match based on your preferences and lifestyle.</p>
            <a href="index.php?page=quiz" class="btn-white">Start Quiz Now</a>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section py-20 bg-white">
    <div class="container">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Why Choose The Scent</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="benefit-card text-center" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-leaf text-4xl text-primary mb-4"></i>
                <h3 class="text-xl font-semibold mb-4">Natural Ingredients</h3>
                <p>Premium quality raw materials sourced from around the world.</p>
            </div>
            <div class="benefit-card text-center" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-heart text-4xl text-primary mb-4"></i>
                <h3 class="text-xl font-semibold mb-4">Wellness Focus</h3>
                <p>Products designed to enhance both mental and physical well-being.</p>
            </div>
            <div class="benefit-card text-center" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-certificate text-4xl text-primary mb-4"></i>
                <h3 class="text-xl font-semibold mb-4">Expert Crafted</h3>
                <p>Unique formulations created by aromatherapy experts.</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-20 bg-gray-50">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-6">Stay Connected</h2>
            <p class="mb-8">Subscribe to receive updates, exclusive offers, and aromatherapy tips.</p>
            <form id="newsletter-form" class="flex gap-4 justify-center">
                <input type="email" name="email" placeholder="Enter your email" required
                       class="flex-1 max-w-md px-4 py-2 rounded-lg border border-gray-300">
                <button type="submit" class="btn-primary">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<!-- Initialize AOS -->
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
    
    // Initialize Particles.js
    particlesJS.load('particles-js', '/particles.json');
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>