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

    // Get total projects count
    public function getTotalProjects() {
        $this->db->query('SELECT COUNT(*) as count FROM projects');
        $result = $this->db->single();
        return $result->count;
    }

    // Get active projects count
    public function getActiveProjects() {
        $this->db->query("SELECT COUNT(*) as count FROM projects WHERE status = 'active'");
        $result = $this->db->single();
        return $result->count;
    }

    // Get completed projects count
    public function getCompletedProjects() {
        $this->db->query("SELECT COUNT(*) as count FROM projects WHERE status = 'completed'");
        $result = $this->db->single();
        return $result->count;
    }

    // Get project counts by phase
    public function getProjectPhaseDistribution() {
        $this->db->query("SELECT current_phase, COUNT(*) as count 
                        FROM projects 
                        WHERE status = 'active' 
                        GROUP BY current_phase");
        $results = $this->db->resultSet();
        
        $phases = [
            'document_submission' => 0,
            'first_payment' => 0,
            'installation' => 0,
            'final_payment' => 0,
            'engineer_approval' => 0,
            'grid_connection' => 0,
            'completed' => 0
        ];
        
        foreach ($results as $result) {
            $phases[$result->current_phase] = $result->count;
        }
        
        return $phases;
    }

    // Get monthly project counts for the current year
    public function getMonthlyProjectCounts() {
        $currentYear = date('Y');
        
        $this->db->query("SELECT MONTH(created_at) as month, COUNT(*) as count 
                        FROM projects 
                        WHERE YEAR(created_at) = :year 
                        GROUP BY MONTH(created_at)");
        $this->db->bind(':year', $currentYear);
        $results = $this->db->resultSet();
        
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = 0;
        }
        
        foreach ($results as $result) {
            $months[$result->month] = $result->count;
        }
        
        return $months;
    }
    public function getAllProjects() {
        $this->db->query('SELECT p.*, u.name as customer_name, u.email as customer_email
                         FROM projects p
                         LEFT JOIN users u ON p.customer_id = u.user_id
                         ORDER BY p.created_at DESC');
        return $this->db->resultSet();
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

    public function getAgreementEquipmentWithInventory($agreementId)
    {
        $this->db->query('SELECT ae.*, 
                    i.name, 
                    i.description,
                    i.quantity as inventory_quantity,
                    ae.quantity as required_quantity
                    FROM agreement_equipment ae
                    JOIN inventory i ON ae.inventory_id = i.id
                    WHERE ae.agreement_id = :agreement_id');

        $this->db->bind(':agreement_id', $agreementId);
        return $this->db->resultSet();
    }

    public function releaseEquipmentForProject($projectId, $equipment)
    {
        $success = true;

        // Update inventory quantities
        foreach ($equipment as $item) {
            $newQuantity = $item->inventory_quantity - $item->required_quantity;

            $this->db->query('UPDATE inventory 
                        SET quantity = :quantity 
                        WHERE id = :id');
            $this->db->bind(':quantity', $newQuantity);
            $this->db->bind(':id', $item->inventory_id);

            // If any update fails, set success to false
            if (!$this->db->execute()) {
                $success = false;
                break;
            }
        }

        // Only update project status if inventory updates were successful
        if ($success) {
            $this->db->query('UPDATE projects 
                    SET equipment_released = TRUE,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE project_id = :project_id');
            $this->db->bind(':project_id', $projectId);
            $success = $this->db->execute();
        }

        return $success;
    }

    public function getInstallationPhase($projectId)
    {
        $this->db->query('SELECT * FROM installation_phase WHERE project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    /**
     * Get installation by ID
     * 
     * @param int $installationId Installation ID
     * @return object|bool Installation object or false
     */
    public function getInstallationById($installationId)
    {
        $this->db->query('SELECT * FROM installation_phase WHERE installation_id = :installation_id');
        $this->db->bind(':installation_id', $installationId);
        return $this->db->single();
    }

    /**
     * Get installation schedule
     * 
     * @param int $installationId Installation ID
     * @return object|bool Schedule object or false
     */
    public function getInstallationSchedule($installationId)
    {
        $this->db->query('SELECT * FROM installation_schedule WHERE installation_id = :installation_id ORDER BY id DESC LIMIT 1');
        $this->db->bind(':installation_id', $installationId);
        return $this->db->single();
    }

    /**
     * Create installation phase
     * 
     * @param array $data Installation data
     * @return int|bool Installation ID or false
     */
    public function createInstallationPhase($data)
    {
        $this->db->query('INSERT INTO installation_phase (project_id, status) 
                     VALUES (:project_id, :status)');

        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':status', $data['status']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function assignEngineerToInstallation($installationId, $engineerId)
    {
        $this->db->query('INSERT INTO installation_engineer (installation_id, engineer_id) 
                     VALUES (:installation_id, :engineer_id)');

        $this->db->bind(':installation_id', $installationId);
        $this->db->bind(':engineer_id', $engineerId);

        return $this->db->execute();
    }

    public function getAssignedEngineer($installationId)
    {
        $this->db->query('SELECT e.*, u.name, u.email, u.phone
                     FROM installation_engineer ie
                     JOIN employees e ON ie.engineer_id = e.employee_id
                     JOIN users u ON e.user_id = u.user_id
                     WHERE ie.installation_id = :installation_id
                     AND ie.assign = TRUE
                     ORDER BY ie.created_at DESC
                     LIMIT 1');

        $this->db->bind(':installation_id', $installationId);
        return $this->db->single();
    }

    public function reassignEngineer($installationId, $engineerId)
    {
        // First set all current assignments to false
        $this->db->query('UPDATE installation_engineer 
                     SET assign = FALSE, 
                         updated_at = CURRENT_TIMESTAMP
                     WHERE installation_id = :installation_id 
                     AND assign = TRUE');

        $this->db->bind(':installation_id', $installationId);
        $this->db->execute();

        // Then create a new assignment
        return $this->assignEngineerToInstallation($installationId, $engineerId);
    }
    /**
     * Create installation schedule
     * 
     * @param array $data Schedule data
     * @return bool Success status
     */
    public function createInstallationSchedule($data)
    {
        $this->db->query('INSERT INTO installation_schedule (installation_id, start_date, start_time, end_date, status) 
                     VALUES (:installation_id, :start_date, :start_time, :end_date, :status)');

        $this->db->bind(':installation_id', $data['installation_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    /**
     * Update installation schedule
     * 
     * @param int $scheduleId Schedule ID
     * @param array $data Schedule data
     * @return bool Success status
     */
    public function updateInstallationSchedule($scheduleId, $data)
    {
        $query = 'UPDATE installation_schedule SET ';
        $params = [];

        if (isset($data['start_date'])) {
            $params[] = 'start_date = :start_date';
        }

        if (isset($data['start_time'])) {
            $params[] = 'start_time = :start_time';
        }

        if (isset($data['end_date'])) {
            $params[] = 'end_date = :end_date';
        }

        if (isset($data['status'])) {
            $params[] = 'status = :status';
        }

        if (isset($data['reschedule_request'])) {
            $params[] = 'reschedule_request = :reschedule_request';
        }

        $query .= implode(', ', $params) . ' WHERE id = :id';

        $this->db->query($query);

        $this->db->bind(':id', $scheduleId);

        if (isset($data['start_date'])) {
            $this->db->bind(':start_date', $data['start_date']);
        }

        if (isset($data['start_time'])) {
            $this->db->bind(':start_time', $data['start_time']);
        }

        if (isset($data['end_date'])) {
            $this->db->bind(':end_date', $data['end_date']);
        }

        if (isset($data['status'])) {
            $this->db->bind(':status', $data['status']);
        }

        if (isset($data['reschedule_request'])) {
            $this->db->bind(':reschedule_request', $data['reschedule_request']);
        }

        return $this->db->execute();
    }

    public function getUpcomingInstallations()
    {
        $this->db->query('SELECT ip.installation_id, ip.project_id, ip.status as phase_status, 
                     insched.start_date, insched.start_time, insched.end_date, 
                     insched.status as schedule_status,
                     u.name AS engineer_name
                     FROM installation_phase ip
                     JOIN installation_schedule insched ON ip.installation_id = insched.installation_id
                     LEFT JOIN installation_engineer ie ON ip.installation_id = ie.installation_id AND ie.assign = 1
                     LEFT JOIN employees e ON ie.engineer_id = e.employee_id
                     LEFT JOIN users u ON e.user_id = u.user_id
                     WHERE insched.start_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                     AND insched.status IN ("pending", "accept")
                     ORDER BY insched.start_date ASC');

        return $this->db->resultSet();
    }

    /**
     * Get installation team members
     * 
     * @param int $installationId Installation ID
     * @return array Team members
     */
    public function getInstallationTeamMembers($installationId)
    {
        $this->db->query('SELECT ie.id, e.employee_id, u.name, u.email, u.phone
                     FROM installation_employees ie
                     JOIN employees e ON ie.employee_id = e.employee_id
                     JOIN users u ON e.user_id = u.user_id
                     WHERE ie.installation_id = :installation_id
                     AND ie.assign = 1
                     ORDER BY u.name');

        $this->db->bind(':installation_id', $installationId);
        return $this->db->resultSet();
    }

    public function assignTechnicianToInstallation($installationId, $technicianId)
    {
        $this->db->query('INSERT INTO installation_employees 
                      (installation_id, employee_id, assign, created_at, updated_at) 
                      VALUES (:installation_id, :employee_id, TRUE, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');

        $this->db->bind(':installation_id', $installationId);
        $this->db->bind(':employee_id', $technicianId);

        return $this->db->execute();
    }

    public function removeAllTechnicians($installationId)
    {
        $this->db->query('UPDATE installation_employees 
                     SET assign = FALSE,
                         updated_at = CURRENT_TIMESTAMP
                     WHERE installation_id = :installation_id');

        $this->db->bind(':installation_id', $installationId);
        return $this->db->execute();
    }

    public function getEngineerID($userId)
    {
        $this->db->query('SELECT employee_id FROM employees WHERE user_id = :user_id AND role = "engineer"');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function getAssignedInstallations($EngineerId)
    {
        $this->db->query('SELECT installation_id FROM installation_engineer WHERE engineer_id = :engineer_id AND assign = 1');
        $this->db->bind(':engineer_id', $EngineerId);
        return $this->db->resultSet();
    }

    public function getEngineerProjects($installationIds)
    {
        // Create placeholder string for IN clause
        $placeholders = rtrim(str_repeat('?,', count($installationIds)), ',');

        // Query to get project details - filter by schedule status = 'accept'
        $this->db->query('SELECT DISTINCT
                 ip.installation_id, ip.project_id, ip.status AS installation_status,
                 ins.start_date, ins.start_time, ins.end_date, ins.status AS schedule_status,
                 p.current_phase,
                 u.name AS customer_name,
                 IFNULL(cq.nearest_city, "No Location") AS location
                 FROM installation_phase ip
                 JOIN projects p ON ip.project_id = p.project_id
                 LEFT JOIN installation_schedule ins ON ip.installation_id = ins.installation_id AND ins.status = "accept"
                 LEFT JOIN users u ON p.customer_id = u.user_id
                 LEFT JOIN customerquotation cq ON p.pre_project_id = cq.pre_project_id
                 WHERE ip.installation_id IN (' . $placeholders . ')
                 ORDER BY ins.start_date');

        // Bind installation IDs
        $paramIndex = 1;
        foreach ($installationIds as $id) {
            $this->db->bind($paramIndex++, $id);
        }

        return $this->db->resultSet();
    }

    public function updateInstallationStatus($installationId, $status)
    {
        $this->db->query('UPDATE installation_phase 
                     SET status = :status, 
                         updated_at = NOW()
                     WHERE installation_id = :installation_id');

        $this->db->bind(':installation_id', $installationId);
        $this->db->bind(':status', $status);

        return $this->db->execute();
    }

    public function completeInstallationStep($installationId, $step)
    {
        $column = 'step_' . $step;
        $dateColumn = 'step_' . $step . '_date';

        $this->db->query("UPDATE installation_phase 
                     SET $column = TRUE,
                         $dateColumn = NOW(),
                         updated_at = NOW()
                     WHERE installation_id = :installation_id");

        $this->db->bind(':installation_id', $installationId);

        return $this->db->execute();
    }

    public function saveInstallationNotes($installationId, $notes)
    {
        $this->db->query('UPDATE installation_phase 
                     SET notes = :notes,
                         updated_at = NOW()
                     WHERE installation_id = :installation_id');

        $this->db->bind(':installation_id', $installationId);
        $this->db->bind(':notes', $notes);

        return $this->db->execute();
    }

    public function getProjectWithCustomerInfo($projectId)
    {
        $this->db->query('SELECT p.*, 
                     u.name AS customer_name,
                     u.phone,
                     u.email,
                     cq.nearest_city AS location,
                     cq.address,
                     pa.system_capacity,
                     pa.estimated_generation
                     FROM projects p
                     LEFT JOIN users u ON p.customer_id = u.user_id
                     LEFT JOIN customerquotation cq ON p.pre_project_id = cq.pre_project_id
                     LEFT JOIN project_agreements pa ON p.agreement_id = pa.agreement_id
                     WHERE p.project_id = :project_id');

        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }
  
}
