<?php
class M_Settings {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Get user by ID
    public function getUserById($userId) {
        $this->db->query('SELECT * FROM users WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->single();
    }
    
    // Update user profile
    public function updateProfile($data) {
        $this->db->query('UPDATE users SET email = :email, phone = :phone, profile_picture = :profile_picture WHERE user_id = :user_id');
        
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':profile_picture', $data['profile_picture']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Check if email exists for another user
    public function emailExistsForOtherUser($email, $userId) {
        $this->db->query('SELECT * FROM users WHERE email = :email AND user_id != :user_id');
        $this->db->bind(':email', $email);
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Verify password
    public function verifyPassword($userId, $password) {
        $this->db->query('SELECT password FROM users WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        
        if($row) {
            if(password_verify($password, $row->password)) {
                return true;
            }
        }
        
        return false;
    }
    
    // Change password
    public function changePassword($userId, $newPassword) {
        $this->db->query('UPDATE users SET password = :password WHERE user_id = :user_id');
        
        $this->db->bind(':password', $newPassword);
        $this->db->bind(':user_id', $userId);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
