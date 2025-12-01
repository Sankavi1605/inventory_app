<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($item->name); ?> | Inventory Details</title>
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
        <a href="<?php echo URLROOT; ?>/inventory" class="menu-item active"><i class="fas fa-box"></i> Inventory</a>
        <a href="<?php echo URLROOT; ?>/equipment" class="menu-item"><i class="fas fa-tools"></i> Equipment</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="top-header">
        <div class="top-header__left">
          <h1><?php echo htmlspecialchars($item->name); ?></h1>
          <p>SKU <?php echo htmlspecialchars($item->sku); ?> &middot; <?php echo htmlspecialchars($item->category_name ?? 'Uncategorized'); ?></p>
        </div>
        <div class="top-header__right">
          <a href="<?php echo URLROOT; ?>/inventory" class="ghost-btn">
            <i class="fas fa-arrow-left"></i> Back
          </a>
          <?php if (hasRole('admin')): ?>
          <a href="<?php echo URLROOT; ?>/inventory/edit/<?php echo $item->id; ?>" class="add-btn">
            <i class="fas fa-edit"></i> Edit
          </a>
          <?php endif; ?>
        </div>
      </header>

      <section class="content-section">
        <div class="details-grid">
          <article class="info-card">
            <h3>Stock Summary</h3>
            <ul>
              <li><strong>Available:</strong> <?php echo (int)$item->quantity_available; ?> <?php echo htmlspecialchars($item->unit); ?></li>
              <li><strong>Total:</strong> <?php echo (int)$item->quantity_total; ?> <?php echo htmlspecialchars($item->unit); ?></li>
              <li><strong>Minimum:</strong> <?php echo (int)$item->minimum_quantity; ?></li>
              <li><strong>Location:</strong> <?php echo htmlspecialchars($item->location ?? 'Not set'); ?></li>
            </ul>
          </article>
          <article class="info-card">
            <h3>Valuation & Procurement</h3>
            <ul>
              <li><strong>Unit Cost:</strong> <?php echo $item->unit_cost ? '$' . number_format($item->unit_cost, 2) : 'N/A'; ?></li>
              <li><strong>Supplier:</strong> <?php echo htmlspecialchars($item->supplier ?? 'N/A'); ?></li>
              <li><strong>Purchased:</strong> <?php echo $item->purchase_date ? date('M d, Y', strtotime($item->purchase_date)) : 'N/A'; ?></li>
              <li><strong>Warranty:</strong> <?php echo $item->warranty_expiry ? date('M d, Y', strtotime($item->warranty_expiry)) : 'N/A'; ?></li>
            </ul>
          </article>
          <article class="info-card full-width">
            <h3>Description</h3>
            <p><?php echo htmlspecialchars($item->description ?? 'No description provided.'); ?></p>
          </article>
        </div>
      </section>

      <section class="content-section">
        <div class="section-title">
          <h2>Recent Transactions</h2>
          <span>Last 50 movements recorded for this SKU.</span>
        </div>
        <?php if (!empty($transactions)): ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>From</th>
                <th>To</th>
                <th>Reason</th>
                <th>Performed By</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($transactions as $transaction): ?>
              <tr>
                <td><?php echo date('M d, Y H:i', strtotime($transaction->transaction_date)); ?></td>
                <td><?php echo ucwords($transaction->transaction_type); ?></td>
                <td><?php echo (int)$transaction->quantity; ?></td>
                <td><?php echo (int)$transaction->previous_quantity; ?></td>
                <td><?php echo (int)$transaction->new_quantity; ?></td>
                <td><?php echo htmlspecialchars($transaction->reason ?? '-'); ?></td>
                <td><?php echo htmlspecialchars(trim(($transaction->first_name ?? '') . ' ' . ($transaction->last_name ?? '')) ?: 'System'); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-receipt"></i>
          <h3>No transactions yet</h3>
          <p>Adjust inventory counts to start tracking movement history.</p>
        </div>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>
