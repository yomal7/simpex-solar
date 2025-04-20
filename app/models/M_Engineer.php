<?php

    class M_Engineer {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }

        public function getHolidayRecords($employee_id) {
            $this->db->query('SELECT * FROM holidayrecords WHERE employee_id = :employee_id');
            $this->db->bind(':employee_id', $employee_id);

            return $this->db->resultSet();
        }

        public function addHolidayRecords($data) {
            $this->db->query('INSERT INTO holidayrecords (employee_id, start_date, end_date, number_of_days, reason, status, leave_type) 
                              VALUES (:employee_id, :start_date, :end_date, :number_of_days, :reason, :status, :leave_type)');
            $this->db->bind(':employee_id', $data['employee_id']);
            $this->db->bind(':start_date', $data['start_date']);
            $this->db->bind(':end_date', $data['end_date']);
            $this->db->bind(':number_of_days', $data['number_of_days']);
            $this->db->bind(':reason', $data['reason']);
            $this->db->bind(':status', 'pending'); // Default status is 'pending'
            $this->db->bind(':leave_type', $data['leave_type']);
    
            return $this->db->execute();
        } 

        public function getPendingSiteVisits() {
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
        
        public function getSiteVisitById($visitId) {
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
        
        public function getPackageEquipment($packageId) {
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
        
        public function getPackageFeatures($packageId) {
            $this->db->query('SELECT * FROM packagefeature
                WHERE package_id = :package_id');
                
            $this->db->bind(':package_id', $packageId);
            return $this->db->resultSet();
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
    }
?>