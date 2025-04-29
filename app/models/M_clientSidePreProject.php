<?php
class M_clientSidePreProject{
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getProjectsByCustomerId($customerId) {
        $this->db->query('SELECT * FROM project 
                         WHERE customer_id = :customer_id 
                         ORDER BY created_at DESC');
        
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }

    public function getProjectById($projectId) {
        $this->db->query('SELECT 
            p.*,
            rq.system_capacity,
            rq.estimated_generation,
            rq.base_price,
            rq.service_charge,
            rq.total_price
            FROM project p
            LEFT JOIN reviewed_quotations rq ON p.quotation_id = rq.review_id
            WHERE p.project_id = :project_id');
        
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function getProjectPhaseDetails($projectId) {
        $this->db->query('SELECT 
            current_phase,
            phase_status,
            documents_submitted,
            first_payment_amount,
            final_payment_amount,
            first_payment_date,
            final_payment_date,
            engineer_approval_date,
            grid_connection_date
            FROM project
            WHERE project_id = :project_id');
        
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function updateProjectPhase($projectId, $phase, $status = 'pending') {
        $this->db->query('UPDATE project SET 
            current_phase = :phase,
            phase_status = :status,
            updated_at = CURRENT_TIMESTAMP
            WHERE project_id = :project_id');
        
        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':phase', $phase);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }
    public function getPreProjectsByCustomerId($customerId) {
        $this->db->query('SELECT 
            pp.*,
            cq.quotation_id,
            cq.package_id,
            cq.package_type,
            cq.status as quotation_status,
            cq.created_at as quotation_date
            FROM pre_projects pp
            LEFT JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
            WHERE pp.customer_id = :customer_id 
            ORDER BY pp.created_at DESC');
        
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
    }
    
    public function getQuotationById($quotationId) {
        $this->db->query('SELECT 
            cq.*,
            pp.current_phase,
            pp.status as pre_project_status,
            p.title as package_name,
            p.description as package_description,
            p.price as base_price,
            p.service_charge,
            p.final_price as package_price
            FROM customerquotation cq
            JOIN pre_projects pp ON cq.pre_project_id = pp.pre_project_id
            LEFT JOIN package p ON cq.package_id = p.package_id
            WHERE cq.quotation_id = :quotation_id');
        
        $this->db->bind(':quotation_id', $quotationId);
        return $this->db->single();
    }
    
    public function getActiveQuotationsByCustomerId($customerId) {
        $this->db->query('SELECT 
            cq.*,
            p.title as package_name,
            p.description as package_description,
            p.price as base_price,
            p.service_charge,
            p.final_price as package_price
            FROM customerquotation cq
            LEFT JOIN package p ON cq.package_id = p.package_id
            WHERE cq.user_id = :user_id 
            AND cq.status NOT IN ("accepted_by_customer", "rejected")
            ORDER BY cq.created_at DESC');
        
        $this->db->bind(':user_id', $customerId);
        return $this->db->resultSet();  // Changed from single() to resultSet()
    }
    
    public function getReviewedQuotation($quotationId) {
        $this->db->query('SELECT * FROM reviewed_quotations 
            WHERE quotation_id = :quotation_id 
            AND status NOT IN ("expired", "rejected")
            ORDER BY created_at DESC 
            LIMIT 1');
        
        $this->db->bind(':quotation_id', $quotationId);
        return $this->db->single();
    }
    
    public function acceptQuotation($quotationId) {
        // Update reviewed_quotations status
        $this->db->query('UPDATE reviewed_quotations 
            SET status = "accepted"
            WHERE quotation_id = :quotation_id');
        $this->db->bind(':quotation_id', $quotationId);
        $this->db->execute();

        // Update customerquotation status
        $this->db->query('UPDATE customerquotation 
            SET status = "accepted_by_customer"
            WHERE quotation_id = :quotation_id');
        $this->db->bind(':quotation_id', $quotationId);
        $this->db->execute();

        // Get quotation info
        $this->db->query('SELECT pre_project_id, user_id FROM customerquotation 
            WHERE quotation_id = :quotation_id');
        $this->db->bind(':quotation_id', $quotationId);
        $quotation = $this->db->single();

        // Update pre_projects to site_visit phase
        $this->db->query('UPDATE pre_projects 
            SET status = "active",
                current_phase = "site_visit",
                updated_at = CURRENT_TIMESTAMP
            WHERE pre_project_id = :pre_project_id');
        $this->db->bind(':pre_project_id', $quotation->pre_project_id);
        $this->db->execute();

        // Create initial site visit entry
        $this->db->query('INSERT INTO site_visits (
            pre_project_id, status
        ) VALUES (
            :pre_project_id, "pending"
        )');
        $this->db->bind(':pre_project_id', $quotation->pre_project_id);
        
        return $this->db->execute();
    }

    public function rejectQuotation($quotationId) {
        // Update reviewed_quotations status
        $this->db->query('UPDATE reviewed_quotations 
            SET status = "rejected"
            WHERE quotation_id = :quotation_id');
        $this->db->bind(':quotation_id', $quotationId);
        $this->db->execute();

        // Update customerquotation status
        $this->db->query('UPDATE customerquotation 
            SET status = "rejected_by_customer"
            WHERE quotation_id = :quotation_id');
        $this->db->bind(':quotation_id', $quotationId);
        
        return $this->db->execute();
    }

    public function getReviewedEquipment($reviewId) {
        $this->db->query('SELECT 
            rqe.*,
            i.name as item_name,
            i.description
            FROM reviewed_quotation_equipment rqe
            JOIN inventory i ON rqe.inventory_id = i.id
            WHERE rqe.review_id = :review_id');
        
        $this->db->bind(':review_id', $reviewId);
        return $this->db->resultSet();
    }
    

    public function getReviewedQuotationDetails($quotationId) {
        $this->db->query('SELECT 
            rq.*,
            cq.user_id,
            cq.package_type,
            cq.monthly_consumption,
            cq.nearest_city,
            cq.customizations,
            p.title as package_name,
            p.description as package_description
            FROM reviewed_quotations rq
            JOIN customerquotation cq ON rq.quotation_id = cq.quotation_id
            LEFT JOIN package p ON cq.package_id = p.package_id
            WHERE rq.quotation_id = :quotation_id 
            AND rq.status = "pending_customer_review"
            ORDER BY rq.created_at DESC 
            LIMIT 1');
        
        $this->db->bind(':quotation_id', $quotationId);
        return $this->db->single();
    }
    
    public function getReviewedQuotationEquipment($quotationId) {
        $this->db->query('SELECT 
            rqe.*,
            i.name as item_name,
            i.description
            FROM reviewed_quotations rq
            JOIN reviewed_quotation_equipment rqe ON rq.review_id = rqe.review_id
            JOIN inventory i ON rqe.inventory_id = i.id
            WHERE rq.quotation_id = :quotation_id
            ORDER BY rqe.id ASC');
        
        $this->db->bind(':quotation_id', $quotationId);
        return $this->db->resultSet();
    }


    public function getActiveProjects($userId) {
        try {
            $this->db->query('SELECT pre_project_id, current_phase, status
                             FROM pre_projects
                             WHERE customer_id = :user_id
                             AND status = "active"
                             ORDER BY created_at DESC');
            $this->db->bind(':user_id', $userId);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log("Error in getActiveProjects: " . $e->getMessage());
            return [];
        }
    }

    public function getProjectProgress($pre_project_id) {
        try {
            // Get pre-project data
            $this->db->query('SELECT pp.*, cq.quotation_id
                             FROM pre_projects pp
                             LEFT JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
                             WHERE pp.pre_project_id = :pre_project_id');
            $this->db->bind(':pre_project_id', $pre_project_id);
            $preProject = $this->db->single();

            if (!$preProject) {
                return false;
            }

            // Get project data if exists
            $this->db->query('SELECT * FROM projects
                             WHERE pre_project_id = :pre_project_id
                             AND status = "active"');
            $this->db->bind(':pre_project_id', $pre_project_id);
            $project = $this->db->single();

            return [
                'pre_project' => $preProject,
                'project' => $project
            ];
        } catch (Exception $e) {
            error_log("Error in getProjectProgress: " . $e->getMessage());
            return false;
        }
    }
    
    //################################################
    //-------------------Site Visit-------------------
    //################################################

    public function getSiteVisit($preProjectId) {
        $this->db->query('SELECT sv.*, pp.customer_id 
            FROM site_visits sv
            JOIN pre_projects pp ON sv.pre_project_id = pp.pre_project_id
            WHERE sv.pre_project_id = :pre_project_id');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function confirmSchedule($visitId) {
        $this->db->query('UPDATE site_visits 
            SET status = "confirmed",
                updated_at = CURRENT_TIMESTAMP
            WHERE visit_id = :visit_id');
        
        $this->db->bind(':visit_id', $visitId);
        return $this->db->execute();
    }

    public function requestReschedule($visitId, $reason) {
        // Update site visit status and add reschedule request
        $this->db->query('UPDATE site_visits 
            SET status = "reschedule_requested",
                reschedule_request = :reason,
                reschedule_status = "requested",
                updated_at = CURRENT_TIMESTAMP 
            WHERE visit_id = :visit_id
            AND status = "scheduled"');
        
        $this->db->bind(':visit_id', $visitId);
        $this->db->bind(':reason', $reason);
        
        return $this->db->execute();
    }

    //################################################
    //-------------------Agreement-------------------
    //################################################

    public function getPendingAgreement($preProjectId) {
        $this->db->query("SELECT pa.*, pp.customer_id, pp.current_phase, pp.status as project_status,
                          cs.signature_image as coordinator_signature 
                          FROM project_agreements pa 
                          JOIN pre_projects pp ON pa.pre_project_id = pp.pre_project_id 
                          LEFT JOIN coordinator_signatures cs ON pa.coordinator_signature_id = cs.signature_id 
                          WHERE pa.pre_project_id = :pre_project_id 
                          AND pa.status = 'pending' 
                          ORDER BY pa.created_at DESC 
                          LIMIT 1");
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function getAgreementEquipment($agreementId) {
        $this->db->query("SELECT ae.*, i.name as item_name 
                         FROM agreement_equipment ae 
                         JOIN inventory i ON ae.inventory_id = i.id 
                         WHERE ae.agreement_id = :agreement_id");
        
        $this->db->bind(':agreement_id', $agreementId);
        return $this->db->resultSet();
    }

    public function submitRevisionRequest($agreementId, $revisionNote) {
        $this->db->query("UPDATE project_agreements 
                         SET status = 'revision_requested', 
                             revision_request = :revision_request,
                             updated_at = CURRENT_TIMESTAMP 
                         WHERE agreement_id = :agreement_id");
        
        $this->db->bind(':agreement_id', $agreementId);
        $this->db->bind(':revision_request', $revisionNote);
        return $this->db->execute();
    }

    public function uploadSignature($agreementId, $signaturePath) {
        try {
            // Let's add debug logs for each step
            error_log("Step 1: Getting agreement details");
    
            // Get agreement and customer details
            $this->db->query("SELECT pa.*, pp.customer_id
                             FROM project_agreements pa
                             JOIN pre_projects pp ON pa.pre_project_id = pp.pre_project_id
                             WHERE pa.agreement_id = :agreement_id");
            
            $this->db->bind(':agreement_id', $agreementId);
            $agreement = $this->db->single();
    
            if (!$agreement) {
                error_log("Agreement not found: " . $agreementId);
                return false;
            }
    
            error_log("Step 2: Updating project_agreements status");
    
            // Update agreement status
            $this->db->query("UPDATE project_agreements 
                             SET status = 'completed', /* Changed from 'completed' to match enum */
                                 customer_signature = :signature,
                                 updated_at = CURRENT_TIMESTAMP 
                             WHERE agreement_id = :agreement_id");
            
            $this->db->bind(':agreement_id', $agreementId);
            $this->db->bind(':signature', $signaturePath);
            $agreementUpdated = $this->db->execute();
    
            if (!$agreementUpdated) {
                error_log("Failed to update agreement");
                return false;
            }
    
            error_log("Step 3: Updating pre_projects status");
    
            // Update pre-project status
            $this->db->query("UPDATE pre_projects 
                             SET status = 'completed',
                                 current_phase = 'agreement',
                                 updated_at = CURRENT_TIMESTAMP 
                             WHERE pre_project_id = :pre_project_id");
            
            $this->db->bind(':pre_project_id', $agreement->pre_project_id);
            $preProjectUpdated = $this->db->execute();
    
            if (!$preProjectUpdated) {
                error_log("Failed to update pre-project");
                return false;
            }
    
            error_log("Step 4: Creating new project");
    
            // Log the SQL query before execution
            $query = "INSERT INTO `projects` (
                `pre_project_id`,
                `customer_id`,
                `quotation_id`,
                `agreement_id`,
                `current_phase`,
                `status`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :pre_project_id,
                :customer_id,
                :quotation_id,
                :agreement_id,
                'document_submission',
                'active',
                CURRENT_TIMESTAMP,
                CURRENT_TIMESTAMP
            )";
    
            error_log("SQL Query: " . $query);
    
            $this->db->query($query);
    
            $this->db->bind(':pre_project_id', $agreement->pre_project_id);
            $this->db->bind(':agreement_id', $agreement->agreement_id);
            $this->db->bind(':customer_id', $agreement->customer_id);
            $this->db->bind(':quotation_id', $agreement->quotation_id);
            
            error_log("Binding values: pre_project_id=" . $agreement->pre_project_id . 
                     ", customer_id=" . $agreement->customer_id . 
                     ", quotation_id=" . $agreement->quotation_id);
    
            $projectCreated = $this->db->execute();
            
            if (!$projectCreated) {
                return false;
            }
    
            error_log("Step 5: All operations completed successfully");
            return true;
    
        } catch (Exception $e) {
            error_log("Error in uploadSignature: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function getAgreementById($agreementId) {
        // Get basic agreement data with additional customer information
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
        
        return $agreement;
    }

    // public function getAgreementEquipment($agreementId) {
    //     $query = 'SELECT ae.*, 
    //                 i.name as item_name,
    //                 i.description as item_description,
    //                 i.category as item_category
    //             FROM agreement_equipment ae
    //             LEFT JOIN inventory i ON ae.inventory_id = i.inventory_id
    //             WHERE ae.agreement_id = :agreement_id';
        
    //     $this->db->query($query);
    //     $this->db->bind(':agreement_id', $agreementId);
        
    //     return $this->db->resultSet();
    // }

    public function getAgreementByQuotationId($quotationId) {
        // Get agreement data based on quotation ID
        $query = 'SELECT pa.*,
                    cs.signature_image,
                    u.name as customer_name,
                    u.phone,
                    pp.customer_id
                FROM project_agreements pa
                LEFT JOIN coordinator_signatures cs ON pa.coordinator_signature_id = cs.signature_id
                LEFT JOIN pre_projects pp ON pa.pre_project_id = pp.pre_project_id
                LEFT JOIN users u ON pp.customer_id = u.user_id
                WHERE pa.quotation_id = :quotation_id';
        
        $this->db->query($query);
        $this->db->bind(':quotation_id', $quotationId);
        
        $agreement = $this->db->single();
        
        return $agreement;
    }




    public function cancelProject($preProjectId) {
        // Update pre_projects status
        $this->db->query("UPDATE pre_projects 
                          SET status = 'cancelled', 
                              updated_at = CURRENT_TIMESTAMP 
                          WHERE pre_project_id = :pre_project_id");
        $this->db->bind(':pre_project_id', $preProjectId);
        $preProjectUpdated = $this->db->execute();
    
        // Update project_agreements status
        $this->db->query("UPDATE project_agreements 
                          SET status = 'cancelled', 
                              updated_at = CURRENT_TIMESTAMP 
                          WHERE pre_project_id = :pre_project_id");
        $this->db->bind(':pre_project_id', $preProjectId);
        $agreementUpdated = $this->db->execute();
    
        return $preProjectUpdated && $agreementUpdated;
    }

    

}