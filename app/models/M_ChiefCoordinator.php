<?php
// File: app/models/M_ChiefCoordinator.php

class M_ChiefCoordinator {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPreProjectStats() {
        // Get count of pre-projects by status
        $this->db->query('SELECT status, COUNT(*) as count FROM pre_projects GROUP BY status');
        $statusStats = $this->db->resultSet();
        
        // Get count of pre-projects by phase
        $this->db->query('SELECT current_phase, COUNT(*) as count FROM pre_projects WHERE status = "active" GROUP BY current_phase');
        $phaseStats = $this->db->resultSet();
        
        // Get quotation stats
        $this->db->query('SELECT status, COUNT(*) as count FROM customerquotation GROUP BY status');
        $quotationStats = $this->db->resultSet();
        
        // Get monthly stats for the current year
        $this->db->query('SELECT MONTH(created_at) as month, COUNT(*) as count FROM pre_projects 
                         WHERE YEAR(created_at) = YEAR(CURRENT_DATE) GROUP BY MONTH(created_at)');
        $monthlyStats = $this->db->resultSet();
        
        return [
            'status_stats' => $statusStats,
            'phase_stats' => $phaseStats,
            'quotation_stats' => $quotationStats,
            'monthly_stats' => $monthlyStats
        ];
    }
    
    public function getAllPreProjects($page = 1, $limit = 10) {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        // Get pre-projects with pagination
        $this->db->query('SELECT p.*, u.name as customer_name, 
                         (SELECT COUNT(*) FROM customerquotation WHERE pre_project_id = p.pre_project_id) as quotation_count
                         FROM pre_projects p
                         JOIN users u ON p.customer_id = u.user_id
                         ORDER BY p.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        $preProjects = $this->db->resultSet();
        
        // Get total count for pagination
        $this->db->query('SELECT COUNT(*) as total FROM pre_projects');
        $totalCount = $this->db->single()->total;
        
        return [
            'pre_projects' => $preProjects,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }
    
    public function getRecentQuotations($page = 1, $limit = 10) {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        $this->db->query('SELECT cq.*, u.name as customer_name, p.title as package_name
                         FROM customerquotation cq
                         JOIN users u ON cq.user_id = u.user_id
                         LEFT JOIN package p ON cq.package_id = p.package_id
                         ORDER BY cq.created_at DESC
                         LIMIT :limit OFFSET :offset');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        $quotations = $this->db->resultSet();
        
        // Get total count for pagination
        $this->db->query('SELECT COUNT(*) as total FROM customerquotation');
        $totalCount = $this->db->single()->total;
        
        return [
            'quotations' => $quotations,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }

    // public function getProjects($page = 1, $limit = 10, $timeframe = 'all', $phase = 'all', $status = 'all') {
    //     // Calculate offset for pagination
    //     $offset = ($page - 1) * $limit;
        
    //     // Base query with necessary JOINs
    //     $query = 'SELECT p.*, 
    //               COALESCE(u.name, "Unknown") as customer_name,
    //               COALESCE(pk.title, "N/A") as package_name
    //               FROM projects p
    //               LEFT JOIN users u ON p.customer_id = u.user_id
    //               LEFT JOIN package pk ON p.package_id = pk.package_id
    //               ORDER BY p.created_at DESC 
    //               LIMIT :limit OFFSET :offset';
        
    //     $this->db->query($query);
    //     $this->db->bind(':limit', $limit);
    //     $this->db->bind(':offset', $offset);
        
    //     $projects = $this->db->resultSet();
        
    //     // Get total count
    //     $this->db->query('SELECT COUNT(*) as total FROM projects');
    //     $totalCount = $this->db->single()->total;
        
    //     return [
    //         'projects' => $projects,
    //         'total' => $totalCount,
    //         'page' => $page,
    //         'limit' => $limit,
    //         'total_pages' => ceil($totalCount / $limit)
    //     ];
    // }

    public function getProjects($page = 1, $limit = 10, $timeframe = 'all', $phase = 'all', $status = 'all') {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        // Base query with necessary JOINs
        $query = 'SELECT p.*, 
        COALESCE(u.name, "Unknown") as customer_name,
        COALESCE(pk.title, "N/A") as package_name
        FROM projects p
        LEFT JOIN users u ON p.customer_id = u.user_id
        LEFT JOIN package pk ON p.package_id = pk.package_id
        WHERE 1=1'; // This allows us to conditionally add WHERE clauses
        
        // Add timeframe filter
        if ($timeframe != 'all') {
            switch ($timeframe) {
                case 'today':
                    $query .= ' AND DATE(p.created_at) = CURDATE()';
                    break;
                case 'week':
                    $query .= ' AND YEARWEEK(p.created_at, 1) = YEARWEEK(CURDATE(), 1)';
                    break;
                case 'month':
                    $query .= ' AND MONTH(p.created_at) = MONTH(CURDATE()) AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
                case 'year':
                    $query .= ' AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
            }
        }
        
        // Add phase filter
        if ($phase != 'all') {
            $query .= ' AND p.current_phase = :phase';
        }
        
        // Add status filter
        if ($status != 'all') {
            $query .= ' AND p.status = :status';
        }
        
        // Add ordering and limit
        $query .= ' ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset';
        
        $this->db->query($query);
        
        // Bind parameters if set
        if ($phase != 'all') {
            $this->db->bind(':phase', $phase);
        }
        
        if ($status != 'all') {
            $this->db->bind(':status', $status);
        }
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        $projects = $this->db->resultSet();
        
        // Get total count for pagination (with filters but no limit)
        $countQuery = 'SELECT COUNT(*) as total FROM projects p WHERE 1=1';
        
        // Add same filters to count query
        if ($timeframe != 'all') {
            switch ($timeframe) {
                case 'today':
                    $countQuery .= ' AND DATE(p.created_at) = CURDATE()';
                    break;
                case 'week':
                    $countQuery .= ' AND YEARWEEK(p.created_at, 1) = YEARWEEK(CURDATE(), 1)';
                    break;
                case 'month':
                    $countQuery .= ' AND MONTH(p.created_at) = MONTH(CURDATE()) AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
                case 'year':
                    $countQuery .= ' AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
            }
        }
        
        if ($phase != 'all') {
            $countQuery .= ' AND p.current_phase = :phase';
        }
        
        if ($status != 'all') {
            $countQuery .= ' AND p.status = :status';
        }
        
        $this->db->query($countQuery);
        
        // Bind parameters if set
        if ($phase != 'all') {
            $this->db->bind(':phase', $phase);
        }
        
        if ($status != 'all') {
            $this->db->bind(':status', $status);
        }
        
        $totalCount = $this->db->single()->total;
        
        return [
            'projects' => $projects,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }
    
    // Get project statistics for charts
    public function getProjectStats($timeframe = 'all') {
        // Timeframe clause for all queries
        $timeClause = '';
        if ($timeframe != 'all') {
            switch ($timeframe) {
                case 'today':
                    $timeClause = ' AND DATE(created_at) = CURDATE()';
                    break;
                case 'week':
                    $timeClause = ' AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)';
                    break;
                case 'month':
                    $timeClause = ' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())';
                    break;
                case 'year':
                    $timeClause = ' AND YEAR(created_at) = YEAR(CURDATE())';
                    break;
            }
        }
        
        // Get count of projects by status
        $this->db->query('SELECT status, COUNT(*) as count FROM projects WHERE 1=1' . $timeClause . ' GROUP BY status');
        $statusStats = $this->db->resultSet();
        
        // Get count of projects by phase
        $this->db->query('SELECT current_phase, COUNT(*) as count FROM projects WHERE status = "active"' . $timeClause . ' GROUP BY current_phase');
        $phaseStats = $this->db->resultSet();
        
        // Get monthly stats for the current year
        $this->db->query('SELECT MONTH(created_at) as month, COUNT(*) as count FROM projects 
                         WHERE YEAR(created_at) = YEAR(CURRENT_DATE) GROUP BY MONTH(created_at)');
        $monthlyStats = $this->db->resultSet();
        
        // Get equipment release stats
        $this->db->query('SELECT 
                         equipment_released, 
                         COUNT(*) as count 
                         FROM projects 
                         WHERE status = "active"' . $timeClause . ' 
                         GROUP BY equipment_released');
        $equipmentStats = $this->db->resultSet();
        
        return [
            'status_stats' => $statusStats,
            'phase_stats' => $phaseStats,
            'monthly_stats' => $monthlyStats,
            'equipment_stats' => $equipmentStats
        ];
    }

    public function getStoreStats($timeframe = 'all') {
        $timeCondition = "WHERE deleted_at IS NULL";
    
        switch ($timeframe) {
            case 'today':
                $timeCondition .= " AND DATE(so.created_at) = CURDATE()";
                break;
            case 'week':
                $timeCondition .= " AND YEARWEEK(so.created_at, 1) = YEARWEEK(CURDATE(), 1)";
                break;
            case 'month':
                $timeCondition .= " AND MONTH(so.created_at) = MONTH(CURDATE()) AND YEAR(so.created_at) = YEAR(CURDATE())";
                break;
            case 'year':
                $timeCondition .= " AND YEAR(so.created_at) = YEAR(CURDATE())";
                break;
        }
        
        // Get order count by status
        $this->db->query("SELECT status, COUNT(*) as count FROM store_orders so 
                         $timeCondition AND deleted_at IS NULL
                         GROUP BY status");
        $orderStatusStats = $this->db->resultSet();
        
        // Get total income (include only completed orders)
        $this->db->query("SELECT COALESCE(SUM(price * quantity + delivery_fee - COALESCE(discount, 0)), 0) as total_income 
        FROM store_orders so 
        $timeCondition AND status IN ('delivered', 'processing', 'ready for pickup', 'out for delivery') 
        AND deleted_at IS NULL");
        $incomeStats = $this->db->single();
        
        // Get payment method stats
        $this->db->query("SELECT sp.payment_method, COUNT(*) as count 
                         FROM store_payments sp
                         JOIN store_orders so ON sp.order_id = so.id
                         $timeCondition AND so.deleted_at IS NULL
                         GROUP BY sp.payment_method");
        $paymentMethodStats = $this->db->resultSet();
        
        // Get monthly order statistics
        $this->db->query("SELECT MONTH(created_at) as month, COUNT(*) as count 
                         FROM store_orders 
                         WHERE YEAR(created_at) = YEAR(CURRENT_DATE) AND deleted_at IS NULL
                         GROUP BY MONTH(created_at)");
        $monthlyStats = $this->db->resultSet();
        
        return [
            'order_status_stats' => $orderStatusStats,
            'income_stats' => $incomeStats,
            'payment_method_stats' => $paymentMethodStats,
            'monthly_stats' => $monthlyStats
        ];
    }
    
    public function getStoreOrders($page = 1, $limit = 10, $timeframe = 'all') {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        $timeCondition = '';
        switch ($timeframe) {
            case 'today':
                $timeCondition = "AND DATE(so.created_at) = CURDATE()";
                break;
            case 'week':
                $timeCondition = "AND YEARWEEK(so.created_at, 1) = YEARWEEK(CURDATE(), 1)";
                break;
            case 'month':
                $timeCondition = "AND MONTH(so.created_at) = MONTH(CURDATE()) AND YEAR(so.created_at) = YEAR(CURDATE())";
                break;
            case 'year':
                $timeCondition = "AND YEAR(so.created_at) = YEAR(CURDATE())";
                break;
            default:
                $timeCondition = "";
        }
        
        // Get orders with pagination
        $this->db->query("SELECT so.*, p.name as product_name, u.name as customer_name
                         FROM store_orders so
                         JOIN products p ON so.product_id = p.id
                         JOIN users u ON so.user_id = u.user_id
                         WHERE so.deleted_at IS NULL $timeCondition
                         ORDER BY so.created_at DESC
                         LIMIT :limit OFFSET :offset");
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        $orders = $this->db->resultSet();
        
        // Get total count for pagination
        $this->db->query("SELECT COUNT(*) as total FROM store_orders so 
                         WHERE so.deleted_at IS NULL $timeCondition");
        $totalCount = $this->db->single()->total;
        
        return [
            'orders' => $orders,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }

    public function getPayments($page = 1, $limit = 10, $timeframe = 'all', $payment_type = 'all') {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        // Base query with necessary JOINs
        $query = 'SELECT p.*, o.order_id, pr.pre_project_id, pr.project_id, 
                  COALESCE(u.name, "Unknown") as customer_name
                  FROM payment_log p
                  LEFT JOIN orders o ON p.order_id = o.order_id
                  LEFT JOIN projects pr ON p.project_id = pr.project_id
                  LEFT JOIN users u ON pr.customer_id = u.user_id
                  WHERE 1=1'; // This allows us to conditionally add WHERE clauses
        
        // Add timeframe filter
        if ($timeframe != 'all') {
            switch ($timeframe) {
                case 'today':
                    $query .= ' AND DATE(p.created_at) = CURDATE()';
                    break;
                case 'week':
                    $query .= ' AND YEARWEEK(p.created_at, 1) = YEARWEEK(CURDATE(), 1)';
                    break;
                case 'month':
                    $query .= ' AND MONTH(p.created_at) = MONTH(CURDATE()) AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
                case 'year':
                    $query .= ' AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
            }
        }
        
        // Add payment type filter
        if ($payment_type != 'all') {
            $query .= ' AND p.payment_type = :payment_type';
        }
        
        // Add ordering and limit
        $query .= ' ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset';
        
        $this->db->query($query);
        
        // Bind parameters if set
        if ($payment_type != 'all') {
            $this->db->bind(':payment_type', $payment_type);
        }
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        $payments = $this->db->resultSet();
        
        // Get total count for pagination (with filters but no limit)
        $countQuery = 'SELECT COUNT(*) as total FROM payment_log p WHERE 1=1';
        
        // Add same filters to count query
        if ($timeframe != 'all') {
            switch ($timeframe) {
                case 'today':
                    $countQuery .= ' AND DATE(p.created_at) = CURDATE()';
                    break;
                case 'week':
                    $countQuery .= ' AND YEARWEEK(p.created_at, 1) = YEARWEEK(CURDATE(), 1)';
                    break;
                case 'month':
                    $countQuery .= ' AND MONTH(p.created_at) = MONTH(CURDATE()) AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
                case 'year':
                    $countQuery .= ' AND YEAR(p.created_at) = YEAR(CURDATE())';
                    break;
            }
        }
        
        if ($payment_type != 'all') {
            $countQuery .= ' AND p.payment_type = :payment_type';
        }
        
        $this->db->query($countQuery);
        
        // Bind parameters if set
        if ($payment_type != 'all') {
            $this->db->bind(':payment_type', $payment_type);
        }
        
        $totalCount = $this->db->single()->total;
        
        return [
            'payments' => $payments,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }
    
    // Get payment statistics for charts
    public function getPaymentStats($timeframe = 'all') {
        // Timeframe clause for all queries
        $timeClause = '';
        if ($timeframe != 'all') {
            switch ($timeframe) {
                case 'today':
                    $timeClause = ' AND DATE(created_at) = CURDATE()';
                    break;
                case 'week':
                    $timeClause = ' AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)';
                    break;
                case 'month':
                    $timeClause = ' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())';
                    break;
                case 'year':
                    $timeClause = ' AND YEAR(created_at) = YEAR(CURDATE())';
                    break;
            }
        }
        
        // Get payments by type
        $this->db->query('SELECT payment_type, COUNT(*) as count, SUM(amount) as total_amount 
                         FROM payment_log 
                         WHERE 1=1' . $timeClause . ' 
                         GROUP BY payment_type');
        $typeStats = $this->db->resultSet();
        
        // Get daily payments for the last 7 days
        $this->db->query('SELECT DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total_amount 
                         FROM payment_log 
                         WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                         GROUP BY DATE(created_at) 
                         ORDER BY date');
        $dailyStats = $this->db->resultSet();
        
        // Get monthly payments for the current year
        $this->db->query('SELECT MONTH(created_at) as month, COUNT(*) as count, SUM(amount) as total_amount 
                         FROM payment_log 
                         WHERE YEAR(created_at) = YEAR(CURRENT_DATE) 
                         GROUP BY MONTH(created_at) 
                         ORDER BY month');
        $monthlyStats = $this->db->resultSet();
        
        return [
            'type_stats' => $typeStats,
            'daily_stats' => $dailyStats,
            'monthly_stats' => $monthlyStats
        ];
    }

    public function getEmployeeStats($timeframe = 'all') {
        // Timeframe clause for queries
        $timeClause = '';
        if ($timeframe != 'all') {
            switch ($timeframe) {
                case 'today':
                    $timeClause = ' AND DATE(a.date) = CURDATE()';
                    break;
                case 'week':
                    $timeClause = ' AND a.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
                    break;
                case 'month':
                    $timeClause = ' AND MONTH(a.date) = MONTH(CURDATE()) AND YEAR(a.date) = YEAR(CURDATE())';
                    break;
            }
        }
        
        // Get employees by role
        $this->db->query('SELECT role, COUNT(*) as count FROM employees GROUP BY role');
        $roleStats = $this->db->resultSet();
        
        // Get attendance stats
        $this->db->query('SELECT 
                        DATE(a.date) as date, 
                        COUNT(DISTINCT a.employee_id) as present_count 
                        FROM attendance a 
                        WHERE 1=1' . $timeClause . '
                        GROUP BY DATE(a.date) 
                        ORDER BY date DESC 
                        LIMIT 7');
        $attendanceStats = $this->db->resultSet();
        
        // Get leave stats
        $this->db->query('SELECT 
                        leave_type, 
                        COUNT(*) as count 
                        FROM leaverecords 
                        WHERE status = "Approved" 
                        GROUP BY leave_type');
        $leaveStats = $this->db->resultSet();
        
        return [
            'role_stats' => $roleStats,
            'attendance_stats' => $attendanceStats,
            'leave_stats' => $leaveStats
        ];
    }
    
    // // Get today's attendance count specifically
    // public function getTodayAttendanceCount() {
    //     $this->db->query('SELECT COUNT(DISTINCT employee_id) as present_count 
    //                      FROM attendance 
    //                      WHERE DATE(date) = CURDATE()');
    //     $result = $this->db->single();
    //     return $result ? $result->present_count : 0;
    // }
    
    // // Get total employee count
    // public function getTotalEmployeeCount() {
    //     $this->db->query('SELECT COUNT(*) as total FROM employees');
    //     $result = $this->db->single();
    //     return $result ? $result->total : 0;
    // }

    // public function getAllEmployees($page = 1, $limit = 10, $role = 'all') {
    //     // Calculate offset for pagination
    //     $offset = ($page - 1) * $limit;
        
    //     // Base query with necessary JOINs
    //     $query = 'SELECT e.*, u.name, u.email, u.contact_no,
    //              (SELECT COUNT(*) FROM attendance a WHERE a.employee_id = e.employee_id) as attendance_count,
    //              (SELECT COUNT(*) FROM leaverecords l WHERE l.employee_id = e.employee_id) as leave_count
    //              FROM employees e
    //              LEFT JOIN users u ON e.user_id = u.user_id
    //              WHERE 1=1';
        
    //     // Add role filter if needed
    //     if ($role != 'all') {
    //         $query .= ' AND e.role = :role';
    //     }
        
    //     // Add ordering and limit
    //     $query .= ' ORDER BY e.employee_id DESC LIMIT :limit OFFSET :offset';
        
    //     $this->db->query($query);
        
    //     // Bind parameters
    //     if ($role != 'all') {
    //         $this->db->bind(':role', $role);
    //     }
    //     $this->db->bind(':limit', $limit);
    //     $this->db->bind(':offset', $offset);
        
    //     $employees = $this->db->resultSet();
        
    //     // Get total count for pagination
    //     $countQuery = 'SELECT COUNT(*) as total FROM employees WHERE 1=1';
    //     if ($role != 'all') {
    //         $countQuery .= ' AND role = :role';
    //     }
        
    //     $this->db->query($countQuery);
    //     if ($role != 'all') {
    //         $this->db->bind(':role', $role);
    //     }
        
    //     $totalCount = $this->db->single()->total;
        
    //     return [
    //         'employees' => $employees,
    //         'total' => $totalCount,
    //         'page' => $page,
    //         'limit' => $limit,
    //         'total_pages' => ceil($totalCount / $limit)
    //     ];
    // }

    public function getRecentAttendance($page = 1, $limit = 10, $date = 'all') {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        // Base query
        $query = 'SELECT a.*, e.role, u.name 
                 FROM attendance a
                 JOIN employees e ON a.employee_id = e.employee_id
                 JOIN users u ON e.user_id = u.user_id
                 WHERE 1=1';
        
        // Add date filter if needed
        if ($date != 'all') {
            $query .= ' AND DATE(a.date) = :date';
        }
        
        // Add ordering and limit
        $query .= ' ORDER BY a.date DESC, a.time_in DESC LIMIT :limit OFFSET :offset';
        
        $this->db->query($query);
        
        // Bind parameters
        if ($date != 'all') {
            $this->db->bind(':date', $date);
        }
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        $attendance = $this->db->resultSet();
        
        // Get total count for pagination
        $countQuery = 'SELECT COUNT(*) as total FROM attendance WHERE 1=1';
        if ($date != 'all') {
            $countQuery .= ' AND DATE(date) = :date';
        }
        
        $this->db->query($countQuery);
        if ($date != 'all') {
            $this->db->bind(':date', $date);
        }
        
        $totalCount = $this->db->single()->total;
        
        return [
            'attendance' => $attendance,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }
    
    public function getLeaveRecords($page = 1, $limit = 10, $status = 'all') {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        // Base query
        $query = 'SELECT l.*, e.role, u.name 
                 FROM leaverecords l
                 JOIN employees e ON l.employee_id = e.employee_id
                 JOIN users u ON e.user_id = u.user_id
                 WHERE 1=1';
        
        // Add status filter if needed
        if ($status != 'all') {
            $query .= ' AND l.status = :status';
        }
        
        // Add ordering and limit
        $query .= ' ORDER BY l.start_date DESC LIMIT :limit OFFSET :offset';
        
        $this->db->query($query);
        
        // Bind parameters
        if ($status != 'all') {
            $this->db->bind(':status', $status);
        }
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        $leaves = $this->db->resultSet();
        
        // Get total count for pagination
        $countQuery = 'SELECT COUNT(*) as total FROM leaverecords WHERE 1=1';
        if ($status != 'all') {
            $countQuery .= ' AND status = :status';
        }
        
        $this->db->query($countQuery);
        if ($status != 'all') {
            $this->db->bind(':status', $status);
        }
        
        $totalCount = $this->db->single()->total;
        
        return [
            'leaves' => $leaves,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }

    // public function getAllEmployees($page = 1, $limit = 10, $role = 'all') {
    //     // Calculate offset for pagination
    //     $offset = ($page - 1) * $limit;
        
    //     // Base query with necessary JOINs - Using LEFT JOIN to ensure we get employees even if they have no user record
    //     $query = 'SELECT e.*, u.name, u.email, u.contact_no,
    //              (SELECT COUNT(*) FROM attendance a WHERE a.employee_id = e.employee_id) as attendance_count,
    //              (SELECT COUNT(*) FROM leaverecords l WHERE l.employee_id = e.employee_id) as leave_count
    //              FROM employees e
    //              LEFT JOIN users u ON e.user_id = u.user_id
    //              WHERE 1=1';
        
    //     // Add role filter if needed
    //     if ($role != 'all') {
    //         $query .= ' AND e.role = :role';
    //     }
        
    //     // Add ordering and limit
    //     $query .= ' ORDER BY e.employee_id DESC LIMIT :limit OFFSET :offset';
        
    //     $this->db->query($query);
        
    //     // Bind parameters
    //     if ($role != 'all') {
    //         $this->db->bind(':role', $role);
    //     }
    //     $this->db->bind(':limit', $limit);
    //     $this->db->bind(':offset', $offset);
        
    //     $employees = $this->db->resultSet();
        
    //     // Get total count for pagination
    //     $countQuery = 'SELECT COUNT(*) as total FROM employees WHERE 1=1';
    //     if ($role != 'all') {
    //         $countQuery .= ' AND role = :role';
    //     }
        
    //     $this->db->query($countQuery);
    //     if ($role != 'all') {
    //         $this->db->bind(':role', $role);
    //     }
        
    //     $totalCount = $this->db->single()->total;
        
    //     // Debug the query results
    //     error_log('Employee query: ' . $query);
    //     error_log('Total employees found: ' . count($employees));
        
    //     return [
    //         'employees' => $employees,
    //         'total' => $totalCount,
    //         'page' => $page,
    //         'limit' => $limit,
    //         'total_pages' => ceil($totalCount / $limit)
    //     ];
    // 
    
    public function getAllEmployees($page = 1, $limit = 10, $role = 'all') {
        // Direct query approach to debug database access
        try {
            // Calculate offset for pagination
            $offset = ($page - 1) * $limit;
            
            // Basic query first to test database access
            $query = "SELECT e.*, u.name, u.email, u.phone 
                     FROM employees e 
                     INNER JOIN users u ON e.user_id = u.user_id 
                     ORDER BY e.employee_id DESC 
                     LIMIT :limit OFFSET :offset";
            
            $this->db->query($query);
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
            
            $employees = $this->db->resultSet();
            
            // If we got results, now add the role and attendance data
            if (!empty($employees)) {
                foreach ($employees as $employee) {
                    // Add role with correct field name for the view
                    $employee->employee_role = $employee->role;
                    
                    // Use contact_no as phone if phone field doesn't exist
                    if (!isset($employee->phone) && isset($employee->contact_no)) {
                        $employee->phone = $employee->contact_no;
                    }
                    
                    // Get attendance and leave counts
                    $this->db->query('SELECT COUNT(*) as count FROM attendance WHERE employee_id = :id');
                    $this->db->bind(':id', $employee->employee_id);
                    $result = $this->db->single();
                    $employee->attendance_count = $result ? $result->count : 0;
                    
                    $this->db->query('SELECT COUNT(*) as count FROM leaverecords WHERE employee_id = :id');
                    $this->db->bind(':id', $employee->employee_id);
                    $result = $this->db->single();
                    $employee->leave_count = $result ? $result->count : 0;
                }
            }
            
            // Get total count for pagination
            $this->db->query('SELECT COUNT(*) as total FROM employees');
            $totalCount = $this->db->single()->total;
            
            error_log('Found ' . count($employees) . ' employees out of ' . $totalCount . ' total');
            
            return [
                'employees' => $employees,
                'total' => $totalCount,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($totalCount / $limit)
            ];
        } catch (Exception $e) {
            error_log('Error in getAllEmployees: ' . $e->getMessage());
            return [
                'employees' => [],
                'total' => 0,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => 0
            ];
        }
    }
    // Get today's attendance count specifically
    public function getTodayAttendanceCount() {
        $this->db->query('SELECT COUNT(DISTINCT employee_id) as present_count 
                         FROM attendance 
                         WHERE DATE(date) = CURDATE()');
        $result = $this->db->single();
        $presentCount = $result ? $result->present_count : 0;
        
        // Debug
        error_log('Today\'s attendance count: ' . $presentCount);
        
        return $presentCount;
    }
    
    // Get total employee count
    public function getTotalEmployeeCount() {
        $this->db->query('SELECT COUNT(*) as total FROM employees');
        $result = $this->db->single();
        $totalCount = $result ? $result->total : 0;
        
        // Debug
        error_log('Total employee count: ' . $totalCount);
        
        return $totalCount;
    }
}