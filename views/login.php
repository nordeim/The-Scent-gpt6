<?php require_once 'layout/header.php'; ?>

<section class="auth-section">
    <div class="container">
        <div class="auth-container" data-aos="fade-up">
            <h1>Login</h1>
            <p class="auth-description">Welcome back! Please enter your credentials to continue.</p>

            <form action="index.php?page=login" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter your email address">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required
                               placeholder="Enter your password">
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="forgot-password">
                        <a href="index.php?page=forgot_password">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="btn-primary full-width">Login</button>
            </form>

            <div class="auth-links">
                <p>
                    Don't have an account? 
                    <a href="index.php?page=register">Create one now</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const toggleButton = document.querySelector('.toggle-password');
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
});
</script>

<?php require_once 'layout/footer.php'; ?>