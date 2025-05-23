Okay, I understand the requirements. The main goal is to fix the critical error preventing the landing page from loading correctly and then carefully merge the UI improvements suggested in `issues_to_fix.md` into the existing PHP files (`header.php`, `home.php`, `footer.php`) and `style.css`, ensuring no current functionality (like Add to Cart buttons in `home.php`) is lost.

Here's the plan:

1.  **Diagnose and Fix Critical Error:**
    *   Analyze the error logs: The "headers already sent" errors originating from `header.php:9` are the immediate cause of the visible "Oops" page and subsequent header warnings. This usually means there's output (whitespace, text, BOM) *before* the `<?php` tag or `<!DOCTYPE html>` declaration in `header.php` or a file included *by* it, or possibly in `index.php` before `header.php` is included.
    *   The `mkdir(): Permission denied` and `file_put_contents()` errors in `ErrorHandler.php` indicate the web server process doesn't have permission to create or write to the `logs` directory. This might be the *underlying* error triggering the handler, but the "headers already sent" prevents the handler from working correctly (setting status code, potentially rendering a proper error page).
    *   **Action Plan:**
        *   **Permissions:** Ensure the `logs` directory exists (`/cdrom/project/The-Scent-gpt6/logs/`) and has correct write permissions for the web server user (e.g., `www-data`, `apache`). Commands like `sudo mkdir -p /cdrom/project/The-Scent-gpt6/logs` and `sudo chown www-data:www-data /cdrom/project/The-Scent-gpt6/logs && sudo chmod 755 /cdrom/project/The-Scent-gpt6/logs` (adjust user/group as needed) are typically required.
        *   **Headers Sent:** Carefully inspect `/cdrom/project/The-Scent-gpt6/views/layout/header.php`. Remove *any* characters (spaces, newlines, BOM) before the initial `<?php` tag (line 1) and before the `<!DOCTYPE html>` declaration. Also, check the very end of the file for extraneous whitespace after the closing `?>` tag if one exists near the end. Check `index.php` as well for any output before the `require/include` statement for the header.
    *   **Verification:** After applying these fixes, attempt to reload the landing page. The "Oops" error should be gone, and the page should render (even if the UI isn't perfect yet). Check the error logs again to confirm the permission and header warnings are resolved.

2.  **Merge UI Changes (Step-by-Step):**
    *   **CSS:** Append the entire CSS block provided in `issues_to_fix.md` under the `public/css/style.css` section to the *end* of the actual `public/css/style.css` file. This ensures the new styles are loaded and can override previous ones safely.
    *   **Header (`views/layout/header.php`):**
        *   Compare the existing `header.php.txt` with the suggested `views/layout/header.php`.
        *   Add the class `sample-header` to the main `<nav class="main-nav">` tag.
        *   Verify the order and presence of nav links (`Home`, `Shop`, `Scent Finder`, `About`, `Contact`). Adjust if necessary.
        *   Verify the presence and order of header icons (`<div class="header-icons">`): Search (`fa-search`), Account/Login (`fa-user`), Cart (`fa-shopping-bag`). The provided `header.php.txt` seems to already have this structure, so likely only the `sample-header` class and corresponding CSS are needed.
    *   **Footer (`views/layout/footer.php`):**
        *   Compare the existing `footer.php.txt` with the suggested `views/layout/footer.php`.
        *   The structure (4 columns: About, Shop, Help, Contact) seems to already exist in `footer.php.txt`.
        *   Ensure the newsletter form within the footer (`footer-contact` section) uses the classes `newsletter-input` and `newsletter-btn` and includes the `newsletter-consent` paragraph as shown in the suggestion. *Update the existing form markup*.
        *   Locate the `footer-bottom` div in the suggestion (containing copyright and payment methods). Add this *entire div* just before the closing `</footer>` tag in the actual `views/layout/footer.php` file, replacing or supplementing the existing copyright line if necessary.
        *   Verify all required social icons are present in the `social-icons` div.
    *   **Home (`views/home.php` - Careful Merge):**
        *   Keep the overall structure of the current `home.php.txt` (Hero, About, Featured Products, Benefits, Quiz, Newsletter, Testimonials, Script block).
        *   **Featured Products Section:**
            *   Inside the `foreach ($featuredProducts as $product)` loop:
                *   Replace the existing product card `div` with the structure from the *suggested* `views/home.php`: `<div class="product-card sample-card" ...>`.
                *   Inside the `<div class="product-info" ...>`:
                    *   Keep the `<h3>` for the product name.
                    *   Add the conditional PHP block for the short description/category name from the *suggested* code.
                    *   **Crucially:** Re-insert the *existing* `<div class="product-actions flex gap-2 justify-center mt-4">...</div>` block from `home.php.txt` (containing the "View Details" link and the conditional Add-to-Cart/Out-of-Stock button logic) below the description/category paragraph. This preserves the e-commerce functionality. *Do not* use the simpler `<a>View Product</a>` link from the suggestion here.
            *   After the `endforeach;` and the `else:` block for empty products, add the "Shop All Products" CTA div: `<div class="view-all-cta" ...><a href="..." class="btn btn-primary">Shop All Products</a></div>` as shown in the suggestion.
        *   **Newsletter Section:**
            *   Locate the `<section class="newsletter-section ...">`.
            *   Replace the `<form>` element and its contents with the `<form>` structure from the *suggested* `views/home.php`, including the rounded input/button classes and the `newsletter-consent` paragraph. Ensure the `csrf_token` input remains.
        *   **Keep Existing Script:** Retain the entire `<script>` block at the end of the current `home.php.txt` as it contains essential JS for Add-to-Cart, Newsletter submission, flash messages, AOS, and Particles.

3.  **Final Testing:**
    *   Clear browser cache thoroughly.
    *   Load the landing page.
    *   Verify the critical error is gone.
    *   Check header styling and sticky behavior.
    *   Check featured product card appearance (borders, shadow, image size, spacing).
    *   Verify "View Details" and "Add to Cart" buttons are present and functional on product cards.
    *   Check the "Shop All Products" CTA below the grid.
    *   Check newsletter form styling (main page and footer).
    *   Check footer layout (4 columns) and content, including payment icons and social icons.
    *   Test responsiveness on different screen sizes.

---

**Applying the Merge to `views/home.php`:**

Based on the plan, here is the merged content for `views/home.php`. This integrates the suggested UI elements for Featured Products and Newsletter while preserving the existing sections and Add-to-Cart functionality.

```php
<?php 
// home.php (v3 - Merged UI Fixes + Preserved Functionality)
require_once __DIR__ . '/layout/header.php'; // Uses header-fixed.php content implicitly
echo '<!-- DEBUG: home.php loaded -->';
$delay = 0; // Initialize delay counter for animations
?>

<!-- Hero Section with Video Background -->
<section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden">
    <div id="particles-js" class="absolute inset-0 z-0"></div>
    <video class="absolute inset-0 w-full h-full object-cover z-[-1]" autoplay muted loop playsinline poster="/public/images/scent5.jpg">
        <source src="/public/videos/hero.mp4" type="video/mp4">
        <img src="/public/images/scent5.jpg" alt="Calming Nature" class="w-full h-full object-cover" />
    </video>
    <div class="absolute inset-0 bg-gradient-to-br from-primary/70 to-primary-dark/80 z-10"></div>
    <div class="container relative z-20 flex flex-col items-center justify-center text-center text-white px-6">
        <div data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 font-heading" style="text-shadow: 0 2px 4px rgba(0,0,0,0.7);">Find Your Moment of Calm</h1>
            <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto font-body">Experience premium, natural aromatherapy crafted to enhance well-being and restore balance.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#featured-products" class="btn btn-primary">Explore Our Collections</a>
            </div>
        </div>
    </div>
</section>

<!-- About/Mission Section (Keep existing) -->
<section class="about-section py-20 bg-white" id="about">
    <div class="container">
        <div class="about-container grid md:grid-cols-2 gap-12 items-center">
            <div class="about-image" data-aos="fade-left">
                <img src="<?= file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/about/about.jpg') ? '/public/images/about/about.jpg' : 'https://placehold.co/800x600/e9ecef/495057?text=About+The+Scent' ?>"
                     alt="About The Scent" 
                     class="rounded-lg shadow-xl w-full">
            </div>
            <div class="about-content" data-aos="fade-right">
                <h2 class="text-3xl font-bold mb-6">Rooted in Nature, Crafted with Care</h2>
                <p class="mb-6">At The Scent, we harness the power of nature to nurture your mental and physical well-being. Our high-quality, sustainably sourced ingredients are transformed into exquisite aromatherapy products by expert hands.</p>
                <p class="mb-6">Our unique and creative formulations are crafted with expertise to create harmonious, balanced, and well-rounded aromatherapy products that enhance both mental and physical health.</p>
                <a href="index.php?page=about" class="btn btn-secondary">Learn Our Story</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section (Merged UI) -->
<section class="featured-section py-16 bg-light" id="featured-products">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-12" data-aos="fade-up">Featured Collections</h2>
        <div class="featured-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 px-6">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <!-- Apply suggested card structure/style -->
                    <div class="product-card sample-card" data-aos="zoom-in" style="border-radius:8px; box-shadow:0 4px 15px rgba(0,0,0,0.05); overflow:hidden;">
                        <img src="<?= htmlspecialchars($product['image'] ?? '/public/images/placeholder.jpg') ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-64 object-cover" loading="lazy">
                        <div class="product-info" style="padding:1.5rem; text-align:center;">
                            <h3 style="margin-bottom:0.5rem; font-size:1.3rem;"><?= htmlspecialchars($product['name']) ?></h3>
                            
                            <!-- Apply suggested short description / category display logic -->
                            <?php if (!empty($product['short_description'])): ?>
                                <p style="font-size:0.9rem; color:#666; margin-bottom:1rem;"><?= htmlspecialchars($product['short_description']) ?></p>
                            <?php elseif (!empty($product['category_name'])): ?>
                                <p style="font-size:0.9rem; color:#666; margin-bottom:1rem;"><?= htmlspecialchars($product['category_name']) ?></p>
                            <?php endif; ?>

                            <!-- *** Re-integrate existing actions to preserve functionality *** -->
                            <div class="product-actions flex gap-2 justify-center mt-4">
                                <a href="index.php?page=product&id=<?= $product['id'] ?>" class="btn btn-primary">View Details</a> 
                                <?php if (isset($product['stock_quantity']) && $product['stock_quantity'] > 0): ?>
                                    <button class="btn btn-secondary add-to-cart" 
                                            data-product-id="<?= $product['id'] ?>"
                                            <?= isset($product['low_stock_threshold']) && $product['stock_quantity'] <= $product['low_stock_threshold'] ? 'data-low-stock="true"' : '' ?>>
                                        Add to Cart
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-disabled" disabled>Out of Stock</button>
                                <?php endif; ?>
                            </div>
                            <!-- *** End of re-integrated actions *** -->
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
        <!-- Apply suggested "Shop All" CTA below grid -->
        <div class="view-all-cta" style="text-align:center; margin-top:3rem;">
            <a href="index.php?page=products" class="btn btn-primary">Shop All Products</a>
        </div>
    </div>
</section>

<!-- Benefits Section (Keep existing) -->
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

<!-- Quiz/Finder Section (Keep existing) -->
<section class="quiz-section py-20 bg-light" id="finder">
    <div class="container">
        <h2 class="text-3xl font-bold text-center mb-8" data-aos="fade-up">Discover Your Perfect Scent</h2>
        <p class="text-center mb-12 text-lg" data-aos="fade-up" data-aos-delay="100">Tailor your aromatherapy experience to your mood and needs.</p>
        <div class="grid md:grid-cols-5 gap-6 mb-8 finder-grid">
            <div class="finder-card flex flex-col items-center p-6 bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="0">
                <i class="fas fa-leaf text-4xl text-primary mb-4"></i>
                <h3 class="font-semibold mb-2">Relaxation</h3>
                <p class="text-sm text-gray-600 text-center">Calming scents to help you unwind.</p>
            </div>
            <div class="finder-card flex flex-col items-center p-6 bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-bolt text-4xl text-primary mb-4"></i>
                <h3 class="font-semibold mb-2">Energy</h3>
                <p class="text-sm text-gray-600 text-center">Invigorating aromas to uplift your day.</p>
            </div>
            <div class="finder-card flex flex-col items-center p-6 bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-brain text-4xl text-primary mb-4"></i>
                <h3 class="font-semibold mb-2">Focus</h3>
                <p class="text-sm text-gray-600 text-center">Clarifying blends for a clear mind.</p>
            </div>
            <div class="finder-card flex flex-col items-center p-6 bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-moon text-4xl text-primary mb-4"></i>
                <h3 class="font-semibold mb-2">Sleep</h3>
                <p class="text-sm text-gray-600 text-center">Soothing scents for a peaceful night's rest.</p>
            </div>
            <div class="finder-card flex flex-col items-center p-6 bg-white rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="400">
                <i class="fas fa-balance-scale text-4xl text-primary mb-4"></i>
                <h3 class="font-semibold mb-2">Balance</h3>
                <p class="text-sm text-gray-600 text-center">Harmonious aromas to center you.</p>
            </div>
        </div>
        <div class="text-center" data-aos="fade-up" data-aos-delay="500">
            <a href="index.php?page=quiz" class="btn btn-secondary">Take the Full Scent Quiz</a>
        </div>
    </div>
</section>

<!-- Newsletter Section (Merged UI) -->
<section class="newsletter-section py-20 bg-light" id="newsletter">
    <div class="container">
        <div class="max-w-2xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-6">Stay Connected</h2>
            <p class="mb-8">Subscribe to receive updates, exclusive offers, and aromatherapy tips.</p>
            <!-- Apply suggested form structure/style -->
            <form id="newsletter-form" class="newsletter-form flex flex-col sm:flex-row gap-4 justify-center">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="email" name="email" placeholder="Enter your email" required class="newsletter-input flex-1 px-4 py-2 rounded-full border border-gray-300 focus:border-primary">
                <button type="submit" class="btn btn-primary newsletter-btn">Subscribe</button>
            </form>
            <p class="newsletter-consent" style="font-size:0.8rem;opacity:0.7; margin-top:1rem;">By subscribing, you agree to our <a href="index.php?page=privacy" style="color:#A0C1B1;text-decoration:underline;">Privacy Policy</a>.</p>
        </div>
    </div>
</section>

<!-- Testimonials Section (Keep existing) -->
<section class="py-20 bg-white" id="testimonials">
    <div class="container">
        <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">What Our Community Says</h2>
        <div class="testimonial-grid grid md:grid-cols-3 gap-8">
            <div class="testimonial-card bg-light p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="0">
                <p class="mb-4 italic">"The Lavender Essential Oil transformed my bedtime routine—its calming aroma truly helps me unwind."</p>
                <span class="block font-semibold mb-2">- Sarah L., Los Angeles</span>
                <div class="text-accent text-lg">★★★★★</div>
            </div>
            <div class="testimonial-card bg-light p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                <p class="mb-4 italic">"The Focus Blend oil improved my concentration at home without overwhelming my senses."</p>
                <span class="block font-semibold mb-2">- Michael T., Chicago</span>
                <div class="text-accent text-lg">★★★★★</div>
            </div>
            <div class="testimonial-card bg-light p-8 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                <p class="mb-4 italic">"Handcrafted soaps that feel divine and truly nourish sensitive skin. A luxurious experience."</p>
                <span class="block font-semibold mb-2">- Emma R., Seattle</span>
                <div class="text-accent text-lg">★★★★★</div>
            </div>
        </div>
    </div>
</section>

<!-- Keep Existing Script Block -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        offset: 100,
        once: true
    });

    // Initialize Particles.js if element exists
    if (document.getElementById('particles-js')) {
        particlesJS.load('particles-js', '/public/particles.json', function() {
            console.log('Particles.js loaded');
        });
    }

    // Handle add to cart buttons (Retained from original home.php JS logic)
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            const isLowStock = this.dataset.lowStock === 'true';
            
            const csrfTokenInput = document.querySelector('input[name="csrf_token"]');
            const csrfToken = csrfTokenInput ? csrfTokenInput.value : '';

            if (!csrfToken) {
                 console.error('CSRF token not found!');
                 showFlashMessage('Security token missing. Please refresh.', 'error');
                 return; 
            }

            try {
                const response = await fetch('index.php?page=cart&action=add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=1&csrf_token=${encodeURIComponent(csrfToken)}` 
                });

                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const data = await response.json();
                    
                    if (data.success) {
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cart_count; 
                        }
                        
                        showFlashMessage('Product added to cart', 'success');
                        
                        if (data.stock_status === 'out_of_stock') {
                            this.disabled = true;
                            this.classList.remove('btn-secondary');
                            this.classList.add('btn-disabled');
                            this.textContent = 'Out of Stock';
                        }
                        
                        if (data.stock_status === 'low_stock' && !isLowStock) {
                            showFlashMessage('Limited quantity available', 'info');
                            this.dataset.lowStock = 'true';
                        }
                    } else {
                        showFlashMessage(data.message || 'Error adding to cart', 'error');
                    }
                } else {
                    console.error('Received non-JSON response:', await response.text());
                    showFlashMessage('An unexpected error occurred.', 'error');
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
                    // Update the form's parent div content to show success, instead of just the form itself
                    this.parentElement.innerHTML = '<p class="text-green-600 font-semibold">Thank you for subscribing!</p>';
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
        // Try to find an existing flash message container or create one
        let flashContainer = document.querySelector('.flash-message-container');
        if (!flashContainer) {
            flashContainer = document.createElement('div');
            flashContainer.className = 'flash-message-container fixed top-5 right-5 z-[1100]'; // High z-index
            document.body.appendChild(flashContainer);
        }

        const flashDiv = document.createElement('div');
        // Map simple types to Tailwind bg colors (adjust as needed)
        const colorMap = {
            success: 'bg-green-100 border-green-400 text-green-700',
            error: 'bg-red-100 border-red-400 text-red-700',
            info: 'bg-blue-100 border-blue-400 text-blue-700',
            warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
        };
        flashDiv.className = `flash-message border px-4 py-3 rounded relative shadow-md mb-2 ${colorMap[type] || colorMap['info']}`;
        flashDiv.setAttribute('role', 'alert');
        
        const messageSpan = document.createElement('span');
        messageSpan.className = 'block sm:inline';
        messageSpan.textContent = message;
        flashDiv.appendChild(messageSpan);

        const closeButton = document.createElement('span');
        closeButton.className = 'absolute top-0 bottom-0 right-0 px-4 py-3';
        closeButton.innerHTML = '<svg class="fill-current h-6 w-6 text-current" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>';
        closeButton.onclick = () => flashDiv.remove();
        flashDiv.appendChild(closeButton);
        
        flashContainer.appendChild(flashDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
             if (flashDiv) {
                 flashDiv.style.opacity = '0';
                 flashDiv.style.transition = 'opacity 0.5s ease-out';
                 setTimeout(() => flashDiv.remove(), 500);
             }
        }, 5000);
    }

     // Sticky Header Logic (Add if not already present globally)
    const header = document.querySelector('.sample-header'); // Target the header nav
    if (header) {
        const stickyPoint = header.offsetTop + 100; // Adjust offset as needed

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > stickyPoint) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; // Uses footer-fixed.php content implicitly ?>
```

**Summary of Actions Taken:**

1.  **Critical Error:** Addressed by outlining steps to fix permissions and check for premature output in `header.php`.
2.  **CSS:** Instructed to append suggested CSS to `public/css/style.css`.
3.  **Header:** Instructed to add `sample-header` class and verify links/icons in `views/layout/header.php`.
4.  **Footer:** Instructed to update the newsletter form markup and add the `footer-bottom` div with payment icons in `views/layout/footer.php`.
5.  **Home:** Provided the **merged `views/home.php` code**. This code:
    *   Keeps all existing sections (Hero, About, Benefits, Quiz, Testimonials).
    *   Applies the `sample-card` styling to featured products.
    *   **Re-integrates** the original "View Details" and "Add to Cart" buttons into the styled cards.
    *   Adds the "Shop All Products" CTA below the featured grid.
    *   Applies the styled newsletter form markup.
    *   Keeps the essential existing JavaScript block for functionality.

Remember to implement the permission/header fixes first, then apply the CSS changes, update `header.php` and `footer.php`, and finally replace the content of `views/home.php` with the merged code provided above. Test thoroughly after each step.
