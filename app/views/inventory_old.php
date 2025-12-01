<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management | <?php echo SITENAME; ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <!-- Perfect Inventory Dashboard -->
    <div class="inventory-dashboard">
        <!-- Modern Sidebar -->
        <aside class="modern-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="<?php echo URLROOT; ?>/public/img/logo.png" alt="ConstructStock">
                    <span class="sidebar-title">ConstructStock</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="<?php echo URLROOT; ?>/index" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo URLROOT; ?>/inventory/inventory" class="nav-item active">
                    <i class="fas fa-box"></i>
                    <span>Inventory</span>
                </a>
                <a href="<?php echo URLROOT; ?>/inventory/equipment" class="nav-item">
                    <i class="fas fa-tools"></i>
                    <span>Equipment</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                        <a href="<?php echo URLROOT; ?>/auth/logout" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="auth-buttons">
                        <a href="<?php echo URLROOT; ?>/auth/login" class="auth-btn secondary">Login</a>
                        <a href="<?php echo URLROOT; ?>/auth/signup" class="auth-btn primary">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="content-header">
                <div class="header-top">
                    <h1 class="page-title">Inventory Management</h1>
                    <p class="page-subtitle">Manage and track your construction inventory efficiently</p>
                </div>

                <div class="header-actions">
                    <button class="btn-primary" onclick="openCreateModal()">
                        <i class="fas fa-plus"></i>
                        Add New Item
                    </button>
                    <button class="btn-secondary" onclick="exportInventory()">
                        <i class="fas fa-download"></i>
                        Export
                    </button>
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">1,247</div>
                        <div class="stat-label">Total Items</div>
                        <div class="stat-change positive">+12% from last month</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">8</div>
                        <div class="stat-label">Low Stock Items</div>
                        <div class="stat-change negative">Needs attention</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">92%</div>
                        <div class="stat-label">In Stock</div>
                        <div class="stat-change positive">Good availability</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">$125,430</div>
                        <div class="stat-label">Total Value</div>
                        <div class="stat-change positive">+8% increase</div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-left">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search items..." class="search-input">
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" onclick="toggleDropdown('categoryDropdown')">
                            <i class="fas fa-layer-group"></i>
                            Category
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="categoryDropdown" class="dropdown-content">
                            <label><input type="checkbox" value="all" checked> All Categories</label>
                            <label><input type="checkbox" value="Building Materials"> Building Materials</label>
                            <label><input type="checkbox" value="Equipment"> Equipment</label>
                            <label><input type="checkbox" value="Tools"> Tools</label>
                            <label><input type="checkbox" value="Safety"> Safety</label>
                        </div>
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" onclick="toggleDropdown('statusDropdown')">
                            <i class="fas fa-filter"></i>
                            Status
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="statusDropdown" class="dropdown-content">
                            <label><input type="checkbox" value="all" checked> All Status</label>
                            <label><input type="checkbox" value="in-stock"> In Stock</label>
                            <label><input type="checkbox" value="low-stock"> Low Stock</label>
                            <label><input type="checkbox" value="out-of-stock"> Out of Stock</label>
                        </div>
                    </div>
                </div>

                <div class="filters-right">
                    <button class="btn-outline" onclick="resetFilters()">
                        <i class="fas fa-redo"></i>
                        Reset Filters
                    </button>
                </div>
            </div>

            <!-- Modern Inventory Table -->
            <div class="table-container">
                <table class="inventory-table-modern">
                    <thead>
                        <tr>
                            <th>
                                <div class="th-content">
                                    Item Name
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    Category
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    Quantity
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    Unit Price
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    Status
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    Last Updated
                                    <i class="fas fa-sort sort-icon"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="item-info">
                                    <div class="item-icon">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">Cement Bags</div>
                                        <div class="item-sku">SKU: CEM-001</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge building">Building Materials</span>
                            </td>
                            <td>
                                <div class="quantity-info">
                                    <span class="quantity-number">245</span>
                                    <span class="quantity-unit">units</span>
                                </div>
                            </td>
                            <td>
                                <span class="price">$12.50</span>
                            </td>
                            <td>
                                <span class="status-badge success">In Stock</span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <span class="date">2 days ago</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn edit" onclick="editItem('Cement Bags')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteItem('Cement Bags')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="action-btn view" onclick="viewItem('Cement Bags')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="item-info">
                                    <div class="item-icon">
                                        <i class="fas fa-hammer"></i>
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">Steel Rods</div>
                                        <div class="item-sku">SKU: STL-045</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge building">Building Materials</span>
                            </td>
                            <td>
                                <div class="quantity-info">
                                    <span class="quantity-number warning">52</span>
                                    <span class="quantity-unit">units</span>
                                </div>
                            </td>
                            <td>
                                <span class="price">$45.80</span>
                            </td>
                            <td>
                                <span class="status-badge warning">Low Stock</span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <span class="date">1 week ago</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn edit" onclick="editItem('Steel Rods')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteItem('Steel Rods')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="action-btn view" onclick="viewItem('Steel Rods')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="item-info">
                                    <div class="item-icon">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">Power Drill</div>
                                        <div class="item-sku">SKU: PWR-012</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge equipment">Equipment</span>
                            </td>
                            <td>
                                <div class="quantity-info">
                                    <span class="quantity-number">12</span>
                                    <span class="quantity-unit">units</span>
                                </div>
                            </td>
                            <td>
                                <span class="price">$189.99</span>
                            </td>
                            <td>
                                <span class="status-badge success">In Stock</span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <span class="date">3 days ago</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn edit" onclick="editItem('Power Drill')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteItem('Power Drill')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="action-btn view" onclick="viewItem('Power Drill')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-info">
                    Showing 1-10 of 247 items
                </div>
                <div class="pagination-controls">
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <span class="page-ellipsis">...</span>
                    <button class="page-btn">25</button>
                    <button class="page-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Modern Edit Modal -->
    <div id="editModal" class="modern-modal">
        <div class="modal-overlay" onclick="closeEditModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Item</h2>
                <button class="modal-close" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="itemName">Item Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-box"></i>
                            <input type="text" id="itemName" name="itemName" placeholder="Enter item name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <div class="input-wrapper">
                            <i class="fas fa-layer-group"></i>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Building Materials">Building Materials</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Tools">Tools</option>
                                <option value="Safety">Safety</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <div class="input-wrapper">
                            <i class="fas fa-sort-numeric-up"></i>
                            <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="unitPrice">Unit Price</label>
                        <div class="input-wrapper">
                            <i class="fas fa-dollar-sign"></i>
                            <input type="number" id="unitPrice" name="unitPrice" placeholder="Enter unit price" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <div class="input-wrapper">
                        <i class="fas fa-align-left"></i>
                        <textarea id="description" name="description" placeholder="Enter item description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern Create Modal -->
    <div id="createModal" class="modern-modal">
        <div class="modal-overlay" onclick="closeCreateModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Item</h2>
                <button class="modal-close" onclick="closeCreateModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createForm" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="newItemName">Item Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-box"></i>
                            <input type="text" id="newItemName" name="newItemName" placeholder="Enter item name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newCategory">Category</label>
                        <div class="input-wrapper">
                            <i class="fas fa-layer-group"></i>
                            <select id="newCategory" name="newCategory" required>
                                <option value="">Select Category</option>
                                <option value="Building Materials">Building Materials</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Tools">Tools</option>
                                <option value="Safety">Safety</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="newQuantity">Quantity</label>
                        <div class="input-wrapper">
                            <i class="fas fa-sort-numeric-up"></i>
                            <input type="number" id="newQuantity" name="newQuantity" placeholder="Enter quantity" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="newUnitPrice">Unit Price</label>
                        <div class="input-wrapper">
                            <i class="fas fa-dollar-sign"></i>
                            <input type="number" id="newUnitPrice" name="newUnitPrice" placeholder="Enter unit price" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="newSku">SKU</label>
                    <div class="input-wrapper">
                        <i class="fas fa-barcode"></i>
                        <input type="text" id="newSku" name="newSku" placeholder="Enter SKU (optional)">
                    </div>
                </div>
                <div class="form-group">
                    <label for="newDescription">Description</label>
                    <div class="input-wrapper">
                        <i class="fas fa-align-left"></i>
                        <textarea id="newDescription" name="newDescription" placeholder="Enter item description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeCreateModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --info-color: #06b6d4;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --bg-dark: #1e293b;
            --border-color: #e5e7eb;
            --border-focus: #3b82f6;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        .inventory-dashboard {
            display: flex;
            height: 100vh;
            background: var(--bg-secondary);
        }

        /* Modern Sidebar */
        .modern-sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--bg-dark) 0%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-xl);
            z-index: 100;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo img {
            width: 32px;
            height: 32px;
            filter: brightness(0) invert(1);
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-item.active {
            background: rgba(59, 130, 246, 0.2);
            color: white;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        .logout-btn {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .auth-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .auth-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .auth-btn.primary {
            background: var(--primary-color);
            color: white;
        }

        .auth-btn.secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
        }

        .content-header {
            margin-bottom: 2rem;
        }

        .header-top {
            margin-bottom: 1rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: white;
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #f97316);
        }

        .stat-icon.success {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, var(--info-color), #0891b2);
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.75rem;
            font-weight: 600;
        }

        .stat-change.positive {
            color: var(--success-color);
        }

        .stat-change.negative {
            color: var(--warning-color);
        }

        /* Filters Section */
        .filters-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filters-left {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            color: var(--text-light);
            z-index: 1;
        }

        .search-input {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.875rem;
            width: 250px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-dropdown {
            position: relative;
        }

        .filter-btn {
            background: white;
            border: 2px solid var(--border-color);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            z-index: 1000;
            margin-top: 0.5rem;
            display: none;
        }

        .dropdown-content.show {
            display: block;
        }

        .dropdown-content label {
            display: block;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 0.875rem;
        }

        .dropdown-content label:hover {
            background: var(--bg-secondary);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .inventory-table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .inventory-table-modern th {
            background: var(--bg-tertiary);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
        }

        .th-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sort-icon {
            color: var(--text-light);
            font-size: 0.75rem;
            cursor: pointer;
        }

        .inventory-table-modern td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .inventory-table-modern tbody tr:hover {
            background: var(--bg-secondary);
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .item-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .item-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .item-sku {
            font-size: 0.75rem;
            color: var(--text-light);
        }

        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .category-badge.building {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .category-badge.equipment {
            background: rgba(139, 92, 246, 0.1);
            color: var(--secondary-color);
        }

        .quantity-number {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .quantity-number.warning {
            color: var(--warning-color);
        }

        .quantity-unit {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-left: 0.25rem;
        }

        .price {
            font-weight: 600;
            color: var(--text-primary);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .status-badge.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .date {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .action-btn.edit {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .action-btn.edit:hover {
            background: var(--primary-color);
            color: white;
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
        }

        .action-btn.delete:hover {
            background: var(--error-color);
            color: white;
        }

        .action-btn.view {
            background: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
        }

        .action-btn.view:hover {
            background: var(--info-color);
            color: white;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding: 1rem 0;
        }

        .pagination-info {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .pagination-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .page-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-primary);
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .page-btn:hover:not(:disabled) {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .page-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-ellipsis {
            color: var(--text-light);
            padding: 0 0.5rem;
        }

        /* Modal Styles */
        .modern-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }

        .modern-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-2xl);
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
        }

        .modal-form {
            padding: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            z-index: 1;
        }

        .input-wrapper input,
        .input-wrapper select,
        .input-wrapper textarea {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
        }

        .input-wrapper textarea {
            resize: vertical;
            min-height: 80px;
        }

        .input-wrapper input:focus,
        .input-wrapper select:focus,
        .input-wrapper textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .modern-sidebar {
                width: 240px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .inventory-dashboard {
                flex-direction: column;
            }

            .modern-sidebar {
                width: 100%;
                height: auto;
            }

            .sidebar-nav {
                display: flex;
                padding: 0.5rem;
                gap: 0.5rem;
            }

            .nav-item {
                flex: 1;
                justify-content: center;
                padding: 0.5rem;
            }

            .nav-item span {
                display: none;
            }

            .main-content {
                padding: 1rem;
            }

            .header-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>

    <script>
        // Dropdown Toggle
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const allDropdowns = document.querySelectorAll('.dropdown-content');

            allDropdowns.forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.remove('show');
                }
            });

            dropdown.classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.matches('.filter-btn')) {
                const dropdowns = document.querySelectorAll('.dropdown-content');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        // Modal Functions
        function openCreateModal() {
            document.getElementById('createModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        function editItem(itemName) {
            // Load item data into form
            document.getElementById('itemName').value = itemName;
            // Add more fields as needed
            document.getElementById('editModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function deleteItem(itemName) {
            if (confirm(`Are you sure you want to delete "${itemName}"?`)) {
                // Implement delete functionality
                console.log('Deleting item:', itemName);
            }
        }

        function viewItem(itemName) {
            // Implement view functionality
            console.log('Viewing item:', itemName);
        }

        function exportInventory() {
            // Implement export functionality
            console.log('Exporting inventory...');
        }

        function resetFilters() {
            // Reset all filters
            document.getElementById('searchInput').value = '';
            const checkboxes = document.querySelectorAll('.dropdown-content input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
        }

        // Form submission handlers
        document.getElementById('createForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Handle form submission
            console.log('Creating new item...');
            closeCreateModal();
        });

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Handle form submission
            console.log('Updating item...');
            closeEditModal();
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.inventory-table-modern tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
            }
        });
    </script>
</body>
</html>
    <?php require APPROOT . '/views/inc/components/footer.php'; ?>

    <style>
    .btn-login:hover {
      background: #007bff !important;
      color: #fff !important;
      transform: translateY(-1px);
    }
    .btn-signup:hover {
      background: #0056b3 !important;
      transform: translateY(-1px);
    }
    .logout-btn:hover {
      background: #e74c3c !important;
      color: #fff !important;
      transform: translateY(-1px);
    }
    .user-info {
      transition: all 0.3s ease;
    }
    .user-info:hover {
      background: #d1e9ff !important;
    }
    </style>
</body>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal related variables
    const modal = document.getElementById('editModal');
    const closeModal = document.querySelector('.close-modal');
    const editForm = document.getElementById('editForm');
    let currentRow;

    // Filter related variables
    const filterDropdowns = document.querySelectorAll('.filter-dropdown');
    const searchInput = document.querySelector('.search-input');
    let activeFilters = {
        categories: ['all'],
        status: ['all']
    };

    // Edit button functionality
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            currentRow = this.closest('tr');
            const cells = currentRow.cells;
            
            document.getElementById('itemName').value = cells[0].textContent;
            document.getElementById('category').value = cells[1].textContent;
            document.getElementById('quantity').value = cells[2].textContent.split(' ')[0];
            
            modal.style.display = 'block';
        });
    });


    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this item?')) {
                const row = this.closest('tr');
                row.remove();
            }
        });
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Edit form submission
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (currentRow) {
            currentRow.cells[0].textContent = document.getElementById('itemName').value;
            currentRow.cells[1].textContent = document.getElementById('category').value;
            currentRow.cells[2].textContent = document.getElementById('quantity').value + ' units';
        }
        
        modal.style.display = 'none';
    });

    // Filter Functionality
    filterDropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.filter-button');
        
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            filterDropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('active');
                }
            });
            dropdown.classList.toggle('active');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        filterDropdowns.forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });

    // Prevent dropdown from closing when clicking inside
    document.querySelectorAll('.dropdown-content').forEach(content => {
        content.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });

    // Handle filter changes
    function handleFilterChange() {
        const rows = document.querySelectorAll('.inventory-table tbody tr');
        const searchTerm = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const category = row.cells[1].textContent;
            const status = row.cells[3].textContent.toLowerCase().replace(/\s+/g, '-');
            const itemName = row.cells[0].textContent.toLowerCase();
            
            const matchesCategory = activeFilters.categories.includes('all') || 
                                  activeFilters.categories.includes(category);
            const matchesStatus = activeFilters.status.includes('all') || 
                                activeFilters.status.includes(status);
            const matchesSearch = itemName.includes(searchTerm);

            row.style.display = (matchesCategory && matchesStatus && matchesSearch) ? '' : 'none';
        });

        updateFilterButtonStyles();
    }

    // Handle checkbox changes
    document.querySelectorAll('.dropdown-content input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const filterType = this.closest('.filter-dropdown').querySelector('.filter-button').textContent.trim().toLowerCase();
            const value = this.value;
            const filterArray = filterType.includes('category') ? activeFilters.categories : activeFilters.status;

            if (value === 'all') {
                if (this.checked) {
                    const siblings = this.closest('.dropdown-content').querySelectorAll('input[type="checkbox"]');
                    siblings.forEach(sibling => {
                        if (sibling !== this) {
                            sibling.checked = false;
                        }
                    });
                    if (filterType.includes('category')) {
                        activeFilters.categories = ['all'];
                    } else {
                        activeFilters.status = ['all'];
                    }
                }
            } else {
                const allCheckbox = this.closest('.dropdown-content').querySelector('input[value="all"]');
                allCheckbox.checked = false;

                if (this.checked) {
                    const index = filterArray.indexOf(value);
                    if (index === -1) {
                        filterArray.push(value);
                        const allIndex = filterArray.indexOf('all');
                        if (allIndex !== -1) {
                            filterArray.splice(allIndex, 1);
                        }
                    }
                } else {
                    const index = filterArray.indexOf(value);
                    if (index !== -1) {
                        filterArray.splice(index, 1);
                    }
                }

                if (filterArray.length === 0) {
                    allCheckbox.checked = true;
                    filterArray.push('all');
                }
            }

            handleFilterChange();
        });
    });

    // Search functionality
    searchInput.addEventListener('input', handleFilterChange);

    function updateFilterButtonStyles() {
        const categoryButton = document.querySelector('.filter-dropdown:first-child .filter-button');
        const statusButton = document.querySelector('.filter-dropdown:nth-child(2) .filter-button');
        
        categoryButton.classList.toggle('has-active-filters', !activeFilters.categories.includes('all'));
        statusButton.classList.toggle('has-active-filters', !activeFilters.status.includes('all'));
    }

// Helper function to remove element from array
Array.prototype.remove = function(element) {
    const index = this.indexOf(element);
    if (index !== -1) {
        this.splice(index, 1);
    }
};
    });


    // Add Inventory functionality
const addButton = document.querySelector('.add-button');
const createModal = document.getElementById('createModal');
const closeModalCreate = document.querySelector('.close-modal-create');
const createForm = document.getElementById('createForm');

// Open create modal
addButton.addEventListener('click', () => {
    createModal.style.display = 'block';
});

// Close create modal
closeModalCreate.addEventListener('click', () => {
    createModal.style.display = 'none';
});

// Close create modal when clicking outside
window.addEventListener('click', (event) => {
    if (event.target === createModal) {
        createModal.style.display = 'none';
    }
});

// Handle create form submission
createForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const itemName = document.getElementById('newItemName').value;
    const category = document.getElementById('newCategory').value;
    const quantity = document.getElementById('newQuantity').value;
    
    // Create new row
    const newRow = document.createElement('tr');
    
    // Determine status based on quantity
    let status = 'In Stock';
    let statusClass = 'in-stock';
    if (quantity <= 0) {
        status = 'Out of Stock';
        statusClass = 'out-of-stock';
    } else if (quantity <= 20) {
        status = 'Low Stock';
        statusClass = 'low-stock';
    }
    
    // Set row content
    newRow.innerHTML = `
        <td>${itemName}</td>
        <td>${category}</td>
        <td>${quantity} units</td>
        <td class="${statusClass}">${status}</td>
        <td>
            <button class="edit-button">Edit</button>
            <button class="delete-button">Delete</button>
        </td>
    `;
    
    // Add event listeners to new buttons
    newRow.querySelector('.edit-button').addEventListener('click', function() {
        currentRow = this.closest('tr');
        const cells = currentRow.cells;
        
        document.getElementById('itemName').value = cells[0].textContent;
        document.getElementById('category').value = cells[1].textContent;
        document.getElementById('quantity').value = cells[2].textContent.split(' ')[0];
        
        modal.style.display = 'block';
    });
    
    newRow.querySelector('.delete-button').addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this item?')) {
            this.closest('tr').remove();
        }
    });
    
    // Add new row to table
    document.querySelector('.inventory-table tbody').appendChild(newRow);
    
    // Reset form and close modal
    createForm.reset();
    createModal.style.display = 'none';
});
</script>
</html>