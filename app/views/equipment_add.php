<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Equipment</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/dashboard.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/components/form-styles.css">
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="logo">
        <a href="<?php echo URLROOT; ?>" class="logo-image">
          <img class="logo-image" src="<?php echo URLROOT; ?>/public/img/logo.png" alt="Sameepa Logo">
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
      <header class="header">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 1.5rem; flex-wrap: wrap;">
          <div>
            <h1 style="margin: 0;">Add New Equipment</h1>
            <p style="margin: 0; color: #6b7280;">Capture full equipment details to keep your records accurate.</p>
          </div>
          <a href="<?php echo URLROOT; ?>/equipment" class="btn-login" style="border: 2px solid #007bff; border-radius: 8px; padding: 0.5rem 1rem; font-weight: 600; color: #007bff; text-decoration: none;">&larr; Back to list</a>
        </div>
      </header>

      <section class="content-section" style="padding: 2rem;">
        <?php flash('equipment_message'); ?>
        <div class="card" style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); padding: 2rem;">
          <form action="<?php echo URLROOT; ?>/equipment/add" method="POST" class="form-grid" style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
            <div class="form-group" style="grid-column: span 2;">
              <label for="name">Equipment Name <span style="color: #e74c3c;">*</span></label>
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" placeholder="e.g., CAT 320 Excavator" required>
              <?php if (!empty($name_err)) : ?>
                <small class="error-text"><?php echo htmlspecialchars($name_err); ?></small>
              <?php endif; ?>
            </div>

            <div class="form-group" style="grid-column: span 2;">
              <label for="description">Description</label>
              <textarea id="description" name="description" rows="3" placeholder="Short summary of the equipment"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
            </div>

            <div class="form-group">
              <label for="serial_number">Serial Number</label>
              <input type="text" id="serial_number" name="serial_number" value="<?php echo htmlspecialchars($serial_number ?? ''); ?>" placeholder="Unique serial or asset tag">
              <?php if (!empty($serial_number_err)) : ?>
                <small class="error-text"><?php echo htmlspecialchars($serial_number_err); ?></small>
              <?php endif; ?>
            </div>

            <div class="form-group">
              <label for="model">Model</label>
              <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($model ?? ''); ?>" placeholder="Model identifier">
            </div>

            <div class="form-group">
              <label for="manufacturer">Manufacturer</label>
              <input type="text" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($manufacturer ?? ''); ?>" placeholder="Brand or maker">
            </div>

            <div class="form-group">
              <label for="category">Category</label>
              <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($category ?? ''); ?>" placeholder="e.g., Heavy Machinery">
            </div>

            <div class="form-group">
              <label for="status">Status</label>
              <select id="status" name="status">
                <?php foreach (($status_options ?? ['operational']) as $option) : ?>
                  <option value="<?php echo htmlspecialchars($option); ?>" <?php echo (($status ?? '') === $option) ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', ' ', $option)); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="location">Location</label>
              <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location ?? ''); ?>" placeholder="Current storage or site">
            </div>

            <div class="form-group">
              <label for="purchase_date">Purchase Date</label>
              <input type="date" id="purchase_date" name="purchase_date" value="<?php echo htmlspecialchars($purchase_date ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="purchase_cost">Purchase Cost</label>
              <input type="number" step="0.01" id="purchase_cost" name="purchase_cost" value="<?php echo htmlspecialchars($purchase_cost ?? ''); ?>" placeholder="0.00">
            </div>

            <div class="form-group">
              <label for="warranty_expiry">Warranty Expiry</label>
              <input type="date" id="warranty_expiry" name="warranty_expiry" value="<?php echo htmlspecialchars($warranty_expiry ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="last_maintenance">Last Maintenance</label>
              <input type="date" id="last_maintenance" name="last_maintenance" value="<?php echo htmlspecialchars($last_maintenance ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="next_maintenance">Next Maintenance</label>
              <input type="date" id="next_maintenance" name="next_maintenance" value="<?php echo htmlspecialchars($next_maintenance ?? ''); ?>">
            </div>

            <div class="form-group">
              <label for="assigned_to">Assign To</label>
              <select id="assigned_to" name="assigned_to">
                <option value="">Unassigned</option>
                <?php foreach (($users ?? []) as $user) : ?>
                  <option value="<?php echo htmlspecialchars($user->id); ?>" <?php echo (($assigned_to ?? '') == $user->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-actions" style="grid-column: span 2; display: flex; gap: 1rem; flex-wrap: wrap;">
              <button type="submit" class="add-btn" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-save"></i> Save Equipment
              </button>
              <a href="<?php echo URLROOT; ?>/equipment" class="logout-btn" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                <i class="fas fa-times"></i> Cancel
              </a>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
