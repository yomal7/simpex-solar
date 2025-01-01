<?php
class M_CustomerProject {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getProjectsByCustomerId($customerId) {
        $this->db->query('SELECT * FROM project 
                         WHERE customer_id = :customer_id 
                         ORDER BY created_at DESC');
        
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }

    public function getProjectById($projectId) {
        $this->db->query('SELECT 
            p.*,
            rq.system_capacity,
            rq.estimated_generation,
            rq.base_price,
            rq.service_charge,
            rq.total_price
            FROM project p
            LEFT JOIN reviewed_quotations rq ON p.quotation_id = rq.review_id
            WHERE p.project_id = :project_id');
        
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function getProjectPhaseDetails($projectId) {
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
            FROM project
            WHERE project_id = :project_id');
        
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function updateProjectPhase($projectId, $phase, $status = 'pending') {
        $this->db->query('UPDATE project SET 
            current_phase = :phase,
            phase_status = :status,
            updated_at = CURRENT_TIMESTAMP
            WHERE project_id = :project_id');
        
        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':phase', $phase);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }
}