<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | <?php echo SITENAME; ?></title>
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

            <form action="<?php echo URLROOT; ?>/auth/authenticate" method="post" class="auth-form">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="form-input"
                        value="<?php echo htmlspecialchars($data['username'] ?? ($data['username/email'] ?? '')); ?>"
                        autocomplete="username"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input"
                        autocomplete="current-password"
                        required>
                </div>

                <div class="form-aux">
                    <label class="checkbox-field">
                        <input type="checkbox" name="remember" value="1">
                        Keep me signed in
                    </label>
                    <a href="<?php echo URLROOT; ?>/auth/forgot">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <div class="auth-toggle">
                Need an account? <a href="<?php echo URLROOT; ?>/auth/signup">Request access</a>
            </div>

        </section>
    </div>
</body>
</html>