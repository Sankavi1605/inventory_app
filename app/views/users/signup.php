<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | <?php echo SITENAME; ?></title>
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

            <form action="<?php echo URLROOT; ?>/auth/register" method="post" class="auth-form">
                <div class="split-row">
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" name="first_name" id="first_name" class="form-input" value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" name="last_name" id="last_name" class="form-input" value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Work email</label>
                    <input type="email" name="email" id="email" class="form-input" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" autocomplete="email" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-input" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" autocomplete="username" required>
                </div>

                <div class="split-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-input" autocomplete="new-password" required>
                        <p class="field-hint">Use at least 8 characters, a number, and a symbol.</p>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-input" autocomplete="new-password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone (optional)</label>
                    <input type="tel" name="phone" id="phone" class="form-input" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                </div>

                <label class="checkbox-field">
                    <input type="checkbox" name="terms" id="terms" <?php echo !empty($data['terms']) ? 'checked' : ''; ?> required>
                    I accept the ConstructStock Terms of Service and Privacy Policy.
                </label>

                <button type="submit" class="btn-primary">Submit request</button>
            </form>

            <div class="auth-toggle">
                Already verified? <a href="<?php echo URLROOT; ?>/auth/login">Sign in</a>
            </div>

        </section>
    </div>
</body>
</html>