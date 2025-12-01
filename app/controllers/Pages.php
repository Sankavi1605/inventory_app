<?php

class Pages extends Controller
{
    private $userModel;
    private $inventoryModel;
    private $equipmentModel;
    private $alertModel;
    private $activityLogModel;
    private $categoryModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->inventoryModel = $this->model('InventoryItem');
    $this->equipmentModel = $this->model('EquipmentModel');
        $this->alertModel = $this->model('Alert');
        $this->activityLogModel = $this->model('ActivityLog');
        $this->categoryModel = $this->model('Category');
    }

    public function index()
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        $inventoryItems = $this->inventoryModel->getAllItems();
        $lowStockItems = $this->inventoryModel->getLowStockItems();
        $equipment = $this->equipmentModel->getAllEquipment();
        $maintenanceDue = $this->equipmentModel->getEquipmentDueForMaintenance();
        $activity = $this->activityLogModel->getActivityLogs(5);

        $assignedEquipment = array_values(array_filter($equipment, function ($item) {
            return !empty($item->assigned_first_name) || $item->status === 'assigned';
        }));

        $stats = [
            'total_items' => count($inventoryItems),
            'low_stock' => count($lowStockItems),
            'equipment_assigned' => count($assignedEquipment),
            'maintenance_due' => count($maintenanceDue)
        ];

        $data = [
            'user' => $_SESSION,
            'stats' => $stats,
            'low_stock_items' => array_slice($lowStockItems, 0, 3),
            'assigned_equipment' => array_slice($assignedEquipment, 0, 3),
            'maintenance_due' => array_slice($maintenanceDue, 0, 3),
            'activity' => $activity
        ];

        $this->view('index', $data);
    }

    public function inventory()
    {
        requireLogin();

        $items = $this->inventoryModel->getAllItems();
        $categories = $this->categoryModel->getAllCategories();

        $data = [
            'items' => $items,
            'categories' => $categories
        ];

        $this->view('inventory', $data);
    }

    public function equipment()
    {
        requireLogin();

        $equipment = $this->equipmentModel->getAllEquipment();
        $users = $this->userModel->getAllUsers();

        $data = [
            'equipment' => $equipment,
            'users' => $users
        ];

        $this->view('equipment', $data);
    }

    public function alerts()
    {
        requireRole('admin');

        $alerts = $this->alertModel->getAllAlerts();

        $data = [
            'alerts' => $alerts
        ];

        $this->view('alerts', $data);
    }

    public function dashboard()
    {
        requireLogin();

        $data = [
            'user' => $_SESSION
        ];

        $data['stats'] = [
            'total_items' => count($this->inventoryModel->getAllItems()),
            'low_stock_items' => count($this->inventoryModel->getLowStockItems()),
            'total_equipment' => count($this->equipmentModel->getAllEquipment()),
            'maintenance_due' => count($this->equipmentModel->getEquipmentDueForMaintenance()),
            'total_users' => count($this->userModel->getAllUsers()),
            'recent_activity' => $this->activityLogModel->getActivityLogs(5)
        ];

        $this->view('dashboard', $data);
    }

    public function landing()
    {
        redirect('auth/login');
    }

    public function about()
    {
        $this->view('v_about');
    }

    public function contact()
    {
        $this->view('v_contact');
    }

    public function unauthorized()
    {
        $this->view('unauthorized');
    }

    public function profile()
    {
        requireLogin();

        $userController = new UserController();
        $userController->profile();
    }

    public function help()
    {
        requireLogin();
        $this->view('help');
    }

    public function reports()
    {
        requireRole('admin');
        $this->view('reports');
    }

    public function settings()
    {
        requireRole('admin');
        $this->view('settings');
    }
}
