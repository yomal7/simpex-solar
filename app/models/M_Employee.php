<?php
class M_Employee
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllEmployees()
    {
        $this->db->query('SELECT e.*, u.name, u.email, u.phone
                      FROM employees e
                      LEFT JOIN users u ON e.user_id= u.user_id
                      ORDER BY e.employee_id');
        return $this->db->resultSet();
    }

    public function getEmployeeById($employeeId)
    {
        $this->db->query('SELECT e.*, u.name, u.email, u.phone, u.profile_picture
                        FROM employees e
                        LEFT JOIN users u ON e.user_id= u.user_id 
                        WHERE e.employee_id = :id');
        $this->db->bind(':id', $employeeId);
        return $this->db->single();
    }

    public function getEmployeeByUserId($userId)
    {
        $this->db->query('SELECT e.*, u.name, u.email, u.phone
                        FROM employees e
                        LEFT JOIN users u ON e.user_id= u.user_id 
                        WHERE e.user_id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->single();
    }
    
    public function create($data)
    {
        // Insert user data into the users table
        $this->db->query('INSERT INTO users (name, email, password, phone, role, profile_picture) 
                         VALUES(:name, :email, :password, :phone, :role, :profile_image)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':role', 'employee');
        $this->db->bind(':profile_image', $data['profile_image'] ?? null);
        $this->db->execute();

        // Get the last inserted user_id
        $user_id = $this->db->lastInsertId();

        // Insert employee data into the employee table
        $this->db->query('INSERT INTO employees (user_id, role, address) VALUES(:user_id, :role, :address)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':address', $data['address']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function edit($data)
    {
        // Update employee data
        $this->db->query('UPDATE employees SET role = :role, address = :address WHERE employee_id = :employee_id');
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':employee_id', $data['employee_id']);
        
        if (!$this->db->execute()) {
            return false;
        }

        // Update user data
        $this->db->query('UPDATE users SET name = :name, email = :email, phone = :phone, profile_picture = :profile_picture 
                         WHERE user_id = :user_id');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':profile_picture', $data['profile_image']);
        $this->db->bind(':user_id', $data['user_id']);

        return $this->db->execute();
    }

    public function delete($employeeId)
    {
        $this->db->query('DELETE FROM employees WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->execute();
    }

    public function getEmployeeType()
    {
        $this->db->query('SELECT role FROM employees WHERE user_id = :user_id');
        $this->db->bind(':user_id', $_SESSION['user_id']);
        
        return $this->db->single();
    }  
    
    public function getHolidayRecords($limit = 10, $offset = 0)
    {
        $this->db->query('SELECT * FROM holidayrecords 
        ORDER BY 
        CASE 
            WHEN status = "pending" THEN 0 
            ELSE 1 
        END,
        start_date ASC 
        LIMIT :limit OFFSET :offset');

        // $this->db->bind(':employee_id', $employee_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind('offset', $offset);

        return $this->db->resultSet();
    }

    public function getTotalHolidayRecords()
    {
        $this->db->query('SELECT COUNT(*) AS total FROM holidayrecords');
        return $this->db->single()->total;
    }

    public function getHolidayRecordById($recordId)
    {
        $this->db->query('SELECT * FROM holidayrecords WHERE id = :id');
        $this->db->bind(':id', $recordId);

        return $this->db->single();
    }

    public function updateComment($id, $comment)
    {
        $this->db->query('UPDATE holidayrecords SET comment = :comment WHERE id = :id');
        $this->db->bind(':comment', $comment);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteComment($recordId)
    {
        $this->db->query('UPDATE holidayrecords SET comment = NULL WHERE id = :id');
        $this->db->bind(':id', $recordId);
        return $this->db->execute();
    }

    public function getAssignedTasksDetails($employee_id, $start_date, $end_date)
    {
        $this->db->query('SELECT t.* 
        FROM taskss t
        WHERE t.employee_id = :employee_id 
        AND (
            (t.start_date BETWEEN :start_date AND :end_date)
            OR (t.end_date BETWEEN :start_date AND :end_date)
            OR (:start_date BETWEEN t.start_date AND t.end_date)
        )
        ORDER BY t.end_date ASC');
        $this->db->bind(':employee_id', $employee_id);
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);

        return $this->db->resultSet();
    }

    public function updateStatus($recordId, $status)
    {
        $this->db->query('UPDATE holidayrecords SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $recordId);
        return $this->db->execute();
    }

    /**
     * Get employees by role
     * 
     * @param string $role Role to filter by (engineer, technician, deliveryPerson)
     * @return array Employees matching the role
     */
    public function getEmployeesByRole($role)
    {
        $this->db->query('SELECT e.*, u.name, u.email, u.phone
                     FROM employees e
                     LEFT JOIN users u ON e.user_id = u.user_id
                     WHERE e.role = :role
                     ORDER BY u.name');
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }
}
