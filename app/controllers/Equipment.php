<?php

class Equipment extends Controller
{
    private $equipmentModel;
    private $userModel;
    private $activityLogModel;

    public function __construct()
    {
        requireLogin();
    $this->equipmentModel = $this->model('EquipmentModel');
        $this->userModel = $this->model('User');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // Display all equipment
    public function index()
    {
        $equipment = $this->equipmentModel->getAllEquipment();
        $users = $this->userModel->getAllUsers();

        $data = [
            'equipment' => $equipment,
            'users' => $users,
            'search' => '',
            'status_filter' => ''
        ];

        $this->view('equipment', $data);
    }

    // Add new equipment
    public function add()
    {
        requireRole('admin');

        $statusOptions = ['operational', 'maintenance', 'assigned', 'out_of_service', 'retired'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'serial_number' => trim($_POST['serial_number']),
                'model' => trim($_POST['model']),
                'manufacturer' => trim($_POST['manufacturer']),
                'category' => trim($_POST['category']),
                'status' => trim($_POST['status']),
                'location' => trim($_POST['location']),
                'purchase_date' => trim($_POST['purchase_date']),
                'purchase_cost' => trim($_POST['purchase_cost']),
                'warranty_expiry' => trim($_POST['warranty_expiry']),
                'last_maintenance' => trim($_POST['last_maintenance']),
                'next_maintenance' => trim($_POST['next_maintenance']),
                'assigned_to' => (isset($_POST['assigned_to']) && $_POST['assigned_to'] !== '') ? trim($_POST['assigned_to']) : null,
                'created_by' => getCurrentUserId(),
                'name_err' => '',
                'serial_number_err' => '',
                'status_options' => $statusOptions,
                'users' => $this->userModel->getAllUsers()
            ];

            // Validate serial number
            if (!empty($data['serial_number']) && $this->equipmentModel->getEquipmentBySerial($data['serial_number'])) {
                $data['serial_number_err'] = 'Serial number already exists';
            }

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter equipment name';
            }

            // If no errors, add equipment
            if (empty($data['name_err']) && empty($data['serial_number_err'])) {
                $newEquipmentId = $this->equipmentModel->addEquipment($data);

                if ($newEquipmentId) {
                    $logValues = $data;
                    unset($logValues['users'], $logValues['status_options'], $logValues['name_err'], $logValues['serial_number_err']);

                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'create_equipment',
                        'table_name' => 'equipment',
                        'record_id' => $newEquipmentId,
                        'new_values' => $logValues,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('equipment_message', 'Equipment added successfully', 'alert alert-success');
                    redirect('equipment/index');
                }

                flash('equipment_message', 'Unable to save equipment. Please try again.', 'alert alert-danger');
                redirect('equipment/add');
            }

            $this->view('equipment_add', $data);
        } else {
            $data = [
                'name' => '',
                'description' => '',
                'serial_number' => '',
                'model' => '',
                'manufacturer' => '',
                'category' => '',
                'status' => 'operational',
                'location' => '',
                'purchase_date' => '',
                'purchase_cost' => '',
                'warranty_expiry' => '',
                'last_maintenance' => '',
                'next_maintenance' => '',
                'assigned_to' => null,
                'name_err' => '',
                'serial_number_err' => '',
                'status_options' => $statusOptions,
                'users' => $this->userModel->getAllUsers()
            ];

            $this->view('equipment_add', $data);
        }
    }

    // Edit equipment
    public function edit($id)
    {
        requireRole('admin');

        $equipment = $this->equipmentModel->getEquipmentById($id);

        if (!$equipment) {
            flash('equipment_message', 'Equipment not found', 'alert alert-danger');
            redirect('equipment/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'serial_number' => trim($_POST['serial_number']),
                'model' => trim($_POST['model']),
                'manufacturer' => trim($_POST['manufacturer']),
                'category' => trim($_POST['category']),
                'status' => trim($_POST['status']),
                'location' => trim($_POST['location']),
                'purchase_date' => trim($_POST['purchase_date']),
                'purchase_cost' => trim($_POST['purchase_cost']),
                'warranty_expiry' => trim($_POST['warranty_expiry']),
                'last_maintenance' => trim($_POST['last_maintenance']),
                'next_maintenance' => trim($_POST['next_maintenance']),
                'assigned_to' => !empty($_POST['assigned_to']) ? trim($_POST['assigned_to']) : null,
                'name_err' => '',
                'serial_number_err' => ''
            ];

            // Store old values for logging
            $oldValues = [
                'name' => $equipment->name,
                'description' => $equipment->description,
                'status' => $equipment->status,
                'location' => $equipment->location,
                'assigned_to' => $equipment->assigned_to
            ];

            // Validate
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter equipment name';
            }

            // Check serial number if changed
            if ($data['serial_number'] != $equipment->serial_number && !empty($data['serial_number'])) {
                if ($this->equipmentModel->getEquipmentBySerial($data['serial_number'])) {
                    $data['serial_number_err'] = 'Serial number already exists';
                }
            }

            // If no errors, update equipment
            if (empty($data['name_err']) && empty($data['serial_number_err'])) {
                if ($this->equipmentModel->updateEquipment($data)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'update_equipment',
                        'table_name' => 'equipment',
                        'record_id' => $id,
                        'old_values' => $oldValues,
                        'new_values' => $data,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('equipment_message', 'Equipment updated successfully', 'alert alert-success');
                    redirect('equipment/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $users = $this->userModel->getAllUsers();
                $data['users'] = $users;
                $data['equipment_item'] = $equipment;
                $this->view('equipment_edit', $data);
            }
        } else {
            $users = $this->userModel->getAllUsers();

            $data = [
                'users' => $users,
                'equipment_item' => $equipment,
                'id' => $equipment->id,
                'name' => $equipment->name,
                'description' => $equipment->description,
                'serial_number' => $equipment->serial_number,
                'model' => $equipment->model,
                'manufacturer' => $equipment->manufacturer,
                'category' => $equipment->category,
                'status' => $equipment->status,
                'location' => $equipment->location,
                'purchase_date' => $equipment->purchase_date,
                'purchase_cost' => $equipment->purchase_cost,
                'warranty_expiry' => $equipment->warranty_expiry,
                'last_maintenance' => $equipment->last_maintenance,
                'next_maintenance' => $equipment->next_maintenance,
                'assigned_to' => $equipment->assigned_to,
                'name_err' => '',
                'serial_number_err' => ''
            ];

            $this->view('equipment_edit', $data);
        }
    }

    // Delete equipment
    public function delete($id)
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $equipment = $this->equipmentModel->getEquipmentById($id);

            if ($equipment) {
                // Log activity before deletion
                $logData = [
                    'user_id' => getCurrentUserId(),
                    'action' => 'delete_equipment',
                    'table_name' => 'equipment',
                    'record_id' => $id,
                    'old_values' => $equipment,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ];
                $this->activityLogModel->logActivity($logData);

                if ($this->equipmentModel->deleteEquipment($id)) {
                    flash('equipment_message', 'Equipment removed successfully', 'alert alert-success');
                } else {
                    flash('equipment_message', 'Failed to remove equipment', 'alert alert-danger');
                }
            } else {
                flash('equipment_message', 'Equipment not found', 'alert alert-danger');
            }
        }

        redirect('equipment/index');
    }

    // Assign equipment to user
    public function assign($id)
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $userId = trim($_POST['assigned_to']);
            $equipment = $this->equipmentModel->getEquipmentById($id);

            if ($equipment) {
                if ($this->equipmentModel->assignEquipment($id, $userId)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'assign_equipment',
                        'table_name' => 'equipment',
                        'record_id' => $id,
                        'new_values' => ['assigned_to' => $userId],
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('equipment_message', 'Equipment assigned successfully', 'alert alert-success');
                } else {
                    flash('equipment_message', 'Failed to assign equipment', 'alert alert-danger');
                }
            }
        }

        redirect('equipment/index');
    }

    // Unassign equipment
    public function unassign($id)
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $equipment = $this->equipmentModel->getEquipmentById($id);

            if ($equipment) {
                if ($this->equipmentModel->unassignEquipment($id)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'unassign_equipment',
                        'table_name' => 'equipment',
                        'record_id' => $id,
                        'old_values' => ['assigned_to' => $equipment->assigned_to],
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('equipment_message', 'Equipment unassigned successfully', 'alert alert-success');
                } else {
                    flash('equipment_message', 'Failed to unassign equipment', 'alert alert-danger');
                }
            }
        }

        redirect('equipment/index');
    }

    // Update equipment status
    public function updateStatus($id)
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $status = trim($_POST['status']);
            $equipment = $this->equipmentModel->getEquipmentById($id);

            if ($equipment) {
                if ($this->equipmentModel->updateStatus($id, $status)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'update_equipment_status',
                        'table_name' => 'equipment',
                        'record_id' => $id,
                        'old_values' => ['status' => $equipment->status],
                        'new_values' => ['status' => $status],
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('equipment_message', 'Equipment status updated successfully', 'alert alert-success');
                } else {
                    flash('equipment_message', 'Failed to update status', 'alert alert-danger');
                }
            }
        }

        redirect('equipment/index');
    }

    // Add maintenance record
    public function addMaintenance($id)
    {
        requireRole('maintenance');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'equipment_id' => $id,
                'maintenance_type' => trim($_POST['maintenance_type']),
                'description' => trim($_POST['description']),
                'cost' => trim($_POST['cost']),
                'performed_by' => trim($_POST['performed_by']),
                'maintenance_date' => trim($_POST['maintenance_date']),
                'next_maintenance_date' => trim($_POST['next_maintenance_date']),
                'created_by' => getCurrentUserId()
            ];

            if ($this->equipmentModel->addMaintenanceRecord($data)) {
                // Update equipment next maintenance date
                $equipmentData = [
                    'id' => $id,
                    'name' => '',
                    'description' => '',
                    'serial_number' => '',
                    'model' => '',
                    'manufacturer' => '',
                    'category' => '',
                    'status' => 'operational',
                    'location' => '',
                    'purchase_date' => '',
                    'purchase_cost' => '',
                    'warranty_expiry' => '',
                    'last_maintenance' => $data['maintenance_date'],
                    'next_maintenance' => $data['next_maintenance_date'],
                    'assigned_to' => null
                ];
                $this->equipmentModel->updateEquipment($equipmentData);

                // Log activity
                $logData = [
                    'user_id' => getCurrentUserId(),
                    'action' => 'add_maintenance_record',
                    'table_name' => 'equipment_maintenance',
                    'record_id' => $id,
                    'new_values' => $data,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ];
                $this->activityLogModel->logActivity($logData);

                flash('equipment_message', 'Maintenance record added successfully', 'alert alert-success');
            } else {
                flash('equipment_message', 'Failed to add maintenance record', 'alert alert-danger');
            }
        }

        redirect('equipment/view/' . $id);
    }

    // Search equipment
    public function search()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $searchTerm = trim($_POST['search']);
            $statusFilter = trim($_POST['status_filter']);

            if ($searchTerm) {
                $equipment = $this->equipmentModel->searchEquipment($searchTerm);
            } else {
                $equipment = $this->equipmentModel->getAllEquipment();
            }

            if ($statusFilter) {
                $equipment = $this->equipmentModel->getEquipmentByStatus($statusFilter);
            }

            $users = $this->userModel->getAllUsers();

            $data = [
                'equipment' => $equipment,
                'users' => $users,
                'search' => $searchTerm,
                'status_filter' => $statusFilter
            ];

            $this->view('equipment', $data);
        } else {
            redirect('equipment/index');
        }
    }

    // View equipment details
    public function show($id)
    {
        $equipment = $this->equipmentModel->getEquipmentById($id);
        $maintenanceRecords = $this->equipmentModel->getMaintenanceRecords($id);

        if (!$equipment) {
            flash('equipment_message', 'Equipment not found', 'alert alert-danger');
            redirect('equipment/index');
        }

        $data = [
            'equipment' => $equipment,
            'maintenance_records' => $maintenanceRecords
        ];

        $this->view('equipment_details', $data);
    }

    // Get equipment due for maintenance (AJAX)
    public function dueForMaintenance()
    {
        requireRole('maintenance');
        $equipment = $this->equipmentModel->getEquipmentDueForMaintenance();
        echo json_encode($equipment);
    }

    // Get my assigned equipment
    public function myEquipment()
    {
        $userId = getCurrentUserId();
        $equipment = $this->equipmentModel->getEquipmentByUser($userId);

        $data = [
            'equipment' => $equipment
        ];

        $this->view('my_equipment', $data);
    }
}
