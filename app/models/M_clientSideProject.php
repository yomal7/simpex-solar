<?php

class M_clientSideProject
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getProjectByPreProjectId($preProjectId)
    {
        $this->db->query('SELECT * FROM projects WHERE pre_project_id = :pre_project_id');
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function getDocumentSubmission($projectId)
    {
        $this->db->query('SELECT * FROM documentSubmission WHERE project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function submitDocument($data)
    {
        // Check if document already exists for this project
        $this->db->query('SELECT id FROM documentSubmission WHERE project_id = :project_id');
        $this->db->bind(':project_id', $data['project_id']);
        $existingDoc = $this->db->single();

        if ($existingDoc) {
            // Update existing document
            $this->db->query('UPDATE documentSubmission 
                             SET document = :document, 
                                 status = :status, 
                                 updated_at = NOW() 
                             WHERE project_id = :project_id');
        } else {
            // Create new document submission
            $this->db->query('INSERT INTO documentSubmission 
                             (project_id, document, status, created_at, updated_at) 
                             VALUES 
                             (:project_id, :document, :status, NOW(), NOW())');
        }

        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':document', $data['document']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function getAgreementByPreProjectId($preProjectId)
    {
        $this->db->query('SELECT pa.* 
                     FROM project_agreements pa
                     JOIN projects p ON pa.agreement_id = p.agreement_id
                     WHERE p.pre_project_id = :pre_project_id
                     ORDER BY pa.created_at DESC
                     LIMIT 1');

        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    /**  M_clientSideProject.php
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
     * Create project payment
     * 
     * @param array $data Payment data
     * @return int|bool Payment ID or false
     */
    public function createProjectPayment($data)
    {
        $this->db->query('INSERT INTO project_payments (
                          project_id,
                          payment_method,
                          amount,
                          payment_phase,
                          payment_status)
                          VALUES (
                          :project_id,
                          :payment_method,
                          :amount,
                          :payment_phase,
                          :payment_status)');

        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':payment_phase', $data['payment_phase']);
        $this->db->bind(':payment_status', $data['payment_status']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
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
        if (isset($data['transaction_id'])) {
            $params[] = 'transaction_id = :transaction_id';
        }

        $query .= implode(', ', $params) . ', updated_at = CURRENT_TIMESTAMP WHERE id = :payment_id';

        $this->db->query($query);
        $this->db->bind(':payment_id', $paymentId);

        if (isset($data['payment_status'])) {
            $this->db->bind(':payment_status', $data['payment_status']);
        }
        if (isset($data['transaction_id'])) {
            $this->db->bind(':transaction_id', $data['transaction_id']);
        }

        return $this->db->execute();
    }

    /**
     * Create project bank slip
     * 
     * @param array $data Bank slip data
     * @return int|bool Bank slip ID or false
     */
    public function createProjectBankSlip($data)
    {
        $this->db->query('INSERT INTO project_bankslips (
                          projectpayment_id,
                          slip_file,
                          status)
                          VALUES (
                          :projectpayment_id,
                          :slip_file,
                          :status)');

        $this->db->bind(':projectpayment_id', $data['projectpayment_id']);
        $this->db->bind(':slip_file', $data['slip_file']);
        $this->db->bind(':status', $data['status']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Record that a bank slip was downloaded
     * 
     * @param int $projectId Project ID
     * @param string $paymentPhase Payment phase
     * @return bool Success status
     */
    public function recordSlipDownloaded($projectId, $paymentPhase, $amount = 0)
    {
        // First check if a payment record exists, create one if not
        $payment = $this->getProjectPayment($projectId, $paymentPhase);

        if (!$payment) {
            // Create a payment record with payment_status = false
            $paymentId = $this->createProjectPayment([
                'project_id' => $projectId,
                'payment_method' => 'bank deposit',
                'amount' => $amount, // Use the provided amount
                'payment_phase' => $paymentPhase,
                'payment_status' => false
            ]);
        } else {
            $paymentId = $payment->id;

            // Update the amount if it has changed
            if ($payment->amount != $amount && $amount > 0) {
                $this->db->query('UPDATE project_payments SET 
                            amount = :amount,
                            updated_at = CURRENT_TIMESTAMP
                            WHERE id = :id');
                $this->db->bind(':amount', $amount);
                $this->db->bind(':id', $paymentId);
                $this->db->execute();
            }
        }

        // Check if a bank slip record exists, update it or create a new one
        $slip = $this->getProjectBankSlip($projectId, $paymentPhase);

        if ($slip) {
            // Update existing slip record
            $this->db->query('UPDATE project_bankslips SET 
                         slip_downloaded = TRUE,
                         updated_at = CURRENT_TIMESTAMP
                         WHERE id = :id');

            $this->db->bind(':id', $slip->id);
            return $this->db->execute();
        } else {
            // Create new slip record
            $this->db->query('INSERT INTO project_bankslips (
                         projectpayment_id,
                         slip_downloaded,
                         status,
                         created_at,
                         updated_at)
                         VALUES (
                         :projectpayment_id,
                         TRUE,
                         "pending",
                         CURRENT_TIMESTAMP,
                         CURRENT_TIMESTAMP)');

            $this->db->bind(':projectpayment_id', $paymentId);
            return $this->db->execute();
        }
    }

    /**
     * Update project bank slip with uploaded file
     * 
     * @param int $slipId Bank slip ID
     * @param string $slipFile Path to uploaded slip file
     * @return bool Success status
     */
    public function updateBankSlipFile($slipId, $slipFile)
    {
        $this->db->query('UPDATE project_bankslips 
                     SET slip_file = :slip_file,
                         status = :status,
                         updated_at = CURRENT_TIMESTAMP
                     WHERE id = :id');

        $this->db->bind(':slip_file', $slipFile);
        $this->db->bind(':status', 'pending');
        $this->db->bind(':id', $slipId);

        return $this->db->execute();
    }

    /**
     * Delete pending payment
     */
    public function deletePendingPayment($projectId, $phase)
    {
        // First get the payment to check if it's pending
        $payment = $this->getProjectPayment($projectId, $phase);

        if (!$payment || $payment->payment_status) {
            // If payment doesn't exist or is already completed, do nothing
            return false;
        }

        // Delete associated bank slips first (due to foreign key constraint)
        $this->db->query('DELETE FROM project_bankslips 
                     WHERE projectpayment_id = :payment_id');
        $this->db->bind(':payment_id', $payment->id);
        $this->db->execute();

        // Now delete the payment record
        $this->db->query('DELETE FROM project_payments 
                     WHERE id = :payment_id AND payment_status = 0');
        $this->db->bind(':payment_id', $payment->id);

        return $this->db->execute();
    }

    

    /**
     * Update project phase after payment
     * 
     * @param int $projectId The project ID
     * @param string $phase The new project phase
     * @return bool Success status
     */
    public function updateProjectPhase($projectId, $phase)
    {
        $this->db->query('UPDATE projects 
                        SET current_phase = :phase,
                            updated_at = CURRENT_TIMESTAMP
                        WHERE project_id = :project_id');
                        
        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':phase', $phase);
    }  
    public function getInstallationPhase($projectId)
    {
        $this->db->query('SELECT * FROM installation_phase WHERE project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function getInstallationSchedule($installationId)
    {
        $this->db->query('SELECT * FROM installation_schedule 
                     WHERE installation_id = :installation_id 
                     ORDER BY id DESC LIMIT 1');
        $this->db->bind(':installation_id', $installationId);
        return $this->db->single();
    }

    public function getAssignedEngineer($installationId)
    {
        $this->db->query('SELECT e.*, u.name, u.email, u.phone
                     FROM installation_engineer ie
                     JOIN employees e ON ie.engineer_id = e.employee_id
                     JOIN users u ON e.user_id = u.user_id
                     WHERE ie.installation_id = :installation_id
                     AND ie.assign = 1
                     ORDER BY ie.id DESC 
                     LIMIT 1');
        $this->db->bind(':installation_id', $installationId);
        return $this->db->single();
    }

    public function acceptInstallationSchedule($scheduleId)
    {
        $this->db->query('UPDATE installation_schedule 
                     SET status = "accept", 
                         updated_at = NOW() 
                     WHERE id = :schedule_id');
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->execute();
    }

    public function requestInstallationReschedule($scheduleId, $reason)
    {
        $this->db->query('UPDATE installation_schedule 
                     SET status = "request", 
                         reschedule_request = :reason, 
                         updated_at = NOW() 
                     WHERE id = :schedule_id');
        $this->db->bind(':schedule_id', $scheduleId);
        $this->db->bind(':reason', $reason);

        return $this->db->execute();
    }
}
