<?php 
require_once __DIR__ . '/layout/header.php';
$delay = 0; // Initialize delay counter for animations
?>

<!-- Hero Section -->
<section class="hero-section">
    <!-- Particles Background -->
    <div id="particles-js" class="absolute inset-0 z-0"></div>
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary/50 to-primary-dark/50 z-10"></div>
    
    <!-- Hero Content -->
    <div class="container relative z-20 h-full flex items-center justify-center text-center text-white">
        <div data-aos="fade-up" data-aos-duration="1000">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Discover Natural Wellness</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">Experience premium, natural aromatherapy crafted to enhance well-being and restore balance.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php?page=quiz" class="btn-primary">Find Your Perfect Scent</a>
                <a href="index.php?page=products" class="btn-secondary">Shop Collection</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-section" data-aos="fade-up">
    <div class="container">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Featured Collections</h2>
        <div class="featured-grid">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card" data-aos="fade-up" data-aos-delay="<?= $delay += 100 ?>">
                        <?php if (!empty($product['display_badge'])): ?>
                            <span class="badge badge-<?= strtolower(str_replace(' ', '-', $product['display_badge'])) ?>">
                                <?= htmlspecialchars($product['display_badge']) ?>
                            </span>
                        <?php endif; ?>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-64 object-cover"
                             loading="lazy">
                        <div class="product-card-content">
                            <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-category"><?= htmlspecialchars($product['category_name']) ?></p>
                            <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                            <div class="product-actions">
                                <a href="index.php?page=product&id=<?= $product['id'] ?>" class="btn-primary">View Details</a>
                                <?php if ($product['stock_quantity'] > 0): ?>
                                    <button class="btn-secondary add-to-cart" 
                                            data-product-id="<?= $product['id'] ?>"
                                            <?= $product['stock_quantity'] <= $product['low_stock_threshold'] ? 'data-low-stock="true"' : '' ?>>
                                        Add to Cart
                                    </button>
                                <?php else: ?>
                                    <button class="btn-disabled" disabled>Out of Stock</button>
                                <?php endif; ?>
                            </div>
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
    </div>
</section>

<!-- Our Story Section -->
<section class="py-20 bg-white">
    <div class="container">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="about-content" data-aos="fade-right">
                <h2 class="text-3xl font-bold mb-6">Our Story</h2>
                <p class="mb-6">At The Scent, we produce a range of therapeutic aromatherapy products using high-quality raw materials sourced from around the world.</p>
                <p class="mb-6">Our unique and creative formulations are crafted with expertise to create harmonious, balanced, and well-rounded aromatherapy products that enhance both mental and physical well-being.</p>
                <a href="index.php?page=about" class="btn-secondary">Learn More</a>
            </div>
            <div class="about-image" data-aos="fade-left">
                <img src="<?= file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/about/about.jpg') ? '/public/images/about/about.jpg' : 'https://placehold.co/800x600/e9ecef/495057?text=About+The+Scent' ?>" 
                     alt="About The Scent" 
                     class="rounded-lg shadow-xl w-full">
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-20 bg-white">
    <div class="container">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Why Choose The Scent</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="benefit-card" data-aos="fade-up" data-aos-delay="0">
                <i class="fas fa-leaf text-4xl text-primary mb-4"></i>
                <h3 class="text-xl font-semibold mb-4">Natural Ingredients</h3>
                <p>Premium quality raw materials sourced from around the world.</p>
            </div>
            <div class="benefit-card" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-heart text-4xl text-primary mb-4"></i>
                <h3 class="text-xl font-semibold mb-4">Wellness Focus</h3>
                <p>Products designed to enhance both mental and physical well-being.</p>
            </div>
            <div class="benefit-card" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-certificate text-4xl text-primary mb-4"></i>
                <h3 class="text-xl font-semibold mb-4">Expert Crafted</h3>
                <p>Unique formulations created by aromatherapy experts.</p>
            </div>
        </div>
    </div>
</section>

<!-- Quiz Section -->
<section class="quiz-section">
    <div class="container">
        <div class="quiz-content" data-aos="fade-up">
            <h2>Find Your Perfect Scent</h2>
            <p class="mb-8">Take our personalized quiz to discover aromatherapy products tailored to your needs.</p>
            <a href="index.php?page=quiz" class="btn-white">Take the Quiz</a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-20 bg-light">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-6">Stay Connected</h2>
            <p class="mb-8">Subscribe to receive updates, exclusive offers, and aromatherapy tips.</p>
            <form id="newsletter-form" class="newsletter-form">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" class="btn-primary">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        offset: 100,
        once: true
    });

    // Initialize Particles.js
    particlesJS.load('particles-js', '/public/particles.json', function() {
        console.log('Particles.js loaded');
    });

    // Handle add to cart buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const isLowStock = this.dataset.lowStock === 'true';
            
            try {
                const response = await fetch('index.php?page=cart&action=add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?? '' ?>'
                    },
                    body: `product_id=${productId}&quantity=1`
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                    
                    // Show success message
                    showFlashMessage('Product added to cart', 'success');
                    
                    // Disable button if product is now out of stock
                    if (data.stock_status === 'out_of_stock') {
                        this.disabled = true;
                        this.classList.remove('btn-secondary');
                        this.classList.add('btn-disabled');
                        this.textContent = 'Out of Stock';
                    }
                    
                    // Show low stock warning
                    if (data.stock_status === 'low_stock' && !isLowStock) {
                        showFlashMessage('Limited quantity available', 'info');
                        this.dataset.lowStock = 'true';
                    }
                } else {
                    showFlashMessage(data.message || 'Error adding to cart', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showFlashMessage('Error adding to cart', 'error');
            }
        });
    });

    // Newsletter form submission
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('index.php?page=newsletter&action=subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                });

                const data = await response.json();
                
                if (data.success) {
                    this.innerHTML = '<p class="text-success">Thank you for subscribing!</p>';
                } else {
                    showFlashMessage(data.message || 'Subscription failed', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showFlashMessage('Subscription failed', 'error');
            }
        });
    }

    // Flash message helper
    function showFlashMessage(message, type = 'info') {
        const flashDiv = document.createElement('div');
        flashDiv.className = `flash-message ${type}`;
        flashDiv.textContent = message;
        document.body.appendChild(flashDiv);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            flashDiv.style.opacity = '0';
            setTimeout(() => flashDiv.remove(), 300);
        }, 3000);
    }
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>