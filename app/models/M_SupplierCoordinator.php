<?php
class M_SupplierCoordinator
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }


    public function getClientsWithChats() {
        
        $this->db->query('SELECT 
            u.user_id, 
            u.name, 
            u.email, 
            u.profile_picture,
            (SELECT message FROM messages 
             WHERE ((sender_id = u.user_id AND receiver_id = :supplier_id) 
             OR (sender_id = :supplier_id AND receiver_id = u.user_id))
             ORDER BY timestamp DESC LIMIT 1) as last_message,
            (SELECT timestamp FROM messages 
             WHERE ((sender_id = u.user_id AND receiver_id = :supplier_id) 
             OR (sender_id = :supplier_id AND receiver_id = u.user_id))
             ORDER BY timestamp DESC LIMIT 1) as last_time,
            (SELECT COUNT(*) FROM messages 
             WHERE sender_id = u.user_id 
             AND receiver_id = :supplier_id
             AND is_read = 0) as unread_count
        FROM 
            users u
        WHERE 
            u.role = "customer" 
        AND 
            EXISTS (
                SELECT 1 FROM messages m 
                WHERE (m.sender_id = u.user_id AND m.receiver_id = :supplier_id)
                OR (m.sender_id = :supplier_id AND m.receiver_id = u.user_id)
            )
        ORDER BY 
            last_time DESC');
                     
        $this->db->bind(':supplier_id', $_SESSION['user_id']);
        
        return $this->db->resultSet();
    }
}
