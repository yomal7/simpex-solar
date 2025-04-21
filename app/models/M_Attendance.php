<?php
class M_Attendance
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // Mark attendance for an employee
    // public function markAttendance($data)
    // {
    //     $this->db->query('INSERT INTO attendance (employee_id, date, time_in) VALUES(:employee_id, :date, :time_in)');
    //     $this->db->bind(':employee_id', $data['employee_id']);
    //     $this->db->bind(':date', $data['date']);
    //     $this->db->bind(':time_in', $data['time_in']);

    //     if ($this->db->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // get attendance records by date
    public function getAttendanceByDate($date)
    {
        $this->db->query('SELECT e.employee_id, u.name, e.role AS emp_role, a.date,TIME_FORMAT(a.time_in, "%h:%i %p") AS time_in, TIME_FORMAT(a.time_out, "%h:%i %p") AS time_out
                 FROM employees e
                 INNER JOIN users u ON e.user_id = u.user_id
                 LEFT JOIN attendance a ON e.employee_id = a.employee_id AND a.date = :date
                 ORDER BY a.time_in DESC');
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }

    // get attendance records by employee id
    public function getAttendanceByEmployeeId($employeeId)
    {
        $this->db->query('SELECT a.*, e.name as employee_name 
                          FROM attendance a 
                          LEFT JOIN employee e ON a.employee_id = e.employee_id 
                          WHERE a.employee_id = :employee_id');
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->resultSet();
    }

    // get attendance records by employee id and date
    public function markClockIn($employee_id, $date, $time_in) {
        // Check if a record already exists for this employee on this date
        $this->db->query('SELECT * FROM attendance WHERE employee_id = :employee_id AND date = :date');
        $this->db->bind(':employee_id', $employee_id);
        $this->db->bind(':date', $date);
        
        $record = $this->db->single();
        
        if ($record) {
            // Update existing record's time_in
            $this->db->query('UPDATE attendance SET time_in = :time_in WHERE employee_id = :employee_id AND date = :date');
            $this->db->bind(':time_in', $time_in);
            $this->db->bind(':employee_id', $employee_id);
            $this->db->bind(':date', $date);
        } else {
            // Create new attendance record
            $this->db->query('INSERT INTO attendance (employee_id, date, time_in) VALUES (:employee_id, :date, :time_in)');
            $this->db->bind(':employee_id', $employee_id);
            $this->db->bind(':date', $date);
            $this->db->bind(':time_in', $time_in);
        }
        
        return $this->db->execute();
    }

    // Mark clock out for an employee
    public function markClockOut($employee_id, $date, $time_out) {
        // Update the time_out field
        $this->db->query('UPDATE attendance SET time_out = :time_out WHERE employee_id = :employee_id AND date = :date');
        $this->db->bind(':time_out', $time_out);
        $this->db->bind(':employee_id', $employee_id);
        $this->db->bind(':date', $date);
        
        return $this->db->execute();
    }
}