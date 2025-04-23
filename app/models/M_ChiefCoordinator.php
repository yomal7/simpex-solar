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
        
        // Get stage completion time stats (average days spent in each phase)
        $this->db->query('SELECT 
                         current_phase, 
                         AVG(DATEDIFF(updated_at, created_at)) as avg_days 
                         FROM projects 
                         WHERE status = "active"' . $timeClause . ' 
                         GROUP BY current_phase');
        $timeStats = $this->db->resultSet();
        
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
            'time_stats' => $timeStats,
            'equipment_stats' => $equipmentStats
        ];
    }
}