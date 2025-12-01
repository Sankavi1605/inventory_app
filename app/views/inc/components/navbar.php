

<!-- Modern Dashboard Navbar -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/dashboard.css">
<nav class="dashboard-navbar" style="background: #f7f9fc; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 0 2rem; font-family: 'Poppins', sans-serif;">
    <div class="dashboard-navdiv" style="display: flex; align-items: center; justify-content: space-between; height: 70px;">
        <a href="<?php echo URLROOT; ?>" class="dashboard-logo" style="display: flex; align-items: center; gap: 1rem; text-decoration: none;">
            <img src="<?php echo URLROOT; ?>/public/img/logo.png" alt="ConstructStock Logo" style="height: 48px;">
            <span style="font-size: 1.5rem; font-weight: 700; color: #222;">ConstructStock</span>
        </a>
        <ul class="dashboard-menu" style="display: flex; align-items: center; gap: 2rem; list-style: none; margin: 0; padding: 0;">
            <li><a href="<?php echo URLROOT; ?>/index" class="menu-item" style="color: #333; font-weight: 500; text-decoration: none;">Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/inventory/inventory" class="menu-item" style="color: #333; font-weight: 500; text-decoration: none;">Inventory</a></li>
            <li><a href="<?php echo URLROOT; ?>/inventory/equipment" class="menu-item" style="color: #333; font-weight: 500; text-decoration: none;">Equipment</a></li>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <li class="user-info" style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 1rem; background: #e8f4fd; border-radius: 25px;">
                    <i class="fas fa-user-circle" style="color: #007bff; font-size: 1.2rem;"></i>
                    <span style="color: #333; font-weight: 600;">
                        <?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?>
                    </span>
                    <a href="<?php echo URLROOT; ?>/auth/logout" class="logout-btn" style="color: #e74c3c; font-weight: 600; text-decoration: none; padding: 0.3rem 0.8rem; background: #ffeaea; border-radius: 15px; transition: all 0.3s;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            <?php else : ?>
                <li><a href="<?php echo URLROOT; ?>/auth/signup" class="menu-item" style="color: #007bff; font-weight: 500; text-decoration: none;">Sign Up</a></li>
                <li><a href="<?php echo URLROOT; ?>/auth/login" class="menu-item" style="color: #007bff; font-weight: 500; text-decoration: none;">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
.logout-btn:hover {
    background: #e74c3c !important;
    color: #fff !important;
    transform: translateY(-1px);
}
.user-info {
    transition: all 0.3s ease;
}
.user-info:hover {
    background: #d1e9ff !important;
}
</style>