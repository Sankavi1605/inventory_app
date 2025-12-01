<?php
$pageTitle = 'Inventory Dashboard';
$activeNav = 'inventory';
$pageStyles = [URLROOT . '/public/css/inventory.css?v=' . time()];
$pageScripts = [URLROOT . '/public/js/inventory-page.js?v=20241115'];
require APPROOT . '/views/inc/components/dashboard_shell_start.php';
?>

<?php
  $inventoryItems = $items ?? [];
  $categoryList = $categories ?? [];
  $lowStockCount = 0;
  $totalInventoryValue = 0.0;

  foreach ($inventoryItems as $inventoryItem) {
    $availableQty = (int)($inventoryItem->quantity_available ?? 0);
    $minimumQty = (int)($inventoryItem->minimum_quantity ?? 0);
    $unitCost = (float)($inventoryItem->unit_cost ?? 0);

    if ($minimumQty > 0 && $availableQty <= $minimumQty) {
      $lowStockCount++;
    }

    if ($availableQty > 0 && $unitCost > 0) {
      $totalInventoryValue += $availableQty * $unitCost;
    }
  }

  $formattedInventoryValue = $totalInventoryValue > 0
  ? '$' . number_format($totalInventoryValue, 2)
  : '—';
  $inventoryCount = count($inventoryItems);
  $healthyItems = max($inventoryCount - $lowStockCount, 0);
  $healthyPercent = $inventoryCount > 0 ? round(($healthyItems / $inventoryCount) * 100) : 100;
  $categoryBreakdown = [];
  foreach ($inventoryItems as $inventoryItem) {
    $categoryName = $inventoryItem->category_name ?? 'Uncategorized';
    $categoryBreakdown[$categoryName] = ($categoryBreakdown[$categoryName] ?? 0) + 1;
  }
  arsort($categoryBreakdown);
  $topCategories = array_slice($categoryBreakdown, 0, 3, true);
?>

    <!-- Main Content -->
    <main class="main-content">
      <?php
        $pageHeading = 'Inventory Management';
        $pageSubheading = 'Oversee stock levels and category performance at a glance.';
        $searchPlaceholder = 'Search inventory...';
        $searchId = 'inventorySearch';
        require APPROOT . '/views/inc/components/page_header.php';
      ?>

      <section class="stats">
        <article class="stat-card">
          <span>Total Items</span>
          <strong class="operational"><?php echo count($inventoryItems); ?></strong>
          <small>Currently tracked assets</small>
        </article>
        <article class="stat-card">
          <span>Categories</span>
          <strong class="assigned"><?php echo count($categoryList); ?></strong>
          <small>Organized storage groups</small>
        </article>
        <article class="stat-card">
          <span>Low Stock</span>
          <strong class="maintenance"><?php echo $lowStockCount; ?></strong>
          <small>Items below minimum levels</small>
        </article>
        <article class="stat-card">
          <span>Total Value</span>
          <strong class="assigned"><?php echo $formattedInventoryValue; ?></strong>
          <small>Based on available quantity</small>
        </article>
      </section>

      <section class="priority-row">
        <article class="priority-card priority-card--success">
          <div class="priority-icon priority-icon--success">
            <i class="fas fa-shield-alt"></i>
          </div>
          <p>Healthy stock</p>
          <strong><?php echo $healthyItems; ?></strong>
          <span class="priority-meta"><?php echo $healthyPercent; ?>% of catalog</span>
        </article>
        <article class="priority-card priority-card--accent">
          <div class="priority-icon priority-icon--accent">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <p>Total inventory value</p>
          <strong><?php echo $formattedInventoryValue; ?></strong>
          <?php if (!empty($topCategories)): ?>
            <ul class="mini-list">
              <?php foreach ($topCategories as $categoryName => $categoryCount): ?>
                <li>
                  <span><?php echo htmlspecialchars($categoryName); ?></span>
                  <strong><?php echo $categoryCount; ?></strong>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <span class="priority-meta">Add items to unlock category insights</span>
          <?php endif; ?>
        </article>
        <article class="priority-card priority-card--alert">
          <div class="priority-icon priority-icon--alert">
            <i class="fas fa-exclamation-circle"></i>
          </div>
          <p>Low stock alerts</p>
          <strong><?php echo $lowStockCount; ?></strong>
          <span class="priority-meta"><?php echo count($categoryList); ?> categories monitored</span>
        </article>
      </section>

      <section class="content-section">
        <?php flash('inventory_message'); ?>

        <div class="section-toolbar">
          <div class="toolbar-left">
            <?php if (!empty($categoryList)): ?>
              <div class="select-group">
                <i class="fas fa-layer-group"></i>
                <select id="categoryFilter">
                  <option value="all">All categories</option>
                  <?php foreach ($categoryList as $category): ?>
                    <?php
                      $categoryName = $category->name ?? 'Uncategorized';
                      $categoryKey = strtolower(trim($categoryName));
                      $categorySlug = preg_replace('/[^a-z0-9]+/', '-', $categoryKey);
                      $categorySlug = trim($categorySlug, '-') ?: 'uncategorized';
                    ?>
                    <option value="<?php echo htmlspecialchars($categorySlug, ENT_QUOTES); ?>">
                      <?php echo htmlspecialchars($categoryName); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
            <div class="chip-group">
              <span class="chip chip-static">
                <i class="fas fa-heartbeat"></i>
                <?php echo $healthyPercent; ?>% healthy
              </span>
              <button class="chip" type="button" id="lowStockToggle">
                <i class="fas fa-exclamation-triangle"></i>
                Low Stock Only
              </button>
            </div>
          </div>
          <div class="toolbar-right">
            <button class="ghost-btn" type="button" onclick="window.print()">
              <i class="fas fa-file-export"></i>
              Export
            </button>
            <button class="add-btn" type="button" onclick="window.location.href='<?php echo URLROOT; ?>/inventory/add'">
              <i class="fas fa-plus"></i>
              Add Item
            </button>
          </div>
        </div>

        <?php if (!empty($inventoryItems)): ?>
          <div class="inventory-catalog" id="inventoryCatalog">
            <?php foreach ($inventoryItems as $item): ?>
              <?php
                $name = $item->name ?? 'Untitled Item';
                $description = $item->description ?? '';
                $sku = $item->sku ?? '';
                $available = (int)($item->quantity_available ?? 0);
                $totalQty = (int)($item->quantity_total ?? 0);
                $unit = trim($item->unit ?? '');
                $location = $item->location ?? '';
                $categoryName = $item->category_name ?? 'Uncategorized';
                $categoryKey = strtolower(trim($categoryName));
                $categorySlug = preg_replace('/[^a-z0-9]+/', '-', $categoryKey);
                $categorySlug = trim($categorySlug, '-') ?: 'uncategorized';
                $supplier = $item->supplier ?? '';
                $minimum = (int)($item->minimum_quantity ?? 0);
                $unitCost = (float)($item->unit_cost ?? 0);
                $itemValue = $unitCost > 0 ? $unitCost * max($available, 0) : 0;
                $valueLabel = $itemValue > 0 ? '$' . number_format($itemValue, 2) : '—';
                $isLowStock = $minimum > 0 && $available <= $minimum;
                $qtyBadgeClass = $isLowStock ? 'low' : 'healthy';
                $quantityLabel = $totalQty > 0 ? $available . ' / ' . $totalQty : (string)$available;
                if ($unit !== '') {
                  $quantityLabel .= ' ' . $unit;
                }
                $searchIndex = strtolower(trim(
                  ($name ?? '') . ' ' . $sku . ' ' . $categoryName . ' ' . $location . ' ' . $description . ' ' . $supplier
                ));
              ?>
              <article
                class="inventory-card"
                data-category="<?php echo htmlspecialchars($categorySlug, ENT_QUOTES); ?>"
                data-search="<?php echo htmlspecialchars($searchIndex, ENT_QUOTES); ?>"
                data-low-stock="<?php echo $isLowStock ? '1' : '0'; ?>"
              >
                <div class="inventory-card__header">
                  <div class="inventory-card__title">
                    <h3><?php echo htmlspecialchars($name); ?></h3>
                    <?php if (!empty($sku)): ?>
                      <div class="inventory-card__sku">SKU: <?php echo htmlspecialchars($sku); ?></div>
                    <?php endif; ?>
                  </div>
                  <span class="qty-badge <?php echo $qtyBadgeClass; ?>">
                    <i class="fas fa-box"></i>
                    <?php echo htmlspecialchars($quantityLabel); ?>
                  </span>
                </div>

                <?php if (!empty($description)): ?>
                  <p class="inventory-card__description"><?php echo htmlspecialchars($description); ?></p>
                <?php endif; ?>

                <div class="inventory-card__chips">
                  <span class="inventory-tag">
                    <i class="fas fa-layer-group"></i>
                    <?php echo htmlspecialchars($categoryName); ?>
                  </span>
                  <?php if (!empty($location)): ?>
                    <span class="inventory-location">
                      <i class="fas fa-map-marker-alt"></i>
                      <?php echo htmlspecialchars($location); ?>
                    </span>
                  <?php endif; ?>
                  <?php if (!empty($supplier)): ?>
                    <span class="inventory-meta">
                      <i class="fas fa-truck-loading"></i>
                      <?php echo htmlspecialchars($supplier); ?>
                    </span>
                  <?php endif; ?>
                </div>

                <div class="inventory-card__footer">
                  <div class="inventory-card__footer-group">
                    <span class="inventory-meta">
                      <i class="fas fa-dollar-sign"></i>
                      <?php echo htmlspecialchars($valueLabel); ?>
                    </span>
                    <?php if ($minimum > 0): ?>
                      <span class="inventory-meta">
                        <i class="fas fa-level-down-alt"></i>
                        Min <?php echo htmlspecialchars($minimum); ?>
                      </span>
                    <?php endif; ?>
                  </div>
                  <div class="inventory-actions">
                    <button class="ghost-btn" type="button" onclick="window.location.href='<?php echo URLROOT; ?>/inventory/show/<?php echo $item->id; ?>'">
                      <i class="fas fa-eye"></i>
                      View
                    </button>
                    <button class="ghost-btn" type="button" onclick="window.location.href='<?php echo URLROOT; ?>/inventory/edit/<?php echo $item->id; ?>'">
                      <i class="fas fa-edit"></i>
                      Edit
                    </button>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
          <div class="empty-state" id="inventoryEmptyState" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>No items match your filters</h3>
            <p>Try adjusting the search term or category filters to broaden your results.</p>
          </div>
        <?php else: ?>
          <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>No inventory items found</h3>
            <p>Add your first inventory item to get started.</p>
            <button class="add-btn" type="button" onclick="window.location.href='<?php echo URLROOT; ?>/inventory/add'">
              <i class="fas fa-plus"></i>
              Add First Item
            </button>
          </div>
        <?php endif; ?>
      </section>
    </main>

<?php
$afterContainerInclude = APPROOT . '/views/inc/components/footer.php';
require APPROOT . '/views/inc/components/dashboard_shell_end.php';
?>




