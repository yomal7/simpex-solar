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

/**
     * Get all clients who have chatted with this operations coordinator
     * 
     * @return array List of clients with chat metadata
     */
    public function getClientsWithChats() {
        // This query gets all customers who have exchanged messages with this coordinator
        // It also includes:
        // - The most recent message for preview
        // - The timestamp of the most recent message for sorting
        // - A count of unread messages for notification badges
        $this->db->query('SELECT 
            u.user_id, 
            u.name, 
            u.email, 
            u.profile_picture,
            (SELECT message FROM messages 
             WHERE ((sender_id = u.user_id AND receiver_id = :operations_id) 
             OR (sender_id = :operations_id AND receiver_id = u.user_id))
             ORDER BY timestamp DESC LIMIT 1) as last_message,
            (SELECT timestamp FROM messages 
             WHERE ((sender_id = u.user_id AND receiver_id = :operations_id) 
             OR (sender_id = :operations_id AND receiver_id = u.user_id))
             ORDER BY timestamp DESC LIMIT 1) as last_time,
            (SELECT COUNT(*) FROM messages 
             WHERE sender_id = u.user_id 
             AND receiver_id = :operations_id
             AND is_read = 0) as unread_count
        FROM 
            users u
        WHERE 
            u.role = "customer" 
        AND 
            EXISTS (
                SELECT 1 FROM messages m 
                WHERE (m.sender_id = u.user_id AND m.receiver_id = :operations_id)
                OR (m.sender_id = :operations_id AND m.receiver_id = u.user_id)
            )
        ORDER BY 
            last_time DESC');
                     
        $this->db->bind(':operations_id', $_SESSION['user_id']);
        
        return $this->db->resultSet();
    }
}