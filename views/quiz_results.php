<?php require_once __DIR__ . '/layout/header.php'; ?>

<section class="quiz-results-section">
    <div class="container">
        <div class="results-container" data-aos="fade-up">
            <h1>Your Personalized Scent Profile</h1>
            
            <?php if (isset($preferences) && !empty($preferences)): ?>
                <div class="preference-history">
                    <h3>Your Scent Journey</h3>
                    <div class="preference-stats">
                        <div class="top-preferences">
                            <h4>Your Top Scent Types</h4>
                            <ul>
                                <?php foreach (array_slice($preferences, 0, 3) as $pref): ?>
                                    <li>
                                        <span class="pref-type"><?= htmlspecialchars(ucfirst($pref['scent_type'])) ?></span>
                                        <span class="pref-count"><?= $pref['frequency'] ?> times chosen</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="mood-preferences">
                            <h4>Your Preferred Effects</h4>
                            <ul>
                                <?php 
                                $moodPrefs = array_filter($preferences, function($p) {
                                    return isset($p['mood_effect']);
                                });
                                foreach (array_slice($moodPrefs, 0, 3) as $pref): 
                                ?>
                                    <li>
                                        <span class="pref-type"><?= htmlspecialchars(ucfirst($pref['mood_effect'])) ?></span>
                                        <span class="pref-count"><?= $pref['frequency'] ?> times chosen</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="recommendations">
                <h2>Recommended Products for You</h2>
                <div class="products-grid">
                    <?php foreach ($recommendations as $product): ?>
                        <div class="product-card" data-aos="fade-up">
                            <div class="product-image">
                                <img src="<?= htmlspecialchars($product['image']) ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="product-info">
                                <h3><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="price">$<?= number_format($product['price'], 2) ?></p>
                                <p class="description"><?= htmlspecialchars($product['description']) ?></p>
                                <button class="add-to-cart" data-product-id="<?= $product['id'] ?>">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!isset($_SESSION['user'])): ?>
                <div class="auth-prompt" data-aos="fade-up">
                    <h3>Save Your Preferences</h3>
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

<style>
.preference-history {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.preference-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 1rem;
}

.top-preferences, .mood-preferences {
    background: white;
    padding: 1.5rem;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pref-type {
    font-weight: 600;
    color: #2c3e50;
}

.pref-count {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-left: 0.5rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}
</style>

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
                    'X-Requested-With': 'XMLHttpRequest'
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
                    button.textContent = 'Added to Cart';
                    button.classList.add('added');
                    setTimeout(() => {
                        button.textContent = 'Add to Cart';
                        button.classList.remove('added');
                    }, 2000);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>