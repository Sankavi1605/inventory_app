<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
</head>
<body>
    <?php require APPROOT . '/views/inc/components/header.php'; ?>
    <?php require APPROOT . '/views/inc/components/navbar.php'; ?>

    <div class="dashboard-container">
        <?php require APPROOT . '/views/inc/components/side_panel_superadmin.php'; ?>

        <main class="user-admin">
            <header class="section-header">
                <div>
                    <h1>Users</h1>
                    <p>Single-admin mode: every account has full access.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/usercontroller/create" class="btn btn--primary">
                    <i class="fas fa-user-plus"></i> New User
                </a>
            </header>

            <?php flash('user_message'); ?>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)) : ?>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                                    <td><?php echo htmlspecialchars($user->username); ?></td>
                                    <td><?php echo htmlspecialchars($user->email); ?></td>
                                    <td><?php echo htmlspecialchars($user->phone ?? '-'); ?></td>
                                    <td class="actions">
                                        <a href="<?php echo URLROOT; ?>/usercontroller/edit/<?php echo $user->id; ?>" class="btn btn--ghost">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <?php if ($user->id != getCurrentUserId()) : ?>
                                            <form action="<?php echo URLROOT; ?>/usercontroller/delete/<?php echo $user->id; ?>" method="POST" onsubmit="return confirm('Delete this user?');">
                                                <button type="submit" class="btn btn--danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="empty">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/components/footer.php'; ?>

    <style>
        .user-admin { flex: 1; padding: 2rem; }
        .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
        .table-wrapper { background:#fff; border-radius:18px; box-shadow:0 16px 40px -30px rgba(15,23,42,.35); overflow:auto; }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th, .data-table td { padding:1rem; text-align:left; border-bottom:1px solid #edf2f7; }
        .data-table tbody tr:hover { background:#f8fafc; }
        .actions { display:flex; gap:.5rem; align-items:center; }
        .btn { border:none; padding:.45rem .9rem; border-radius:10px; cursor:pointer; text-decoration:none; font-weight:600; display:inline-flex; gap:.4rem; align-items:center; }
        .btn--primary { background:#2563eb; color:#fff; }
        .btn--ghost { background:#eef2ff; color:#4c1d95; }
        .btn--danger { background:#fee2e2; color:#b91c1c; border:none; }
        .empty { text-align:center; padding:2rem; }
    </style>
</body>
</html>
