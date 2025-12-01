<?php

class InventoryItem
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all inventory items with categories
    public function getAllItems()
    {
        $this->db->query('SELECT ii.*, c.name as category_name, u.first_name as created_by_name, u.last_name as created_by_lastname
                          FROM inventory_items ii
                          LEFT JOIN categories c ON ii.category_id = c.id
                          LEFT JOIN users u ON ii.created_by = u.id
                          ORDER BY ii.name ASC');
        return $this->db->resultSet();
    }

    // Get item by ID
    public function getItemById($id)
    {
        $this->db->query('SELECT ii.*, c.name as category_name, u.first_name as created_by_name, u.last_name as created_by_lastname
                          FROM inventory_items ii
                          LEFT JOIN categories c ON ii.category_id = c.id
                          LEFT JOIN users u ON ii.created_by = u.id
                          WHERE ii.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get item by SKU
    public function getItemBySKU($sku)
    {
        $this->db->query('SELECT * FROM inventory_items WHERE sku = :sku');
        $this->db->bind(':sku', $sku);
        return $this->db->single();
    }

    // Add new inventory item
    public function addItem($data)
    {
        $this->db->query('INSERT INTO inventory_items
                          (name, description, category_id, sku, quantity_available, quantity_total, unit, location,
                           minimum_quantity, unit_cost, supplier, purchase_date, warranty_expiry, created_by)
                          VALUES
                          (:name, :description, :category_id, :sku, :quantity_available, :quantity_total, :unit, :location,
                           :minimum_quantity, :unit_cost, :supplier, :purchase_date, :warranty_expiry, :created_by)');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':quantity_available', $data['quantity_available']);
        $this->db->bind(':quantity_total', $data['quantity_total']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':minimum_quantity', $data['minimum_quantity']);
        $this->db->bind(':unit_cost', $data['unit_cost']);
        $this->db->bind(':supplier', $data['supplier']);
        $this->db->bind(':purchase_date', $data['purchase_date']);
        $this->db->bind(':warranty_expiry', $data['warranty_expiry']);
        $this->db->bind(':created_by', $data['created_by']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    // Update inventory item
    public function updateItem($data)
    {
        $this->db->query('UPDATE inventory_items SET
                          name = :name,
                          description = :description,
                          category_id = :category_id,
                          quantity_available = :quantity_available,
                          quantity_total = :quantity_total,
                          unit = :unit,
                          location = :location,
                          minimum_quantity = :minimum_quantity,
                          unit_cost = :unit_cost,
                          supplier = :supplier,
                          purchase_date = :purchase_date,
                          warranty_expiry = :warranty_expiry
                          WHERE id = :id');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':quantity_available', $data['quantity_available']);
        $this->db->bind(':quantity_total', $data['quantity_total']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':minimum_quantity', $data['minimum_quantity']);
        $this->db->bind(':unit_cost', $data['unit_cost']);
        $this->db->bind(':supplier', $data['supplier']);
        $this->db->bind(':purchase_date', $data['purchase_date']);
        $this->db->bind(':warranty_expiry', $data['warranty_expiry']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute();
    }

    // Delete inventory item
    public function deleteItem($id)
    {
        $this->db->query('DELETE FROM inventory_items WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Update item quantity
    public function updateQuantity($itemId, $newQuantity, $previousQuantity, $transactionType, $performedBy, $reason = '', $notes = '')
    {
        $this->db->beginTransaction();

        try {
            // Update the item quantity
            $this->db->query('UPDATE inventory_items SET
                              quantity_available = :quantity,
                              last_inventory_check = NOW()
                              WHERE id = :id');
            $this->db->bind(':quantity', $newQuantity);
            $this->db->bind(':id', $itemId);
            $this->db->execute();

            // Record the transaction
            $this->db->query('INSERT INTO inventory_transactions
                              (item_id, transaction_type, quantity, previous_quantity, new_quantity, reason, performed_by, notes)
                              VALUES (:item_id, :transaction_type, :quantity, :previous_quantity, :new_quantity, :reason, :performed_by, :notes)');

            $quantityChange = $newQuantity - $previousQuantity;
            $this->db->bind(':item_id', $itemId);
            $this->db->bind(':transaction_type', $transactionType);
            $this->db->bind(':quantity', $quantityChange);
            $this->db->bind(':previous_quantity', $previousQuantity);
            $this->db->bind(':new_quantity', $newQuantity);
            $this->db->bind(':reason', $reason);
            $this->db->bind(':performed_by', $performedBy);
            $this->db->bind(':notes', $notes);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Get low stock items
    public function getLowStockItems()
    {
        $this->db->query('SELECT ii.*, c.name as category_name
                          FROM inventory_items ii
                          LEFT JOIN categories c ON ii.category_id = c.id
                          WHERE ii.quantity_available <= ii.minimum_quantity AND ii.minimum_quantity > 0
                          ORDER BY ii.quantity_available ASC');
        return $this->db->resultSet();
    }

    // Get items by category
    public function getItemsByCategory($categoryId)
    {
        $this->db->query('SELECT ii.*, c.name as category_name
                          FROM inventory_items ii
                          LEFT JOIN categories c ON ii.category_id = c.id
                          WHERE ii.category_id = :category_id
                          ORDER BY ii.name ASC');
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    // Search items
    public function searchItems($searchTerm)
    {
        $this->db->query('SELECT ii.*, c.name as category_name
                          FROM inventory_items ii
                          LEFT JOIN categories c ON ii.category_id = c.id
                          WHERE ii.name LIKE :search OR ii.description LIKE :search OR ii.sku LIKE :search OR ii.location LIKE :search
                          ORDER BY ii.name ASC');
        $this->db->bind(':search', '%' . $searchTerm . '%');
        return $this->db->resultSet();
    }

    // Get item transactions
    public function getItemTransactions($itemId, $limit = 50)
    {
        $this->db->query('SELECT it.*, u.first_name, u.last_name
                          FROM inventory_transactions it
                          LEFT JOIN users u ON it.performed_by = u.id
                          WHERE it.item_id = :item_id
                          ORDER BY it.transaction_date DESC
                          LIMIT :limit');
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}
