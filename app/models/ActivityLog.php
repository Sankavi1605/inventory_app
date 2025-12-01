<?php

class ActivityLog
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Log activity
    public function logActivity($data)
    {
        $this->db->query('INSERT INTO activity_logs
                          (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent)
                          VALUES (:user_id, :action, :table_name, :record_id, :old_values, :new_values, :ip_address, :user_agent)');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':action', $data['action']);
        $this->db->bind(':table_name', $data['table_name']);
        $this->db->bind(':record_id', $data['record_id']);
        $this->db->bind(':old_values', $data['old_values'] ? json_encode($data['old_values']) : null);
        $this->db->bind(':new_values', $data['new_values'] ? json_encode($data['new_values']) : null);
        $this->db->bind(':ip_address', $data['ip_address']);
        $this->db->bind(':user_agent', $data['user_agent']);

        return $this->db->execute();
    }

    // Get activity logs
    public function getActivityLogs($limit = 100, $offset = 0)
    {
        $this->db->query('SELECT al.*, u.first_name, u.last_name, u.username
                          FROM activity_logs al
                          LEFT JOIN users u ON al.user_id = u.id
                          ORDER BY al.created_at DESC
                          LIMIT :limit OFFSET :offset');

        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // Get activity logs for user
    public function getUserActivityLogs($userId, $limit = 50)
    {
        $this->db->query('SELECT * FROM activity_logs
                          WHERE user_id = :user_id
                          ORDER BY created_at DESC
                          LIMIT :limit');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // Get activity logs for table
    public function getTableActivityLogs($tableName, $recordId = null, $limit = 50)
    {
        $query = 'SELECT al.*, u.first_name, u.last_name, u.username
                  FROM activity_logs al
                  LEFT JOIN users u ON al.user_id = u.id
                  WHERE al.table_name = :table_name';

        if ($recordId) {
            $query .= ' AND al.record_id = :record_id';
        }

        $query .= ' ORDER BY al.created_at DESC LIMIT :limit';

        $this->db->query($query);
        $this->db->bind(':table_name', $tableName);

        if ($recordId) {
            $this->db->bind(':record_id', $recordId);
        }

        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // Search activity logs
    public function searchActivityLogs($searchTerm, $limit = 100)
    {
        $this->db->query('SELECT al.*, u.first_name, u.last_name, u.username
                          FROM activity_logs al
                          LEFT JOIN users u ON al.user_id = u.id
                          WHERE al.action LIKE :search
                          OR al.table_name LIKE :search
                          OR u.username LIKE :search
                          OR u.first_name LIKE :search
                          OR u.last_name LIKE :search
                          ORDER BY al.created_at DESC
                          LIMIT :limit');

        $this->db->bind(':search', '%' . $searchTerm . '%');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }
}