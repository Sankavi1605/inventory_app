<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/auth.css?v=<?php echo time(); ?>">
</head>
<body class="auth-body">
    <div class="auth-shell">
        <section class="auth-panel">
            <?php flash('signup_message'); ?>

            <header>
                <h2>Create access</h2>
                <p>Submit your details and we’ll provision the proper role once approved.</p>
            </header>

            <?php foreach (['first_name_err','last_name_err','email_err','username_err','password_err','confirm_password_err'] as $errorKey): ?>
                <?php if (!empty($data[$errorKey])): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($data[$errorKey]); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <form action="<?php echo URLROOT; ?>/auth/register" method="post" class="auth-form" id="signupForm">
                <div class="split-row">
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" name="first_name" id="first_name" class="form-input" placeholder="first_name" value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" name="last_name" id="last_name" class="form-input" placeholder="last_name" value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Work email</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" autocomplete="email" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-input" placeholder="username" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" autocomplete="username" required>
                </div>

                <div class="split-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" autocomplete="new-password" required>
                            <i class="fas fa-eye input-icon" id="togglePassword" title="Show password"></i>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <div class="input-with-icon">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-input" placeholder="••••••••" autocomplete="new-password" required>
                            <i class="fas fa-eye input-icon" id="toggleConfirmPassword" title="Show password"></i>
                        </div>
                    </div>
                </div>

                <p class="field-hint" id="passwordHint" style="margin-top: -0.75rem;">Use at least 8 characters, a number, and a symbol.</p>

                <div class="form-group">
                    <label for="phone">Phone (optional)</label>
                    <input type="tel" name="phone" id="phone" class="form-input" placeholder="+94 " value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                </div>

                <label class="checkbox-field">
                    <input type="checkbox" name="terms" id="terms" <?php echo !empty($data['terms']) ? 'checked' : ''; ?> required>
                    <span>I accept the ConstructStock Terms of Service and Privacy Policy.</span>
                </label>

                <button type="submit" class="btn-primary" id="submitBtn">
                    <span>Submit request</span>
                </button>
            </form>

            <div class="auth-toggle">
                Already verified? <a href="<?php echo URLROOT; ?>/auth/login">Sign in</a>
            </div>

        </section>
    </div>

    <script>
        // Password visibility toggles
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        function setupPasswordToggle(toggleBtn, input) {
            toggleBtn?.addEventListener('click', function() {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
                this.title = type === 'password' ? 'Show password' : 'Hide password';
            });
        }
        
        setupPasswordToggle(togglePassword, passwordInput);
        setupPasswordToggle(toggleConfirmPassword, confirmPasswordInput);

        // Password strength indicator
        const strengthBar = document.getElementById('strengthBar');
        const strengthContainer = document.getElementById('passwordStrength');
        const passwordHint = document.getElementById('passwordHint');
        
        passwordInput?.addEventListener('input', function() {
            const password = this.value;
            
            if (password.length === 0) {
                strengthContainer.classList.remove('active');
                passwordHint.textContent = 'Use at least 8 characters, a number, and a symbol.';
                passwordHint.style.color = '';
                return;
            }
            
            strengthContainer.classList.add('active');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            
            if (strength <= 1) {
                strengthBar.classList.add('weak');
                passwordHint.textContent = 'Weak password. Add more characters and complexity.';
                passwordHint.style.color = '#dc2626';
            } else if (strength <= 3) {
                strengthBar.classList.add('medium');
                passwordHint.textContent = 'Medium strength. Consider adding special characters.';
                passwordHint.style.color = '#f59e0b';
            } else {
                strengthBar.classList.add('strong');
                passwordHint.textContent = 'Strong password!';
                passwordHint.style.color = '#16a34a';
            }
        });

        // Password match validation
        confirmPasswordInput?.addEventListener('input', function() {
            if (this.value && passwordInput.value !== this.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Form submission loading state
        const signupForm = document.getElementById('signupForm');
        const submitBtn = document.getElementById('submitBtn');
        
        signupForm?.addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>