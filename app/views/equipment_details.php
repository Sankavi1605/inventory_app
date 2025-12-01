<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($equipment->name); ?> | Equipment Details</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css?v=20241111">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/dashboard.css?v=20241111">
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="logo">
        <a href="<?php echo URLROOT; ?>" class="logo-image">
          <img class="logo-image" src="<?php echo URLROOT; ?>/public/img/logo.png" alt="ConstructStock Logo">
        </a>
        ConstructStock
      </div>
      <nav class="menu">
        <a href="<?php echo URLROOT; ?>/index" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="<?php echo URLROOT; ?>/inventory" class="menu-item"><i class="fas fa-box"></i> Inventory</a>
        <a href="<?php echo URLROOT; ?>/equipment" class="menu-item active"><i class="fas fa-tools"></i> Equipment</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="top-header">
        <div class="top-header__left">
          <h1><?php echo htmlspecialchars($equipment->name); ?></h1>
          <p>Status: <span class="status-pill status-<?php echo htmlspecialchars(strtolower($equipment->status)); ?>">
            <?php echo ucwords(str_replace('_', ' ', $equipment->status)); ?>
          </span></p>
        </div>
        <div class="top-header__right">
          <a href="<?php echo URLROOT; ?>/equipment" class="ghost-btn">
            <i class="fas fa-arrow-left"></i> Back
          </a>
          <?php if (hasRole('admin')): ?>
          <a href="<?php echo URLROOT; ?>/equipment/edit/<?php echo $equipment->id; ?>" class="add-btn">
            <i class="fas fa-edit"></i> Edit
          </a>
          <?php endif; ?>
        </div>
      </header>

      <section class="content-section">
        <div class="details-grid">
          <article class="info-card">
            <h3>Identification</h3>
            <ul>
              <li><strong>Serial:</strong> <?php echo htmlspecialchars($equipment->serial_number ?? 'N/A'); ?></li>
              <li><strong>Model:</strong> <?php echo htmlspecialchars($equipment->model ?? 'N/A'); ?></li>
              <li><strong>Manufacturer:</strong> <?php echo htmlspecialchars($equipment->manufacturer ?? 'N/A'); ?></li>
              <li><strong>Category:</strong> <?php echo htmlspecialchars($equipment->category ?? 'N/A'); ?></li>
            </ul>
          </article>
          <article class="info-card">
            <h3>Lifecycle</h3>
            <ul>
              <li><strong>Purchase Date:</strong> <?php echo $equipment->purchase_date ? date('M d, Y', strtotime($equipment->purchase_date)) : 'N/A'; ?></li>
              <li><strong>Purchase Cost:</strong> <?php echo $equipment->purchase_cost ? '$' . number_format($equipment->purchase_cost, 2) : 'N/A'; ?></li>
              <li><strong>Warranty Expiry:</strong> <?php echo $equipment->warranty_expiry ? date('M d, Y', strtotime($equipment->warranty_expiry)) : 'N/A'; ?></li>
            </ul>
          </article>
          <article class="info-card">
            <h3>Maintenance</h3>
            <ul>
              <li><strong>Last Maintenance:</strong> <?php echo $equipment->last_maintenance ? date('M d, Y', strtotime($equipment->last_maintenance)) : 'N/A'; ?></li>
              <li><strong>Next Maintenance:</strong> <?php echo $equipment->next_maintenance ? date('M d, Y', strtotime($equipment->next_maintenance)) : 'N/A'; ?></li>
            </ul>
          </article>
          <article class="info-card">
            <h3>Assignment</h3>
            <ul>
              <li><strong>Location:</strong> <?php echo htmlspecialchars($equipment->location ?? 'N/A'); ?></li>
              <li><strong>Assigned To:</strong>
                <?php
                if ($equipment->assigned_first_name) {
                    echo htmlspecialchars($equipment->assigned_first_name . ' ' . $equipment->assigned_last_name);
                } else {
                    echo 'Unassigned';
                }
                ?>
              </li>
            </ul>
          </article>
        </div>
      </section>

      <section class="content-section">
        <div class="section-title">
          <h2>Maintenance History</h2>
          <span>Chronological log of service records</span>
        </div>
        <?php if (!empty($maintenance_records)): ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Cost</th>
                <th>Performed By</th>
                <th>Next Due</th>
                <th>Logged By</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($maintenance_records as $record): ?>
              <tr>
                <td><?php echo date('M d, Y', strtotime($record->maintenance_date)); ?></td>
                <td><?php echo ucwords($record->maintenance_type); ?></td>
                <td><?php echo htmlspecialchars($record->description ?? '-'); ?></td>
                <td><?php echo $record->cost ? '$' . number_format($record->cost, 2) : 'N/A'; ?></td>
                <td><?php echo htmlspecialchars($record->performed_by ?? 'Internal'); ?></td>
                <td><?php echo $record->next_maintenance_date ? date('M d, Y', strtotime($record->next_maintenance_date)) : 'N/A'; ?></td>
                <td><?php echo htmlspecialchars(trim(($record->creator_name ?? '') . ' ' . ($record->creator_lastname ?? '')) ?: 'System'); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-tools"></i>
          <h3>No maintenance logged</h3>
          <p>Add a maintenance record to start the equipment history.</p>
        </div>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>
