<?php
/**
 * Shared dashboard shell start: outputs the document head, sidebar, and opens the layout wrapper.
 */
$cssVersion = time();
$pageTitle = $pageTitle ?? SITENAME;
$activeNav = $activeNav ?? '';
$pageStyles = $pageStyles ?? [];

$styleUrls = array_merge([
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
  URLROOT . "/public/css/style.css?v={$cssVersion}",
], $pageStyles, [
  URLROOT . "/public/css/dashboard.css?v={$cssVersion}"
]);
$styleUrls = array_values(array_unique($styleUrls));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
<?php foreach ($styleUrls as $href) : ?>
    <link rel="stylesheet" href="<?php echo $href; ?>">
<?php endforeach; ?>
</head>
<body class="theme-dark">
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="logo">
        <a href="<?php echo URLROOT; ?>" class="logo-image">
          <img class="logo-image" src="<?php echo URLROOT; ?>/public/img/logo.png" alt="ConstructStock Logo">
        </a>
        ConstructStock
      </div>
      <nav class="menu">
        <a href="<?php echo URLROOT; ?>/index" class="menu-item<?php echo $activeNav === 'dashboard' ? ' active' : ''; ?>">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?php echo URLROOT; ?>/inventory" class="menu-item<?php echo $activeNav === 'inventory' ? ' active' : ''; ?>">
          <i class="fas fa-box"></i> Inventory
        </a>
        <a href="<?php echo URLROOT; ?>/equipment" class="menu-item<?php echo $activeNav === 'equipment' ? ' active' : ''; ?>">
          <i class="fas fa-tools"></i> Equipment
        </a>
      </nav>
    </aside>
    <!-- Main content continues in the view -->
