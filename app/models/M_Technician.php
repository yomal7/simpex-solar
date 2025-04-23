<?php

class M_Technician
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // public function getTasks($employee_id) {
    //     $this->db->query('SELECT * FROM tasks WHERE employee_id = :employee_id');
    //     $this->db->bind(':employee_id', $employee_id);

    //     return $this->db->resultSet();
    // }

    public function getHolidayRecords($employee_id)
    {
        $this->db->query('SELECT * FROM holidayrecords WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employee_id);

        return $this->db->resultSet();
    }

    public function addHolidayRecords($data)
    {
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

    public function getTasks($employee_id)
    {
        $this->db->query('SELECT * FROM tasks WHERE employee_id = :employee_id');
        $this->db->bind('employee_id', $employee_id);

        return $this->db->resultSet();
    }


    public function getTechnicianID($userId)
    {
        $this->db->query('SELECT employee_id FROM employees WHERE user_id = :user_id AND role = "technician"');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function getAssignedInstallations($technicianId)
    {
        $this->db->query('SELECT installation_id FROM installation_employees WHERE employee_id = :technician_id AND assign = 1');
        $this->db->bind(':technician_id', $technicianId);
        return $this->db->resultSet();
    }

    public function getTechnicianProjects($installationIds) {
        $this->db->query('SELECT project_id FROM installation_phase WHERE installation_id = :installation_id');
        $this->db->bind('installation_id', $installationIds);
        return $this->db->resultSet();
    }

    public function getCustomerByProjectId($projectId) {
        $this->db->query('SELECT u.*, cq.* FROM users u, projects p, installation_phase ip, customerquotation cq
                          WHERE p.project_id = ip.project_id AND p.customer_id = u.user_id AND p.pre_project_id = cq.pre_project_id AND p.project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function getProjectDetails($project_id) {
        $this->db->query('SELECT p.*, ip.* FROM projects p 
                          LEFT JOIN installation_phase ip ON p.project_id = ip.project_id
                          WHERE p.project_id = :project_id');
        $this->db->bind(':project_id', $project_id);
        return $this->db->single();
    }
    
    public function getAssignedEngineer($installationId)
    {
        $this->db->query('SELECT e.*, u.name, u.phone
                     FROM installation_engineer ie
                     JOIN employees e ON ie.engineer_id = e.employee_id
                     JOIN users u ON e.user_id = u.user_id
                     WHERE ie.installation_id = :installation_id
                     AND ie.assign = TRUE
                     ORDER BY ie.created_at DESC
                     LIMIT 1');

        $this->db->bind(':installation_id', $installationId);
        return $this->db->single();
    }

    public function getInstallationTeamMembers($installationId)
    {
        $this->db->query('SELECT ie.id, e.employee_id, u.name, u.email, u.phone
                     FROM installation_employees ie
                     JOIN employees e ON ie.employee_id = e.employee_id
                     JOIN users u ON e.user_id = u.user_id
                     WHERE ie.installation_id = :installation_id
                     AND ie.assign = 1
                     ORDER BY u.name');

        $this->db->bind(':installation_id', $installationId);
        return $this->db->resultSet();
    }

    public function getInstallationByID($installation_id) {
        $this->db->query('SELECT * FROM installation_phase 
                          WHERE installation_id = :installation_id');
        $this->db->bind(':installation_id', $installation_id);
        return $this->db->single();
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
