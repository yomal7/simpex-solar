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
        $this->db->query('SELECT e.*, u.name, u.email, u.phone
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
        $this->db->query('INSERT INTO users (name, email, password, phone, role) VALUES(:name, :email, :password, :phone, :role)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':role', 'employee');
        $this->db->execute();

        // Get the last inserted user_id
        $user_id = $this->db->lastInsertId();

        // Insert employee data into the employee table
        $this->db->query('INSERT INTO employees (user_id, role) VALUES(:user_id, :role)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':role', $data['role']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function edit($data)
    {
        $this->db->query('UPDATE employees SET role = :role WHERE employee_id = :employee_id');
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':employee_id', $data['employee_id']);

        $this->db->query('UPDATE users SET name = :name, email = :email, phone = :phone WHERE user_id = :user_id');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':user_id', $data['user_id']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($employeeId)
    {
        $this->db->query('DELETE FROM employees WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->execute();
    }

    public function getEmployeeType() {
        $this->db->query('SELECT role FROM employees WHERE user_id = :user_id');
        $this->db->bind(':user_id', $_SESSION['user_id']);
        
        return $this->db->single();
    }    

}
