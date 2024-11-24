<?php
class M_Users {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, role) VALUES(:name, :email, :password, :role)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);

        // Execute
        return $this->db->execute();
    }

    public function login($email, $password) {
        $this->db->query('SELECT user_id, name, email, password, role FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        
        if($row) {
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
                return $row;
            }
        }
        
        return false;
    }

    public function getUserByEmail($email) {
        $this->db->query('SELECT user_id, name, email, password, role FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        return $this->db->single();
    }

    public function findUserByEmail($email) {
        $this->db->query('SELECT user_id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $this->db->single();
        
        return $this->db->rowCount() > 0;
    }
}