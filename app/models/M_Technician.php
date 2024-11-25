<?php

    class M_Technician {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }

        public function getTasks($employee_id) {
            $this->db->query('SELECT * FROM tasks WHERE employee_id = :employee_id');
            $this->db->bind(':employee_id', $employee_id);

            return $this->db->resultSet();
        }

        










        // public function getDeliveryPersonByUserId($userId) {
        //     $this->db->query('SELECT * FROM clients WHERE user_id = :user_id');
        //     $this->db->bind(':user_id', $userId);

        //     return $this->db->single();
        // }

        // public function getRecentTasks($clientId) {
        //     $this->db->query('SELECT * FROM tasks WHERE client_id = :client_id ORDER BY created_at DESC LIMIT 5');
        //     $this->db->bind(':client_id', $clientId);

        //     return $this->db->resultSet();
        // }

        // public function getAllTasks($clientId) {
        //     $this->db->query('SELECT * FROM tasks WHERE client_id = :client_id ORDER BY created_at DESC');
        //     $this->db->bind(':client_id', $clientId);

        //     return $this->db->resultSet();
        // }

        // public function getProjectById($projectId) {
        //     $this->db->query('SELECT * FROM projects WHERE id = :id');
        //     $this->db->bind(':id', $projectId);

        //     return $this->db->single();
        // }
}
?>