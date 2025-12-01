<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Critical inline styles to test CSS application */
        .login-container {
            background: white !important;
            padding: 3rem !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            width: 100% !important;
            max-width: 400px !important;
            text-align: center !important;
            margin: 0 auto !important;
        }

        .auth-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
            min-height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 20px !important;
        }

        .form-group {
            margin-bottom: 1.5rem !important;
            text-align: left !important;
        }

        .form-group input {
            width: 100% !important;
            padding: 0.75rem !important;
            border: 1px solid #ddd !important;
            border-radius: 5px !important;
            font-size: 1rem !important;
            box-sizing: border-box !important;
        }

        .btn {
            width: 100% !important;
            padding: 0.75rem !important;
            background: #007bff !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            cursor: pointer !important;
        }

        .form-group label {
            display: block !important;
            margin-bottom: 0.5rem !important;
            color: #333 !important;
            font-weight: 500 !important;
        }
    </style>
</head>

<body class="auth-container">
    <?php flash('login_message'); ?>
    <?php flash('signup_message'); ?>

    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <img src="<?php echo URLROOT; ?>/img/logo.png" alt="ConstructStock">
            </div>
            <h1 class="login-title">Sign In</h1>
            <p class="login-subtitle">Access your inventory management dashboard</p>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($data['username/email_err'])): ?>
            <div class="error-message">
                <?php echo $data['username/email_err']; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['password_err'])): ?>
            <div class="error-message">
                <?php echo $data['password_err']; ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?php echo URLROOT; ?>/auth/authenticate" method="post" id="loginForm">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" name="username" id="username" value="<?php echo $data['username'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="user-options">
            <a href="<?php echo URLROOT; ?>/auth/forgot">Forgot Password?</a>
        </div>

        <div class="register-link">
            Don't have an account? <a href="<?php echo URLROOT; ?>/auth/signup">Sign up</a>
        </div>
    </div>
</body>
</html>