    </main>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content" data-aos="fade-up">
                <h2>Join Our Community</h2>
                <p>Subscribe to receive aromatherapy tips and exclusive offers.</p>
                <form action="index.php?page=newsletter" method="POST" class="newsletter-form">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn-primary">Subscribe</button>
                </form>
                <p class="newsletter-consent">
                    By subscribing, you agree to our <a href="index.php?page=privacy">Privacy Policy</a> and consent to receive marketing emails.
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About The Scent</h3>
                    <p>Premium aromatherapy products crafted to enhance well-being and restore balance. Natural ingredients, sustainably sourced.</p>
                    <div class="social-links">
                        <a href="https://facebook.com/thescent" target="_blank" aria-label="Follow us on Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://instagram.com/thescent" target="_blank" aria-label="Follow us on Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://pinterest.com/thescent" target="_blank" aria-label="Follow us on Pinterest">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php?page=products">Shop All</a></li>
                        <li><a href="index.php?page=quiz">Scent Quiz</a></li>
                        <li><a href="index.php?page=about">Our Story</a></li>
                        <li><a href="index.php?page=sustainability">Sustainability</a></li>
                        <li><a href="index.php?page=faq">FAQ</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="index.php?page=contact">Contact Us</a></li>
                        <li><a href="index.php?page=shipping">Shipping Information</a></li>
                        <li><a href="index.php?page=returns">Returns & Exchanges</a></li>
                        <li><a href="index.php?page=track-order">Track Your Order</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-envelope"></i> info@thescent.com</p>
                    <p><i class="fas fa-phone"></i> (555) 123-4567</p>
                    <p><i class="fas fa-clock"></i> Mon-Fri: 9am-5pm EST</p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> The Scent. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="index.php?page=privacy">Privacy Policy</a>
                    <a href="index.php?page=terms">Terms of Service</a>
                </div>
                <div class="payment-methods">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-paypal"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Initialize Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50
        });

        // Initialize Particles.js
        particlesJS.load('particles-js', 'particles.json', function() {
            console.log('Particles.js loaded');
        });

        // Mobile Menu Toggle
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            this.classList.toggle('active');
            document.querySelector('.mobile-nav').classList.toggle('active');
            document.querySelector('.main-header').classList.toggle('mobile-menu-active');
        });

        // Sticky Header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 100) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }
        });

        // Dropdown Menu
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>