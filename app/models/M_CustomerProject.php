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

    public function getDocumentSubmission($projectId)
    {
        $this->db->query('SELECT * FROM documentSubmission WHERE project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function getDocumentById($documentId)
    {
        $this->db->query('SELECT * FROM documentSubmission WHERE id = :id');
        $this->db->bind(':id', $documentId);
        return $this->db->single();
    }

    public function updateDocumentStatus($documentId, $status, $rejectionReason = null)
    {
        $this->db->query('UPDATE documentSubmission 
                     SET status = :status, 
                         rejection_reason = :rejection_reason, 
                         updated_at = NOW() 
                     WHERE id = :id');

        $this->db->bind(':id', $documentId);
        $this->db->bind(':status', $status);
        $this->db->bind(':rejection_reason', $rejectionReason);

        return $this->db->execute();
    }

    public function updateProjectsPhase($projectId, $phase)
    {
        $this->db->query('UPDATE projects SET 
            current_phase = :phase,
            updated_at = CURRENT_TIMESTAMP
            WHERE project_id = :project_id');

        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':phase', $phase);

        return $this->db->execute();
    }

    /**
     * Get agreement by id
     * 
     * @param int $agreementId Agreement ID
     * @return object|bool Agreement object or false
     */
    public function getAgreementById($agreementId)
    {
        $this->db->query('SELECT * FROM project_agreements WHERE agreement_id = :agreement_id');
        $this->db->bind(':agreement_id', $agreementId);
        return $this->db->single();
    }

    /**
     * Get project payment by phase
     * 
     * @param int $projectId Project ID
     * @param string $phase Payment phase (first_payment, final_payment)
     * @return object|bool Payment object or false
     */
    public function getProjectPayment($projectId, $phase)
    {
        $this->db->query('SELECT * FROM project_payments 
                    WHERE project_id = :project_id 
                    AND payment_phase = :payment_phase
                    ORDER BY created_at DESC
                    LIMIT 1');

        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':payment_phase', $phase);
        return $this->db->single();
    }

    /**
     * Get project bank slip
     * 
     * @param int $projectId Project ID
     * @param string $phase Payment phase (first_payment, final_payment)
     * @return object|bool Bank slip object or false
     */
    public function getProjectBankSlip($projectId, $phase)
    {
        $this->db->query('SELECT pbs.* 
                    FROM project_bankslips pbs
                    JOIN project_payments pp ON pbs.projectpayment_id = pp.id
                    WHERE pp.project_id = :project_id 
                    AND pp.payment_phase = :payment_phase
                    ORDER BY pbs.created_at DESC
                    LIMIT 1');

        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':payment_phase', $phase);
        return $this->db->single();
    }

    /**
     * Update project payment
     * 
     * @param int $paymentId Payment ID
     * @param array $data Payment data
     * @return bool Success status
     */
    public function updateProjectPayment($paymentId, $data)
    {
        $query = 'UPDATE project_payments SET ';
        $params = [];

        if (isset($data['payment_status'])) {
            $params[] = 'payment_status = :payment_status';
        }

        if (isset($data['amount'])) {
            $params[] = 'amount = :amount';
        }

        $query .= implode(', ', $params) . ', updated_at = CURRENT_TIMESTAMP WHERE id = :payment_id';

        $this->db->query($query);
        $this->db->bind(':payment_id', $paymentId);

        if (isset($data['payment_status'])) {
            $this->db->bind(':payment_status', $data['payment_status']);
        }

        if (isset($data['amount'])) {
            $this->db->bind(':amount', $data['amount']);
        }

        return $this->db->execute();
    }

    /**
     * Update bank slip status
     * 
     * @param int $slipId Bank slip ID
     * @param string $status New status
     * @param string|null $rejectReason Reason for rejection (if applicable)
     * @return bool Success status
     */
    public function updateBankSlipStatus($slipId, $status, $rejectReason = null)
    {
        $this->db->query('UPDATE project_bankslips
                    SET status = :status,
                        reject_reason = :reject_reason,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id');

        $this->db->bind(':id', $slipId);
        $this->db->bind(':status', $status);
        $this->db->bind(':reject_reason', $rejectReason);

        return $this->db->execute();
    }

    public function getInstallationPendingReleaseProjects()
    {
        $this->db->query('SELECT p.*, u.name as customer_name, IFNULL(cq.nearest_city, "No Location") as location 
             FROM projects p
             LEFT JOIN users u ON p.customer_id = u.user_id
             LEFT JOIN customerquotation cq ON p.pre_project_id = cq.pre_project_id
             WHERE p.current_phase = "installation" 
             AND p.equipment_released = FALSE
             AND p.status = "active"
             ORDER BY p.updated_at ASC');

        return $this->db->resultSet();
    }

    /**
     * Release equipment for a project
     * 
     * @param int $projectId Project ID
     * @return bool Success status
     */
    public function releaseProjectEquipment($projectId)
    {
        $this->db->query('UPDATE projects SET 
                    equipment_released = TRUE,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE project_id = :project_id');

        $this->db->bind(':project_id', $projectId);
        return $this->db->execute();
    }
}
