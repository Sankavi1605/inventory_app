<?php
/**
 * Shared page header component with title, search, user info, and theme toggle
 */
$pageHeading = $pageHeading ?? 'Dashboard';
$pageSubheading = $pageSubheading ?? '';
$showSearch = $showSearch ?? true;
$searchPlaceholder = $searchPlaceholder ?? 'Search...';
$searchId = $searchId ?? '';
?>
<header class="top-header">
  <div class="top-header__left">
    <h1><?php echo htmlspecialchars($pageHeading); ?></h1>
    <?php if ($pageSubheading): ?>
      <p><?php echo htmlspecialchars($pageSubheading); ?></p>
    <?php endif; ?>
  </div>
  <div class="top-header__right">
    <?php if ($showSearch): ?>
      <div class="top-search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="<?php echo htmlspecialchars($searchPlaceholder); ?>" autocomplete="off"<?php echo $searchId ? ' id="' . htmlspecialchars($searchId) . '"' : ''; ?>>
      </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['user_id'])) : ?>
      <div class="user-chip">
        <i class="fas fa-user-circle"></i>
        <span><?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?></span>
        <a href="<?php echo URLROOT; ?>/auth/logout" class="chip-action">
          <i class="fas fa-sign-out-alt"></i>
          Logout
        </a>
      </div>
    <?php else : ?>
      <div class="auth-actions">
        <a href="<?php echo URLROOT; ?>/auth/login">Login</a>
        <a href="<?php echo URLROOT; ?>/auth/signup">Sign Up</a>
      </div>
    <?php endif; ?>
  </div>
</header>
