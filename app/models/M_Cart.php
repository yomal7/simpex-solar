<?php
// app/models/M_Cart.php
class M_Cart
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Get cart by user ID
    public function getCartByUserId($userId)
    {
        $this->db->query('SELECT * FROM cart WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    // Add item to cart
    public function addToCart($data)
    {
        // Check if item already exists in cart
        $this->db->query('SELECT * FROM cart_items WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':product_id', $data['product_id']);

        $existingItem = $this->db->single();

        if ($existingItem) {
            // Update quantity if item exists
            return $this->updateQuantity($existingItem->id, $existingItem->quantity + $data['quantity']);
        } else {
            // Insert new item
            $this->db->query('INSERT INTO cart_items (user_id, product_id, quantity, price_at_time) 
                             VALUES (:user_id, :product_id, :quantity, :price_at_time)');

            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':product_id', $data['product_id']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':price_at_time', $data['price_at_time']);

            return $this->db->execute();
        }
    }

    // Get cart items
    public function getCartItems($userId)
    {
        $this->db->query('SELECT ci.*, p.name, p.image1, p.price as current_price
                         FROM cart_items ci 
                         JOIN products p ON ci.product_id = p.id 
                         WHERE ci.user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Update quantity
    public function updateQuantity($cartItemId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($cartItemId);
        }

        $this->db->query('UPDATE cart_items SET quantity = :quantity WHERE id = :id');
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $cartItemId);
        return $this->db->execute();
    }

    // Remove from cart
    public function removeFromCart($cartItemId)
    {
        $this->db->query('DELETE FROM cart_items WHERE id = :id');
        $this->db->bind(':id', $cartItemId);
        return $this->db->execute();
    }

    // Get cart total
    public function getCartTotal($userId)
    {
        $this->db->query('SELECT SUM(quantity * price_at_time) as total 
                         FROM cart_items 
                         WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Clear cart
    public function clearCart($userId)
    {
        $this->db->query('DELETE FROM cart_items WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
}
