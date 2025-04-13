<?php require_once 'layout/header.php'; ?>

<section class="quiz-results">
    <div class="container">
        <div class="results-container" data-aos="fade-up">
            <h1>Your Perfect Scent Matches</h1>
            <p class="results-intro">Based on your preferences, we've curated these personalized recommendations just for you.</p>
            
            <div class="recommendations-grid">
                <?php foreach ($recommendations as $index => $product): ?>
                    <div class="product-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                            <div class="product-actions">
                                <a href="index.php?page=products&id=<?= $product['id'] ?>" class="btn-secondary">View Details</a>
                                <button class="btn-primary add-to-cart" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="results-cta" data-aos="fade-up">
                <h2>Why These Products?</h2>
                <p>These recommendations are tailored to your unique preferences and lifestyle. Each product has been carefully selected to help you achieve your aromatherapy goals.</p>
                
                <div class="next-steps">
                    <a href="index.php?page=products" class="btn-secondary">Browse All Products</a>
                    <a href="#" class="btn-primary open-chat">Get Expert Advice</a>
                </div>
            </div>
            
            <?php if (!isLoggedIn()): ?>
                <div class="save-results-prompt" data-aos="fade-up">
                    <h3>Want to save your results?</h3>
                    <p>Create an account to save your quiz results and get personalized recommendations in the future.</p>
                    <div class="auth-buttons">
                        <a href="index.php?page=register" class="btn-primary">Sign Up</a>
                        <a href="index.php?page=login" class="btn-secondary">Log In</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('index.php?page=cart&action=add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cartCount;
                    }
                    
                    // Show success message
                    alert('Product added to cart!');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    // Handle chat button
    const chatButton = document.querySelector('.open-chat');
    if (chatButton) {
        chatButton.addEventListener('click', function(e) {
            e.preventDefault();
            // Initialize chat widget
            if (window.initChat) {
                window.initChat();
            }
        });
    }
});
</script>

<?php require_once 'layout/footer.php'; ?>