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

    // public function getUserOrders($userId)
    // {
    //     $this->db->query("SELECT 
    //     so.*, p.name AS product_name
    //     FROM store_orders so
    //     JOIN products p ON so.product_id = p.id
    //     WHERE so.user_id = :user_id 
    //     AND so.deleted_at IS NULL
    //     ORDER BY so.created_at DESC");

    //     $this->db->bind(':user_id', $userId);
    //     return $this->db->resultSet();
    // }

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
            // 1. Update order status to processing
            $this->db->query("UPDATE store_orders 
                         SET status = 'processing',
                             updated_at = CURRENT_TIMESTAMP 
                         WHERE id = :id");
            $this->db->bind(':id', $orderId);

            if (!$this->db->execute()) {
                throw new Exception("Failed to update order status");
            }

            // 2. Create payment record
            $this->db->query("INSERT INTO store_payments 
                         (order_id, payment_method, payment_status) 
                         VALUES 
                         (:order_id, 'bank deposit', false)");

            $this->db->bind(':order_id', $orderId);

            if (!$this->db->execute()) {
                throw new Exception("Failed to create payment record");
            }

            // Get payment ID for bank slip
            $paymentId = $this->db->lastInsertId();

            // 3. Create bank slip record
            $this->db->query("INSERT INTO bank_slips 
                         (payment_id, image, status) 
                         VALUES 
                         (:payment_id, :image, 'pending')");

            $this->db->bind(':payment_id', $paymentId);
            $this->db->bind(':image', $filePath);

            if (!$this->db->execute()) {
                throw new Exception("Failed to create bank slip record");
            }

            return true;
        } catch (Exception $e) {
            error_log('Payment processing error: ' . $e->getMessage());
            return false;
        }
    }

    //new funtions
    // Create order
    public function createOrder($orderData, $cartItems)
    {
        // Generate order number
        $orderNumber = 'ORD' . date('Ymd') . rand(1000, 9999);

        // Insert order
        $this->db->query('INSERT INTO orders (user_id, order_number, total_amount, shipping_address, 
                                           contact_phone, payment_method, status) 
                         VALUES (:user_id, :order_number, :total_amount, :shipping_address, 
                                :contact_phone, :payment_method, :status)');

        $this->db->bind(':user_id', $orderData['user_id']);
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':total_amount', $orderData['total_amount']);
        $this->db->bind(':shipping_address', $orderData['shipping_address']);
        $this->db->bind(':contact_phone', $orderData['contact_phone']);
        $this->db->bind(':payment_method', $orderData['payment_method']);
        $this->db->bind(':status', 'pending');

        if (!$this->db->execute()) {
            return false;
        }

        $orderId = $this->db->lastInsertId();

        // Insert order items
        $allItemsInserted = true;
        foreach ($cartItems as $item) {
            $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price_at_time) 
                             VALUES (:order_id, :product_id, :quantity, :price_at_time)');

            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':product_id', $item->product_id);
            $this->db->bind(':quantity', $item->quantity);
            $this->db->bind(':price_at_time', $item->price_at_time);

            if (!$this->db->execute()) {
                $allItemsInserted = false;
                break;
            }
        }

        // If any order item failed to insert, you might want to manually delete the order
        if (!$allItemsInserted) {
            $this->deleteOrder($orderId);
            return false;
        }

        return $orderId;
    }

    // Helper function to delete an order if item insertion fails
    private function deleteOrder($orderId)
    {
        // Delete any already inserted order items
        $this->db->query('DELETE FROM order_items WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        $this->db->execute();

        // Delete the order
        $this->db->query('DELETE FROM orders WHERE id = :id');
        $this->db->bind(':id', $orderId);
        $this->db->execute();
    }

    // Get order by ID
    public function getOrderById($orderId)
    {
        $this->db->query('SELECT o.*, u.name as customer_name, u.email 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.user_id 
                     WHERE o.id = :id');
        $this->db->bind(':id', $orderId);
        return $this->db->single();
    }

    // Get user orders
    public function getUserOrders($userId)
    {
        $this->db->query('SELECT o.*, 
                     (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as total_items 
                     FROM orders o 
                     WHERE o.user_id = :user_id 
                     ORDER BY o.created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get order items
    public function getOrderItems($orderId)
    {
        $this->db->query('SELECT oi.*, p.name, p.image1 
                     FROM order_items oi 
                     JOIN products p ON oi.product_id = p.id 
                     WHERE oi.order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    // Get order total
    public function getOrderTotal($orderId)
    {
        $this->db->query('SELECT SUM(quantity * price_at_time) as total 
                     FROM order_items 
                     WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Get order payment
    // public function getOrderPayment($orderId)
    // {
    //     $this->db->query('SELECT * FROM payments WHERE order_id = :order_id');
    //     $this->db->bind(':order_id', $orderId);
    //     return $this->db->single();
    // }
    public function getOrderPayment($orderId)
    {
        $this->db->query('SELECT * FROM payments WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        $result = $this->db->single();
        return $result ?: null; // Return null if no record is found
    }

    // Update order status
    public function updateOrderStatus($orderId, $status)
    {
        $this->db->query('UPDATE orders 
                     SET status = :status, 
                         updated_at = CURRENT_TIMESTAMP 
                     WHERE id = :id');

        $this->db->bind(':status', $status);
        $this->db->bind(':id', $orderId);

        return $this->db->execute();
    }

    // Update order details
    public function updateOrder($orderId, $data)
    {
        $this->db->query('UPDATE orders 
                     SET shipping_address = :shipping_address, 
                         contact_phone = :contact_phone, 
                         payment_method = :payment_method, 
                         updated_at = CURRENT_TIMESTAMP 
                     WHERE id = :id');

        $this->db->bind(':shipping_address', $data['shipping_address']);
        $this->db->bind(':contact_phone', $data['contact_phone']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':id', $orderId);

        return $this->db->execute();
    }

    // Get orders by status
    public function getOrdersByStatus($status)
    {
        $this->db->query('SELECT o.*, u.name as customer_name 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.user_id 
                     WHERE o.status = :status 
                     ORDER BY o.created_at DESC');
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    // Get all orders (for admin)
    public function getAllOrders()
    {
        $this->db->query('SELECT o.*, u.name as customer_name 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.user_id 
                     ORDER BY o.created_at DESC');
        return $this->db->resultSet();
    }

    public function getRecentOrders($limit = 10, $startDate = null, $endDate = null)
    {
        $sql = "SELECT 
            so.id, 
            so.product_id,
            so.user_id,
            so.quantity,
            so.price, 
            so.delivery_fee,
            so.discount,
            so.status,
            so.created_at,
            p.name AS product_name,
            u.name AS customer_name
        FROM store_orders so
        JOIN products p ON so.product_id = p.id
        JOIN users u ON so.user_id = u.user_id
        WHERE so.deleted_at IS NULL ";

        // Add date filters if provided
        if ($startDate && $endDate) {
            $sql .= "AND so.created_at BETWEEN :start_date AND :end_date ";
        } elseif ($startDate) {
            $sql .= "AND so.created_at >= :start_date ";
        } elseif ($endDate) {
            $sql .= "AND so.created_at <= :end_date ";
        }

        $sql .= "ORDER BY so.created_at DESC LIMIT :limit";

        $this->db->query($sql);

        // Bind parameters
        if ($startDate) {
            $this->db->bind(':start_date', $startDate . ' 00:00:00');
        }
        if ($endDate) {
            $this->db->bind(':end_date', $endDate . ' 23:59:59');
        }
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    /**
     * Get order statistics
     *
     * @return array Order statistics
     */
    public function getOrderStats()
    {
        // Get total number of orders
        $this->db->query("SELECT COUNT(*) as total FROM store_orders WHERE deleted_at IS NULL");
        $totalOrders = $this->db->single()->total;

        // Get total revenue
        $this->db->query("SELECT SUM(price * quantity + delivery_fee - IFNULL(discount, 0)) as total 
                     FROM store_orders WHERE deleted_at IS NULL");
        $totalRevenue = $this->db->single()->total ?? 0;

        // Get orders by status
        $this->db->query("SELECT status, COUNT(*) as count 
                     FROM store_orders 
                     WHERE deleted_at IS NULL 
                     GROUP BY status");
        $ordersByStatus = [];
        foreach ($this->db->resultSet() as $row) {
            $ordersByStatus[$row->status] = $row->count;
        }

        return [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'ordersByStatus' => $ordersByStatus
        ];
    }

    /**
     * Get top selling products
     *
     * @param int $limit Number of products to retrieve
     * @return array Top selling products
     */
    public function getTopSellingProducts($limit = 5)
    {
        $this->db->query("SELECT 
                        p.id,
                        p.name,
                        SUM(so.quantity) as units_sold,
                        SUM(so.price * so.quantity) as total_revenue
                     FROM store_orders so
                     JOIN products p ON so.product_id = p.id
                     WHERE so.deleted_at IS NULL
                     GROUP BY p.id, p.name
                     ORDER BY units_sold DESC
                     LIMIT :limit");

        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Format recent orders for report display
     *
     * @param int $limit Number of orders to retrieve
     * @return array Formatted recent orders
     */
    public function getFormattedRecentOrders($limit = 10)
    {
        $orders = $this->getRecentOrders($limit);
        $formattedOrders = [];

        foreach ($orders as $order) {
            $formattedOrder = new stdClass();
            $formattedOrder->order_number = 'ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $formattedOrder->customer_name = $order->customer_name;
            $formattedOrder->total_amount = ($order->price * $order->quantity) + $order->delivery_fee - ($order->discount ?? 0);
            $formattedOrder->status = $order->status;
            $formattedOrder->created_at = $order->created_at;

            $formattedOrders[] = $formattedOrder;
        }

        return $formattedOrders;
    }

    // Record that a bank slip was downloaded
    public function recordSlipDownload($orderId, $bankId)
    {
        // Check if record already exists
        $this->db->query('SELECT id FROM slip_download WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        $existing = $this->db->single();

        if ($existing) {
            // Update existing record
            $this->db->query('UPDATE slip_download SET bank_id = :bank_id, downloaded_at = CURRENT_TIMESTAMP WHERE order_id = :order_id');
            $this->db->bind(':bank_id', $bankId);
            $this->db->bind(':order_id', $orderId);
        } else {
            // Create new record
            $this->db->query('INSERT INTO slip_download (order_id, bank_id) VALUES (:order_id, :bank_id)');
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':bank_id', $bankId);
        }

        return $this->db->execute();
    }

    // Get slip download record
    public function getSlipDownload($orderId)
    {
        $this->db->query('SELECT * FROM slip_download WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->single();
    }

    //Delivery
    public function getDeliveryPersons()
    {
        $this->db->query('SELECT e.employee_id, u.name 
                     FROM employees e 
                     JOIN users u ON e.user_id = u.user_id 
                     WHERE e.role = "deliveryPerson"');
        return $this->db->resultSet();
    }

    /**
     * Assign delivery person to order
     */
    public function assignDeliveryPerson($orderId, $deliveryPersonId)
    {
        $this->db->query('UPDATE orders 
                     SET deliver_id = :deliver_id, 
                         status = "shipped" 
                     WHERE id = :order_id');
        $this->db->bind(':deliver_id', $deliveryPersonId);
        $this->db->bind(':order_id', $orderId);
        return $this->db->execute();
    }

    /**
     * Get delivery person information for an order
     */
    public function getOrderDeliveryPerson($orderId)
    {
        $this->db->query('SELECT e.employee_id, u.name 
                     FROM orders o
                     JOIN employees e ON o.deliver_id = e.employee_id
                     JOIN users u ON e.user_id = u.user_id
                     WHERE o.id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->single();
    }

    public function getDeliveryPersonPendingOrders($deliveryPersonId)
    {
        $this->db->query('SELECT o.*, u.name as customer_name
                         FROM orders o
                         JOIN users u ON o.user_id = u.user_id
                         WHERE o.deliver_id = :delivery_person_id
                         AND o.status = "shipped"
                         AND o.delivered_at IS NULL
                         ORDER BY o.created_at DESC');
        $this->db->bind(':delivery_person_id', $deliveryPersonId);
        return $this->db->resultSet();
    }

    public function getDeliveryPersonCompletedOrders($deliveryPersonId)
    {
        $this->db->query('SELECT o.*, u.name as customer_name
                     FROM orders o
                     JOIN users u ON o.user_id = u.user_id
                     WHERE o.deliver_id = :delivery_person_id
                     AND o.status = "delivered"
                     AND o.delivered_at IS NOT NULL
                     ORDER BY o.delivered_at DESC');
        $this->db->bind(':delivery_person_id', $deliveryPersonId);
        return $this->db->resultSet();
    }

    // Mark order as delivered for deliveryPerson
    public function markOrderAsDelivered($orderId, $deliveryReport = null)
    {
        $this->db->query('UPDATE orders 
                     SET delivery_report = :delivery_report, 
                         delivered_at = CURRENT_TIMESTAMP         
                     WHERE id = :order_id');
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':delivery_report', $deliveryReport);
        return $this->db->execute();
    }
}
