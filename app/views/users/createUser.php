<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User | <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
</head>
<body>
    <?php require APPROOT . '/views/inc/components/header.php'; ?>
    <?php require APPROOT . '/views/inc/components/navbar.php'; ?>
    <div class="dashboard-container">
        <?php require APPROOT . '/views/inc/components/side_panel_superadmin.php'; ?>
        <main class="user-form">
            <h1>Create User</h1>
            <?php flash('user_message'); ?>
            <form action="<?php echo URLROOT; ?>/usercontroller/create" method="POST" class="form-grid">
                <label>
                    <span>First Name</span>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>" required>
                </label>
                <label>
                    <span>Last Name</span>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>" required>
                </label>
                <label>
                    <span>Username</span>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" required>
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" required>
                </label>
                <label>
                    <span>Phone</span>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" required>
                </label>
                <label>
                    <span>Confirm Password</span>
                    <input type="password" name="confirm_password" required>
                </label>
                <button type="submit" class="btn btn--primary">Create</button>
            </form>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/components/footer.php'; ?>

    <style>
        .user-form { flex: 1; padding: 2rem; }
        .form-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:1rem; }
        label { display:flex; flex-direction:column; font-weight:600; }
        input { padding:.7rem; border:1px solid #d1d5db; border-radius:10px; }
        .btn { margin-top:1rem; border:none; padding:.75rem 1.25rem; border-radius:12px; background:#2563eb; color:#fff; font-weight:600; cursor:pointer; }
    </style>
</body>
</html>
