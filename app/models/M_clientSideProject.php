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

    public function getAgreementByID($agreementId)
    {
        $this->db->query('SELECT * FROM project_agreements WHERE agreement_id = :agreement_id');
        $this->db->bind(':agreement_id', $agreementId);
        return $this->db->single();
    }

    /**
     * Get project payment
     */
    public function getProjectPayment($projectId, $paymentPhase)
    {
        $this->db->query('SELECT * FROM project_payments 
                     WHERE project_id = :project_id 
                     AND payment_phase = :payment_phase');
        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':payment_phase', $paymentPhase);
        return $this->db->single();
    }

    /**
     * Create project payment
     */
    public function createProjectPayment($data)
    {
        $this->db->query('INSERT INTO project_payments 
                     (project_id, payment_method, amount, payment_phase, payment_status, created_at, updated_at) 
                     VALUES 
                     (:project_id, :payment_method, :amount, :payment_phase, :payment_status, NOW(), NOW())');

        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':payment_phase', $data['payment_phase']);
        $this->db->bind(':payment_status', $data['payment_status']);

        return $this->db->execute();
    }

    /**
     * Update project payment
     */
    public function updateProjectPayment($paymentId, $data)
    {
        $this->db->query('UPDATE project_payments 
                     SET payment_method = :payment_method,
                         amount = :amount, 
                         payment_status = :payment_status,
                         updated_at = NOW()
                     WHERE id = :id');

        $this->db->bind(':id', $paymentId);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':payment_status', $data['payment_status']);

        return $this->db->execute();
    }

    /**
     * Get payment by ID
     */
    public function getPaymentById($paymentId)
    {
        $this->db->query('SELECT * FROM project_payments WHERE id = :id');
        $this->db->bind(':id', $paymentId);
        return $this->db->single();
    }

    /**
     * Create or update bank slip entry
     */
    public function createOrUpdateBankSlip($paymentId)
    {
        // Check if entry exists
        $this->db->query('SELECT id FROM project_bankslips WHERE projectpayment_id = :payment_id');
        $this->db->bind(':payment_id', $paymentId);
        $existing = $this->db->single();

        if ($existing) {
            // Update existing entry
            $this->db->query('UPDATE project_bankslips 
                         SET slip_downloaded = false, 
                             updated_at = NOW() 
                         WHERE projectpayment_id = :payment_id');
            $this->db->bind(':payment_id', $paymentId);
            return $this->db->execute();
        } else {
            // Create new entry
            $this->db->query('INSERT INTO project_bankslips 
                         (projectpayment_id, status, slip_downloaded, created_at, updated_at) 
                         VALUES 
                         (:payment_id, "pending", false, NOW(), NOW())');
            $this->db->bind(':payment_id', $paymentId);
            return $this->db->execute();
        }
    }

    /**
     * Get bank slip by payment ID
     */
    public function getBankSlipByPaymentId($paymentId)
    {
        $this->db->query('SELECT * FROM project_bankslips WHERE projectpayment_id = :payment_id');
        $this->db->bind(':payment_id', $paymentId);
        return $this->db->single();
    }

    /**
     * Mark bank slip as downloaded
     */
    public function markSlipAsDownloaded($paymentId)
    {
        $this->db->query('UPDATE project_bankslips 
                     SET slip_downloaded = true, 
                         updated_at = NOW() 
                     WHERE projectpayment_id = :payment_id');
        $this->db->bind(':payment_id', $paymentId);
        return $this->db->execute();
    }

    /**
     * Reset bank slip downloaded status
     */
    public function resetBankSlip($paymentId)
    {
        $this->db->query('UPDATE project_bankslips 
                     SET slip_downloaded = false, 
                         slip_file = NULL,
                         updated_at = NOW() 
                     WHERE projectpayment_id = :payment_id');
        $this->db->bind(':payment_id', $paymentId);
        return $this->db->execute();
    }

    /**
     * Update bank slip with uploaded file
     */
    public function updateBankSlipFile($paymentId, $fileName)
    {
        $this->db->query('UPDATE project_bankslips 
                     SET slip_file = :slip_file, 
                         updated_at = NOW() 
                     WHERE projectpayment_id = :payment_id');
        $this->db->bind(':payment_id', $paymentId);
        $this->db->bind(':slip_file', $fileName);
        return $this->db->execute();
    }
}
