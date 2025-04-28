<?php
class M_CustomerPreProject {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // public function submitQuotation($data) {
    //     // Create pre-project
    //     $this->db->query('INSERT INTO customerquotation (
    //         pre_project_id,
    //         user_id, 
    //         package_id, 
    //         address, 
    //         monthly_consumption, 
    //         nearest_city, 
    //         customizations,
    //         package_type,
    //         status
    //     ) VALUES (
    //         :pre_project_id,
    //         :user_id, 
    //         :package_id, 
    //         :address, 
    //         :monthly_consumption, 
    //         :nearest_city, 
    //         :customizations,
    //         :package_type,
    //         "pending"
    //     )');
        
    //     $this->db->bind(':pre_project_id', $preProjectId);
    //     $this->db->bind(':user_id', $data['user_id']);
    //     $this->db->bind(':package_id', $data['package_id']);
    //     $this->db->bind(':address', $data['address']);
    //     $this->db->bind(':monthly_consumption', $data['monthly_consumption']);
    //     $this->db->bind(':nearest_city', $data['nearest_city']);
    //     $this->db->bind(':customizations', $data['customizations']);
    //     $this->db->bind(':package_type', $data['package_type']);
        
    //     if (!$this->db->execute()) {
    //         return false;
    //     }
        
    //     // Update user phone
    //     $this->db->query('UPDATE users SET
    //         phone = :phone
    //         WHERE user_id = :user_id');
        
    //     $this->db->bind(':phone', $data['phone']);
    //     $this->db->bind(':user_id', $data['user_id']);
        
    //     return $this->db->execute();
    // }

    public function submitQuotation($data) {
        // 1. First create the pre-project
        $this->db->query('INSERT INTO pre_projects (
            customer_id,
            current_phase,
            status,
            created_at,
            updated_at
        ) VALUES (
            :customer_id,
            "quotation",
            "active",
            CURRENT_TIMESTAMP,
            CURRENT_TIMESTAMP
        )');
        
        $this->db->bind(':customer_id', $data['user_id']);
        
        if (!$this->db->execute()) {
            error_log("Failed to create pre-project");
            return false;
        }
        
        // Get the newly created pre_project_id
        $preProjectId = $this->db->lastInsertId();
        
        // 2. Now create the customer quotation with the pre_project_id
        $this->db->query('INSERT INTO customerquotation (
            pre_project_id,
            user_id, 
            package_id, 
            address, 
            monthly_consumption, 
            nearest_city, 
            customizations,
            package_type,
            status
        ) VALUES (
            :pre_project_id,
            :user_id, 
            :package_id, 
            :address, 
            :monthly_consumption, 
            :nearest_city, 
            :customizations,
            :package_type,
            "pending"
        )');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':package_id', $data['package_id']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':monthly_consumption', $data['monthly_consumption']);
        $this->db->bind(':nearest_city', $data['nearest_city']);
        $this->db->bind(':customizations', $data['customizations']);
        $this->db->bind(':package_type', $data['package_type']);
        
        if (!$this->db->execute()) {
            error_log("Failed to create quotation");
            return false;
        }
        
        // 3. Update user phone if provided
        if (!empty($data['phone'])) {
            $this->db->query('UPDATE users SET
                phone = :phone
                WHERE user_id = :user_id');
            
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':user_id', $data['user_id']);
            
            if (!$this->db->execute()) {
                error_log("Failed to update phone number");
                // Continue anyway since the main operations succeeded
            }
        }
        
        return true;
    }

    public function getQuotationById($id) {
        $this->db->query('
            SELECT cq.*, pp.current_phase, pp.status as project_status
            FROM customerquotation cq
            JOIN pre_projects pp ON cq.pre_project_id = pp.pre_project_id
            WHERE cq.quotation_id = :id
        ');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getQuotationsByUserId($userId) {
        $this->db->query('
            SELECT cq.*, pp.current_phase, pp.status as project_status
            FROM customerquotation cq
            JOIN pre_projects pp ON cq.pre_project_id = pp.pre_project_id
            WHERE cq.user_id = :user_id
            ORDER BY cq.created_at DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getQuotationStats() {
        $stats = [
            'pending' => 0,
            'under_review' => 0,
            'reviewed' => 0
        ];

        $this->db->query('SELECT status, COUNT(*) as count 
                         FROM customerquotation 
                         WHERE status IN ("pending", "under_review", "reviewed")
                         GROUP BY status');
        
        $results = $this->db->resultSet();
        
        foreach ($results as $result) {
            $stats[$result->status] = $result->count;
        }
        
        return $stats;
    }

    public function getQuotations() {
        $this->db->query('SELECT 
            cq.quotation_id,
            cq.monthly_consumption,
            cq.nearest_city,
            cq.status,
            cq.created_at,
            u.name as customer_name
            FROM customerquotation cq
            JOIN users u ON cq.user_id = u.user_id
            ORDER BY cq.created_at DESC');
        
        return $this->db->resultSet();
    }

    public function getQuotationsByStatus($status) {
        $this->db->query('SELECT 
            cq.quotation_id,
            cq.monthly_consumption,
            cq.nearest_city,
            cq.status,
            cq.created_at,
            u.name as customer_name
            FROM customerquotation cq
            JOIN users u ON cq.user_id = u.user_id
            WHERE cq.status = :status
            ORDER BY cq.created_at DESC');
            
        $this->db->bind(':status', $status);
        
        return $this->db->resultSet();
    }

    public function updateQuotationStatus($quotationId, $status) {
        $this->db->query('UPDATE customerquotation 
                         SET status = :status 
                         WHERE quotation_id = :quotation_id');
        
        $this->db->bind(':status', $status);
        $this->db->bind(':quotation_id', $quotationId);
        
        return $this->db->execute();
    }


    public function getPreProjectById($preProjectId) {
        $this->db->query('SELECT 
            pp.*,
            u.name as customer_name,
            u.email,
            u.phone,
            cq.address,
            cq.monthly_consumption,
            cq.nearest_city,
            cq.package_type,
            cq.status as quotation_status,
            p.title as package_name
            FROM pre_projects pp
            JOIN users u ON pp.customer_id = u.user_id
            LEFT JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
            LEFT JOIN package p ON cq.package_id = p.package_id
            WHERE pp.pre_project_id = :pre_project_id');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function updatePreProjectPhase($preProjectId, $phase) {
        $this->db->query('UPDATE pre_projects 
                         SET current_phase = :phase 
                         WHERE pre_project_id = :pre_project_id');
        
        $this->db->bind(':phase', $phase);
        $this->db->bind(':pre_project_id', $preProjectId);
        
        return $this->db->execute();
    }

    public function getProjectStats() {
        $stats = [
            'quotation' => 0,
            'site_visit' => 0,
            'agreement' => 0
        ];

        $this->db->query('SELECT current_phase, COUNT(*) as count 
                         FROM pre_projects 
                         WHERE status = "active"
                         GROUP BY current_phase');
        
        $results = $this->db->resultSet();
        
        foreach ($results as $result) {
            $stats[$result->current_phase] = $result->count;
        }
        
        return $stats;
    }


    public function getPreProjectStats() {
        $stats = [
            'quotation' => 0,
            'site_visit' => 0,
            'agreement' => 0
        ];
    
        $this->db->query('SELECT current_phase, COUNT(*) as count 
                         FROM pre_projects 
                         WHERE status = "active"
                         GROUP BY current_phase');
        
        $results = $this->db->resultSet();
        
        foreach ($results as $result) {
            $stats[$result->current_phase] = $result->count;
        }
        
        return $stats;
    }
    
    public function getAllPreProjects($phase = null) {
        $sql = 'SELECT 
            pp.*,
            u.name as customer_name,
            cq.nearest_city,
            cq.monthly_consumption,
            cq.created_at,
            cq.status as quotation_status
            FROM pre_projects pp
            JOIN users u ON pp.customer_id = u.user_id
            LEFT JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
            WHERE pp.status = "active"';
        
        if ($phase && $phase !== 'all') {
            $sql .= ' AND pp.current_phase = :phase';
        }
        
        $sql .= ' ORDER BY pp.created_at DESC';
        
        $this->db->query($sql);
        
        if ($phase && $phase !== 'all') {
            $this->db->bind(':phase', $phase);
        }
        
        return $this->db->resultSet();
    }

    public function getQuotationByPreProjectId($preProjectId) {
        $this->db->query('SELECT 
            cq.*,
            p.title as package_name,
            p.description as package_description,
            p.price as package_price
            FROM customerquotation cq
            LEFT JOIN package p ON cq.package_id = p.package_id
            WHERE cq.pre_project_id = :pre_project_id');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }
    //Quotation Phase

    public function getQuotationWithPackageDetails($preProjectId) {
        $this->db->query('SELECT 
            cq.*,
            p.title as package_name,
            p.description as package_description,
            p.price as base_price,
            p.service_charge,
            p.final_price as package_price,
            p.type as package_type
            FROM customerquotation cq
            LEFT JOIN package p ON cq.package_id = p.package_id
            WHERE cq.pre_project_id = :pre_project_id');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }
    
    public function getPackageEquipmentDetails($packageId) {
        $this->db->query('SELECT 
            pe.equipment_id,
            pe.package_id,
            pe.quantity,
            i.id as item_id,
            i.name as item_name,
            i.price as unit_price,
            i.description
            FROM packageequipment pe
            JOIN inventory i ON pe.item_id = i.id
            WHERE pe.package_id = :package_id
            AND i.deleted_at IS NULL');
        
        $this->db->bind(':package_id', $packageId);
        return $this->db->resultSet();
    }

    public function getPackageEquipments($packageId) {
        $this->db->query('SELECT 
            pe.*,
            i.name as item_name,
            i.price as unit_price,
            i.description
            FROM packageequipment pe
            JOIN inventory i ON pe.item_id = i.id
            WHERE pe.package_id = :package_id');
        
        $this->db->bind(':package_id', $packageId);
        return $this->db->resultSet();
    }
    
    public function getAvailableInventory() {
        $this->db->query('SELECT 
            id, name, price, quantity, description 
            FROM inventory 
            WHERE deleted_at IS NULL AND quantity > 0');
        return $this->db->resultSet();
    }
    
    public function createReviewedQuotation($data) {
        $this->db->query('INSERT INTO reviewed_quotations (
            quotation_id,
            system_capacity,
            estimated_generation,
            base_price,
            service_charge,
            total_price,
            notes,
            valid_until,
            status
        ) VALUES (
            :quotation_id,
            :system_capacity,
            :estimated_generation,
            :base_price,
            :service_charge,
            :total_price,
            :notes,
            :valid_until,
            "pending_customer_review"
        )');
    
        $this->db->bind(':quotation_id', $data['quotation_id']);
        $this->db->bind(':system_capacity', $data['system_capacity']);
        $this->db->bind(':estimated_generation', $data['estimated_generation']);
        $this->db->bind(':base_price', $data['base_price']);
        $this->db->bind(':service_charge', $data['service_charge']);
        $this->db->bind(':total_price', $data['total_price']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':valid_until', date('Y-m-d', strtotime('+7 days')));
    
        if($this->db->execute()) {
            $reviewId = $this->db->lastInsertId();
            return $this->addReviewedEquipment($reviewId, $data['equipment']);
        }
        return false;
    }
    
    private function addReviewedEquipment($reviewId, $equipment) {
        foreach($equipment as $item) {
            $this->db->query('INSERT INTO reviewed_quotation_equipment (
                review_id,
                inventory_id,
                quantity,
                unit_price,
                total_price,
                is_from_package,
                modification_type
            ) VALUES (
                :review_id,
                :inventory_id,
                :quantity,
                :unit_price,
                :total_price,
                :is_from_package,
                :modification_type
            )');
    
            $this->db->bind(':review_id', $reviewId);
            $this->db->bind(':inventory_id', $item['inventory_id']);
            $this->db->bind(':quantity', $item['quantity']);
            $this->db->bind(':unit_price', $item['unit_price']);
            $this->db->bind(':total_price', $item['quantity'] * $item['unit_price']);
            $this->db->bind(':is_from_package', $item['is_from_package']);
            $this->db->bind(':modification_type', $item['modification_type']);
    
            if(!$this->db->execute()) {
                return false;
            }
        }
        return true;
    }

    //################################################################
    //------------------------Site Visit Phase------------------------
    //################################################################



    
    public function getSiteVisitByPreProjectId($preProjectId) {
        $this->db->query('SELECT sv.*, pp.current_phase, pp.status as project_status 
            FROM site_visits sv
            JOIN pre_projects pp ON sv.pre_project_id = pp.pre_project_id
            WHERE sv.pre_project_id = :pre_project_id
            ORDER BY sv.created_at DESC
            LIMIT 1');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function scheduleSiteVisit($preProjectId, $date, $time) {
        $this->db->query('UPDATE site_visits 
            SET visit_date = :visit_date,
                visit_time = :visit_time,
                status = "scheduled",
                updated_at = CURRENT_TIMESTAMP
            WHERE pre_project_id = :pre_project_id
            AND status = "pending"');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        $this->db->bind(':visit_date', $date);
        $this->db->bind(':visit_time', $time);
        
        return $this->db->execute();
    }

    public function completeSiteVisit($preProjectId, $notes) {
        $this->db->query('UPDATE site_visits 
            SET status = "completed",
                site_notes = :site_notes,
                updated_at = CURRENT_TIMESTAMP
            WHERE pre_project_id = :pre_project_id 
            AND (status = "scheduled" OR status = "confirmed")');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        $this->db->bind(':site_notes', $notes);
        
        if($this->db->execute()) {
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

    public function requestReschedule($preProjectId, $reason) {
        $this->db->query('UPDATE site_visits 
            SET status = "reschedule_requested",
                reschedule_request = :reason,
                reschedule_status = "requested",
                updated_at = CURRENT_TIMESTAMP
            WHERE pre_project_id = :pre_project_id
            AND status = "scheduled"');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        $this->db->bind(':reason', $reason);
        return $this->db->execute();
    }
    
    public function handleReschedule($visitId, $newDate, $newTime) {
        $this->db->query('UPDATE site_visits 
            SET visit_date = :visit_date,
                visit_time = :visit_time,
                status = "scheduled",
                reschedule_status = "rescheduled",
                reschedule_request = NULL,
                updated_at = CURRENT_TIMESTAMP
            WHERE visit_id = :visit_id 
            AND status = "reschedule_requested"');
        
        $this->db->bind(':visit_id', $visitId);
        $this->db->bind(':visit_date', $newDate);
        $this->db->bind(':visit_time', $newTime);
        return $this->db->execute();
    }
    
    // public function getAllSiteVisits() {
    //     $this->db->query('SELECT 
    //         sv.*,
    //         pp.customer_id,
    //         u.name as customer_name,
    //         cq.nearest_city
    //         FROM site_visits sv
    //         JOIN pre_projects pp ON sv.pre_project_id = pp.pre_project_id
    //         JOIN users u ON pp.customer_id = u.user_id
    //         JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
    //         WHERE sv.status IN ("scheduled", "reschedule_requested")
    //         ORDER BY sv.visit_date ASC, sv.visit_time ASC');
        
    //     return $this->db->resultSet();
    // }

    public function getAllSiteVisits() {
        $this->db->query('SELECT 
            sv.*,
            pp.customer_id,
            u.name as customer_name,
            cq.nearest_city
            FROM site_visits sv
            JOIN pre_projects pp ON sv.pre_project_id = pp.pre_project_id
            JOIN users u ON pp.customer_id = u.user_id
            JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
            WHERE sv.status IN ("scheduled", "confirmed", "completed", "reschedule_requested")
            ORDER BY sv.visit_date ASC, sv.visit_time ASC');
        
        return $this->db->resultSet();
    }

    //#########################################################################################
    //-------------------------------------Agreement Phase-------------------------------------
    //#########################################################################################

    public function getReviewedEquipment($reviewId) {
        if (!$reviewId) {
            return [];
        }
    
        $this->db->query('SELECT rqe.*, i.name as item_name 
                         FROM reviewed_quotation_equipment rqe
                         JOIN inventory i ON rqe.inventory_id = i.id
                         WHERE rqe.review_id = :review_id');
        
        $this->db->bind(':review_id', $reviewId);
        return $this->db->resultSet();
    }


    public function getAgreementByPreProjectId($preProjectId) {
        $this->db->query('SELECT pa.*, cs.signature_image as coordinator_signature
                         FROM project_agreements pa
                         LEFT JOIN coordinator_signatures cs ON pa.coordinator_signature_id = cs.signature_id
                         WHERE pa.pre_project_id = :pre_project_id
                         ORDER BY pa.created_at DESC
                         LIMIT 1');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function getAgreementEquipment($agreementId) {
        $this->db->query('SELECT ae.*, i.name as item_name 
                         FROM agreement_equipment ae
                         JOIN inventory i ON ae.inventory_id = i.id
                         WHERE ae.agreement_id = :agreement_id');
        
        $this->db->bind(':agreement_id', $agreementId);
        return $this->db->resultSet();
    }

    public function getLatestReviewedQuotation($preProjectId) {
        $this->db->query('SELECT rq.*, cq.quotation_id 
                         FROM reviewed_quotations rq
                         JOIN customerquotation cq ON rq.quotation_id = cq.quotation_id
                         WHERE cq.pre_project_id = :pre_project_id
                         ORDER BY rq.created_at DESC
                         LIMIT 1');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function getReviwedQuotationByPreProjectId($preProjectId) {
        // First get the latest reviewed quotation for this project
        $this->db->query('SELECT rq.*, 
                                 cq.pre_project_id, 
                                 cq.nearest_city,
                                 cq.quotation_id as quotation_id,
                                 rq.review_id as review_id,
                                 rq.system_capacity,
                                 rq.estimated_generation,
                                 rq.base_price,
                                 rq.service_charge,
                                 rq.total_price
                          FROM customerquotation cq
                          LEFT JOIN reviewed_quotations rq ON cq.quotation_id = rq.quotation_id
                          WHERE cq.pre_project_id = :pre_project_id
                          ORDER BY rq.created_at DESC
                          LIMIT 1');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        $result = $this->db->single();
    
        if (!$result) {
            return null;
        }
    
        return $result;
    }

    public function getReviewedQuotationById($reviewId) {
        $this->db->query('SELECT * FROM reviewed_quotations WHERE review_id = :review_id');
        $this->db->bind(':review_id', $reviewId);
        return $this->db->single();
    }

    public function getReviewedQuotationByPreProjectId($preProjectId) {
        $this->db->query('SELECT rq.* 
                         FROM reviewed_quotations rq
                         JOIN customerquotation cq ON rq.quotation_id = cq.quotation_id
                         WHERE cq.pre_project_id = :pre_project_id
                         AND rq.status = "accepted"
                         ORDER BY rq.created_at DESC
                         LIMIT 1');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function getReviewedQuotationEquipment($reviewId) {
        $this->db->query('SELECT rqe.*, i.name as item_name 
                         FROM reviewed_quotation_equipment rqe
                         JOIN inventory i ON rqe.inventory_id = i.id
                         WHERE rqe.review_id = :review_id');
        
        $this->db->bind(':review_id', $reviewId);
        return $this->db->resultSet();
    }




    public function createAgreement($data) {
        $this->db->query('INSERT INTO project_agreements (
            pre_project_id,
            review_id,
            quotation_id,
            system_capacity,
            estimated_generation,
            base_price,
            service_charge,
            total_price,
            notes,
            coordinator_signature_id,
            status,
            valid_until
        ) VALUES (
            :pre_project_id,
            :review_id,
            :quotation_id,
            :system_capacity,
            :estimated_generation,
            :base_price,
            :service_charge,
            :total_price,
            :notes,
            :coordinator_signature_id,
            "pending",
            DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)
        )');
    
        $this->db->bind(':pre_project_id', $data['pre_project_id']);
        $this->db->bind(':review_id', $data['review_id']);
        $this->db->bind(':quotation_id', $data['quotation_id']);
        $this->db->bind(':system_capacity', $data['system_capacity']);
        $this->db->bind(':estimated_generation', $data['estimated_generation']);
        $this->db->bind(':base_price', $data['base_price']);
        $this->db->bind(':service_charge', $data['service_charge']);
        $this->db->bind(':total_price', $data['total_price']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':coordinator_signature_id', $data['coordinator_signature_id']);
    
        if($this->db->execute()) {
            $agreementId = $this->db->lastInsertId();
            return $this->addEquipmentToAgreement($agreementId, $data['equipment']);
        }
        return false;
    }

    private function addEquipmentToAgreement($agreementId, $equipment) {
        foreach ($equipment as $item) {
            $this->db->query('INSERT INTO agreement_equipment (
                agreement_id,
                inventory_id,
                quantity,
                unit_price,
                total_price
            ) VALUES (
                :agreement_id,
                :inventory_id,
                :quantity,
                :unit_price,
                :total_price
            )');

            $this->db->bind(':agreement_id', $agreementId);
            $this->db->bind(':inventory_id', $item['id']);
            $this->db->bind(':quantity', $item['quantity']);
            $this->db->bind(':unit_price', $item['unitPrice']);
            $this->db->bind(':total_price', $item['quantity'] * $item['unitPrice']);

            if(!$this->db->execute()) {
                return false;
            }
        }
        return true;
    }



    public function getAgreementById($agreementId) {
        // Get basic agreement data
        $query = 'SELECT pa.*, 
            cs.signature_image,
            u.name as customer_name,
            u.phone
        FROM project_agreements pa
        LEFT JOIN coordinator_signatures cs ON pa.coordinator_signature_id = cs.signature_id
        LEFT JOIN pre_projects pp ON pa.pre_project_id = pp.pre_project_id
        LEFT JOIN users u ON pp.customer_id = u.user_id
        WHERE pa.agreement_id = :agreement_id';
    
        $this->db->query($query);
        $this->db->bind(':agreement_id', $agreementId);
        $agreement = $this->db->single();
    
        if (!$agreement) {
            return null;
        }
    
        // Get equipment for this agreement
        $equipmentQuery = 'SELECT ae.*, i.name as item_name
            FROM agreement_equipment ae
            JOIN inventory i ON ae.inventory_id = i.id
            WHERE ae.agreement_id = :agreement_id';
    
        $this->db->query($equipmentQuery);
        $this->db->bind(':agreement_id', $agreementId);
        $agreement->equipment = $this->db->resultSet();
    
        return $agreement;
    }


    
    public function updateAgreementStatus($agreementId, $status) {
        $this->db->query('UPDATE project_agreements 
                         SET status = :status 
                         WHERE agreement_id = :agreement_id');
        
        $this->db->bind(':agreement_id', $agreementId);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }



    public function createRevisedAgreement($data) {
        // Create new agreement
        $insertQuery = "INSERT INTO project_agreements (
            pre_project_id,
            review_id,
            quotation_id,
            system_capacity,
            estimated_generation,
            base_price,
            service_charge,
            total_price,
            notes,
            coordinator_signature_id,
            status,
            valid_until
        ) VALUES (
            :pre_project_id,
            :review_id,
            :quotation_id,
            :system_capacity,
            :estimated_generation,
            :base_price,
            :service_charge,
            :total_price,
            :notes,
            :coordinator_signature_id,
            'pending',
            DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)
        )";
    
        $this->db->query($insertQuery);
        $this->db->bind(':pre_project_id', $data['pre_project_id']);
        $this->db->bind(':review_id', $data['review_id']);
        $this->db->bind(':quotation_id', $data['quotation_id']);
        $this->db->bind(':system_capacity', $data['system_capacity']);
        $this->db->bind(':estimated_generation', $data['estimated_generation']);
        $this->db->bind(':base_price', $data['base_price']);
        $this->db->bind(':service_charge', $data['service_charge']);
        $this->db->bind(':total_price', $data['total_price']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':coordinator_signature_id', $data['coordinator_signature_id']);
    
        if (!$this->db->execute()) {
            return false;
        }
    
        $newAgreementId = $this->db->lastInsertId();
    
        // Add equipment
        foreach ($data['equipment'] as $item) {
            $equipmentQuery = "INSERT INTO agreement_equipment (
                agreement_id,
                inventory_id,
                quantity,
                unit_price,
                total_price
            ) VALUES (
                :agreement_id,
                :inventory_id,
                :quantity,
                :unit_price,
                :total_price
            )";
    
            $this->db->query($equipmentQuery);
            $this->db->bind(':agreement_id', $newAgreementId);
            $this->db->bind(':inventory_id', $item['id']);
            $this->db->bind(':quantity', $item['quantity']);
            $this->db->bind(':unit_price', $item['unitPrice']);
            $this->db->bind(':total_price', $item['quantity'] * $item['unitPrice']);
    
            if (!$this->db->execute()) {
                return false;
            }
        }
    
        return $newAgreementId;
    }
    
    public function cancelAgreement($agreementId) {
        $this->db->query("UPDATE project_agreements SET status = 'cancelled', updated_at = CURRENT_TIMESTAMP WHERE agreement_id = :id");
        $this->db->bind(':id', $agreementId);
        
        if (!$this->db->execute()) {
            return false;
        }
    
        // Verify the update
        $this->db->query("SELECT status FROM project_agreements WHERE agreement_id = :id");
        $this->db->bind(':id', $agreementId);
        $result = $this->db->single();
        
        return ($result && $result->status === 'cancelled');
    }

    public function saveCoordinatorSignature($coordinatorId, $signaturePath) {
        $this->db->query('INSERT INTO coordinator_signatures (coordinator_id, signature_image) 
                         VALUES (:coordinator_id, :signature_image)');
        
        $this->db->bind(':coordinator_id', $coordinatorId);
        $this->db->bind(':signature_image', $signaturePath);
        
        return $this->db->execute();
    }

    public function getCoordinatorSignature($coordinatorId) {
        $this->db->query('SELECT * FROM coordinator_signatures 
                         WHERE coordinator_id = :coordinator_id 
                         ORDER BY created_at DESC LIMIT 1');
        
        $this->db->bind(':coordinator_id', $coordinatorId);
        return $this->db->single();
    }

    public function updateCustomerSignature($agreementId, $signaturePath) {
        $this->db->query('UPDATE project_agreements 
                         SET customer_signature = :signature,
                             status = "completed"
                         WHERE agreement_id = :agreement_id');
        
        $this->db->bind(':signature', $signaturePath);
        $this->db->bind(':agreement_id', $agreementId);
        
        return $this->db->execute();
    }




    //For operations Dashboard in Operations Coordinator
    public function getRevisionRequestedAgreement($preProjectId) {
        $query = 'SELECT * FROM project_agreements 
                  WHERE pre_project_id = :pre_project_id 
                  AND status = "revision_requested"
                  ORDER BY created_at DESC 
                  LIMIT 1';
                  
        $this->db->query($query);
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }


}