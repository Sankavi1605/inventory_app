<?php

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all categories
    public function getAllCategories()
    {
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // Get category by ID
    public function getCategoryById($id)
    {
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add new category
    public function addCategory($data)
    {
        $this->db->query('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);

        return $this->db->execute();
    }

    // Update category
    public function updateCategory($data)
    {
        $this->db->query('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute();
    }

    // Delete category
    public function deleteCategory($id)
    {
        $this->db->query('DELETE FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // Check if category has items
    public function hasItems($id)
    {
        $this->db->query('SELECT COUNT(*) as count FROM inventory_items WHERE category_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        return $result->count > 0;
    }
}