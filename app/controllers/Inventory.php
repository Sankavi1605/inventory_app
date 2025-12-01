<?php

class Inventory extends Controller
{
    private $inventoryModel;
    private $categoryModel;
    private $activityLogModel;

    public function __construct()
    {
        requireLogin();
        $this->inventoryModel = $this->model('InventoryItem');
        $this->categoryModel = $this->model('Category');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // Display inventory items
    public function index()
    {
        $items = $this->inventoryModel->getAllItems();
        $categories = $this->categoryModel->getAllCategories();

        $data = [
            'items' => $items,
            'categories' => $categories,
            'search' => '',
            'category_filter' => ''
        ];

        $this->view('inventory', $data);
    }

    // Add new inventory item
    public function add()
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'category_id' => trim($_POST['category_id']),
                'sku' => trim($_POST['sku']),
                'quantity_available' => trim($_POST['quantity_available']),
                'quantity_total' => trim($_POST['quantity_total']),
                'unit' => trim($_POST['unit']),
                'location' => trim($_POST['location']),
                'minimum_quantity' => trim($_POST['minimum_quantity']),
                'unit_cost' => trim($_POST['unit_cost']),
                'supplier' => trim($_POST['supplier']),
                'purchase_date' => trim($_POST['purchase_date']),
                'warranty_expiry' => trim($_POST['warranty_expiry']),
                'created_by' => getCurrentUserId(),
                'name_err' => '',
                'sku_err' => '',
                'quantity_err' => ''
            ];

            // Validate SKU
            if (empty($data['sku'])) {
                $data['sku_err'] = 'Please enter SKU';
            } elseif ($this->inventoryModel->getItemBySKU($data['sku'])) {
                $data['sku_err'] = 'SKU already exists';
            }

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter item name';
            }

            // Validate quantities
            if (!is_numeric($data['quantity_available']) || $data['quantity_available'] < 0) {
                $data['quantity_err'] = 'Please enter valid quantity';
            }

            if (!is_numeric($data['quantity_total']) || $data['quantity_total'] < $data['quantity_available']) {
                $data['quantity_err'] = 'Total quantity must be greater than or equal to available quantity';
            }

            // If no errors, add item
            if (empty($data['name_err']) && empty($data['sku_err']) && empty($data['quantity_err'])) {
                $newItemId = $this->inventoryModel->addItem($data);

                if ($newItemId) {
                    // Log activity
                    $logValues = $data;
                    unset($logValues['name_err'], $logValues['sku_err'], $logValues['quantity_err']);

                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'create_inventory_item',
                        'table_name' => 'inventory_items',
                        'record_id' => $newItemId,
                        'new_values' => $logValues,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('inventory_message', 'Inventory item added successfully', 'alert alert-success');
                    redirect('inventory/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $categories = $this->categoryModel->getAllCategories();
                $data['categories'] = $categories;
                $this->view('inventory_add', $data);
            }
        } else {
            $categories = $this->categoryModel->getAllCategories();

            $data = [
                'categories' => $categories,
                'name' => '',
                'description' => '',
                'category_id' => '',
                'sku' => '',
                'quantity_available' => '',
                'quantity_total' => '',
                'unit' => 'pieces',
                'location' => '',
                'minimum_quantity' => '',
                'unit_cost' => '',
                'supplier' => '',
                'purchase_date' => '',
                'warranty_expiry' => '',
                'name_err' => '',
                'sku_err' => '',
                'quantity_err' => ''
            ];

            $this->view('inventory_add', $data);
        }
    }

    // Edit inventory item
    public function edit($id)
    {
        requireRole('admin');

        $item = $this->inventoryModel->getItemById($id);

        if (!$item) {
            flash('inventory_message', 'Item not found', 'alert alert-danger');
            redirect('inventory/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'category_id' => trim($_POST['category_id']),
                'quantity_available' => trim($_POST['quantity_available']),
                'quantity_total' => trim($_POST['quantity_total']),
                'unit' => trim($_POST['unit']),
                'location' => trim($_POST['location']),
                'minimum_quantity' => trim($_POST['minimum_quantity']),
                'unit_cost' => trim($_POST['unit_cost']),
                'supplier' => trim($_POST['supplier']),
                'purchase_date' => trim($_POST['purchase_date']),
                'warranty_expiry' => trim($_POST['warranty_expiry']),
                'name_err' => '',
                'quantity_err' => ''
            ];

            // Store old values for logging
            $oldValues = [
                'name' => $item->name,
                'description' => $item->description,
                'quantity_available' => $item->quantity_available,
                'location' => $item->location
            ];

            // Validate
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter item name';
            }

            if (!is_numeric($data['quantity_available']) || $data['quantity_available'] < 0) {
                $data['quantity_err'] = 'Please enter valid quantity';
            }

            if (!is_numeric($data['quantity_total']) || $data['quantity_total'] < $data['quantity_available']) {
                $data['quantity_err'] = 'Total quantity must be greater than or equal to available quantity';
            }

            // If no errors, update item
            if (empty($data['name_err']) && empty($data['quantity_err'])) {
                if ($this->inventoryModel->updateItem($data)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'update_inventory_item',
                        'table_name' => 'inventory_items',
                        'record_id' => $id,
                        'old_values' => $oldValues,
                        'new_values' => $data,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('inventory_message', 'Inventory item updated successfully', 'alert alert-success');
                    redirect('inventory/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $categories = $this->categoryModel->getAllCategories();
                $data['categories'] = $categories;
                $data['item'] = $item;
                $this->view('inventory_edit', $data);
            }
        } else {
            $categories = $this->categoryModel->getAllCategories();

            $data = [
                'categories' => $categories,
                'item' => $item,
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'category_id' => $item->category_id,
                'sku' => $item->sku,
                'quantity_available' => $item->quantity_available,
                'quantity_total' => $item->quantity_total,
                'unit' => $item->unit,
                'location' => $item->location,
                'minimum_quantity' => $item->minimum_quantity,
                'unit_cost' => $item->unit_cost,
                'supplier' => $item->supplier,
                'purchase_date' => $item->purchase_date,
                'warranty_expiry' => $item->warranty_expiry,
                'name_err' => '',
                'quantity_err' => ''
            ];

            $this->view('inventory_edit', $data);
        }
    }

    // Delete inventory item
    public function delete($id)
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item = $this->inventoryModel->getItemById($id);

            if ($item) {
                // Log activity before deletion
                $logData = [
                    'user_id' => getCurrentUserId(),
                    'action' => 'delete_inventory_item',
                    'table_name' => 'inventory_items',
                    'record_id' => $id,
                    'old_values' => $item,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ];
                $this->activityLogModel->logActivity($logData);

                if ($this->inventoryModel->deleteItem($id)) {
                    flash('inventory_message', 'Inventory item removed successfully', 'alert alert-success');
                } else {
                    flash('inventory_message', 'Failed to remove item', 'alert alert-danger');
                }
            } else {
                flash('inventory_message', 'Item not found', 'alert alert-danger');
            }
        }

        redirect('inventory/index');
    }

    // Update item quantity (transaction)
    public function updateQuantity($id)
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $item = $this->inventoryModel->getItemById($id);
            if (!$item) {
                flash('inventory_message', 'Item not found', 'alert alert-danger');
                redirect('inventory/index');
            }

            $transactionType = trim($_POST['transaction_type']);
            $quantity = trim($_POST['quantity']);
            $reason = trim($_POST['reason']);
            $notes = trim($_POST['notes']);

            if (!is_numeric($quantity) || $quantity <= 0) {
                flash('inventory_message', 'Please enter valid quantity', 'alert alert-danger');
                redirect('inventory/index');
            }

            $previousQuantity = $item->quantity_available;
            $newQuantity = $previousQuantity;

            switch ($transactionType) {
                case 'in':
                    $newQuantity += $quantity;
                    break;
                case 'out':
                    $newQuantity -= $quantity;
                    if ($newQuantity < 0) {
                        flash('inventory_message', 'Insufficient quantity available', 'alert alert-danger');
                        redirect('inventory/index');
                    }
                    break;
                case 'adjustment':
                    $newQuantity = $quantity;
                    break;
            }

            if ($this->inventoryModel->updateQuantity($id, $newQuantity, $previousQuantity, $transactionType, getCurrentUserId(), $reason, $notes)) {
                // Log activity
                $logData = [
                    'user_id' => getCurrentUserId(),
                    'action' => 'inventory_transaction',
                    'table_name' => 'inventory_transactions',
                    'record_id' => $id,
                    'new_values' => [
                        'transaction_type' => $transactionType,
                        'quantity' => $quantity,
                        'previous_quantity' => $previousQuantity,
                        'new_quantity' => $newQuantity,
                        'reason' => $reason
                    ],
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ];
                $this->activityLogModel->logActivity($logData);

                flash('inventory_message', 'Inventory updated successfully', 'alert alert-success');
            } else {
                flash('inventory_message', 'Failed to update inventory', 'alert alert-danger');
            }
        }

        redirect('inventory/index');
    }

    // Search inventory
    public function search()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $searchTerm = trim($_POST['search']);
            $categoryFilter = trim($_POST['category_filter']);

            if ($searchTerm) {
                $items = $this->inventoryModel->searchItems($searchTerm);
            } else {
                $items = $this->inventoryModel->getAllItems();
            }

            if ($categoryFilter) {
                $items = $this->inventoryModel->getItemsByCategory($categoryFilter);
            }

            $categories = $this->categoryModel->getAllCategories();

            $data = [
                'items' => $items,
                'categories' => $categories,
                'search' => $searchTerm,
                'category_filter' => $categoryFilter
            ];

            $this->view('inventory', $data);
        } else {
            redirect('inventory/index');
        }
    }

    // View item details
    public function show($id)
    {
        $item = $this->inventoryModel->getItemById($id);
        $transactions = $this->inventoryModel->getItemTransactions($id);

        if (!$item) {
            flash('inventory_message', 'Item not found', 'alert alert-danger');
            redirect('inventory/index');
        }

        $data = [
            'item' => $item,
            'transactions' => $transactions
        ];

        $this->view('inventory_details', $data);
    }

    // Get low stock items (AJAX)
    public function lowStock()
    {
        requireRole('admin');
        $items = $this->inventoryModel->getLowStockItems();
        echo json_encode($items);
    }
}
