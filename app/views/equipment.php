<?php
$pageTitle = 'Equipment Dashboard';
$activeNav = 'equipment';
$pageStyles = [URLROOT . '/public/css/equipment.css?v=' . time()];
$pageScripts = [URLROOT . '/public/js/equipment-page.js?v=20241115'];
require APPROOT . '/views/inc/components/dashboard_shell_start.php';
?>

    <!-- Main Content -->
    <main class="main-content">
      <?php
        $pageHeading = 'Equipment Management';
        $pageSubheading = 'Monitor maintenance schedules and assignments in one place.';
        $searchPlaceholder = 'Search equipment...';
        $searchId = 'equipmentHeaderSearch';
        require APPROOT . '/views/inc/components/page_header.php';
      ?>

<?php
  $equipmentList = $equipment ?? [];
  $statusGroups = [
    'operational' => 0,
    'maintenance' => 0,
    'assigned' => 0,
    'out_of_service' => 0,
    'retired' => 0,
    'other' => 0
  ];

  foreach ($equipmentList as $eq) {
      $statusKey = strtolower($eq->status ?? '');
      if (array_key_exists($statusKey, $statusGroups)) {
          $statusGroups[$statusKey]++;
      } else {
          $statusGroups['other']++;
      }
  }

  $totalEquipment = array_sum($statusGroups);
    $operationalPercent = $totalEquipment > 0 ? round(($statusGroups['operational'] / $totalEquipment) * 100) : 0;
    $outPercent = $totalEquipment > 0 ? round((($statusGroups['out_of_service'] + $statusGroups['maintenance']) / $totalEquipment) * 100) : 0;
?>

      <section class="stats">
        <article class="stat-card">
          <span>Total Equipment</span>
          <strong class="assigned"><?php echo $totalEquipment; ?></strong>
          <small>Tracked across all statuses</small>
        </article>
        <article class="stat-card">
          <span>Operational</span>
          <strong class="operational"><?php echo $statusGroups['operational']; ?></strong>
          <small>Ready for deployment</small>
        </article>
        <article class="stat-card">
          <span>Maintenance</span>
          <strong class="maintenance"><?php echo $statusGroups['maintenance']; ?></strong>
          <small>Currently undergoing service</small>
        </article>
        <article class="stat-card">
          <span>Assigned</span>
          <strong class="accent"><?php echo $statusGroups['assigned']; ?></strong>
          <small>Allocated to team members</small>
        </article>
        <article class="stat-card">
          <span>Out of Service</span>
          <strong class="out"><?php echo $statusGroups['out_of_service']; ?></strong>
          <small>Temporarily unavailable</small>
        </article>
      </section>

      <section class="priority-row">
        <article class="priority-card priority-card--success">
          <div class="priority-icon priority-icon--success">
            <i class="fas fa-hard-hat"></i>
          </div>
          <p>Operational coverage</p>
          <strong><?php echo $statusGroups['operational']; ?></strong>
          <span class="priority-meta"><?php echo $operationalPercent; ?>% of fleet ready</span>
        </article>
        <article class="priority-card priority-card--warning">
          <div class="priority-icon priority-icon--warning">
            <i class="fas fa-wrench"></i>
          </div>
          <p>In service queue</p>
          <strong><?php echo $statusGroups['maintenance']; ?></strong>
          <span class="priority-meta"><?php echo $outPercent; ?>% awaiting maintenance/out</span>
        </article>
        <article class="priority-card priority-card--info">
          <div class="priority-icon priority-icon--info">
            <i class="fas fa-user-cog"></i>
          </div>
          <p>Active assignments</p>
          <strong><?php echo $statusGroups['assigned']; ?></strong>
          <span class="priority-meta"><?php echo $statusGroups['out_of_service']; ?> temporarily offline</span>
        </article>
      </section>

      <section class="content-section">
        <?php flash('equipment_message'); ?>

        <div class="section-toolbar">
          <div class="toolbar-left">
            <div class="select-group">
              <i class="fas fa-filter"></i>
              <select id="statusFilter">
                <option value="all">All statuses</option>
                <option value="operational">Operational</option>
                <option value="maintenance">Maintenance</option>
                <option value="assigned">Assigned</option>
                <option value="out_of_service">Out of Service</option>
                <option value="retired">Retired</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="chip-group">
              <span class="chip chip-static">
                <i class="fas fa-tachometer-alt"></i>
                <?php echo $operationalPercent; ?>% ready
              </span>
            </div>
          </div>
          <div class="toolbar-right">
            <button class="ghost-btn" onclick="window.print()">
              <i class="fas fa-print"></i>
              Export
            </button>
            <button class="add-btn" onclick="window.location.href='<?php echo URLROOT; ?>/equipment/add'">
              <i class="fas fa-plus"></i>
              Add Equipment
            </button>
          </div>
        </div>

  <div class="equipment-list" id="equipmentList">
          <?php if (!empty($equipmentList)): ?>
            <?php foreach ($equipmentList as $item): ?>
              <?php
                $statusSlug = strtolower($item->status ?? 'other');
                $statusLabel = ucwords(str_replace('_', ' ', $item->status ?? 'Unknown'));
              ?>
              <article class="equipment-card" data-status="<?php echo htmlspecialchars($statusSlug); ?>" data-search="<?php echo htmlspecialchars(strtolower($item->name . ' ' . ($item->serial_number ?? '') . ' ' . ($item->location ?? '') . ' ' . ($item->description ?? '')), ENT_QUOTES); ?>">
                <div class="equipment-card__header">
                  <div class="equipment-card__identity">
                    <span class="status-pill status-<?php echo htmlspecialchars($statusSlug); ?>"><?php echo htmlspecialchars($statusLabel); ?></span>
                    <h3><?php echo htmlspecialchars($item->name); ?></h3>
                    <?php if (!empty($item->serial_number)): ?>
                      <span class="serial">#<?php echo htmlspecialchars($item->serial_number); ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="equipment-card__meta">
                    <span class="meta-item">
                      <i class="fas fa-map-marker-alt"></i>
                      <?php echo htmlspecialchars($item->location ?? 'Unknown location'); ?>
                    </span>
                    <?php if (!empty($item->category)): ?>
                      <span class="meta-item">
                        <i class="fas fa-layer-group"></i>
                        <?php echo htmlspecialchars($item->category); ?>
                      </span>
                    <?php endif; ?>
                    <?php if (!empty($item->assigned_first_name)): ?>
                      <span class="meta-item">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($item->assigned_first_name . ' ' . $item->assigned_last_name); ?>
                      </span>
                    <?php endif; ?>
                  </div>
                </div>

                <?php if (!empty($item->description)): ?>
                  <p class="equipment-card__description"><?php echo htmlspecialchars($item->description); ?></p>
                <?php endif; ?>

                <div class="equipment-card__footer">
                  <div class="date-stack">
                    <?php if (!empty($item->next_maintenance)): ?>
                      <span class="date-item">
                        <i class="fas fa-tools"></i>
                        Next maintenance: <?php echo htmlspecialchars(date('M d, Y', strtotime($item->next_maintenance))); ?>
                      </span>
                    <?php endif; ?>
                    <?php if (!empty($item->purchase_date)): ?>
                      <span class="date-item muted">
                        <i class="fas fa-receipt"></i>
                        Purchased: <?php echo htmlspecialchars(date('M d, Y', strtotime($item->purchase_date))); ?>
                      </span>
                    <?php endif; ?>
                  </div>

                  <div class="card-actions">
                    <button class="ghost-btn" onclick="window.location.href='<?php echo URLROOT; ?>/equipment/show/<?php echo $item->id; ?>'">
                      <i class="fas fa-eye"></i>
                      View
                    </button>
                    <button class="ghost-btn" onclick="window.location.href='<?php echo URLROOT; ?>/equipment/edit/<?php echo $item->id; ?>'">
                      <i class="fas fa-edit"></i>
                      Edit
                    </button>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">
              <i class="fas fa-tools"></i>
              <h3>No equipment found</h3>
              <p>Add your first equipment item to get started.</p>
              <button class="add-btn" onclick="window.location.href='<?php echo URLROOT; ?>/equipment/add'">
                <i class="fas fa-plus"></i>
                Add Equipment
              </button>
            </div>
          <?php endif; ?>
        </div>
        <div class="empty-state" id="equipmentEmptyState" style="display: none;">
          <i class="fas fa-search"></i>
          <h3>No equipment matches your filters</h3>
          <p>Adjust the search term or status filter to broaden results.</p>
        </div>
      </section>
    </main>
<?php
$afterContainerInclude = APPROOT . '/views/inc/components/footer.php';
require APPROOT . '/views/inc/components/dashboard_shell_end.php';
?>
