<?php

class EquipmentModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all equipment with assigned user details
    public function getAllEquipment()
    {
        $this->db->query('SELECT e.*, u.first_name as assigned_first_name, u.last_name as assigned_last_name,
                          u.username as assigned_username, creator.first_name as creator_name, creator.last_name as creator_lastname
                          FROM equipment e
                          LEFT JOIN users u ON e.assigned_to = u.id
                          LEFT JOIN users creator ON e.created_by = creator.id
                          ORDER BY e.name ASC');
        return $this->db->resultSet();
    }

    // Get equipment by ID
    public function getEquipmentById($id)
    {
        $this->db->query('SELECT e.*, u.first_name as assigned_first_name, u.last_name as assigned_last_name,
                          u.username as assigned_username, creator.first_name as creator_name, creator.last_name as creator_lastname
                          FROM equipment e
                          LEFT JOIN users u ON e.assigned_to = u.id
                          LEFT JOIN users creator ON e.created_by = creator.id
                          WHERE e.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get equipment by serial number
    public function getEquipmentBySerial($serialNumber)
    {
        $this->db->query('SELECT * FROM equipment WHERE serial_number = :serial_number');
        $this->db->bind(':serial_number', $serialNumber);
        return $this->db->single();
    }

    // Add new equipment
    public function addEquipment($data)
    {
        $this->db->query('INSERT INTO equipment
                          (name, description, serial_number, model, manufacturer, category, status, location,
                           purchase_date, purchase_cost, warranty_expiry, last_maintenance, next_maintenance, assigned_to, created_by)
                          VALUES
                          (:name, :description, :serial_number, :model, :manufacturer, :category, :status, :location,
                           :purchase_date, :purchase_cost, :warranty_expiry, :last_maintenance, :next_maintenance, :assigned_to, :created_by)');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':serial_number', $data['serial_number']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':manufacturer', $data['manufacturer']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':purchase_date', $data['purchase_date']);
        $this->db->bind(':purchase_cost', $data['purchase_cost']);
        $this->db->bind(':warranty_expiry', $data['warranty_expiry']);
        $this->db->bind(':last_maintenance', $data['last_maintenance']);
        $this->db->bind(':next_maintenance', $data['next_maintenance']);
        $this->db->bind(':assigned_to', $data['assigned_to']);
        $this->db->bind(':created_by', $data['created_by']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    // Update equipment
    public function updateEquipment($data)
    {
        $this->db->query('UPDATE equipment SET
                          name = :name,
                          description = :description,
                          serial_number = :serial_number,
                          model = :model,
                          manufacturer = :manufacturer,
                          category = :category,
                          status = :status,
                          location = :location,
                          purchase_date = :purchase_date,
                          purchase_cost = :purchase_cost,
                          warranty_expiry = :warranty_expiry,
                          last_maintenance = :last_maintenance,
                          next_maintenance = :next_maintenance,
                          assigned_to = :assigned_to
                          WHERE id = :id');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':serial_number', $data['serial_number']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':manufacturer', $data['manufacturer']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':purchase_date', $data['purchase_date']);
        $this->db->bind(':purchase_cost', $data['purchase_cost']);
        $this->db->bind(':warranty_expiry', $data['warranty_expiry']);
        $this->db->bind(':last_maintenance', $data['last_maintenance']);
        $this->db->bind(':next_maintenance', $data['next_maintenance']);
        $this->db->bind(':assigned_to', $data['assigned_to']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute();
    }

    // Delete equipment
    public function deleteEquipment($id)
    {
        $this->db->query('DELETE FROM equipment WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Assign equipment to user
    public function assignEquipment($equipmentId, $userId)
    {
        $this->db->query('UPDATE equipment SET assigned_to = :user_id WHERE id = :equipment_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':equipment_id', $equipmentId);
        return $this->db->execute();
    }

    // Unassign equipment
    public function unassignEquipment($equipmentId)
    {
        $this->db->query('UPDATE equipment SET assigned_to = NULL WHERE id = :equipment_id');
        $this->db->bind(':equipment_id', $equipmentId);
        return $this->db->execute();
    }

    // Update equipment status
    public function updateStatus($equipmentId, $status)
    {
        $this->db->query('UPDATE equipment SET status = :status WHERE id = :equipment_id');
        $this->db->bind(':status', $status);
        $this->db->bind(':equipment_id', $equipmentId);
        return $this->db->execute();
    }

    // Get equipment by assigned user
    public function getEquipmentByUser($userId)
    {
        $this->db->query('SELECT * FROM equipment WHERE assigned_to = :user_id ORDER BY name ASC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get equipment by status
    public function getEquipmentByStatus($status)
    {
        $this->db->query('SELECT e.*, u.first_name as assigned_first_name, u.last_name as assigned_last_name
                          FROM equipment e
                          LEFT JOIN users u ON e.assigned_to = u.id
                          WHERE e.status = :status
                          ORDER BY e.name ASC');
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    // Get equipment due for maintenance
    public function getEquipmentDueForMaintenance()
    {
        $this->db->query('SELECT e.*, u.first_name as assigned_first_name, u.last_name as assigned_last_name
                          FROM equipment e
                          LEFT JOIN users u ON e.assigned_to = u.id
                          WHERE (e.next_maintenance <= CURDATE() OR e.next_maintenance IS NULL)
                          AND e.status != \'retired\'
                          ORDER BY e.next_maintenance ASC');
        return $this->db->resultSet();
    }

    // Search equipment
    public function searchEquipment($searchTerm)
    {
        $this->db->query('SELECT e.*, u.first_name as assigned_first_name, u.last_name as assigned_last_name
                          FROM equipment e
                          LEFT JOIN users u ON e.assigned_to = u.id
                          WHERE e.name LIKE :search OR e.description LIKE :search OR e.serial_number LIKE :search
                          OR e.model LIKE :search OR e.manufacturer LIKE :search OR e.location LIKE :search
                          ORDER BY e.name ASC');
        $this->db->bind(':search', '%' . $searchTerm . '%');
        return $this->db->resultSet();
    }

    // Get maintenance records for equipment
    public function getMaintenanceRecords($equipmentId)
    {
        $this->db->query('SELECT em.*, creator.first_name as creator_name, creator.last_name as creator_lastname
                          FROM equipment_maintenance em
                          LEFT JOIN users creator ON em.created_by = creator.id
                          WHERE em.equipment_id = :equipment_id
                          ORDER BY em.maintenance_date DESC');
        $this->db->bind(':equipment_id', $equipmentId);
        return $this->db->resultSet();
    }

    // Add maintenance record
    public function addMaintenanceRecord($data)
    {
        $this->db->query('INSERT INTO equipment_maintenance
                          (equipment_id, maintenance_type, description, cost, performed_by, maintenance_date, next_maintenance_date, created_by)
                          VALUES
                          (:equipment_id, :maintenance_type, :description, :cost, :performed_by, :maintenance_date, :next_maintenance_date, :created_by)');

        $this->db->bind(':equipment_id', $data['equipment_id']);
        $this->db->bind(':maintenance_type', $data['maintenance_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':cost', $data['cost']);
        $this->db->bind(':performed_by', $data['performed_by']);
        $this->db->bind(':maintenance_date', $data['maintenance_date']);
        $this->db->bind(':next_maintenance_date', $data['next_maintenance_date']);
        $this->db->bind(':created_by', $data['created_by']);

        return $this->db->execute();
    }
}
