<?php require_once 'layout/header.php'; ?>

<section class="checkout-section">
    <div class="container">
        <div class="checkout-container" data-aos="fade-up">
            <h1>Checkout</h1>
            
            <div class="checkout-grid">
                <!-- Shipping Form -->
                <div class="shipping-details">
                    <h2>Shipping Details</h2>
                    <form id="checkoutForm" action="index.php?page=checkout" method="POST">
                        <div class="form-group">
                            <label for="shipping_name">Full Name *</label>
                            <input type="text" id="shipping_name" name="shipping_name" required
                                   value="<?= htmlspecialchars(getCurrentUser()['name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_email">Email Address *</label>
                            <input type="email" id="shipping_email" name="shipping_email" required
                                   value="<?= htmlspecialchars(getCurrentUser()['email'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Street Address *</label>
                            <input type="text" id="shipping_address" name="shipping_address" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="shipping_city">City *</label>
                                <input type="text" id="shipping_city" name="shipping_city" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="shipping_state">State/Province *</label>
                                <input type="text" id="shipping_state" name="shipping_state" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="shipping_zip">ZIP/Postal Code *</label>
                                <input type="text" id="shipping_zip" name="shipping_zip" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="shipping_country">Country *</label>
                                <select id="shipping_country" name="shipping_country" required>
                                    <option value="">Select Country</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="GB">United Kingdom</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="order_notes">Order Notes (Optional)</label>
                            <textarea id="order_notes" name="order_notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="summary-item">
                                <div class="item-info">
                                    <span class="item-quantity"><?= $item['quantity'] ?>×</span>
                                    <span class="item-name"><?= htmlspecialchars($item['product']['name']) ?></span>
                                </div>
                                <span class="item-price">$<?= number_format($item['subtotal'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>FREE</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    
                    <div class="payment-section">
                        <h3>Payment Method</h3>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="credit_card" checked>
                                <span class="option-content">
                                    <i class="fas fa-credit-card"></i>
                                    Credit Card
                                </span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="paypal">
                                <span class="option-content">
                                    <i class="fab fa-paypal"></i>
                                    PayPal
                                </span>
                            </label>
                        </div>
                        
                        <div id="creditCardForm" class="payment-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="card_number">Card Number</label>
                                    <input type="text" id="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="card_expiry">Expiry Date</label>
                                    <input type="text" id="card_expiry" placeholder="MM/YY">
                                </div>
                                <div class="form-group">
                                    <label for="card_cvv">CVV</label>
                                    <input type="text" id="card_cvv" placeholder="123">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" form="checkoutForm" class="btn-primary place-order">
                        Place Order
                    </button>
                    
                    <div class="secure-checkout">
                        <i class="fas fa-lock"></i>
                        Secure Checkout
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    const creditCardForm = document.getElementById('creditCardForm');
    
    // Handle payment method toggle
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                creditCardForm.style.display = 'block';
            } else {
                creditCardForm.style.display = 'none';
            }
        });
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const required = [
            'shipping_name',
            'shipping_email',
            'shipping_address',
            'shipping_city',
            'shipping_state',
            'shipping_zip',
            'shipping_country'
        ];
        
        let valid = true;
        required.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                valid = false;
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>