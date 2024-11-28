<?php
class M_Inventory
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    // Get all inventory items
    public function getAllItems()
    {
        $this->db->query('
        SELECT 
            Inventory.id AS item_id,
            Inventory.name AS product_name, 
            Suppliers.name AS supplier_name, 
            Inventory.price, 
            Inventory.quantity
        FROM 
            Inventory
        INNER JOIN 
            Suppliers 
        ON 
            Inventory.supplier_id = Suppliers.id
        WHERE 
            Inventory.deleted_at IS NULL
    ');
        return $this->db->resultSet();
    }

    // Optional: Method to get all items, including soft-deleted ones
    public function getAllItemsIncludingDeleted()
    {
        $this->db->query('
        SELECT 
            Inventory.id AS item_id,
            Inventory.name AS product_name, 
            Suppliers.name AS supplier_name, 
            Inventory.price, 
            Inventory.quantity,
            Inventory.deleted_at
        FROM 
            Inventory
        INNER JOIN 
            Suppliers 
        ON 
            Inventory.supplier_id = Suppliers.id
    ');
        return $this->db->resultSet();
    }


    // Get item by ID
    public function getItemById($itemId)
    {
        $this->db->query(
            'SELECT 
            i.id AS item_id,
            i.name AS item_name,
            i.price,
            i.quantity,
            i.description,
            i.blog_link,
            i.image,
            s.id AS supplier_id,
            s.name AS supplier_name
        FROM inventory i
        JOIN suppliers s ON i.supplier_id = s.id
        WHERE i.id = :item_id'
        );

        // Bind the item ID
        $this->db->bind(':item_id', $itemId);

        // Return the single result
        return $this->db->single();
    }


    // Create new inventory item
    public function createItem($data)
    {
        $this->db->query('INSERT INTO Inventory 
            (name, supplier_id, description, price, quantity, blog_link, image) 
            VALUES (:name, :supplier_id, :description, :price, :quantity, :blog_link, :image_path)');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':blog_link', $data['blog_link']);
        $this->db->bind(':image_path', $data['image_path'] ?? null);

        $this->db->execute();
        return $this->db->lastInsertId();
    }

    // Update existing inventory item
    // public function updateItem($itemId, $data)
    // {
    //     $this->db->query('UPDATE Inventory 
    //         SET name = :name, 
    //             supplier_id = :supplier, 
    //             description = :description, 
    //             price = :price, 
    //             quantity = :quantity, 
    //             status = :status,
    //             blog_link = :blog_link, 
    //             image_path = :image_path 
    //         WHERE item_id = :item_id');

    //     $this->db->bind(':item_id', $itemId);
    //     $this->db->bind(':name', $data['name']);
    //     $this->db->bind(':supplier_id', $data['supplier_id']);
    //     $this->db->bind(':description', $data['description']);
    //     $this->db->bind(':price', $data['price']);
    //     $this->db->bind(':quantity', $data['quantity']);
    //     $this->db->bind(':status', $data['status']);
    //     $this->db->bind(':blog_link', $data['blog_link']);
    //     $this->db->bind(':image_path', $data['image_path'] ?? null);
    //     return $this->db->execute();
    // }

    public function updateItem($itemId, $data)
    {
        $this->db->query('UPDATE inventory 
        SET supplier_id = :supplier_id,
            description = :description,
            price = :price,
            quantity = :quantity,
            blog_link = :blog_link
        WHERE id = :item_id');

        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':blog_link', $data['blog_link']);

        return $this->db->execute();
    }


    // Delete inventory item
    // public function deleteItem($itemId)
    // {
    //     $this->db->query('DELETE FROM Inventory WHERE item_id = :item_id');
    //     $this->db->bind(':item_id', $itemId);
    //     return $this->db->execute();
    // }

    public function softDeleteItem($itemId)
    {
        try {
            // Prepare the soft delete query
            $this->db->query('UPDATE Inventory SET deleted_at = CURRENT_TIMESTAMP WHERE id = :item_id AND deleted_at IS NULL');
            $this->db->bind(':item_id', $itemId);

            // Execute and check if rows were affected
            $this->db->execute();

            // Return true if a row was actually updated
            return $this->db->rowCount() > 0;
        } catch (Exception $e) {
            // Log the error
            error_log("Soft Delete Error for Item ID $itemId: " . $e->getMessage());
            return false;
        }
    }

    // Decrease item quantity when used in a package
    public function decreaseItemQuantity($itemId, $quantity)
    {
        $this->db->query('UPDATE Inventory 
            SET quantity = quantity - :quantity 
            WHERE item_id = :item_id AND quantity >= :quantity');

        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':quantity', $quantity);

        return $this->db->execute();
    }



    // Check item availability
    public function checkItemAvailability($itemId, $requiredQuantity)
    {
        $this->db->query('SELECT quantity FROM Inventory WHERE item_id = :item_id');
        $this->db->bind(':item_id', $itemId);
        $item = $this->db->single();

        return $item->quantity >= $requiredQuantity;
    }

    // Get low stock items
    public function getLowStockItems($threshold = 10)
    {
        $this->db->query('SELECT * FROM Inventory WHERE quantity <= :threshold');
        $this->db->bind(':threshold', $threshold);
        return $this->db->resultSet();
    }


    public function getInventoryItems() {
        $this->db->query('SELECT id, name, price, quantity 
                          FROM inventory 
                          WHERE quantity > 0 
                          AND deleted_at IS NULL');
        return $this->db->resultSet();
    }
}
