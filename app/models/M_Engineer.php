<?php

class M_Engineer
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPendingSiteVisits()
    {
        $this->db->query('SELECT 
                sv.*,
                pp.customer_id,
                u.name as customer_name,
                cq.nearest_city,
                cq.address,
                u.phone
                FROM site_visits sv
                JOIN pre_projects pp ON sv.pre_project_id = pp.pre_project_id
                JOIN users u ON pp.customer_id = u.user_id
                JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
                WHERE sv.status IN ("scheduled", "confirmed")
                ORDER BY sv.visit_date ASC, sv.visit_time ASC');

        return $this->db->resultSet();
    }

    public function getSiteVisitById($visitId)
    {
        $this->db->query('SELECT 
                sv.*,
                pp.customer_id,
                u.name as customer_name,
                cq.nearest_city,
                cq.address,
                u.phone,
                cq.package_id,
                p.title as package_name,
                p.description as package_description,
                p.type as package_type,
                p.warranty_years,
                p.price as package_base_price,
                p.service_charge,
                p.final_price
                FROM site_visits sv
                JOIN pre_projects pp ON sv.pre_project_id = pp.pre_project_id
                JOIN users u ON pp.customer_id = u.user_id
                JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
                LEFT JOIN package p ON cq.package_id = p.package_id
                WHERE sv.visit_id = :visit_id');

        $this->db->bind(':visit_id', $visitId);
        return $this->db->single();
    }

    public function getPackageEquipment($packageId)
    {
        $this->db->query('SELECT 
                pe.*,
                i.name as item_name,
                i.description as item_description,
                i.price as item_price
                FROM packageequipment pe
                JOIN inventory i ON pe.item_id = i.id
                WHERE pe.package_id = :package_id');

        $this->db->bind(':package_id', $packageId);
        return $this->db->resultSet();
    }

    public function getPackageFeatures($packageId)
    {
        $this->db->query('SELECT * FROM packagefeature
                WHERE package_id = :package_id');

        $this->db->bind(':package_id', $packageId);
        return $this->db->resultSet();
    }

    public function completeSiteVisit($preProjectId, $notes)
    {
        $this->db->query('UPDATE site_visits 
                SET status = "completed",
                    site_notes = :site_notes,
                    updated_at = CURRENT_TIMESTAMP
                WHERE pre_project_id = :pre_project_id 
                AND (status = "scheduled" OR status = "confirmed")');

        $this->db->bind(':pre_project_id', $preProjectId);
        $this->db->bind(':site_notes', $notes);

        if ($this->db->execute()) {
            // Update pre_project phase to agreement
            $this->db->query('UPDATE pre_projects 
                    SET current_phase = "agreement",
                        updated_at = CURRENT_TIMESTAMP
                    WHERE pre_project_id = :pre_project_id');

            $this->db->bind(':pre_project_id', $preProjectId);
            return $this->db->execute();
        }
        return false;
    }

    // Engineer Approval
    public function getApprovalProjects($engineerId)
    {
        $this->db->query('SELECT pc.*, p.*, u.name as customer_name, cq.nearest_city as location
                             FROM project_certificates pc
                             JOIN projects p ON pc.project_id = p.project_id
                             JOIN users u ON p.customer_id = u.user_id
                             LEFT JOIN customerquotation cq ON p.pre_project_id = cq.pre_project_id
                             WHERE pc.engineer_id = :engineer_id
                             ORDER BY pc.created_at DESC');
        $this->db->bind(':engineer_id', $engineerId);
        return $this->db->resultSet();
    }

    public function getProjectCertificate($projectId)
    {
        $this->db->query('SELECT * FROM project_certificates 
                     WHERE project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function submitProjectCertificates($projectId, $data)
    {
        $this->db->query('UPDATE project_certificates 
                     SET installation_certificate_1 = :cert1,
                         installation_certificate_2 = :cert2,
                         installation_image_1 = :img1,
                         installation_image_2 = :img2,
                         completed_at = CURRENT_TIMESTAMP
                     WHERE project_id = :project_id');

        $this->db->bind(':cert1', $data['installation_certificate_1']);
        $this->db->bind(':cert2', $data['installation_certificate_2'] ?? null);
        $this->db->bind(':img1', $data['installation_image_1']);
        $this->db->bind(':img2', $data['installation_image_2'] ?? null);
        $this->db->bind(':project_id', $projectId);

        return $this->db->execute();
    }
}
