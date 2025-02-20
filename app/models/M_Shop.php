<?php
class M_Shop
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // public function getAllProducts()
    // {
    //     try {
    //         $query = "SELECT p.*, c.category 
    //                  FROM Products p 
    //                  JOIN Categories c ON p.category_id = c.id
    //                  ORDER BY p.name";
    //         $this->db->query($query);
    //         return $this->db->resultSet();
    //     } catch (PDOException $e) {
    //         error_log("Database error: " . $e->getMessage());
    //         return null;
    //     }
    // }

    // public function getProductsByCategory($category)
    // {
    //     try {
    //         $query = "SELECT p.*, c.category 
    //                  FROM Products p 
    //                  JOIN Categories c ON p.category_id = c.id 
    //                  WHERE c.category = :category
    //                  ORDER BY p.name";
    //         $this->db->query($query);
    //         $this->db->bind(':category', $category);
    //         return $this->db->resultSet();
    //     } catch (PDOException $e) {
    //         error_log("Database error: " . $e->getMessage());
    //         return null;
    //     }
    // }

    // public function getProductById($id)
    // {
    //     try {
    //         $query = "SELECT p.*, c.category, s.name as supplier_name, s.contact_number 
    //                  FROM Products p 
    //                  JOIN Categories c ON p.category_id = c.id 
    //                  JOIN Suppliers s ON p.supplier_id = s.id 
    //                  WHERE p.id = :id";
    //         $this->db->query($query);
    //         $this->db->bind(':id', $id);
    //         return $this->db->single();
    //     } catch (PDOException $e) {
    //         error_log("Database error: " . $e->getMessage());
    //         return null;
    //     }
    // }

    public function getProducts()
    {
        $this->db->query('SELECT p.*, s.name as supplier_name 
                         FROM products p 
                         LEFT JOIN suppliers s ON p.supplier_id = s.id 
                         WHERE p.deleted_at IS NULL
                         ORDER BY p.created_at DESC');
        return $this->db->resultSet();
    }

    public function getProductsByCategory($category)
    {
        $this->db->query('SELECT p.*, s.name as supplier_name 
                         FROM products p 
                         LEFT JOIN suppliers s ON p.supplier_id = s.id 
                         WHERE p.deleted_at IS NULL And p.category = :category
                         ORDER BY p.created_at DESC');
        return $this->db->resultSet();
    }

    public function getProductById($id)
    {
        $this->db->query('SELECT p.*, s.name as supplier_name 
                         FROM products p 
                         LEFT JOIN suppliers s ON p.supplier_id = s.id 
                         WHERE p.id = :id AND p.deleted_at IS NULL');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getProductFeatures($productId)
    {
        $this->db->query('SELECT feature FROM product_features 
                         WHERE product_id = :product_id');
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }

    public function addProduct($data)
    {
        $this->db->query('INSERT INTO products (name, price, description, category, 
                         supplier_id, blog_link, image1, image2, image3) 
                         VALUES (:name, :price, :description, :category, 
                         :supplier_id, :blog_link, :image1, :image2, :image3)');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':blog_link', $data['blog_link']);
        $this->db->bind(':image1', $data['image1']);
        $this->db->bind(':image2', $data['image2']);
        $this->db->bind(':image3', $data['image3']);

        if ($this->db->execute()) {
            $productId = $this->db->lastInsertId();

            // Add features if provided
            if (!empty($data['features'])) {
                foreach ($data['features'] as $feature) {
                    $this->addProductFeature($productId, $feature);
                }
            }
            return true;
        }
        return false;
    }

    public function addProductFeature($productId, $feature)
    {
        $this->db->query('INSERT INTO product_features (product_id, feature) 
                         VALUES (:product_id, :feature)');
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':feature', $feature);
        return $this->db->execute();
    }

    public function updateProduct($id, $data)
    {
        $this->db->query('UPDATE products SET 
                         name = :name,
                         price = :price,
                         description = :description,
                         category = :category,
                         supplier_id = :supplier_id,
                         blog_link = :blog_link,
                         image1 = :image1,
                         image2 = :image2,
                         image3 = :image3,
                         updated_at = CURRENT_TIMESTAMP
                         WHERE id = :id');

        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':blog_link', $data['blog_link']);
        $this->db->bind(':image1', $data['image1']);
        $this->db->bind(':image2', $data['image2']);
        $this->db->bind(':image3', $data['image3']);

        return $this->db->execute();
    }

    public function deleteProduct($id)
    {
        $this->db->query('UPDATE products SET deleted_at = CURRENT_TIMESTAMP WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteProductFeatures($productId)
    {
        $this->db->query('DELETE FROM product_features 
                         WHERE product_id = :product_id');
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function addPreOrder($data)
    {
        // First get product price
        $this->db->query("SELECT price FROM products WHERE id = :id");
        $this->db->bind(':id', $data['product_id']);
        $product = $this->db->single();

        if (!$product) {
            return false;
        }

        $this->db->query('INSERT INTO store_orders (
        product_id, user_id, delivery_option, full_name, 
        email, phone_number, street_address, city, 
        province, postal_code, address_notes, quantity,
        price, delivery_fee, status
    ) VALUES (
        :product_id, :user_id, :delivery_option, :full_name,
        :email, :phone_number, :street_address, :city,
        :province, :postal_code, :address_notes, :quantity,
        :price, :delivery_fee, "pending"
    )');

        $delivery_fee = ($data['delivery_option'] === 'deliver') ? 450.00 : 0.00;

        // Bind values
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':delivery_option', $data['delivery_option']);
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':street_address', $data['street_address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':address_notes', $data['address_notes']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':price', $product->price);
        $this->db->bind(':delivery_fee', $delivery_fee);

        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            return false;
        }
    }

    public function getUserOrders($userId)
    {
        $this->db->query("SELECT 
        so.*, p.name AS product_name
        FROM store_orders so
        JOIN products p ON so.product_id = p.id
        WHERE so.user_id = :user_id 
        AND so.deleted_at IS NULL
        ORDER BY so.created_at DESC");

        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getPendingOrders()
    {
        $this->db->query("
        SELECT 
            so.*, p.name AS product_name, p.price AS product_price 
        FROM store_orders so
        JOIN products p ON so.product_id = p.id
        WHERE so.status = 'pending'
        AND so.deleted_at IS NULL
        ORDER BY so.created_at DESC
    ");
        return $this->db->resultSet();
    }

    public function getProcessingOrders()
    {
        $this->db->query("
        SELECT 
            so.*, p.name AS product_name
        FROM store_orders so
        JOIN products p ON so.product_id = p.id
        WHERE so.status = 'processing'
        AND so.deleted_at IS NULL
        ORDER BY so.created_at DESC
    ");
        return $this->db->resultSet();
    }

    public function getActiveOrders()
    {
        $this->db->query("
        SELECT 
            so.*, p.name AS product_name
        FROM store_orders so
        JOIN products p ON so.product_id = p.id
        WHERE so.status IN ('pending', 'approved', 'processing', 'ready for pickup', 'out for delivery')
        AND so.deleted_at IS NULL
        ORDER BY so.created_at DESC
    ");
        return $this->db->resultSet();
    }

    public function getOrderDetails($id)
    {
        $this->db->query("SELECT so.*, p.name as product_name, p.image1 
                          FROM store_orders so
                          JOIN products p ON so.product_id = p.id 
                          WHERE so.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function approveOrder($data)
    {
        $this->db->query("UPDATE store_orders 
                          SET status = 'approved',
                              price = :price,
                              delivery_fee = :delivery_fee,
                              discount = :discount,
                              updated_at = CURRENT_TIMESTAMP
                          WHERE id = :id");

        $this->db->bind(':id', $data->orderId);
        $this->db->bind(':price', $data->price);
        $this->db->bind(':delivery_fee', $data->delivery_fee);
        $this->db->bind(':discount', $data->discount);

        return $this->db->execute();
    }

    public function rejectOrder($orderId)
    {
        $this->db->query("UPDATE store_orders 
                          SET status = 'rejected',
                              updated_at = CURRENT_TIMESTAMP 
                          WHERE id = :id");
        $this->db->bind(':id', $orderId);
        return $this->db->execute();
    }

    public function getOrderDetailsByID($id)
    {
        $this->db->query("SELECT so.*, p.name as product_name, p.image1
                      FROM store_orders so
                      JOIN products p ON so.product_id = p.id
                      WHERE so.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function cancelOrder($orderId)
    {
        $this->db->query("UPDATE store_orders SET status = 'cancelled' WHERE id = :id");
        $this->db->bind(':id', $orderId);
        return $this->db->execute();
    }

    public function processPayment($orderId, $paymentMethod)
    {
        try {
            // Update order status to processing for cash payments
            if ($paymentMethod === 'cash') {
                $this->db->query("UPDATE store_orders SET status = 'processing' WHERE id = :id");
                $this->db->bind(':id', $orderId);

                if (!$this->db->execute()) {
                    return false;
                }

                // Create payment record
                $this->db->query("INSERT INTO store_payments (order_id, payment_method, payment_status) 
                             VALUES (:order_id, :payment_method, :payment_status)");

                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':payment_method', 'cash');
                $this->db->bind(':payment_status', false);

                return $this->db->execute();
            }
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function uploadBankSlip($orderId, $filePath)
    {
        try {
            // First update order status
            $this->db->query("UPDATE store_orders SET status = 'processing' WHERE id = :id");
            $this->db->bind(':id', $orderId);

            if (!$this->db->execute()) {
                throw new Exception("Failed to update order status");
            }

            // Then create payment record
            $this->db->query("INSERT INTO store_payments 
            (order_id, payment_method, payment_status, bank_slip_image, bank_slip_status) 
            VALUES 
            (:order_id, :payment_method, :payment_status, :bank_slip_image, :bank_slip_status)");

            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':payment_method', 'bank');
            $this->db->bind(':payment_status', false);
            $this->db->bind(':bank_slip_image', $filePath);
            $this->db->bind(':bank_slip_status', 'pending');

            if (!$this->db->execute()) {
                throw new Exception("Failed to create payment record");
            }

            return true;
        } catch (Exception $e) {
            error_log('Payment processing error: ' . $e->getMessage());
            return false;
        }
    }
}
