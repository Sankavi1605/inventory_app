<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Using external CSS for styling */

        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-header {
            margin-bottom: 2rem;
        }

        .login-logo {
            margin-bottom: 1rem;
        }

        .login-logo img {
            width: 80px;
            height: auto;
        }

        .login-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 0.75rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: left;
        }

        .register-link {
            margin-top: 1.5rem;
            color: #666;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .user-options {
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-options a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .user-options a:hover {
            text-decoration: underline;
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