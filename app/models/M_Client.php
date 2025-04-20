<?php

// Models/M_Clients.php
class M_Client
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getClientByUserId($userId)
    {
        $this->db->query('SELECT * FROM users WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);

        return $this->db->single();
    }

    // public function getUserById($id) {
    //     $this->db->query('SELECT * FROM users WHERE user_id = :id');
    //     $this->db->bind(':id', $id);
    //     return $this->db->single();
    // }

    public function getUserById($userId)
    {
        $this->db->query('SELECT user_id, name, email, phone, profile_picture, role FROM users WHERE user_id = :user_id AND role = "customer"');
        $this->db->bind(':user_id', $userId);

        $result = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return $result;
        }

        return false;
    }

    public function updateProfile($userId, $data)
    {
        $this->db->query('UPDATE users SET phone = :phone WHERE user_id = :user_id AND role = "customer"');

        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }


    public function verifyPassword($userId, $password)
    {
        $this->db->query('SELECT password FROM users WHERE user_id = :user_id AND role = "customer"');
        $this->db->bind(':user_id', $userId);

        $row = $this->db->single();

        if ($row) {
            return password_verify($password, $row->password);
        }

        return false;
    }

    public function updatePassword($userId, $newPassword)
    {
        $this->db->query('UPDATE users SET password = :password WHERE user_id = :user_id AND role = "customer"');

        $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    public function updateProfilePicture($userId, $fileName)
    {
        $this->db->query('UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id AND role = "customer"');

        $this->db->bind(':profile_picture', $fileName);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }





    public function getOngoingProjects($userId)
    {
        $this->db->query('SELECT *
                              FROM customerquotation 
                              WHERE user_id = :user_id 
                              AND status = "accepted_by_customer"');

        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getProjectStats($userId)
    {
        // Get active projects count (pre-projects with accepted quotations)
        $this->db->query('SELECT COUNT(*) as count FROM pre_projects pp
                              LEFT JOIN reviewed_quotations rq ON pp.quotation_id = rq.quotation_id
                              WHERE pp.customer_id = :user_id 
                              AND pp.status = "active"
                              AND rq.status = "accepted"');
        $this->db->bind(':user_id', $userId);
        $activeProjects = $this->db->single()->count;

        // Get total completed projects
        $this->db->query('SELECT COUNT(*) as count FROM pre_projects 
                              WHERE customer_id = :user_id 
                              AND status = "completed"');
        $this->db->bind(':user_id', $userId);
        $completedProjects = $this->db->single()->count;

        return [
            'active_projects' => $activeProjects,
            'total_solutions' => $activeProjects + $completedProjects,
            'pending_quotations' => 0
        ];
    }


    public function getOperationsCoordinator()
    {
        $this->db->query('SELECT user_id, name, email FROM users WHERE role = "operationsCoordinator" LIMIT 1');
        return $this->db->single();
    }

    public function getSupplierCoordinator()
    {
        $this->db->query('SELECT user_id, name, email FROM users WHERE role = "supplierCoordinator" LIMIT 1');
        return $this->db->single();
    }

    public function getHRAdministrator()
    {
        $this->db->query('SELECT user_id, name, email FROM users WHERE role = "hRAdministrator" LIMIT 1');
        return $this->db->single();
    }
}
