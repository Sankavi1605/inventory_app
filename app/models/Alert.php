<?php

class Alert
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Create new alert
    public function createAlert($data)
    {
        $this->db->query('INSERT INTO alerts (title, message, type, target_role, target_user, expires_at)
                          VALUES (:title, :message, :type, :target_role, :target_user, :expires_at)');

        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':target_role', $data['target_role']);
        $this->db->bind(':target_user', $data['target_user']);
        $this->db->bind(':expires_at', $data['expires_at']);

        return $this->db->execute();
    }

    // Get alerts for user
    public function getUserAlerts($userId, $userRole)
    {
        $this->db->query('SELECT * FROM alerts
                          WHERE (target_user = :user_id OR target_role = :user_role OR target_user IS NULL OR target_role IS NULL)
                          AND (expires_at IS NULL OR expires_at > NOW())
                          AND is_read = FALSE
                          ORDER BY created_at DESC');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':user_role', $userRole);

        return $this->db->resultSet();
    }

    // Get all alerts for admin
    public function getAllAlerts()
    {
        $this->db->query('SELECT a.*, u.first_name, u.last_name
                          FROM alerts a
                          LEFT JOIN users u ON a.target_user = u.id
                          ORDER BY a.created_at DESC');
        return $this->db->resultSet();
    }

    // Mark alert as read
    public function markAsRead($alertId, $userId)
    {
        $this->db->query('UPDATE alerts SET is_read = TRUE WHERE id = :alert_id AND target_user = :user_id');
        $this->db->bind(':alert_id', $alertId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Delete alert
    public function deleteAlert($alertId)
    {
        $this->db->query('DELETE FROM alerts WHERE id = :alert_id');
        $this->db->bind(':alert_id', $alertId);
        return $this->db->execute();
    }

    // Create low stock alerts
    public function createLowStockAlerts()
    {
        $this->db->query('SELECT * FROM inventory_items
                          WHERE quantity_available <= minimum_quantity AND minimum_quantity > 0');
        $items = $this->db->resultSet();

        foreach ($items as $item) {
            $alertData = [
                'title' => 'Low Stock Alert',
                'message' => "Item '{$item->name}' (SKU: {$item->sku}) is running low on stock. Current quantity: {$item->quantity_available}",
                'type' => 'warning',
                'target_role' => 'admin',
                'target_user' => null,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+7 days'))
            ];
            $this->createAlert($alertData);
        }
    }

    // Create maintenance due alerts
    public function createMaintenanceAlerts()
    {
        $this->db->query('SELECT * FROM equipment
                          WHERE (next_maintenance <= CURDATE() OR next_maintenance IS NULL)
                          AND status != \'retired\'');
        $equipment = $this->db->resultSet();

        foreach ($equipment as $eq) {
            $alertData = [
                'title' => 'Maintenance Due',
                'message' => "Equipment '{$eq->name}' (Serial: {$eq->serial_number}) is due for maintenance",
                'type' => 'info',
                'target_role' => 'maintenance',
                'target_user' => null,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+3 days'))
            ];
            $this->createAlert($alertData);
        }
    }
}