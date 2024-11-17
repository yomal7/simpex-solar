<?php
class M_Users {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        return $this->db->execute();
    }

    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        return $this->db->single();
    }

    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function login($email, $password) {
        $user = $this->getUserByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return false;
    }
}
?>