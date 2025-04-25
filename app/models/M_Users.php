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
        $this->db->query('SELECT user_id, name, email, password, role, profile_picture FROM users WHERE email = :email');
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

    public function getUserById($userId) {
        $this->db->query('SELECT * FROM users WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function findUserByEmail($email) {
        $this->db->query('SELECT user_id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $this->db->single();
        
        return $this->db->rowCount() > 0;
    }

    public function getCoordinators() {
        $this->db->query('SELECT user_id, name, email, role FROM users WHERE role IN ("chiefCoordinator", "operationsCoordinator", "hRAdministrator", "supplierCoordinator") ORDER BY role');
        return $this->db->resultSet();
    }

    public function getCoordinatorByRole($role) {
        $this->db->query('SELECT user_id, name, email, role FROM users WHERE role = :role');
        $this->db->bind(':role', $role);
        return $this->db->single();
    }

    public function updateCoordinator($data) {
        $this->db->query('UPDATE users SET name = :name, email = :email, role = :role WHERE user_id = :user_id');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);

        return $this->db->execute();
    }

    public function deleteUser($userId) {
        $this->db->query('DELETE FROM users WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function updatePhone($userId, $phone) {
        $this->db->query('UPDATE users SET phone = :phone WHERE user_id = :user_id');
        $this->db->bind(':phone', $phone);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function resetPassword($email, $password) {
        $this->db->query('UPDATE users SET password = :password WHERE email = :email');
        $this->db->bind(':password', $password);
        $this->db->bind(':email', $email);
        
        return $this->db->execute();
    }
}