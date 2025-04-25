<?php
class M_HR {
    private $db;

    public function __construct() {
        $this->db = new Database;
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
             WHERE ((sender_id = u.user_id AND receiver_id = :hr_id) 
             OR (sender_id = :hr_id AND receiver_id = u.user_id))
             ORDER BY timestamp DESC LIMIT 1) as last_message,
            (SELECT timestamp FROM messages 
             WHERE ((sender_id = u.user_id AND receiver_id = :hr_id) 
             OR (sender_id = :hr_id AND receiver_id = u.user_id))
             ORDER BY timestamp DESC LIMIT 1) as last_time,
            (SELECT COUNT(*) FROM messages 
             WHERE sender_id = u.user_id 
             AND receiver_id = :hr_id
             AND is_read = 0) as unread_count
        FROM 
            users u
        WHERE 
            u.role = "customer" 
        AND 
            EXISTS (
                SELECT 1 FROM messages m 
                WHERE (m.sender_id = u.user_id AND m.receiver_id = :hr_id)
                OR (m.sender_id = :hr_id AND m.receiver_id = u.user_id)
            )
        ORDER BY 
            last_time DESC');
                     
        $this->db->bind(':hr_id', $_SESSION['user_id']);
        
        return $this->db->resultSet();
    }
}