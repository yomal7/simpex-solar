<?php

class M_Technician
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getHolidayRecords($employee_id, $limit = 5, $offset = 0)
    {
        $this->db->query('SELECT * FROM holidayrecords 
        WHERE employee_id = :employee_id 
        ORDER BY created_at DESC 
        LIMIT :limit OFFSET :offset');

        $this->db->bind(':employee_id', $employee_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind('offset', $offset);

        return $this->db->resultSet();
    }

    public function getTotalHolidayRecords($employee_id)
    {
        $this->db->query('SELECT COUNT(*) AS total FROM holidayrecords WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employee_id);
        return $this->db->single()->total;
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
        $this->db->bind(':status', 'pending');
        $this->db->bind(':leave_type', $data['leave_type']);

        return $this->db->execute();
    }

    public function getProjectTasks($employee_id, $limit = 10, $offset = 0)
{
    $this->db->query('SELECT id, project_id, title, description, start_date, end_date, status, comment 
    FROM taskss 
    WHERE employee_id = :employee_id
    ORDER BY end_date ASC
    LIMIT :limit OFFSET :offset');
    
    $this->db->bind(':employee_id', $employee_id);
    $this->db->bind(':limit', $limit);
    $this->db->bind(':offset', $offset);

    return $this->db->resultSet();
}

public function getProjectTasksById($taskId) {
    $this->db->query('SELECT * FROM taskss WHERE id = :id');
    $this->db->bind(':id', $taskId);
    
    return $this->db->single();
}


    public function getTotalProjectTasks($employee_id)
    {
        $this->db->query('SELECT COUNT(*) AS total FROM taskss WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employee_id);
        return $this->db->single()->total;
    }

    
    public function updateTaskComment($id, $comment) {
        $this->db->query('UPDATE taskss SET comment = :comment WHERE id = :id');
        $this->db->bind(':comment', $comment);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateTaskStatus($id, $status) {
        $this->db->query('UPDATE taskss SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteTaskComment($taskId) {
        $this->db->query('UPDATE taskss SET comment = NULL WHERE id = :id');
        $this->db->bind(':id', $taskId);
        return $this->db->execute();
    }
}
