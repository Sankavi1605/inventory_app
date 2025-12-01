<?php
$pageTitle = 'Dashboard Overview';
$activeNav = 'dashboard';
require APPROOT . '/views/inc/components/dashboard_shell_start.php';
?>

    <!-- Main Content -->
    <main class="main-content">
      <?php
        $pageHeading = 'Dashboard Overview';
        $pageSubheading = 'Track inventory health and team activity in real time.';
        $searchPlaceholder = 'Search inventory...';
        require APPROOT . '/views/inc/components/page_header.php';
      ?>

      <?php
        $stats = $stats ?? [];
        $assignedList = $assigned_equipment ?? [];
        $lowStockList = $low_stock_items ?? [];
        $maintenanceList = $maintenance_due ?? [];
        $lowStockTotal = is_countable($lowStockList) ? count($lowStockList) : 0;
        $maintenanceCount = is_countable($maintenanceList) ? count($maintenanceList) : 0;
        $assignedCount = is_countable($assignedList) ? count($assignedList) : 0;
        $totalItems = (int)($stats['total_items'] ?? 0);
        $healthyItems = max($totalItems - $lowStockTotal, 0);
        $healthyPercent = $totalItems > 0 ? round(($healthyItems / $totalItems) * 100) : 100;
      ?>
      <section class="stats">
        <article class="stat-card">
          <span>Total Items</span>
          <strong class="operational"><?php echo number_format($stats['total_items'] ?? 0); ?></strong>
          <small>Across all storage locations</small>
        </article>
        <article class="stat-card">
          <span>Low Stock</span>
          <strong class="maintenance"><?php echo number_format($stats['low_stock'] ?? 0); ?></strong>
          <small>Items needing replenishment</small>
        </article>
        <article class="stat-card">
          <span>Equipment Assigned</span>
          <strong class="out"><?php echo number_format($stats['equipment_assigned'] ?? 0); ?></strong>
          <small>Checked out to teams</small>
        </article>
        <article class="stat-card">
          <span>Maintenance Due</span>
          <strong class="assigned"><?php echo number_format($stats['maintenance_due'] ?? 0); ?></strong>
          <small>Upcoming service tasks</small>
        </article>
      </section>

      <section class="priority-row">
        <article class="priority-card priority-card--alert">
          <div class="priority-icon priority-icon--alert">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <p>Critical low stock</p>
          <strong><?php echo $lowStockTotal; ?></strong>
          <span class="priority-meta"><?php echo $healthyPercent; ?>% of catalog remains healthy</span>
        </article>
        <article class="priority-card priority-card--info">
          <div class="priority-icon priority-icon--info">
            <i class="fas fa-tools"></i>
          </div>
          <p>Maintenance window</p>
          <strong><?php echo $maintenanceCount; ?></strong>
          <span class="priority-meta"><?php echo $maintenanceCount > 0 ? 'Due next 14 days' : 'All equipment is up to date'; ?></span>
        </article>
        <article class="priority-card priority-card--success">
          <div class="priority-icon priority-icon--success">
            <i class="fas fa-user-check"></i>
          </div>
          <p>Active assignments</p>
          <strong><?php echo $assignedCount; ?></strong>
          <span class="priority-meta"><?php echo $assignedCount > 0 ? 'Checked out to teams' : 'All gear is back on site'; ?></span>
        </article>
      </section>

      <section class="content-section">
        <div class="section-toolbar">
          <div class="toolbar-left">
            <div class="section-title">
              <h2>Activity Snapshot</h2>
              <span>Latest movements and replenishment signals</span>
            </div>
          </div>
          <div class="toolbar-right">
            <button class="ghost-btn" type="button">
              <i class="fas fa-file-export"></i>
              Export Report
            </button>
            <button class="add-btn" type="button">
              <i class="fas fa-bell"></i>
              Create Alert
            </button>
          </div>
        </div>

        <div class="tile-grid">
          <article class="info-card">
            <h3>Recent Checkouts</h3>
            <small>Latest assigned equipment</small>
            <ul>
              <?php if (!empty($assigned_equipment)) : ?>
                <?php foreach ($assigned_equipment as $equipmentItem) : ?>
                  <li>
                    <strong><?php echo htmlspecialchars($equipmentItem->name); ?></strong>
                    <span>
                      <?php
                        $assignee = trim(($equipmentItem->assigned_first_name ?? '') . ' ' . ($equipmentItem->assigned_last_name ?? ''));
                        echo $assignee ? htmlspecialchars($assignee) : 'Unassigned';
                      ?>
                      &mdash; <?php echo htmlspecialchars($equipmentItem->location ?? 'Unknown location'); ?>
                    </span>
                  </li>
                <?php endforeach; ?>
              <?php else : ?>
                <li><strong>No recent assignments</strong><span>Equipment is currently available.</span></li>
              <?php endif; ?>
            </ul>
          </article>

          <article class="info-card info-card--alert">
            <h3>Low Stock Alerts</h3>
            <small>Monitor replenishment thresholds</small>
            <ul>
              <?php if (!empty($low_stock_items)) : ?>
                <?php foreach ($low_stock_items as $item) : ?>
                  <li>
                    <strong><?php echo htmlspecialchars($item->name); ?></strong>
                    <span><?php echo (int)$item->quantity_available; ?> remaining &mdash; reorder &lt; <?php echo (int)$item->minimum_quantity; ?></span>
                  </li>
                <?php endforeach; ?>
              <?php else : ?>
                <li><strong>Stock is healthy</strong><span>No items are below threshold.</span></li>
              <?php endif; ?>
            </ul>
          </article>

          <article class="info-card">
            <h3>Upcoming Maintenance</h3>
            <small>Due within the next 14 days</small>
            <ul>
              <?php if (!empty($maintenance_due)) : ?>
                <?php foreach ($maintenance_due as $due) : ?>
                  <li>
                    <strong><?php echo htmlspecialchars($due->name); ?></strong>
                    <span>
                      Due <?php echo $due->next_maintenance ? date('M d', strtotime($due->next_maintenance)) : 'N/A'; ?>
                      &mdash; <?php echo htmlspecialchars($due->location ?? 'Unknown location'); ?>
                    </span>
                  </li>
                <?php endforeach; ?>
              <?php else : ?>
                <li><strong>No upcoming work</strong><span>All equipment is up to date.</span></li>
              <?php endif; ?>
            </ul>
          </article>
        </div>
      </section>
    </main>

<?php
$afterContainerInclude = APPROOT . '/views/inc/components/footer.php';
require APPROOT . '/views/inc/components/dashboard_shell_end.php';
?>









