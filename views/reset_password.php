<?php require_once 'layout/header.php'; ?>

<section class="auth-section">
    <div class="container">
        <div class="auth-container" data-aos="fade-up">
            <h1>Reset Password</h1>
            <p class="auth-description">Enter your new password below.</p>

            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="flash-message <?= $_SESSION['flash_type'] ?? 'info' ?>">
                    <?= htmlspecialchars($_SESSION['flash_message']) ?>
                </div>
                <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
            <?php endif; ?>

            <form action="index.php?action=reset_password" method="POST" class="auth-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required 
                           minlength="8" placeholder="Enter your new password">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm New Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" 
                           required minlength="8" placeholder="Confirm your new password">
                </div>

                <button type="submit" class="btn-primary full-width">Reset Password</button>
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