<?php require_once 'layout/header.php'; ?>

<section class="auth-section">
    <div class="container">
        <div class="auth-container" data-aos="fade-up">
            <h1>Forgot Password</h1>
            <p class="auth-description">Enter your email address and we'll send you a link to reset your password.</p>

            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="flash-message <?= $_SESSION['flash_type'] ?? 'info' ?>">
                    <?= htmlspecialchars($_SESSION['flash_message']) ?>
                </div>
                <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
            <?php endif; ?>

            <form action="index.php?action=forgot_password" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter your email address">
                </div>

                <button type="submit" class="btn-primary full-width">Send Reset Link</button>
            </form>

            <div class="auth-links">
                <p>
                    Remember your password? 
                    <a href="index.php?page=login">Back to Login</a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'layout/footer.php'; ?>