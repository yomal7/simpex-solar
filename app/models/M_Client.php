<?php

    // Models/M_Clients.php
    class M_Client {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }

        public function getClientByUserId($userId) {
            $this->db->query('SELECT * FROM users WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);

            return $this->db->single();
        }

        // public function getUserById($id) {
        //     $this->db->query('SELECT * FROM users WHERE user_id = :id');
        //     $this->db->bind(':id', $id);
        //     return $this->db->single();
        // }

        public function getUserById($userId) {
            $this->db->query('SELECT user_id, name, email, phone, profile_picture, role FROM users WHERE user_id = :user_id AND role = "customer"');
            $this->db->bind(':user_id', $userId);
            
            $result = $this->db->single();
            
            if ($this->db->rowCount() > 0) {
                return $result;
            }
            
            return false;
        }
    
        public function updateProfile($userId, $data) {
            $this->db->query('UPDATE users SET phone = :phone WHERE user_id = :user_id AND role = "customer"');
            
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':user_id', $userId);
            
            return $this->db->execute();
        }


        public function verifyPassword($userId, $password) {
            $this->db->query('SELECT password FROM users WHERE user_id = :user_id AND role = "customer"');
            $this->db->bind(':user_id', $userId);
            
            $row = $this->db->single();
            
            if ($row) {
                return password_verify($password, $row->password);
            }
            
            return false;
        }

        public function updatePassword($userId, $newPassword) {
            $this->db->query('UPDATE users SET password = :password WHERE user_id = :user_id AND role = "customer"');
            
            $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
            $this->db->bind(':user_id', $userId);
            
            return $this->db->execute();
        }
    
        public function updateProfilePicture($userId, $fileName) {
            $this->db->query('UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id AND role = "customer"');
            
            $this->db->bind(':profile_picture', $fileName);
            $this->db->bind(':user_id', $userId);
            
            return $this->db->execute();
        }





        public function getOngoingProjects($userId) {
            // Get projects with accepted quotations
            $this->db->query('SELECT 
                pp.pre_project_id, 
                pp.customer_id, 
                pp.current_phase as pre_project_phase, 
                pp.status as pre_project_status,
                pp.created_at, 
                pp.updated_at,
                cq.quotation_id, 
                cq.package_id, 
                cq.package_type,
                cq.nearest_city,
                p.title as package_name, 
                p.type as system_type,
                proj.project_id,
                proj.current_phase as project_phase,
                proj.status as project_status
            FROM pre_projects pp
            LEFT JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
            LEFT JOIN package p ON cq.package_id = p.package_id
            LEFT JOIN projects proj ON pp.pre_project_id = proj.pre_project_id
            WHERE pp.customer_id = :user_id 
            AND (pp.status = "active" OR (proj.status = "active" AND proj.customer_id = :user_id2))
            ORDER BY pp.created_at DESC');
            
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':user_id2', $userId);
            $projects = $this->db->resultSet();
            
            // Enhance project data with progress information
            if ($projects) {
                foreach ($projects as &$project) {
                    // Determine if this is in pre-project or main project phase
                    $projectPhases = ['document_submission', 'first_payment', 'installation', 
                                   'final_payment', 'engineer_approval', 'grid_connection', 'completed'];
                    
                    // If main project exists and is active, use its phase instead
                    if (isset($project->project_id) && $project->project_status == 'active') {
                        $project->current_phase = $project->project_phase;
                        $project->is_project_phase = true;
                        $project->is_pre_project_phase = false;
                        
                        // Calculate progress based on main project phase
                        switch ($project->current_phase) {
                            case 'document_submission': $project->progress_percentage = 45; break;
                            case 'first_payment': $project->progress_percentage = 55; break;
                            case 'installation': $project->progress_percentage = 70; break;
                            case 'final_payment': $project->progress_percentage = 80; break;
                            case 'engineer_approval': $project->progress_percentage = 90; break;
                            case 'grid_connection': $project->progress_percentage = 95; break;
                            case 'completed': $project->progress_percentage = 100; break;
                            default: $project->progress_percentage = 50; break;
                        }
                    } 
                    // Otherwise, use pre-project phase
                    else {
                        $project->current_phase = $project->pre_project_phase;
                        $project->is_project_phase = false;
                        $project->is_pre_project_phase = true;
                        
                        // Calculate progress based on pre-project phase
                        switch ($project->current_phase) {
                            case 'quotation': $project->progress_percentage = 10; break;
                            case 'site_visit': $project->progress_percentage = 25; break;
                            case 'agreement': $project->progress_percentage = 40; break;
                            default: $project->progress_percentage = 10; break;
                        }
                    }
                }
            }
            
            return $projects;
        }

        public function getProjectStats($userId) {
            // Get active projects count (pre-projects with accepted quotations)
            $this->db->query('SELECT COUNT(*) as count FROM pre_projects pp
                  LEFT JOIN reviewed_quotations rq ON pp.quotation_id = rq.quotation_id
                  WHERE pp.customer_id = :user_id 
                  AND pp.status = "active"
                  AND rq.status = "accepted"');
            $this->db->bind(':user_id', $userId);
            $activeProjectsRow = $this->db->single();
            $activeProjects = $activeProjectsRow ? $activeProjectsRow->count : 0;
        
            // Get total completed projects
            $this->db->query('SELECT COUNT(*) as count FROM projects 
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

        public function getActiveQuotationsByCustomerId($userId) {
        $this->db->query('SELECT 
            cq.*,
            pp.current_phase,
            pp.status as project_status,
            p.title as package_name,
            p.description as package_description,
            p.price as base_price,
            p.service_charge,
            p.final_price as package_price,
            p.type as system_type
            FROM customerquotation cq
            LEFT JOIN pre_projects pp ON cq.pre_project_id = pp.pre_project_id
            LEFT JOIN package p ON cq.package_id = p.package_id
            WHERE cq.user_id = :user_id 
            AND cq.status NOT IN ("accepted_by_customer", "rejected_by_customer", "cancelled")
            ORDER BY cq.created_at DESC');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
        }
        
        // Get quotation by ID
        public function getQuotationById($quotationId) {
        $this->db->query('SELECT 
            cq.*,
            pp.current_phase,
            pp.status as project_status,
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
        
        // Get pre-projects by customer ID
        public function getPreProjectsByCustomerId($customerId) {
        $this->db->query('SELECT 
            pp.*,
            cq.quotation_id,
            cq.package_id,
            cq.package_type,
            cq.nearest_city,
            cq.status as quotation_status,
            cq.created_at as quotation_date
            FROM pre_projects pp
            LEFT JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
            WHERE pp.customer_id = :customer_id 
            ORDER BY pp.created_at DESC');
        
        $this->db->bind(':customer_id', $customerId);
        return $this->db->resultSet();
        }
        
        // Get active projects for a customer
        public function getActiveProjects($userId) {
        $this->db->query('SELECT pre_project_id, current_phase, status
              FROM pre_projects
              WHERE customer_id = :user_id
              AND status = "active"
              ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
        }
        
        // Get project progress details for both pre-project and project phases
        public function getProjectProgress($preProjectId) {
        try {
            // Get pre-project data
            $this->db->query('SELECT pp.*, cq.quotation_id, cq.package_id, cq.nearest_city, 
                 cq.status as quotation_status, p.title as package_name
                 FROM pre_projects pp
                 LEFT JOIN customerquotation cq ON pp.pre_project_id = cq.pre_project_id
                 LEFT JOIN package p ON cq.package_id = p.package_id
                 WHERE pp.pre_project_id = :pre_project_id');
            $this->db->bind(':pre_project_id', $preProjectId);
            $preProject = $this->db->single();

            if (!$preProject) {
                return false;
            }

            // Check if project exists (main project phase)
            $this->db->query('SELECT * FROM projects 
                 WHERE pre_project_id = :pre_project_id');
            $this->db->bind(':pre_project_id', $preProjectId);
            $project = $this->db->single();

            // Get site visit data if exists
            $siteVisit = null;
            if ($preProject->current_phase == 'site_visit' || $preProject->current_phase == 'agreement') {
                $this->db->query('SELECT * FROM site_visits 
                     WHERE pre_project_id = :pre_project_id 
                     ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':pre_project_id', $preProjectId);
                $siteVisit = $this->db->single();
            }
            
            // Get agreement data if exists
            $agreement = null;
            if ($preProject->current_phase == 'agreement' || ($project && $project->status == 'active')) {
                $this->db->query('SELECT * FROM project_agreements 
                     WHERE pre_project_id = :pre_project_id 
                     ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':pre_project_id', $preProjectId);
                $agreement = $this->db->single();
            }

            // Get first payment data if exists
            $firstPayment = null;
            if ($project && $project->current_phase != 'document_submission') {
                $this->db->query('SELECT * FROM payments 
                     WHERE project_id = :project_id AND type = "first_payment"
                     ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':project_id', $project->project_id);
                $firstPayment = $this->db->single();
            }

            // Get installation data if exists
            $installation = null;
            if ($project && in_array($project->current_phase, ['installation', 'final_payment', 'engineer_approval', 'grid_connection', 'completed'])) {
                $this->db->query('SELECT * FROM installations 
                     WHERE project_id = :project_id
                     ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':project_id', $project->project_id);
                $installation = $this->db->single();
            }

            // Get final payment data if exists
            $finalPayment = null;
            if ($project && in_array($project->current_phase, ['final_payment', 'engineer_approval', 'grid_connection', 'completed'])) {
                $this->db->query('SELECT * FROM payments 
                     WHERE project_id = :project_id AND type = "final_payment"
                     ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':project_id', $project->project_id);
                $finalPayment = $this->db->single();
            }

            // Get engineer approval data if exists
            $engineerApproval = null;
            if ($project && in_array($project->current_phase, ['engineer_approval', 'grid_connection', 'completed'])) {
                $this->db->query('SELECT * FROM engineer_approvals 
                     WHERE project_id = :project_id
                     ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':project_id', $project->project_id);
                $engineerApproval = $this->db->single();
            }

            // Get grid connection data if exists
            $gridConnection = null;
            if ($project && in_array($project->current_phase, ['grid_connection', 'completed'])) {
                $this->db->query('SELECT * FROM grid_connections 
                     WHERE project_id = :project_id
                     ORDER BY created_at DESC LIMIT 1');
                $this->db->bind(':project_id', $project->project_id);
                $gridConnection = $this->db->single();
            }

            // Calculate overall progress percentage
            $progressPercentage = 0;
            
            // Pre-project phases (0-40%)
            if ($preProject->status == 'active') {
                if ($preProject->current_phase == 'quotation') $progressPercentage = 10;
                else if ($preProject->current_phase == 'site_visit') $progressPercentage = 20;
                else if ($preProject->current_phase == 'agreement') $progressPercentage = 30;
            }
            
            // Main project phases (40-100%) 
            if ($project) {
                if ($project->current_phase == 'document_submission') $progressPercentage = 40;
                else if ($project->current_phase == 'first_payment') $progressPercentage = 50;
                else if ($project->current_phase == 'installation') $progressPercentage = 60;
                else if ($project->current_phase == 'final_payment') $progressPercentage = 70;
                else if ($project->current_phase == 'engineer_approval') $progressPercentage = 80;
                else if ($project->current_phase == 'grid_connection') $progressPercentage = 90;
                else if ($project->current_phase == 'completed') $progressPercentage = 100;
            }

            return [
                'pre_project' => $preProject,
                'project' => $project,
                'site_visit' => $siteVisit,
                'agreement' => $agreement,
                'first_payment' => $firstPayment,
                'installation' => $installation,
                'final_payment' => $finalPayment,
                'engineer_approval' => $engineerApproval,
                'grid_connection' => $gridConnection,
                'progress_percentage' => $progressPercentage,
                'is_pre_project_phase' => ($preProject->status == 'active' && (!$project || $project->status != 'active')),
                'is_project_phase' => ($project && $project->status == 'active')
            ];
        } catch (Exception $e) {
            error_log("Error in getProjectProgress: " . $e->getMessage());
            return false;
        }
        }
        
        // Accept a quotation
        public function acceptQuotation($quotationId) {
        try {
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
            $this->db->query('SELECT pre_project_id, user_id, package_id FROM customerquotation 
            WHERE quotation_id = :quotation_id');
            $this->db->bind(':quotation_id', $quotationId);
            $quotation = $this->db->single();

            if (!$quotation) {
            return false;
            }

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
            $this->db->execute();
            
            return true;
        } catch (Exception $e) {
            error_log("Error in acceptQuotation: " . $e->getMessage());
            return false;
        }
        }
        
        // Reject a quotation
        public function rejectQuotation($quotationId) {
        try {
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
            $this->db->execute();
            
            return true;
        } catch (Exception $e) {
            error_log("Error in rejectQuotation: " . $e->getMessage());
            return false;
        }
        }
        
        // Get site visit information
        public function getSiteVisit($preProjectId) {
        $this->db->query('SELECT sv.*, pp.customer_id 
            FROM site_visits sv
            JOIN pre_projects pp ON sv.pre_project_id = pp.pre_project_id
            WHERE sv.pre_project_id = :pre_project_id
            ORDER BY sv.created_at DESC
            LIMIT 1');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
        }
        
        // Get pending agreement
        public function getPendingAgreement($preProjectId) {
        $this->db->query('SELECT pa.*, pp.customer_id, pp.current_phase, pp.status as project_status,
                  cs.signature_image as coordinator_signature 
                  FROM project_agreements pa 
                  JOIN pre_projects pp ON pa.pre_project_id = pp.pre_project_id 
                  LEFT JOIN coordinator_signatures cs ON pa.coordinator_signature_id = cs.signature_id 
                  WHERE pa.pre_project_id = :pre_project_id 
                  AND pa.status = "pending" 
                  ORDER BY pa.created_at DESC 
                  LIMIT 1');
        
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
        }
        
        // Get equipment for an agreement
        public function getAgreementEquipment($agreementId) {
        $this->db->query('SELECT ae.*, i.name as item_name 
                 FROM agreement_equipment ae 
                 JOIN inventory i ON ae.inventory_id = i.id 
                 WHERE ae.agreement_id = :agreement_id');
        
        $this->db->bind(':agreement_id', $agreementId);
        return $this->db->resultSet();
        }
}
?>