</main>
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php?page=products">Shop</a></li>
                        <li><a href="index.php?page=quiz">Find Your Scent</a></li>
                        <li><a href="index.php?page=about">About Us</a></li>
                        <li><a href="index.php?page=contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="index.php?page=shipping">Shipping Info</a></li>
                        <li><a href="index.php?page=returns">Returns</a></li>
                        <li><a href="index.php?page=faq">FAQ</a></li>
                        <li><a href="index.php?page=privacy">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Newsletter</h3>
                    <p>Subscribe for updates and exclusive offers</p>
                    <form id="newsletter-form" class="newsletter-form">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <button type="submit" class="btn-primary">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> The Scent. All rights reserved.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            offset: 100,
            once: true
        });

        // Newsletter Form
        document.getElementById('newsletter-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[name="email"]').value;
            
            fetch('index.php?page=newsletter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&source=footer`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<p class="success">Thank you for subscribing!</p>';
                } else {
                    alert(data.message || 'Subscription failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Subscription failed. Please try again.');
            });
        });

        // Add to Cart functionality
        document.querySelectorAll('.add-to-cart')?.forEach(button => {
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
                        alert('Product added to cart!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to add product to cart. Please try again.');
                });
            });
        });
    </script>
</body>
</html>