<?php

class Api extends Controller
{
    private $inventoryModel;
    private $equipmentModel;
    private $userModel;
    private $alertModel;
    private $activityLogModel;
    private $categoryModel;

    public function __construct()
    {
        requireLogin();
        $this->inventoryModel = $this->model('InventoryItem');
    $this->equipmentModel = $this->model('EquipmentModel');
        $this->userModel = $this->model('User');
        $this->alertModel = $this->model('Alert');
        $this->activityLogModel = $this->model('ActivityLog');
        $this->categoryModel = $this->model('Category');

        header('Content-Type: application/json');
    }

    // Get inventory statistics
    public function getInventoryStats()
    {
        requireRole('admin');

        $totalItems = $this->inventoryModel->getAllItems();
        $lowStockItems = $this->inventoryModel->getLowStockItems();

        $stats = [
            'total_items' => count($totalItems),
            'low_stock_items' => count($lowStockItems),
            'low_stock_details' => $lowStockItems
        ];

        echo json_encode($stats);
    }

    // Get equipment statistics
    public function getEquipmentStats()
    {
        requireRole('admin');

        $totalEquipment = $this->equipmentModel->getAllEquipment();
        $maintenanceDue = $this->equipmentModel->getEquipmentDueForMaintenance();

        $statusCounts = [
            'operational' => 0,
            'maintenance' => 0,
            'repair' => 0,
            'retired' => 0
        ];

        foreach ($totalEquipment as $equipment) {
            $statusCounts[$equipment->status]++;
        }

        $stats = [
            'total_equipment' => count($totalEquipment),
            'maintenance_due' => count($maintenanceDue),
            'maintenance_due_details' => $maintenanceDue,
            'status_counts' => $statusCounts
        ];

        echo json_encode($stats);
    }

    // Get user statistics
    public function getUserStats()
    {
        requireRole('admin');

        $users = $this->userModel->getAllUsers();
        $total = count($users);
        $stats = [
            'total_users' => $total,
            'active_users' => $total,
            'inactive_users' => 0,
            'role_counts' => [
                'superadmin' => 0,
                'admin' => $total,
                'security' => 0,
                'maintenance' => 0,
                'resident' => 0,
                'external' => 0
            ]
        ];

        echo json_encode($stats);
    }

    // Get recent activity
    public function getRecentActivity($limit = 10)
    {
        requireRole('admin');

        $activities = $this->activityLogModel->getActivityLogs($limit);
        echo json_encode($activities);
    }

    // Get alerts for current user
    public function getAlerts()
    {
        $userId = getCurrentUserId();
        $userRole = getCurrentUserRole();
        $alerts = $this->alertModel->getUserAlerts($userId, $userRole);
        echo json_encode($alerts);
    }

    // Mark alert as read
    public function markAlertRead()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $alertId = $data['alert_id'] ?? 0;
            $userId = getCurrentUserId();

            if ($this->alertModel->markAsRead($alertId, $userId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to mark alert as read']);
            }
        }
    }

    // Get inventory item details
    public function getInventoryItem($id)
    {
        $item = $this->inventoryModel->getItemById($id);

        if ($item) {
            $transactions = $this->inventoryModel->getItemTransactions($id, 20);
            echo json_encode([
                'item' => $item,
                'transactions' => $transactions
            ]);
        } else {
            echo json_encode(['error' => 'Item not found']);
        }
    }

    // Get equipment details
    public function getEquipment($id)
    {
        $equipment = $this->equipmentModel->getEquipmentById($id);

        if ($equipment) {
            $maintenanceRecords = $this->equipmentModel->getMaintenanceRecords($id);
            echo json_encode([
                'equipment' => $equipment,
                'maintenance_records' => $maintenanceRecords
            ]);
        } else {
            echo json_encode(['error' => 'Equipment not found']);
        }
    }

    // Quick inventory update
    public function quickInventoryUpdate()
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $itemId = $data['item_id'] ?? 0;
            $quantity = $data['quantity'] ?? 0;
            $transactionType = $data['transaction_type'] ?? 'adjustment';
            $reason = $data['reason'] ?? 'Quick update';

            $item = $this->inventoryModel->getItemById($itemId);
            if (!$item) {
                echo json_encode(['success' => false, 'message' => 'Item not found']);
                return;
            }

            if ($this->inventoryModel->updateQuantity($itemId, $quantity, $item->quantity_available, $transactionType, getCurrentUserId(), $reason)) {
                echo json_encode(['success' => true, 'message' => 'Inventory updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update inventory']);
            }
        }
    }

    // Quick equipment status update
    public function quickEquipmentStatusUpdate()
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $equipmentId = $data['equipment_id'] ?? 0;
            $status = $data['status'] ?? 'operational';

            if ($this->equipmentModel->updateStatus($equipmentId, $status)) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        }
    }

    // Search inventory items
    public function searchInventory()
    {
        $searchTerm = $_GET['q'] ?? '';
        $categoryId = $_GET['category'] ?? '';

        if ($searchTerm) {
            $items = $this->inventoryModel->searchItems($searchTerm);
        } elseif ($categoryId) {
            $items = $this->inventoryModel->getItemsByCategory($categoryId);
        } else {
            $items = $this->inventoryModel->getAllItems();
        }

        echo json_encode($items);
    }

    // Search equipment
    public function searchEquipment()
    {
        $searchTerm = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';

        if ($searchTerm) {
            $equipment = $this->equipmentModel->searchEquipment($searchTerm);
        } elseif ($status) {
            $equipment = $this->equipmentModel->getEquipmentByStatus($status);
        } else {
            $equipment = $this->equipmentModel->getAllEquipment();
        }

        echo json_encode($equipment);
    }

    // Get categories
    public function getCategories()
    {
        $categories = $this->categoryModel->getAllCategories();
        echo json_encode($categories);
    }

    // Get users for assignment
    public function getUsers()
    {
        requireRole('admin');
        $users = $this->userModel->getAllUsers();
        echo json_encode($users);
    }

    // Export inventory data
    public function exportInventory()
    {
        requireRole('admin');

        $items = $this->inventoryModel->getAllItems();
        $csv = "Name,SKU,Category,Available Quantity,Total Quantity,Location,Status,Unit Cost\n";

        foreach ($items as $item) {
            $csv .= "\"{$item->name}\",\"{$item->sku}\",\"{$item->category_name}\",{$item->quantity_available},{$item->quantity_total},\"{$item->location}\",\"{$item->status}\",{$item->unit_cost}\n";
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="inventory_export_' . date('Y-m-d') . '.csv"');
        echo $csv;
    }

    // Export equipment data
    public function exportEquipment()
    {
        requireRole('admin');

        $equipment = $this->equipmentModel->getAllEquipment();
        $csv = "Name,Serial Number,Model,Manufacturer,Category,Status,Location,Assigned To\n";

        foreach ($equipment as $eq) {
            $assignedTo = $eq->assigned_first_name ? $eq->assigned_first_name . ' ' . $eq->assigned_last_name : 'Unassigned';
            $csv .= "\"{$eq->name}\",\"{$eq->serial_number}\",\"{$eq->model}\",\"{$eq->manufacturer}\",\"{$eq->category}\",\"{$eq->status}\",\"{$eq->location}\",\"{$assignedTo}\"\n";
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="equipment_export_' . date('Y-m-d') . '.csv"');
        echo $csv;
    }

    // Generate low stock alerts
    public function generateLowStockAlerts()
    {
        requireRole('admin');
        $this->alertModel->createLowStockAlerts();
        echo json_encode(['success' => true, 'message' => 'Low stock alerts generated']);
    }

    // Generate maintenance alerts
    public function generateMaintenanceAlerts()
    {
        requireRole('admin');
        $this->alertModel->createMaintenanceAlerts();
        echo json_encode(['success' => true, 'message' => 'Maintenance alerts generated']);
    }

    // Get dashboard data
    public function getDashboardData()
    {
        requireRole('admin');

        // Get all statistics
        $inventoryStats = $this->getInventoryStatsData();
        $equipmentStats = $this->getEquipmentStatsData();
        $userStats = $this->getUserStatsData();
        $recentActivity = $this->activityLogModel->getActivityLogs(5);
        $alerts = $this->alertModel->getUserAlerts(getCurrentUserId(), getCurrentUserRole());

        $dashboardData = [
            'inventory' => $inventoryStats,
            'equipment' => $equipmentStats,
            'users' => $userStats,
            'recent_activity' => $recentActivity,
            'alerts' => array_slice($alerts, 0, 5) // Show only 5 most recent alerts
        ];

        echo json_encode($dashboardData);
    }

    // Helper method to get inventory stats
    private function getInventoryStatsData()
    {
        $totalItems = $this->inventoryModel->getAllItems();
        $lowStockItems = $this->inventoryModel->getLowStockItems();

        return [
            'total_items' => count($totalItems),
            'low_stock_items' => count($lowStockItems),
            'low_stock_details' => array_slice($lowStockItems, 0, 5)
        ];
    }

    // Helper method to get equipment stats
    private function getEquipmentStatsData()
    {
        $totalEquipment = $this->equipmentModel->getAllEquipment();
        $maintenanceDue = $this->equipmentModel->getEquipmentDueForMaintenance();

        $statusCounts = [
            'operational' => 0,
            'maintenance' => 0,
            'repair' => 0,
            'retired' => 0
        ];

        foreach ($totalEquipment as $equipment) {
            $statusCounts[$equipment->status]++;
        }

        return [
            'total_equipment' => count($totalEquipment),
            'maintenance_due' => count($maintenanceDue),
            'status_counts' => $statusCounts
        ];
    }

    // Helper method to get user stats
    private function getUserStatsData()
    {
        $users = $this->userModel->getAllUsers();
        $total = count($users);

        return [
            'total_users' => $total,
            'active_users' => $total,
            'role_counts' => [
                'superadmin' => 0,
                'admin' => $total,
                'security' => 0,
                'maintenance' => 0,
                'resident' => 0,
                'external' => 0
            ]
        ];
    }
}
