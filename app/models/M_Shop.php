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

    //for supplier view
    public function getProducts()
    {
        $this->db->query('SELECT 
            p.id, 
            p.name AS product_name, 
            p.price, 
            p.description, 
            s.name AS supplier_name, 
            c.category 
        FROM products p
        JOIN suppliers s ON p.supplier_id = s.id
        JOIN categories c ON p.category_id = c.id
        WHERE p.deleted_at IS NULL');

        return $this->db->resultSet();
    }



    public function addProduct($data)
    {
        // Prepare SQL to insert product
        $this->db->query('INSERT INTO products (name, supplier_id, category_id, price, blog_link, description, image1, image2, image3, created_at) 
                      VALUES (:name, :supplier_id, :category_id, :price, :blog_link, :description, :image1, :image2, :image3, NOW())');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':blog_link', $data['blog_link']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image1', $data['image1']);
        $this->db->bind(':image2', $data['image2']);
        $this->db->bind(':image3', $data['image3']);

        // Execute and return last inserted ID
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function addProductFeatures($data)
    {
        // Prepare SQL to insert features
        $this->db->query('INSERT INTO product_features (product_id, feature, created_at) 
                      VALUES (:product_id, :feature, NOW())');

        // Prepare features array
        $features = array_filter([
            $data['feature1'],
            $data['feature2'],
            $data['feature3'],
            $data['feature4']
        ]);

        // Track success
        $success = true;

        // Insert each non-empty feature
        foreach ($features as $feature) {
            $this->db->bind(':product_id', $data['product_id']);
            $this->db->bind(':feature', $feature);

            if (!$this->db->execute()) {
                $success = false;
            }
        }

        return $success;
    }
}
