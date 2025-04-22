<?php
class M_ChiefCoordinator {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    // Project Statistics
    public function getProjectStats() {
        // Get total projects count
        $this->db->query('SELECT 
            IFNULL((SELECT COUNT(*) FROM projects WHERE deleted_at IS NULL), 0) +
            IFNULL((SELECT COUNT(*) FROM pre_projects WHERE status != "cancelled"), 0) 
            AS total_projects');
        $result = $this->db->single();
        $totalProjects = $result ? $result->total_projects : 0;
        
        // Get active projects count
        $this->db->query('SELECT 
            IFNULL((SELECT COUNT(*) FROM projects WHERE status = "active" AND deleted_at IS NULL), 0) +
            IFNULL((SELECT COUNT(*) FROM pre_projects WHERE status = "active"), 0) 
            AS active_projects');
        $result = $this->db->single();
        $activeProjects = $result ? $result->active_projects : 0;
        
        // Get completed projects count
        $this->db->query('SELECT 
            IFNULL((SELECT COUNT(*) FROM projects WHERE status = "completed" AND deleted_at IS NULL), 0) +
            IFNULL((SELECT COUNT(*) FROM pre_projects WHERE status = "completed"), 0) 
            AS completed_projects');
        $result = $this->db->single();
        $completedProjects = $result ? $result->completed_projects : 0;
        
        // Get cancelled projects count
        $this->db->query('SELECT 
            IFNULL((SELECT COUNT(*) FROM projects WHERE status = "cancelled" AND deleted_at IS NULL), 0) +
            IFNULL((SELECT COUNT(*) FROM pre_projects WHERE status = "cancelled"), 0) 
            AS cancelled_projects');
        $result = $this->db->single();
        $cancelledProjects = $result ? $result->cancelled_projects : 0;
        
        return [
            'total' => $totalProjects,
            'active' => $activeProjects,
            'completed' => $completedProjects,
            'cancelled' => $cancelledProjects
        ];
    }
    
    public function getMonthlyProjectStats() {
        $this->db->query('SELECT 
            DATE_FORMAT(created_at, "%Y-%m") AS month,
            COUNT(*) AS count
            FROM (
                SELECT created_at FROM projects WHERE deleted_at IS NULL
                UNION ALL
                SELECT created_at FROM pre_projects
            ) AS combined_projects
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, "%Y-%m")
            ORDER BY month');
        
        return $this->db->resultSet();
    }
    
    public function getProjectStatusStats() {
        $this->db->query('SELECT 
            "Active" AS status, COUNT(*) AS count
            FROM (
                SELECT status FROM projects WHERE status = "active" AND deleted_at IS NULL
                UNION ALL
                SELECT status FROM pre_projects WHERE status = "active"
            ) AS active_projects
            UNION
            SELECT "Completed" AS status, COUNT(*) AS count
            FROM (
                SELECT status FROM projects WHERE status = "completed" AND deleted_at IS NULL
                UNION ALL
                SELECT status FROM pre_projects WHERE status = "completed"
            ) AS completed_projects
            UNION
            SELECT "Cancelled" AS status, COUNT(*) AS count
            FROM (
                SELECT status FROM projects WHERE status = "cancelled" AND deleted_at IS NULL
                UNION ALL
                SELECT status FROM pre_projects WHERE status = "cancelled"
            ) AS cancelled_projects');
        
        return $this->db->resultSet();
    }
    
    public function getProjectPhaseStats() {
        // For pre_projects
        $this->db->query('SELECT 
            current_phase, COUNT(*) as count
            FROM pre_projects
            WHERE status = "active"
            GROUP BY current_phase');
        
        $preProjectPhases = $this->db->resultSet();
        
        // For projects
        $this->db->query('SELECT 
            current_phase, COUNT(*) as count
            FROM projects
            WHERE status = "active" AND deleted_at IS NULL
            GROUP BY current_phase');
        
        $projectPhases = $this->db->resultSet();
        
        // Combine results
        $phaseStats = [];
        
        foreach ($preProjectPhases as $phase) {
            if (!isset($phaseStats[$phase->current_phase])) {
                $phaseStats[$phase->current_phase] = 0;
            }
            $phaseStats[$phase->current_phase] += $phase->count;
        }
        
        foreach ($projectPhases as $phase) {
            if (!isset($phaseStats[$phase->current_phase])) {
                $phaseStats[$phase->current_phase] = 0;
            }
            $phaseStats[$phase->current_phase] += $phase->count;
        }
        
        // Convert to array of objects for compatibility with other methods
        $result = [];
        foreach ($phaseStats as $phase => $count) {
            $obj = new stdClass();
            $obj->phase = $phase;
            $obj->count = $count;
            $result[] = $obj;
        }
        
        return $result;
    }
    
    // Payment Statistics
    public function getPaymentStats() {
        // Since payment system isn't fully implemented, we'll create mock data
        return [
            'total' => 145000.00,
            'pending' => 35000.00,
            'completed' => 110000.00,
            'this_month' => 45000.00
        ];
    }
    
    public function getMonthlyPaymentStats() {
        // Mock data for monthly payments
        $result = [];
        
        // Get last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = new DateTime();
            $date->modify("-$i months");
            $month = $date->format("Y-m");
            
            $obj = new stdClass();
            $obj->month = $month;
            $obj->amount = rand(15000, 50000);
            $result[] = $obj;
        }
        
        return $result;
    }
    
    public function getPaymentMethodStats() {
        // Mock data for payment methods
        $result = [];
        
        $methods = ['cash', 'bank deposit', 'online'];
        foreach ($methods as $method) {
            $obj = new stdClass();
            $obj->method = $method;
            $obj->count = rand(10, 50);
            $result[] = $obj;
        }
        
        return $result;
    }
    
    // Store Statistics
    public function getStoreStats() {
        // Get total orders count
        $this->db->query('SELECT COUNT(*) AS total_orders FROM store_orders WHERE deleted_at IS NULL');
        $totalOrders = $this->db->single()->total_orders;
        
        // Get pending orders count
        $this->db->query('SELECT COUNT(*) AS pending_orders FROM store_orders 
                          WHERE status IN ("pending", "approved", "processing") AND deleted_at IS NULL');
        $pendingOrders = $this->db->single()->pending_orders;
        
        // Get completed orders count
        $this->db->query('SELECT COUNT(*) AS completed_orders FROM store_orders 
                          WHERE status IN ("delivered", "ready for pickup") AND deleted_at IS NULL');
        $completedOrders = $this->db->single()->completed_orders;
        
        // Get total revenue
        $this->db->query('SELECT SUM(price * quantity) AS total_revenue FROM store_orders WHERE deleted_at IS NULL');
        $totalRevenue = $this->db->single()->total_revenue ?? 0;
        
        return [
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'total_revenue' => $totalRevenue
        ];
    }
    
    public function getMonthlyStoreStats() {
        $this->db->query('SELECT 
            DATE_FORMAT(created_at, "%Y-%m") AS month,
            COUNT(*) AS order_count,
            SUM(price * quantity) AS revenue
            FROM store_orders
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH) AND deleted_at IS NULL
            GROUP BY DATE_FORMAT(created_at, "%Y-%m")
            ORDER BY month');
        
        return $this->db->resultSet();
    }
    
    public function getProductCategoryStats() {
        $this->db->query('SELECT 
            category, COUNT(*) AS order_count
            FROM store_orders so
            JOIN products p ON so.product_id = p.id
            WHERE so.deleted_at IS NULL
            GROUP BY p.category');
        
        return $this->db->resultSet();
    }
    
    // Employee Statistics
    public function getEmployeeStats() {
        // Get total employees count
        $this->db->query('SELECT COUNT(*) AS total_employees FROM employees');
        $totalEmployees = $this->db->single()->total_employees;
        
        // Get employees by role
        $this->db->query('SELECT role, COUNT(*) AS count FROM employees GROUP BY role');
        $employeesByRole = $this->db->resultSet();
        
        // Get leave requests
        $this->db->query('SELECT COUNT(*) AS pending_leaves FROM holidayrecords WHERE status = "Pending"');
        $pendingLeaves = $this->db->single()->pending_leaves;
        
        // Get attendance for today
        $today = date('Y-m-d');
        $this->db->query('SELECT COUNT(*) AS present_today FROM attendance WHERE date = :today');
        $this->db->bind(':today', $today);
        $presentToday = $this->db->single()->present_today;
        
        return [
            'total_employees' => $totalEmployees,
            'employees_by_role' => $employeesByRole,
            'pending_leaves' => $pendingLeaves,
            'present_today' => $presentToday
        ];
    }
    
    public function getMonthlyAttendanceStats() {
        // Get attendance by month for the last 12 months
        $this->db->query('SELECT 
            DATE_FORMAT(date, "%Y-%m") AS month,
            COUNT(DISTINCT employee_id) AS employee_count
            FROM attendance
            WHERE date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(date, "%Y-%m")
            ORDER BY month');
        
        return $this->db->resultSet();
    }
    
    public function getMonthlyLeaveStats() {
        // Get leave requests by month for the last 12 months
        $this->db->query('SELECT 
            DATE_FORMAT(start_date, "%Y-%m") AS month,
            COUNT(*) AS leave_count
            FROM holidayrecords
            WHERE start_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(start_date, "%Y-%m")
            ORDER BY month');
        
        return $this->db->resultSet();
    }
    
    public function getEmployeeRoleStats() {
        $this->db->query('SELECT role, COUNT(*) AS count FROM employees GROUP BY role');
        return $this->db->resultSet();
    }
}