<?php

class M_Services
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // get warranty years
    public function getWarrantyYears($pre_project_id)
    {
        $this->db->query('SELECT p.warranty_years FROM package p
                        JOIN customerquotation cq ON p.package_id = cq.package_id
                        WHERE cq.pre_project_id = :pre_project_id');

        $this->db->bind(':pre_project_id', $pre_project_id);

        return $this->db->single()->warranty_years;
    }

    // get project details
    public function getProjectDetailsByPreProjectID($pre_project_id)
    {
        $this->db->query('SELECT project_id, customer_id, status, current_phase, DATE(updated_at) As completed_date
                        FROM projects
                        WHERE pre_project_id = :pre_project_id');

        $this->db->bind(':pre_project_id', $pre_project_id);

        return $this->db->single();
    }

    // create servicce request
    public function createServiceRequest($data)
    {
        $this->db->query('INSERT INTO services (project_id, pre_project_id, customer_id, issue_type, description, requested_date, status) 
                          VALUES (:project_id, :pre_project_id, :customer_id, :issue_type, :description, :requested_date, :status)');

        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':pre_project_id', $data['pre_project_id']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':issue_type', $data['issue_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':requested_date', date('Y-m-d'));
        $this->db->bind(':status', 'pending');

        return $this->db->execute();
    }


    public function getServiceRequestByPreProjectId($preProjectId)
    {
        $this->db->query('SELECT * FROM services WHERE pre_project_id = :pre_project_id AND status != "completed" ORDER BY requested_date DESC');
        $this->db->bind(':pre_project_id', $preProjectId);

        return $this->db->resultSet();
    }

    public function getAllServices()
    {
        $this->db->query('SELECT s.*, u.name AS customer_name
                          FROM services s
                          INNER JOIN users u ON s.customer_id = u.user_id
                          WHERE s.status != "completed"
                          ORDER BY s.requested_date DESC');

        return $this->db->resultSet();
    }

    /**
     * Get completed projects for a customer that are within warranty period
     * 
     * @param int $customerId The customer ID
     * @return array Array of eligible projects
     */
    public function getProjectsWithinWarranty($customerId)
    {
        $this->db->query('SELECT p.project_id, p.pre_project_id, q.quotation_id, pk.package_id, 
                         pk.name AS package_name, pk.warranty_years, p.created_at,
                         p.status, p.current_phase, p.completed_date  
                         FROM projects p 
                         INNER JOIN customerquotation q ON p.quotation_id = q.quotation_id 
                         INNER JOIN package pk ON q.package_id = pk.package_id
                         WHERE p.customer_id = :customer_id 
                         AND p.status = "completed" 
                         AND p.current_phase = "completed"
                         AND DATE_ADD(COALESCE(p.completed_date, p.created_at), INTERVAL pk.warranty_years YEAR) >= CURDATE()');

        $this->db->bind(':customer_id', $customerId);

        return $this->db->resultSet();
    }

    /**
     * Get service requests for a customer
     * 
     * @param int $customerId The customer ID
     * @return array Array of service requests
     */
    public function getServiceRequests($customerId)
    {
        $this->db->query('SELECT s.*, p.pre_project_id, pk.name AS package_name
                          FROM services s
                          INNER JOIN projects p ON s.project_id = p.project_id
                          INNER JOIN customerquotation q ON p.quotation_id = q.quotation_id
                          INNER JOIN package pk ON q.package_id = pk.package_id
                          WHERE s.customer_id = :customer_id
                          ORDER BY s.requested_date DESC');

        $this->db->bind(':customer_id', $customerId);

        return $this->db->resultSet();
    }

    /**
     * Get a single service request by ID
     * 
     * @param int $serviceId The service request ID
     * @return object The service request
     */
    public function getServiceRequestById($serviceId)
    {
        $this->db->query('SELECT * FROM services
                          WHERE service_id = :service_id');

        $this->db->bind(':service_id', $serviceId);

        return $this->db->single();
    }

    /**
     * Update service request status
     * 
     * @param int $serviceId The service ID
     * @param string $status The new status
     * @param string $comments Optional comments for status change
     * @return bool True if updated, false otherwise
     */
    public function updateServiceStatus($serviceId, $status, $comments = '')
    {
        $this->db->query('UPDATE services SET status = :status, 
                     comments = CASE WHEN :comments = "" THEN comments ELSE :comments END,
                     updated_at = NOW() 
                     WHERE service_id = :service_id');

        $this->db->bind(':status', $status);
        $this->db->bind(':comments', $comments);
        $this->db->bind(':service_id', $serviceId);

        return $this->db->execute();
    }


    public function isProjectInWarranty($projectId)
    {
        $this->db->query('SELECT p.project_id, pk.warranty_years, 
                         p.completed_date, p.created_at, p.status
                         FROM projects p 
                         INNER JOIN customerquotation q ON p.quotation_id = q.quotation_id 
                         INNER JOIN package pk ON q.package_id = pk.package_id
                         WHERE p.project_id = :project_id 
                         AND p.status = "completed" 
                         AND p.current_phase = "completed"
                         AND DATE_ADD(COALESCE(p.completed_date, p.created_at), INTERVAL pk.warranty_years YEAR) >= CURDATE()');

        $this->db->bind(':project_id', $projectId);

        $result = $this->db->single();
        return ($result) ? true : false;
    }

    public function cancelServiceRequest($serviceId)
    {
        $this->db->query('UPDATE services SET status = :status, updated_at = NOW() WHERE service_id = :service_id');

        $this->db->bind(':status', 'cancelled');
        $this->db->bind(':service_id', $serviceId);

        return $this->db->execute();
    }

}
