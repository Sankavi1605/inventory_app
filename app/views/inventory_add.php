<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Inventory Item</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css?v=20241111">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/dashboard.css?v=20241111">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/form-styles.css?v=20241111">
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
      <header class="header">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 1.5rem; flex-wrap: wrap;">
          <div>
            <h1>Add Inventory Item</h1>
            <p class="muted">Capture SKU, quantities, valuation, and sourcing info for audit-ready records.</p>
          </div>
          <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/inventory" class="ghost-btn">
              <i class="fas fa-arrow-left"></i>
              Back to inventory
            </a>
          </div>
        </div>
      </header>

      <section class="content-section" style="padding: 2rem;">
        <?php flash('inventory_message'); ?>
        <div class="card" style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); padding: 2rem;">
          <form action="<?php echo URLROOT; ?>/inventory/add" method="POST" class="form-grid">
            <div class="form-group">
              <label for="name">Item Name <span class="required">*</span></label>
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
              <?php if (!empty($name_err)) : ?><small class="error-text"><?php echo htmlspecialchars($name_err); ?></small><?php endif; ?>
            </div>

            <div class="form-group">
              <label for="sku">SKU <span class="required">*</span></label>
              <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($sku ?? ''); ?>" required>
              <?php if (!empty($sku_err)) : ?><small class="error-text"><?php echo htmlspecialchars($sku_err); ?></small><?php endif; ?>
            </div>

            <div class="form-group full-width">
              <label for="description">Description</label>
              <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            </div>

            <div class="form-group">
              <label for="category_id">Category</label>
              <select id="category_id" name="category_id">
                <option value="">Select category</option>
                <?php foreach (($categories ?? []) as $category): ?>
                  <option value="<?php echo htmlspecialchars($category->id); ?>" <?php echo (($category_id ?? '') == $category->id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category->name); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="unit">Unit</label>
              <input type="text" id="unit" name="unit" value="<?php echo htmlspecialchars($unit ?? 'pieces'); ?>">
            </div>

            <div class="form-group">
              <label for="quantity_available">Quantity Available</label>
              <input type="number" min="0" id="quantity_available" name="quantity_available" value="<?php echo htmlspecialchars($quantity_available ?? 0); ?>">
              <?php if (!empty($quantity_err)) : ?><small class="error-text"><?php echo htmlspecialchars($quantity_err); ?></small><?php endif; ?>
            </div>

            <div class="form-group">
              <label for="quantity_total">Quantity Total</label>
              <input type="number" min="0" id="quantity_total" name="quantity_total" value="<?php echo htmlspecialchars($quantity_total ?? 0); ?>">
            </div>

            <div class="form-group">
              <label for="minimum_quantity">Minimum Quantity</label>
              <input type="number" min="0" id="minimum_quantity" name="minimum_quantity" value="<?php echo htmlspecialchars($minimum_quantity ?? 0); ?>">
            </div>

            <div class="form-group">
              <label for="unit_cost">Unit Cost</label>
              <input type="number" min="0" step="0.01" id="unit_cost" name="unit_cost" value="<?php echo htmlspecialchars($unit_cost ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="supplier">Supplier</label>
              <input type="text" id="supplier" name="supplier" value="<?php echo htmlspecialchars($supplier ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="location">Location</label>
              <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="purchase_date">Purchase Date</label>
              <input type="date" id="purchase_date" name="purchase_date" value="<?php echo htmlspecialchars($purchase_date ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="warranty_expiry">Warranty Expiry</label>
              <input type="date" id="warranty_expiry" name="warranty_expiry" value="<?php echo htmlspecialchars($warranty_expiry ?? ''); ?>">
            </div>

            <div class="form-actions full-width">
              <button type="submit" class="add-btn">
                <i class="fas fa-save"></i>
                Save Item
              </button>
              <a href="<?php echo URLROOT; ?>/inventory" class="ghost-btn">
                Cancel
              </a>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
