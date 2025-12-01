<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Equipment</title>
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
        <a href="<?php echo URLROOT; ?>/inventory" class="menu-item"><i class="fas fa-box"></i> Inventory</a>
        <a href="<?php echo URLROOT; ?>/equipment" class="menu-item active"><i class="fas fa-tools"></i> Equipment</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="header">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 1.5rem; flex-wrap: wrap;">
          <div>
            <h1>Update Equipment</h1>
            <p class="muted">Track lifecycle updates, maintenance schedules, and assignments.</p>
          </div>
          <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/equipment/show/<?php echo $equipment_item->id; ?>" class="ghost-btn">
              <i class="fas fa-eye"></i>
              View details
            </a>
            <a href="<?php echo URLROOT; ?>/equipment" class="ghost-btn">
              <i class="fas fa-arrow-left"></i>
              Back to list
            </a>
          </div>
        </div>
      </header>

      <section class="content-section" style="padding: 2rem;">
        <?php flash('equipment_message'); ?>
        <div class="card" style="background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); padding: 2rem;">
          <form action="<?php echo URLROOT; ?>/equipment/edit/<?php echo $equipment_item->id; ?>" method="POST" class="form-grid">
            <div class="form-group full-width">
              <label for="name">Equipment Name <span class="required">*</span></label>
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? $equipment_item->name); ?>" required>
              <?php if (!empty($name_err)) : ?><small class="error-text"><?php echo htmlspecialchars($name_err); ?></small><?php endif; ?>
            </div>

            <div class="form-group full-width">
              <label for="description">Description</label>
              <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($description ?? $equipment_item->description); ?></textarea>
            </div>

            <div class="form-group">
              <label for="serial_number">Serial Number</label>
              <input type="text" id="serial_number" name="serial_number" value="<?php echo htmlspecialchars($serial_number ?? $equipment_item->serial_number); ?>">
              <?php if (!empty($serial_number_err)) : ?><small class="error-text"><?php echo htmlspecialchars($serial_number_err); ?></small><?php endif; ?>
            </div>

            <div class="form-group">
              <label for="model">Model</label>
              <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($model ?? $equipment_item->model); ?>">
            </div>

            <div class="form-group">
              <label for="manufacturer">Manufacturer</label>
              <input type="text" id="manufacturer" name="manufacturer" value="<?php echo htmlspecialchars($manufacturer ?? $equipment_item->manufacturer); ?>">
            </div>

            <div class="form-group">
              <label for="category">Category</label>
              <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($category ?? $equipment_item->category); ?>">
            </div>

            <div class="form-group">
              <label for="status">Status</label>
              <select id="status" name="status">
                <?php foreach (['operational', 'maintenance', 'assigned', 'out_of_service', 'retired'] as $option): ?>
                  <option value="<?php echo $option; ?>" <?php echo (($status ?? $equipment_item->status) === $option) ? 'selected' : ''; ?>>
                    <?php echo ucwords(str_replace('_', ' ', $option)); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label for="location">Location</label>
              <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location ?? $equipment_item->location); ?>">
            </div>

            <div class="form-group">
              <label for="purchase_date">Purchase Date</label>
              <input type="date" id="purchase_date" name="purchase_date" value="<?php echo htmlspecialchars($purchase_date ?? $equipment_item->purchase_date); ?>">
            </div>

            <div class="form-group">
              <label for="purchase_cost">Purchase Cost</label>
              <input type="number" min="0" step="0.01" id="purchase_cost" name="purchase_cost" value="<?php echo htmlspecialchars($purchase_cost ?? $equipment_item->purchase_cost); ?>">
            </div>

            <div class="form-group">
              <label for="warranty_expiry">Warranty Expiry</label>
              <input type="date" id="warranty_expiry" name="warranty_expiry" value="<?php echo htmlspecialchars($warranty_expiry ?? $equipment_item->warranty_expiry); ?>">
            </div>

            <div class="form-group">
              <label for="last_maintenance">Last Maintenance</label>
              <input type="date" id="last_maintenance" name="last_maintenance" value="<?php echo htmlspecialchars($last_maintenance ?? $equipment_item->last_maintenance); ?>">
            </div>

            <div class="form-group">
              <label for="next_maintenance">Next Maintenance</label>
              <input type="date" id="next_maintenance" name="next_maintenance" value="<?php echo htmlspecialchars($next_maintenance ?? $equipment_item->next_maintenance); ?>">
            </div>

            <div class="form-group">
              <label for="assigned_to">Assign To</label>
              <select id="assigned_to" name="assigned_to">
                <option value="">Unassigned</option>
                <?php foreach (($users ?? []) as $user): ?>
                  <option value="<?php echo $user->id; ?>" <?php echo (($assigned_to ?? $equipment_item->assigned_to) == $user->id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-actions full-width">
              <button type="submit" class="add-btn">
                <i class="fas fa-save"></i>
                Update Equipment
              </button>
              <a href="<?php echo URLROOT; ?>/equipment" class="ghost-btn">Cancel</a>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
