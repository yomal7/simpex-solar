<?php
class M_Inventory {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all inventory items
    public function getAllItems() {
        $this->db->query('SELECT * FROM Inventory');
        return $this->db->resultSet();
    }

    // Get item by ID
    public function getItemById($itemId) {
        $this->db->query('SELECT * FROM Inventory WHERE item_id = :item_id');
        $this->db->bind(':item_id', $itemId);
        return $this->db->single();
    }

    // Create new inventory item
    public function createItem($data) {
        $this->db->query('INSERT INTO Inventory 
            (name, supplier_id, description, price, quantity, status, blog_link, image_path) 
            VALUES (:name, :supplier_id, :description, :price, :quantity, :status, :blog_link, :image_path)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':blog_link', $data['blog_link']);
        $this->db->bind(':image_path', $data['image_path'] ?? null);

        $this->db->execute();
        return $this->db->lastInsertId();
    }

    // Update existing inventory item
    public function updateItem($itemId, $data) {
        $this->db->query('UPDATE Inventory 
            SET name = :name, 
                supplier_id = :supplier, 
                description = :description, 
                price = :price, 
                quantity = :quantity, 
                status = :status,
                blog_link = :blog_link, 
                image_path = :image_path 
            WHERE item_id = :item_id');
        
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':blog_link', $data['blog_link']);
        $this->db->bind(':image_path', $data['image_path'] ?? null);
        return $this->db->execute();
    }

    // Delete inventory item
    public function deleteItem($itemId) {
        $this->db->query('DELETE FROM Inventory WHERE item_id = :item_id');
        $this->db->bind(':item_id', $itemId);
        return $this->db->execute();
    }

    // Decrease item quantity when used in a package
    public function decreaseItemQuantity($itemId, $quantity) {
        $this->db->query('UPDATE Inventory 
            SET quantity = quantity - :quantity 
            WHERE item_id = :item_id AND quantity >= :quantity');
        
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':quantity', $quantity);

        return $this->db->execute();
    }

    

    // Check item availability
    public function checkItemAvailability($itemId, $requiredQuantity) {
        $this->db->query('SELECT quantity FROM Inventory WHERE item_id = :item_id');
        $this->db->bind(':item_id', $itemId);
        $item = $this->db->single();

        return $item->quantity >= $requiredQuantity;
    }

    // Get low stock items
    public function getLowStockItems($threshold = 10) {
        $this->db->query('SELECT * FROM Inventory WHERE quantity <= :threshold');
        $this->db->bind(':threshold', $threshold);
        return $this->db->resultSet();
    }
}