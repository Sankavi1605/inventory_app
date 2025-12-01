<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/auth.css?v=<?php echo time(); ?>">
</head>
<body class="auth-body">
    <div class="auth-shell">
        <section class="auth-panel">
            <?php flash('login_message'); ?>
            <?php flash('signup_message'); ?>

            <header>
                <h2>Welcome back</h2>
                <p>Sign in to manage stock levels, approvals, and equipment insights.</p>
            </header>

            <?php if (!empty($data['username/email_err'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($data['username/email_err']); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($data['password_err'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($data['password_err']); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/auth/authenticate" method="post" class="auth-form" id="loginForm">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="form-input"
                        placeholder="Enter your username or email"
                        value="<?php echo htmlspecialchars($data['username'] ?? ($data['username/email'] ?? '')); ?>"
                        autocomplete="username"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required>
                        <i class="fas fa-eye input-icon" id="togglePassword" title="Show password"></i>
                    </div>
                </div>

                <div class="form-aux">
                    <label class="checkbox-field">
                        <input type="checkbox" name="remember" value="1">
                        <span>Keep me signed in</span>
                    </label>
                    <a href="<?php echo URLROOT; ?>/auth/forgot">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary" id="submitBtn">
                    <span>Sign In</span>
                </button>
            </form>

            <div class="auth-toggle">
                Need an account? <a href="<?php echo URLROOT; ?>/auth/signup">Request access</a>
            </div>

        </section>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword?.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
            this.title = type === 'password' ? 'Show password' : 'Hide password';
        });

        // Form submission loading state
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        loginForm?.addEventListener('submit', function() {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>