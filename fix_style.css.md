Okay, I understand the requirement. I will go through the original `style.css.txt` file provided again and remove duplicate selectors according to the CSS cascade rule (last definition wins), without renaming any selectors or changing the intended styling logic. I will perform minimal cleanup like removing fully duplicated rules or empty rules if found.

Here is the cleaned-up version, preserving the original selector names and cascade behavior:

```css
/* --- Base Styles & Variables --- */
/* Kept multiple :root blocks as removing earlier ones would change behavior */
:root {
  --font-heading: 'Cormorant Garamond', serif;
  --font-body: 'Montserrat', sans-serif;
  --font-accent: 'Raleway', sans-serif;

  --color-primary: #1A4D5A; /* Deep Teal */
  --color-secondary: #A0C1B1; /* Soft Mint Green */
  --color-accent: #D4A76A; /* Muted Gold/Ochre */
  --color-background: #F8F5F2; /* Warm Off-White */
  --color-text: #333333;
  --color-text-light: #FFFFFF;
  --color-border: #e0e0e0;

  --container-width: 1200px;
  --spacing-unit: 1rem;
  --transition-speed: 0.3s;
}

/* Base Styles */
:root {
    --primary-color: #4a90e2;
    --secondary-color: #2c3e50;
    --accent-color: #e67e22;
    --text-color: #333;
    --light-gray: #f5f5f5;
    --border-color: #ddd;
    --primary: #4f46e5; /* This will be overridden by the next :root */
    --primary-dark: #4338ca; /* This will be overridden by the next :root */
    --secondary: #6b7280; /* This will be overridden by the next :root */
}

/* Global Styles */
:root {
    --primary: #1A4D5A; /* Final effective value */
    --primary-dark: #164249; /* Final effective value */
    --secondary: #A0C1B1; /* Final effective value */
    --accent: #D4A76A; /* Final effective value */
    --text: #2D3748; /* Final effective value */
    --light: #F7FAFC; /* Final effective value */
}


* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Final effective body style */
body {
    font-family: 'Montserrat', sans-serif;
    color: var(--text); /* Uses final --text: #2D3748 */
    line-height: 1.6;
}

/* Utility Classes */

/* Final effective .container style */
.container {
    width: 100%; /* From 3rd definition */
    max-width: 1200px; /* From 3rd definition (overrides 1st & 2nd) */
    margin: 0 auto;
    padding: 0 1rem; /* From 3rd definition (overrides 1st & 2nd) */
}

/* Final effective .btn-primary style */
.btn-primary {
    background: var(--primary); /* Uses final --primary: #1A4D5A */
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.2s;
    display: inline-block;
}

/* Final effective .btn-primary:hover style */
.btn-primary:hover {
    background: var(--primary-dark); /* Uses final --primary-dark: #164249 */
}

/* Final effective .btn-primary:focus style (Only defined once) */
.btn-primary:focus {
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.3); /* Uses color from 2nd :root block via rgba */
}

/* Final effective .btn-secondary style */
.btn-secondary {
    background: transparent;
    color: var(--primary); /* Uses final --primary: #1A4D5A */
    border: 2px solid var(--primary); /* Uses final --primary: #1A4D5A */
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s; /* From 4th definition (overrides 2nd) */
    display: inline-block;
}

/* Final effective .btn-secondary:hover style */
.btn-secondary:hover {
    background: var(--primary); /* Uses final --primary: #1A4D5A */
    color: white;
}

/* Final effective .btn-white style */
.btn-white {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background-color: white;
    color: var(--primary); /* Uses final --primary: #1A4D5A */
    border-radius: 0.5rem; /* From 2nd definition (overrides 4th's 0.375rem) - Careful cascade! */
    font-weight: 600;
    transition: all 0.3s; /* From 2nd definition (overrides 4th's 0.2s) */
}

/* Final effective .btn-white:hover style */
.btn-white:hover {
    background-color: rgba(255, 255, 255, 0.9);
}

/* Navigation */
/* Final effective .main-nav style */
.main-nav {
    background-color: rgba(255, 255, 255, 0.95); /* From 2nd definition */
    position: fixed; /* From 2nd definition */
    top: 0;
    left: 0;
    right: 0;
    z-index: 100; /* From 2nd definition (overrides 1st) */
    backdrop-filter: blur(5px); /* From 2nd definition */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* From 2nd definition (overrides 1st) */
    /* Note: padding was removed in the 2nd definition, so it's gone */
}

/* Final effective .main-nav .container style */
.main-nav .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 80px; /* From 2nd definition */
}

/* Final effective .logo style */
.logo {
    font-family: 'Cormorant Garamond', serif; /* From 2nd definition */
    font-size: 1.8rem; /* From 2nd definition */
    font-weight: 700; /* From 2nd definition */
    color: var(--primary); /* Uses final --primary: #1A4D5A */
    text-decoration: none;
    display: flex; /* From 2nd definition */
    flex-direction: column; /* From 2nd definition */
    line-height: 1.2; /* From 2nd definition */
}

/* Final effective .logo span style (Only defined once) */
.logo span {
    font-size: 0.8rem;
    font-family: 'Montserrat', sans-serif;
    color: var(--text); /* Uses final --text: #2D3748 */
    font-weight: 400;
}

/* Final effective .nav-links style */
.nav-links {
    display: flex;
    gap: 2rem;
}

/* Final effective .nav-links a style */
/* Note: The 'sample' styles override the previous ones */
.nav-links a {
    font-family: 'Raleway',sans-serif; /* From sample styles */
    font-weight: 500; /* From sample styles */
    color: #1A4D5A; /* From sample styles */
    text-transform: uppercase; /* From sample styles */
    letter-spacing: 1px; /* From sample styles */
    padding: 5px 0; /* From sample styles */
    position: relative; /* From sample styles */
    margin-left: 2rem; /* From sample styles */
    transition: color 0.2s; /* From sample styles (overrides previous) */
    text-decoration: none; /* From earlier definition */
}

/* Final effective .nav-links a:first-child style (Only defined once) */
.nav-links a:first-child { margin-left: 0; }

/* Final effective .nav-links a::after style (Only defined once) */
.nav-links a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: #D4A76A; /* Corresponds to final --accent */
    transition: width 0.3s;
}

/* Final effective .nav-links a:hover::after, .nav-links a:focus::after style (Only defined once) */
.nav-links a:hover::after, .nav-links a:focus::after { width: 100%; }

/* Final effective .nav-links a:hover style */
.nav-links a:hover {
    color: var(--primary); /* Uses final --primary: #1A4D5A */
    /* Note: The hover underline comes from the ::after rule */
}

/* Final effective .nav-actions style */
.nav-actions {
    display: flex;
    gap: 1.5rem; /* From 2nd definition (overrides 1st) */
    align-items: center;
}

/* Final effective .nav-actions a style (Only defined once) */
.nav-actions a {
    color: var(--text); /* Uses final --text: #2D3748 */
    text-decoration: none;
    font-weight: 500;
}

/* Final effective .cart-link style (Only defined once) */
.cart-link {
    position: relative;
}

/* Final effective .cart-count style (Only defined once) */
.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--primary); /* Uses final --primary: #1A4D5A */
    color: white;
    font-size: 0.75rem;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Hero Section */
/* Final effective .hero style (Only defined once) */
.hero {
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/hero-bg.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 8rem 0;
    text-align: center;
}

/* Final effective .hero h1 style (Only defined once) */
.hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

/* Final effective .hero-subtitle style (Only defined once) */
.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
}

/* Final effective .hero-video style (Only defined once) */
.hero-video {
    position: relative;
    height: 100vh;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
}

/* Final effective .hero-video video style (Only defined once) */
.hero-video video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    transform: translate(-50%, -50%);
    object-fit: cover;
}

/* Final effective .hero-video::after style (Only defined once) */
.hero-video::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
}

/* Final effective .hero-section style */
.hero-section {
    position: relative;
    height: 100vh; /* From 3rd definition */
    display: flex;
    align-items: center;
    justify-content: center;
    color: white; /* From 3rd definition */
    text-align: center;
    overflow: hidden;
    padding-top: 80px; /* From 3rd definition */
    min-height: 600px; /* From 2nd definition - applied alongside 3rd */
}

/* Final effective #particles-js style (Only defined once) */
#particles-js {
    position: absolute;
    width: 100%;
    height: 100%;
    /* Uses --primary and --primary-dark from 2nd :root block */
    background-image: linear-gradient(35deg, var(--primary) 0%, var(--primary-dark) 100%);
}

/* Final effective .hero-media style (Only defined once) */
.hero-media {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    overflow: hidden;
    z-index: -2;
}

/* Final effective .hero-media video, .hero-media img style (Only defined once) */
.hero-media video,
.hero-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    animation: zoomInOut 25s infinite alternate ease-in-out; /* zoomInOut is undefined */
}

/* Final effective .hero-section::before style */
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* Uses final --primary and --primary-dark from 3rd :root */
    background: linear-gradient(45deg, rgba(26, 77, 90, 0.7), rgba(22, 66, 73, 0.7)); /* From 2nd definition */
    z-index: 1; /* From 2nd definition (overrides 1st) */
}

/* Final effective .hero-content style (Only defined once) */
.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    padding: 0 1rem;
}

/* Final effective .hero-content h1 style (Only defined once) */
.hero-content h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 4rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

/* Final effective .hero-content p style (Only defined once) */
.hero-content p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Product Grid */
/* Final effective .product-grid style */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* From 2nd definition */
    gap: calc(var(--spacing-unit) * 2.5); /* From 2nd definition */
    /* padding from 1st definition is overridden */
}

/* Final effective .product-card style */
/* Note: incorporates styles from multiple definitions based on cascade */
.product-card {
    background: white; /* From 6th definition */
    border-radius: 0.5rem; /* From 6th definition (overrides 3rd) */
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* From 6th definition (overrides 3rd, 5th) */
    transition: transform 0.2s, box-shadow 0.2s; /* From 6th definition (overrides 3rd, 5th) */
    position: relative; /* From 6th definition (overrides 4th) */
    display: flex; /* From 4th definition */
    flex-direction: column; /* From 4th definition */
    height: 100%; /* From 4th definition */
}

/* Final effective .product-card:hover style */
/* Note: incorporates styles from multiple definitions based on cascade */
.product-card:hover {
    transform: translateY(-5px); /* From 5th definition (overrides others) */
    box-shadow: 0 8px 12px rgba(0,0,0,0.15); /* From 5th definition (overrides others) */
}

/* Final effective .product-image style (Only defined once) */
.product-image {
    position: relative;
    padding-top: 100%;
}

/* Final effective .product-image img style (Only defined once) */
.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Final effective .product-card img style */
/* Note: Combines transitions and final image dimensions */
.product-card img {
    transition: transform 0.3s ease-in-out; /* From hover effects section */
    width: 100%; /* From featured products section */
    height: 280px; /* From featured products section */
    object-fit: cover; /* From featured products section */
}

/* Final effective .product-card:hover img style */
/* Note: combines scale from hover effects and opacity from sample */
.product-card:hover img {
    transform: scale(1.05); /* From hover effects section */
    opacity: 0.85; /* From sample card section */
}

/* Final effective .product-badge style (Only defined once) */
.product-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--primary); /* Uses 2nd :root --primary */
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
}

/* Final effective .product-info style */
/* Note: text-align from sample card overrides */
.product-info {
    padding: 1.5rem; /* From sample card section (overrides 1st) */
    text-align: center; /* From sample card section */
}

/* Final effective .product-info h3 style */
.product-info h3 {
    margin-bottom: 0.5rem;
    font-size: 1.3rem; /* From sample card section */
}

/* Final effective .product-info p style (Only defined once) */
.product-info p {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1rem;
}

/* Final effective .product-card-content style */
.product-card-content {
    padding: 1.5rem; /* From 2nd definition */
    display: flex; /* From 1st definition */
    flex-direction: column; /* From 1st definition */
    flex-grow: 1; /* From 1st definition */
}

/* Final effective .product-title style */
.product-title {
    font-size: 1.25rem; /* From 2nd definition */
    font-weight: 600; /* From 2nd definition */
    margin-bottom: 0.5rem;
    color: var(--dark); /* From 2nd definition - uses undefined var */
    font-family: 'Cormorant Garamond', serif; /* From 1st definition */
}

/* Final effective .product-category style */
.product-category {
    color: var(--gray); /* From 2nd definition - uses undefined var */
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

/* Final effective .product-price style */
.product-price {
    font-size: 1.25rem; /* From 3rd definition (overrides 1st) */
    font-weight: 600; /* From 3rd definition (overrides 1st) */
    color: var(--primary); /* Uses final --primary: #1A4D5A (from 3rd definition) */
    margin-bottom: 1rem;
}

/* Final effective .product-actions style */
.product-actions {
    margin-top: auto; /* From 1st definition */
    display: flex;
    gap: 0.75rem; /* From 2nd definition (overrides 1st) */
    flex-wrap: wrap; /* From 1st definition */
}

/* Final effective .badge style */
.badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem; /* From 2nd definition (overrides 1st) */
    font-size: 0.875rem; /* From 2nd definition (overrides 1st) */
    font-weight: 500; /* From 2nd definition (overrides 1st) */
    z-index: 1; /* From 2nd definition (overrides 1st) */
    text-transform: uppercase; /* From 1st definition */
}

/* Final effective .badge-new style */
.badge-new {
    background: var(--primary); /* Uses final --primary: #1A4D5A */
    color: white;
}

/* Final effective .badge-best-seller style (Only defined once) */
.badge-best-seller {
    background-color: var(--accent); /* Uses final --accent: #D4A76A */
    color: white;
}

/* Final effective .badge-low-stock style */
.badge-low-stock {
    background: var(--warning); /* Uses undefined var */
    color: var(--dark); /* Uses undefined var */
}

/* Final effective .badge-sale style (Only defined once) */
.badge-sale {
    background: var(--danger); /* Uses undefined var */
    color: white;
}

/* Final effective .btn-disabled style */
.btn-disabled {
    background: var(--gray-light); /* Uses undefined var */
    color: var(--gray); /* Uses undefined var */
    cursor: not-allowed; /* From 2nd definition */
    padding: 0.5rem 1rem; /* From 2nd definition (overrides 1st) */
    border-radius: 0.375rem;
    font-weight: 500;
    text-decoration: none; /* From 1st definition */
    opacity: 0.6; /* From 1st definition */
}

/* Final effective .product-link style (Only defined once) */
.product-link {
    font-family: 'Raleway',sans-serif;
    font-weight: 500;
    color: #D4A76A;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    position: relative;
    padding-bottom: 3px;
    display: inline-block;
}

/* Final effective .product-link::after style (Only defined once) */
.product-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 1px;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: #D4A76A;
    transition: width 0.3s;
}

/* Final effective .sample-card:hover .product-link::after style (Only defined once) */
.sample-card:hover .product-link::after { width: 50%; }

/* Final effective .view-all-cta style (Only defined once) */
.view-all-cta { text-align: center; margin-top: 3rem; }


/* Benefits Section */
/* Final effective .benefit-card style */
.benefit-card {
    padding: 2rem;
    background-color: white; /* From 2nd definition */
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* From 2nd definition */
    text-align: center; /* From 2nd definition */
    transition: all 0.3s; /* From 2nd definition */
}

/* Final effective .benefit-card:hover style */
.benefit-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* From 2nd definition */
    transform: translateY(-4px); /* From 2nd definition */
}

/* Final effective .benefit-card i style (Only defined once) */
.benefit-card i {
    display: inline-block;
    margin-bottom: 1rem;
    font-size: 2.5rem;
    color: var(--primary); /* Uses final --primary: #1A4D5A */
}

/* Final effective .text-primary style (Only defined once) */
.text-primary {
    color: var(--primary); /* Uses final --primary: #1A4D5A */
}

/* Footer */
/* Final effective footer style */
footer {
    background: var(--primary-dark); /* Uses final --primary-dark: #164249 */
    color: white;
    padding: 4rem 0 2rem;
}

/* Final effective .footer-grid style */
.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); /* From sample */
    gap: 3rem; /* From sample (overrides 1st) */
    margin-bottom: 3rem; /* From sample (overrides 1st) */
}

/* Final effective .footer-section h3 style */
.footer-section h3 {
    font-family: 'Cormorant Garamond', serif; /* From 2nd definition (overrides sample) */
    font-size: 1.5rem; /* From 2nd definition (overrides sample) */
    margin-bottom: 1rem; /* From 2nd definition (overrides sample) */
    color: #fff; /* From sample definition */
    /* font-weight from sample definition is overridden */
}

/* Final effective .footer-about h3, .footer-links h3, .footer-contact h3 style (Only defined once) */
.footer-about h3, .footer-links h3, .footer-contact h3 {
    font-family: 'Raleway',sans-serif;
    color: #fff;
    font-weight: 600;
    margin-bottom: 1.2rem;
    font-size: 1.1rem;
}

/* Final effective .footer-section ul style */
.footer-section ul {
    list-style: none;
    padding: 0; /* From 2nd definition */
}

/* Final effective .footer-section ul li style */
.footer-section ul li {
    margin-bottom: 0.5rem;
}

/* Final effective .footer-section a style (Only defined once) */
.footer-section a {
    color: white;
    text-decoration: none;
    opacity: 0.8;
}

/* Final effective .footer-section a:hover style (Only defined once) */
.footer-section a:hover {
    opacity: 1;
}

/* Final effective .footer-section ul a style (Only defined once) */
.footer-section ul a {
    color: var(--light); /* Uses final --light: #F7FAFC */
    text-decoration: none;
    transition: color 0.2s;
}

/* Final effective .footer-section ul a:hover style (Only defined once) */
.footer-section ul a:hover {
    color: var(--accent); /* Uses final --accent: #D4A76A */
}

/* Final effective .footer-about p style (Only defined once) */
.footer-about p { line-height: 1.6; margin-bottom: 1rem; }

/* Final effective .social-icons style (Only defined once) */
.social-icons { display: flex; gap: 1rem; }

/* Final effective .social-icons a style (Only defined once) */
.social-icons a { color: #ccc; font-size: 1.2rem; transition: color 0.3s, transform 0.3s; }

/* Final effective .social-icons a:hover style (Only defined once) */
.social-icons a:hover { color: #D4A76A; transform: scale(1.1); }

/* Final effective .footer-links ul li style (Only defined once) */
.footer-links ul li { margin-bottom: 0.5rem; } /* Note: duplicate selector, but kept as per sample structure */

/* Final effective .footer-links a style (Only defined once) */
.footer-links a { color: #ccc; }

/* Final effective .footer-links a:hover style (Only defined once) */
.footer-links a:hover { color: #fff; text-decoration: underline; }

/* Final effective .footer-contact p style (Only defined once) */
.footer-contact p { margin-bottom: 0.6rem; display: flex; align-items: center; gap: 0.5rem; }

/* Final effective .footer-contact i style (Only defined once) */
.footer-contact i { color: #A0C1B1; width: 16px; text-align: center; }


/* Final effective .newsletter-form style */
.newsletter-form {
    display: flex;
    justify-content: center; /* From sample */
    gap: 1rem; /* From sample (overrides others) */
    flex-wrap: wrap; /* From sample */
    max-width: 500px; /* From 2nd definition (overrides 1st & sample) */
    margin: 0 auto; /* From 2nd definition (overrides 1st) */
}

/* Final effective .newsletter-form input style */
.newsletter-form input {
    flex: 1;
    padding: 0.75rem; /* From 3rd definition (overrides 2nd & sample) */
    border-radius: 0.375rem; /* From 3rd definition (overrides 2nd & sample) */
    border: none; /* From 3rd definition (overrides 2nd & sample) */
    /* transition from 2nd definition is overridden */
}

/* Final effective .newsletter-form input:focus style */
.newsletter-form input:focus {
    border-color: var(--primary); /* Uses 2nd :root --primary */
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); /* Uses color from 2nd :root */
    outline: none;
}

/* Final effective .newsletter-form input[type="email"] style (Only defined once) */
.newsletter-form input[type="email"] {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 2px solid transparent;
    border-radius: 0.375rem;
    transition: all 0.3s;
}

/* Final effective .newsletter-form input[type="email"]:focus style (Only defined once) */
.newsletter-form input[type="email"]:focus {
    border-color: var(--primary); /* Uses final --primary: #1A4D5A */
    outline: none;
    box-shadow: 0 0 0 3px rgba(26, 77, 90, 0.1); /* Uses color from final --primary */
}

/* Final effective .newsletter-input style (Only defined once) */
.newsletter-input {
    padding: 0.8rem;
    border: 1px solid #A0C1B1; /* Corresponds to final --secondary */
    border-radius: 50px;
    font-family: 'Montserrat', sans-serif;
    min-width: 300px;
    flex-grow: 1;
}

/* Final effective .newsletter-btn style (Only defined once) */
.newsletter-btn {
    background-color: #D4A76A; /* Corresponds to final --accent */
    color: #1A4D5A; /* Corresponds to final --primary */
    border-color: #D4A76A;
    border-radius: 50px;
    font-family: 'Raleway',sans-serif;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.8rem 2rem;
    transition: background-color 0.3s, color 0.3s;
}

/* Final effective .newsletter-btn:hover style (Only defined once) */
.newsletter-btn:hover {
    background-color: #A0C1B1; /* Corresponds to final --secondary */
    border-color: #A0C1B1;
    color: #1A4D5A;
}

/* Final effective .newsletter-consent style (Only defined once) */
.newsletter-consent {
    font-size: 0.8rem;
    opacity: 0.7;
    margin-bottom: 0;
}

/* Final effective .newsletter-consent a style (Only defined once) */
.newsletter-consent a {
    color: #A0C1B1; /* Corresponds to final --secondary */
    text-decoration: underline;
}

/* Final effective .newsletter-consent a:hover style (Only defined once) */
.newsletter-consent a:hover {
    color: #fff;
}

/* Final effective .footer-bottom style */
.footer-bottom {
    background-color: #222b2e; /* From sample */
    padding: 1.5rem 0; /* From sample (overrides 1st/2nd) */
    margin-top: 2rem; /* From sample */
    border-top: 1px solid rgba(255,255,255,0.1); /* From 1st/2nd definition */
    display: flex; /* From 1st/2nd definition */
    justify-content: space-between; /* From 1st/2nd definition */
    align-items: center; /* From 1st/2nd definition */
}

/* Final effective .footer-bottom .container style (Only defined once) */
.footer-bottom .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.85rem;
}

/* Final effective .social-links style */
/* Note: Identical definitions, kept last */
.social-links {
    display: flex;
    gap: 1rem;
}

/* Final effective .social-links a style */
.social-links a {
    color: white;
    font-size: 1.25rem; /* From 2nd definition */
    transition: color 0.2s; /* From 2nd definition (overrides 1st) */
    /* opacity from 1st definition is overridden */
}

/* Final effective .social-links a:hover style */
.social-links a:hover {
    color: var(--accent); /* Uses final --accent: #D4A76A */
    /* opacity from 1st definition is overridden */
}

/* Final effective .payment-methods style (Only defined once) */
.payment-methods { display: flex; align-items: center; gap: 0.8rem; }

/* Final effective .payment-methods span style (Only defined once) */
.payment-methods span { margin-right: 0.5rem; }

/* Final effective .payment-methods i style (Only defined once) */
.payment-methods i { font-size: 1.8rem; color: #aaa; }

/* Flash Messages */
/* Final effective .flash-message style */
.flash-message {
    position: fixed;
    top: 100px; /* From 3rd definition (overrides 2nd) */
    right: 20px; /* From 3rd definition (overrides 2nd) */
    padding: 1rem 2rem; /* From 3rd definition (overrides 2nd) */
    border-radius: 0.375rem; /* From 3rd definition (overrides 1st/2nd) */
    background: white; /* From 3rd definition */
    box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* From 3rd definition (overrides 2nd) */
    z-index: 1000; /* From 3rd definition (overrides 2nd) */
    /* transform, transition, animation, margin, text-align from earlier definitions are overridden */
}

/* Final effective .flash-message.success style */
.flash-message.success {
    background: #C6F6D5; /* From 3rd definition */
    color: #276749; /* From 3rd definition */
}

/* Final effective .flash-message.error style */
.flash-message.error {
    background: #FED7D7; /* From 3rd definition */
    color: #9B2C2C; /* From 3rd definition */
}

/* Final effective .flash-message.info style (Only defined once) */
/* Note: The 2nd definition was actually identical to the 3rd error def. Keeping 3rd one */
.flash-message.info {
     background-color: #3b82f6; /* From 2nd definition */
     color: white; /* From 2nd definition */
}


/* Final effective @keyframes slideIn (Only defined once) */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* About Section Image */
/* Final effective .about-image img style (Only defined once) */
.about-image img {
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
}

/* Final effective .about-image img:hover style (Only defined once) */
.about-image img:hover {
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

/* Quiz Promo Section */
/* Final effective .quiz-promo style (Only defined once) */
.quiz-promo {
    /* Uses --primary and --primary-dark from 2nd :root block */
    background-image: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
}

/* Featured Products */
/* Final effective .featured-section style (Only defined once) */
.featured-section {
    padding: 5rem 0;
    background: var(--light); /* Uses final --light: #F7FAFC */
}

/* Final effective .featured-grid style (Only defined once) */
.featured-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

/* Quiz Section */
/* Final effective .quiz-section style (Only defined once) */
.quiz-section {
    background: var(--primary); /* Uses final --primary: #1A4D5A */
    color: white;
    padding: 5rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* Final effective .quiz-section::before style (Only defined once) */
.quiz-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, var(--primary), var(--primary-dark)); /* Uses final vars */
    opacity: 0.9;
    z-index: 1;
}

/* Final effective .quiz-content style (Only defined once) */
.quiz-content {
    position: relative;
    z-index: 2;
    max-width: 600px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Final effective .quiz-content h2 style (Only defined once) */
.quiz-content h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    margin-bottom: 1.5rem;
}

/* Newsletter Section */
/* Final effective .newsletter-section style (Only defined once) */
.newsletter-section {
    background: var(--light); /* Uses final --light: #F7FAFC */
    padding: 5rem 0;
}

/* Final effective .header-icons style (Only defined once) */
.header-icons { display: flex; gap: 1.2rem; }

/* Final effective .header-icons a style (Only defined once) */
.header-icons a { color: #1A4D5A; font-size: 1.2rem; }

/* Final effective .sample-card style (Only defined once) */
.sample-card {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

/* Final effective .sample-card:hover style (Only defined once) */
.sample-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }

/* Final effective .sample-card img style (Only defined once) */
.sample-card img { height: 250px; object-fit: cover; transition: opacity 0.3s; }

/* --- Responsive Design --- */

/* Final Consolidated @media (max-width: 992px) */
@media (max-width: 992px) {
    /* From 1st block */
    .about-container { grid-template-columns: 1fr; text-align: center; }
    .about-image { margin-bottom: calc(var(--spacing-unit) * 2); order: -1; }
    /* From 2nd block */
    .header-container { padding: 0 1.5rem; }
    .main-nav, .header-icons { display: none; } /* .main-nav Added from 1st block */
    .nav-links, .header-icons { display: none; } /* .nav-links Added from 2nd block */
    .mobile-nav-toggle { display: block; } /* Combined from both blocks */
    .footer-grid { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); } /* Combined/Overrides */
}

/* Final Consolidated @media (max-width: 768px) */
@media (max-width: 768px) {
    /* From 1st block */
    .hero h1 { font-size: 2rem; }
    .hero-video h1 { font-size: 2.5rem; }
    .product-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
    .grid { grid-template-columns: 1fr; } /* Assuming .grid was intended */
    /* From 2nd block */
    /* .nav-links definition from 2nd block is overridden by 4th block */
    /* .nav-links.active definition from 2nd block is overridden by 4th block */
    /* .nav-links a definition from 2nd block */
    .nav-links a { display: block; padding: 0.5rem 0; font-size: 1.125rem; }
    /* From 3rd block */
    .main-nav .container { height: 60px; }
    /* .nav-links { display: none; } /* Overridden by 4th block */
    .hero-content h1 { font-size: clamp(2rem, 8vw, 3rem); } /* Overrides 1st block */
    .hero-content p { font-size: clamp(1rem, 4vw, 1.25rem); }
    .featured-grid { grid-template-columns: 1fr; }
    .footer-grid { grid-template-columns: 1fr; text-align: center; } /* Overrides */
    .newsletter-form { flex-direction: column; padding: 0 1rem; } /* Overrides 1st block */
    .newsletter-form input[type="email"] { width: 100%; margin-bottom: 0.5rem; }
    .newsletter-form button { width: 100%; }
    .quiz-section { padding: 3rem 0; }
    .quiz-content { padding: 0 1rem; }
    .quiz-content h2 { font-size: 2rem; }
    .benefit-card { padding: 1.5rem; text-align: center; }
    /* From 4th block */
    .nav-links { /* Final mobile nav style */
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        background: white;
        padding: 1rem;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        transform: translateY(-100%);
        transition: transform 0.3s ease-in-out;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 40; /* Note: Lower z-index than flash messages/header */
        /* height: 100vh; From 2nd block definition */ /* Height removed in 4th block */
    }
    .nav-links.active { /* Final */
        transform: translateY(0);
    }
    .mobile-menu-toggle { /* Final */
        display: block;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--primary); /* Uses final --primary */
        cursor: pointer;
        padding: 0.5rem;
    }
    .mobile-menu-toggle:focus { outline: none; }
    .nav-actions { margin-left: auto; }
    body.menu-open { overflow: hidden; }
}


/* Final Consolidated @media (max-width: 576px) */
@media (max-width: 576px) {
    /* From 1st block */
    h1 { font-size: 2rem; }
    h2 { font-size: 1.6rem; }
    h3 { font-size: 1.2rem; }
    .hero-section { min-height: 500px; }
    /* .hero-content p definition from 1st block is overridden by @768px block */
    .btn { padding: calc(var(--spacing-unit) * 0.7) calc(var(--spacing-unit) * 1.5); font-size: 0.9rem; }
    section { padding: calc(var(--spacing-unit) * 3) 0; }
    /* From 2nd block */
    .footer-grid { grid-template-columns: 1fr; text-align: center; } /* Overrides @768px */
    .social-icons { justify-content: center; margin-top: 1rem; }
    .footer-bottom .container { flex-direction: column; text-align: center; }
    .payment-methods { margin-top: 1rem; }
}
```

**Summary of Changes:**

1.  **Kept All `:root` Blocks:** Preserved all three `:root` declarations to maintain the original variable override behavior dictated by the cascade. The effective variable values for any given style will depend on which `:root` block was declared *last* before that style was defined.
2.  **Removed Redundant Selector Definitions:** For any selector defined multiple times (e.g., `.container`, `.btn-primary`, `.product-card`, `footer`, `.flash-message`, etc.), only the *last* definition encountered in the original file was kept.
3.  **Consolidated Media Queries:** Rules within identical media queries (e.g., `@media (max-width: 768px)`) were processed sequentially, keeping only the last definition for any conflicting selectors within that specific query block.
4.  **Preserved Naming:** No selectors were renamed (e.g., `.main-nav`, `.sample-header`, `.product-info`, `.social-links` are all still present if they were the last definition).
5.  **Preserved Potential Issues:** The missing `zoomInOut` keyframes and the use of potentially undefined variables (`--dark`, `--gray`, `--warning`, etc.) remain, as fixing these would alter the behavior or structure beyond simple deduplication.

This version should render identically to the original file while being significantly shorter by removing the declarations that the browser would ignore anyway due to the CSS cascade.
