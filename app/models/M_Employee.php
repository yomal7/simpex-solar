<?php
class M_Employee {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllEmployees() {
        $this->db->query('SELECT employee_id, name, role FROM employe ORDER BY name');
        return $this->db->resultSet();
    }

    public function getEmployeeIdByUserId($user_id) {
        $this->db->query('SELECT * FROM employees WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->single(); // Use `single()` since we expect one record
    }
    
}