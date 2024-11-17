<?php

    // Models/M_Clients.php
    class M_Clients {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }

        public function getClientByUserId($userId) {
            $this->db->query('SELECT * FROM clients WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);

            return $this->db->single();
        }

        public function getRecentProjects($clientId) {
            $this->db->query('SELECT * FROM projects WHERE client_id = :client_id ORDER BY created_at DESC LIMIT 5');
            $this->db->bind(':client_id', $clientId);

            return $this->db->resultSet();
        }

        public function getNotifications($clientId) {
            $this->db->query('SELECT * FROM notifications WHERE client_id = :client_id AND is_read = 0 ORDER BY created_at DESC');
            $this->db->bind(':client_id', $clientId);

            return $this->db->resultSet();
        }

        public function getProjectProgress($clientId) {
            $this->db->query('
                SELECT 
                    p.id,
                    p.name,
                    p.total_milestones,
                    COUNT(CASE WHEN m.status = "completed" THEN 1 END) as completed_milestones
                FROM projects p
                LEFT JOIN milestones m ON p.id = m.project_id
                WHERE p.client_id = :client_id AND p.status = "active"
                GROUP BY p.id
            ');
            $this->db->bind(':client_id', $clientId);

            return $this->db->resultSet();
        }

        public function updateProfile($data) {
            $this->db->query('UPDATE clients SET 
                company_name = :company_name,
                address = :address,
                phone = :phone,
                contact_person = :contact_person
                WHERE id = :id');

            $this->db->bind(':company_name', $data['company_name']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':phone', $data['phone']);
            $this->db->bind(':contact_person', $data['contact_person']);
            $this->db->bind(':id', $data['client_id']);

            return $this->db->execute();
        }

        public function getAllProjects($clientId) {
            $this->db->query('SELECT * FROM projects WHERE client_id = :client_id ORDER BY created_at DESC');
            $this->db->bind(':client_id', $clientId);

            return $this->db->resultSet();
        }

        public function getProjectById($projectId) {
            $this->db->query('SELECT * FROM projects WHERE id = :id');
            $this->db->bind(':id', $projectId);

            return $this->db->single();
        }

        public function getProjectMilestones($projectId) {
            $this->db->query('SELECT * FROM milestones WHERE project_id = :project_id ORDER BY due_date');
            $this->db->bind(':project_id', $projectId);

            return $this->db->resultSet();
        }

        public function getProjectDocuments($projectId) {
            $this->db->query('SELECT * FROM documents WHERE project_id = :project_id ORDER BY uploaded_at DESC');
            $this->db->bind(':project_id', $projectId);

            return $this->db->resultSet();
        }

        public function getInvoices($clientId) {
            $this->db->query('SELECT * FROM invoices WHERE client_id = :client_id ORDER BY created_at DESC');
            $this->db->bind(':client_id', $clientId);

            return $this->db->resultSet();
        }

        public function createSupportTicket($data) {
            $this->db->query('INSERT INTO support_tickets (client_id, subject, message, priority) 
                VALUES(:client_id, :subject, :message, :priority)');

            $this->db->bind(':client_id', $data['client_id']);
            $this->db->bind(':subject', $data['subject']);
            $this->db->bind(':message', $data['message']);
            $this->db->bind(':priority', $data['priority']);

            return $this->db->execute();
        }

        public function getSupportTickets($clientId) {
            $this->db->query('SELECT * FROM support_tickets WHERE client_id = :client_id ORDER BY created_at DESC');
            $this->db->bind(':client_id', $clientId);

            return $this->db->resultSet();
        }

        public function getMessages($clientId) {
            $this->db->query('SELECT * FROM messages WHERE client_id = :client_id ORDER BY sent_at DESC');
            $this->db->bind(':client_id', $clientId);

            return $this->db->resultSet();
        }
}
?>