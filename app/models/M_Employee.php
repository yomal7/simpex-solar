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
}