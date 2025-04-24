<?php
// app/models/M_Order.php
class M_Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Get order count by status
    public function getOrderCountByStatus($status)
    {
        $this->db->query('SELECT COUNT(*) as count FROM orders WHERE status = :status');
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result->count;
    }

    // Get total orders
    public function getTotalOrders()
    {
        $this->db->query('SELECT COUNT(*) as total FROM orders');
        $result = $this->db->single();
        return $result->total;
    }

    // Get total revenue
    public function getTotalRevenue()
    {
        $this->db->query('SELECT SUM(total_amount) as total_revenue FROM orders 
                          WHERE status != "cancelled"');
        $result = $this->db->single();
        return $result->total_revenue ?? 0;
    }

    // Get orders count by status
    public function getOrdersCountByStatus()
    {
        $this->db->query('SELECT status, COUNT(*) as count FROM orders GROUP BY status');
        $results = $this->db->resultSet();

        $ordersByStatus = [];
        foreach ($results as $result) {
            $ordersByStatus[$result->status] = $result->count;
        }

        return $ordersByStatus;
    }

    // Get top selling products
    public function getTopSellingProducts($limit = 10)
    {
        $this->db->query('SELECT p.name, SUM(oi.quantity) as units_sold, 
                          SUM(oi.quantity * oi.price_at_time) as total_revenue
                          FROM order_items oi
                          JOIN products p ON oi.product_id = p.id
                          JOIN orders o ON oi.order_id = o.id
                          WHERE o.status != "cancelled"
                          GROUP BY p.id
                          ORDER BY units_sold DESC
                          LIMIT :limit');

        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Get recent orders
    public function getRecentOrders($limit = 10)
    {
        $this->db->query('SELECT o.*, u.name as customer_name 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.user_id 
                          ORDER BY o.created_at DESC 
                          LIMIT :limit');

        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
