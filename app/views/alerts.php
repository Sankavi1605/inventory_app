<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/alerts.css">
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
    <div class="logo">
        <a href="<?php echo URLROOT; ?>" class="logo-image">
            <img class="logo-image" src="<?php echo URLROOT; ?>/public/img/logo.png" alt="Sameepa Logo">
        </a>
        ConstructStock
    </div>
      <nav class="menu">
      <a href="<?php echo URLROOT; ?>/index" class="menu-item "><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="<?php echo URLROOT; ?>/inventory/inventory" class="menu-item "><i class="fas fa-box"></i> Inventory</a>
        <a href="<?php echo URLROOT; ?>/inventory/equipment" class="menu-item "><i class="fas fa-tools"></i> Equipment</a>
        <a href="<?php echo URLROOT; ?>/inventory/alerts" class="menu-item active"><i class="fas fa-bell"></i> Alerts</a>
      </nav>
    </aside>


    </body>
</html>