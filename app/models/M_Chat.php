<?php
class M_Chat {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

  
    public function getClientChats($user1Id, $user2Id)
    {
        // Get all messages between these two users
        $this->db->query('SELECT * FROM messages 
                         WHERE (sender_id = :user1_id AND receiver_id = :user2_id)
                         OR (sender_id = :user2_id AND receiver_id = :user1_id)
                         ORDER BY timestamp ASC');
        
        $this->db->bind(':user1_id', $user1Id);
        $this->db->bind(':user2_id', $user2Id);
        
        return $this->db->resultSet();
    }
    

    public function saveMessage($data)
    {
        $this->db->query('INSERT INTO messages (sender_id, sender_role, receiver_id, receiver_role, message, is_read) 
                         VALUES (:sender_id, :sender_role, :receiver_id, :receiver_role, :message, 0)');
        
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':sender_role', $data['sender_role']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':receiver_role', $data['receiver_role']);
        $this->db->bind(':message', $data['message']);
        
        return $this->db->execute();
    }
    

    public function markMessagesAsRead($senderId, $receiverId)
    {
        $this->db->query('UPDATE messages SET is_read = 1 
                         WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND is_read = 0');
        
        $this->db->bind(':sender_id', $senderId);
        $this->db->bind(':receiver_id', $receiverId);
        
        return $this->db->execute();
    }
    

    public function getUnreadCount($senderId, $receiverId)
    {
        $this->db->query('SELECT COUNT(*) as count FROM messages 
                         WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND is_read = 0');
        
        $this->db->bind(':sender_id', $senderId);
        $this->db->bind(':receiver_id', $receiverId);
        
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getLastMessage($user1Id, $user2Id)
    {
        $this->db->query('SELECT * FROM messages 
                         WHERE (sender_id = :user1_id AND receiver_id = :user2_id)
                         OR (sender_id = :user2_id AND receiver_id = :user1_id)
                         ORDER BY timestamp DESC LIMIT 1');
        
        $this->db->bind(':user1_id', $user1Id);
        $this->db->bind(':user2_id', $user2Id);
        
        return $this->db->single();
    }
}