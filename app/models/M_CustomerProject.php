<?php
class M_CustomerProject
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getProjectsByCustomerId($customerId)
    {
        $this->db->query('SELECT * FROM project 
                         WHERE customer_id = :customer_id 
                         ORDER BY created_at DESC');

        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }

    public function getProjectById($projectId)
    {
        $this->db->query('SELECT 
            p.*,
            rq.system_capacity,
            rq.estimated_generation,
            rq.base_price,
            rq.service_charge,
            rq.total_price
            FROM projects p
            LEFT JOIN reviewed_quotations rq ON p.quotation_id = rq.review_id
            WHERE p.project_id = :project_id');

        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function getProjectPhaseDetails($projectId)
    {
        $this->db->query('SELECT 
            current_phase,
            phase_status,
            documents_submitted,
            first_payment_amount,
            final_payment_amount,
            first_payment_date,
            final_payment_date,
            engineer_approval_date,
            grid_connection_date
            FROM projects
            WHERE project_id = :project_id');

        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function updateProjectPhase($projectId, $phase, $status = 'pending')
    {
        $this->db->query('UPDATE projects SET 
            current_phase = :phase,
            phase_status = :status,
            updated_at = CURRENT_TIMESTAMP
            WHERE project_id = :project_id');

        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':phase', $phase);
        $this->db->bind(':status', $status);

        return $this->db->execute();
    }

    public function getProjectCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM projects WHERE status = "active"');
        $result = $this->db->single();
        return $result->count;
    }

    public function getProjectCountByPhase($phase)
    {
        // Simplify the query to avoid potential issues
        $this->db->query('SELECT COUNT(*) as count FROM projects WHERE current_phase = :phase AND status = "active"');
        $this->db->bind(':phase', $phase);
        $result = $this->db->single();

        return ($result && isset($result->count)) ? $result->count : 0;
    }

    public function getAllProjectsWithCustomerDetails()
    {
        $this->db->query('SELECT p.*, u.name as customer_name, IFNULL(cq.nearest_city, "No Location") as location 
             FROM projects p
             LEFT JOIN users u ON p.customer_id = u.user_id
             LEFT JOIN customerquotation cq ON p.pre_project_id = cq.pre_project_id
             WHERE p.status = "active"
             ORDER BY p.created_at DESC');

        return $this->db->resultSet();
    }

    public function getCustomerDetailsByProjectId($projectId)
    {
        $this->db->query('SELECT u.name as customer_name, u.email, u.phone, cq.address, cq.nearest_city as location 
                     FROM projects p
                     LEFT JOIN users u ON p.customer_id = u.user_id
                     LEFT JOIN customerquotation cq ON p.pre_project_id = cq.pre_project_id
                     WHERE p.project_id = :project_id');

        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }
}
