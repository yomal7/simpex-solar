<?php
class M_OperationsCoordinator {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSignatureByCoordinatorId($coordinatorId) {
        $this->db->query('SELECT * FROM coordinator_signatures WHERE coordinator_id = :coordinator_id');
        $this->db->bind(':coordinator_id', $coordinatorId);
        return $this->db->single();
    }

    public function updateSignature($coordinatorId, $signatureImage) {
        // Check if signature exists
        $existing = $this->getSignatureByCoordinatorId($coordinatorId);
        
        if ($existing) {
            // Update existing signature
            $this->db->query('UPDATE coordinator_signatures SET signature_image = :signature_image, 
                             updated_at = CURRENT_TIMESTAMP WHERE coordinator_id = :coordinator_id');
        } else {
            // Insert new signature
            $this->db->query('INSERT INTO coordinator_signatures (coordinator_id, signature_image) 
                             VALUES (:coordinator_id, :signature_image)');
        }

        $this->db->bind(':coordinator_id', $coordinatorId);
        $this->db->bind(':signature_image', $signatureImage);

        return $this->db->execute();
    }

    public function deleteSignature($coordinatorId) {
        $this->db->query('DELETE FROM coordinator_signatures WHERE coordinator_id = :coordinator_id');
        $this->db->bind(':coordinator_id', $coordinatorId);
        return $this->db->execute();
    }
}
