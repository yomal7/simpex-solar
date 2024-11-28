<?php

    class M_Technician {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }

        // public function getTasks($employee_id) {
        //     $this->db->query('SELECT * FROM tasks WHERE employee_id = :employee_id');
        //     $this->db->bind(':employee_id', $employee_id);

        //     return $this->db->resultSet();
        // }

        public function getHolidayRecords($employee_id) {
            $this->db->query('SELECT * FROM holidayrecords WHERE employee_id = :employee_id');
            $this->db->bind(':employee_id', $employee_id);

            return $this->db->resultSet();
        }

        public function addHolidayRecords($data) {
            $this->db->query('INSERT INTO holidayrecords (employee_id, start_date, end_date, number_of_days, reason, status, leave_type) 
                              VALUES (:employee_id, :start_date, :end_date, :number_of_days, :reason, :status, :leave_type)');
            $this->db->bind(':employee_id', $data['employee_id']);
            $this->db->bind(':start_date', $data['start_date']);
            $this->db->bind(':end_date', $data['end_date']);
            $this->db->bind(':number_of_days', $data['number_of_days']);
            $this->db->bind(':reason', $data['reason']);
            $this->db->bind(':status', 'pending'); // Default status is 'pending'
            $this->db->bind(':leave_type', $data['leave_type']);
    
            return $this->db->execute();
        } 

        public function getTasks($employee_id) {
            $this->db->query('SELECT * FROM tasks WHERE employee_id = :employee_id');
            $this->db->bind('employee_id', $employee_id);

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