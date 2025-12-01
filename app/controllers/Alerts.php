<?php

class Alerts extends Controller
{
    private $alertModel;
    private $activityLogModel;

    public function __construct()
    {
        requireLogin();
        $this->alertModel = $this->model('Alert');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // Display alerts page
    public function index()
    {
        requireRole('admin');

        $alerts = $this->alertModel->getAllAlerts();

        $data = [
            'alerts' => $alerts
        ];

        $this->view('alerts', $data);
    }

    // Create new alert
    public function create()
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'message' => trim($_POST['message']),
                'type' => trim($_POST['type']),
                'target_role' => trim($_POST['target_role']),
                'target_user' => !empty($_POST['target_user']) ? trim($_POST['target_user']) : null,
                'expires_at' => !empty($_POST['expires_at']) ? trim($_POST['expires_at']) : null,
                'title_err' => '',
                'message_err' => ''
            ];

            // Validate
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter alert title';
            }

            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter alert message';
            }

            // If no errors, create alert
            if (empty($data['title_err']) && empty($data['message_err'])) {
                if ($this->alertModel->createAlert($data)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'create_alert',
                        'table_name' => 'alerts',
                        'record_id' => $this->db->lastInsertId(),
                        'new_values' => $data,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('alert_message', 'Alert created successfully', 'alert alert-success');
                    redirect('alerts/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('alerts', $data);
            }
        } else {
            redirect('alerts/index');
        }
    }

    // Delete alert
    public function delete($id)
    {
        requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->alertModel->deleteAlert($id)) {
                // Log activity
                $logData = [
                    'user_id' => getCurrentUserId(),
                    'action' => 'delete_alert',
                    'table_name' => 'alerts',
                    'record_id' => $id,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ];
                $this->activityLogModel->logActivity($logData);

                flash('alert_message', 'Alert deleted successfully', 'alert alert-success');
            } else {
                flash('alert_message', 'Failed to delete alert', 'alert alert-danger');
            }
        }

        redirect('alerts/index');
    }

    // Mark alert as read
    public function markRead($id)
    {
        $userId = getCurrentUserId();

        if ($this->alertModel->markAsRead($id, $userId)) {
            flash('alert_message', 'Alert marked as read', 'alert alert-success');
        } else {
            flash('alert_message', 'Failed to mark alert as read', 'alert alert-danger');
        }

        redirect('pages/index');
    }

    // Generate system alerts
    public function generateSystemAlerts()
    {
        requireRole('admin');

        // Generate low stock alerts
        $this->alertModel->createLowStockAlerts();

        // Generate maintenance alerts
        $this->alertModel->createMaintenanceAlerts();

        flash('alert_message', 'System alerts generated successfully', 'alert alert-success');
        redirect('alerts/index');
    }
}