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

<!-- Featured Products Section -->
<section class="py-16 bg-gray-50" id="featured-products">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12" data-aos="fade-up">Featured Collections</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" data-aos="fade-up">
                    <img src="<?= htmlspecialchars($product['image']) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         class="w-full h-48 object-cover"
                         loading="lazy">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="text-gray-600 mb-4">$<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                        <a href="index.php?page=product&id=<?= $product['id'] ?>" 
                           class="inline-block bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Scent Quiz Teaser -->
<section class="py-16 bg-white" id="quiz-teaser">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-3xl mx-auto" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Not Sure Where to Start?</h2>
            <p class="text-xl text-gray-600 mb-8">Let us guide you to your perfect scent. Take our quiz and discover products tailored to your needs.</p>
            <a href="index.php?page=quiz" class="btn-primary inline-block">Find Your Perfect Scent</a>
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