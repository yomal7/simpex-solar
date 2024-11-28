<?php
class M_Shop
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllProducts()
    {
        try {
            $query = "SELECT p.*, c.category 
                     FROM Products p 
                     JOIN Categories c ON p.category_id = c.id
                     ORDER BY p.name";
            $this->db->query($query);
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    public function getProductsByCategory($category)
    {
        try {
            $query = "SELECT p.*, c.category 
                     FROM Products p 
                     JOIN Categories c ON p.category_id = c.id 
                     WHERE c.category = :category
                     ORDER BY p.name";
            $this->db->query($query);
            $this->db->bind(':category', $category);
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    public function getProductById($id)
    {
        try {
            $query = "SELECT p.*, c.category, s.name as supplier_name, s.contact_number 
                     FROM Products p 
                     JOIN Categories c ON p.category_id = c.id 
                     JOIN Suppliers s ON p.supplier_id = s.id 
                     WHERE p.id = :id";
            $this->db->query($query);
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }
}
