<?php
class M_Feedback{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    public function saveFeedback($data){
        $this->db->query('Insert Into feedback(
                   name, 
            email, 
            phone, 
            feedback_type, 
            subject, 
            message, 
            rating, 
            contact_consent, 
            ip_address,
            user_agent,
            created_at
        ) VALUES (
            :name, 
            :email, 
            :phone, 
            :feedback_type, 
            :subject, 
            :message, 
            :rating, 
            :contact_consent, 
            :ip_address,
            :user_agent,
            :created_at
        )');

        $this->db->bind(":name", $data["name"]);
        $this->db->bind(":email", $data["email"]);
        $this->db->bind(":phone", $data["phone"]);
        $this->db->bind(":feedback_type", $data["feedback_type"]);
        $this->db->bind(":subject", $data["subject"]);
        $this->db->bind(":message", $data["message"]);
        $this->db->bind(":rating", $data["rating"]);
        $this->db->bind(":contact_consent", $data["contact_consent"]);
        $this->db->bind(":ip_address", $_SERVER['REMOTE_ADDR']);
        $this->db->bind(":user_agent", $_SERVER['HTTP_USER_AGENT']);
        $this->db->bind(":created_at", date("Y-m-d H:i:s"));

        if ($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function getAllFeedback($limit = null){
        $sql = 'SELECT * FROM feedback ORDER BY created_at DESC';
        
        if ($limit !== null && is_numeric($limit)) {
            $sql .= ' LIMIT :limit';
        }
        
        $this->db->query($sql);
        
        if ($limit !== null && is_numeric($limit)) {
            $this->db->bind(':limit', $limit);
        }
        
        return $this->db->resultSet();
    }

    public function getFeedbackById($id) {
        // SQL query to get a specific feedback entry
        $this->db->query('SELECT * FROM feedback WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    public function updateFeedback($id, $status, $adminNotes=null){
        $this->db->query("UPDATE feedback SET 
            status = :status, 
            admin_notes = :admin_notes, 
            updated_at = NOW() 
            WHERE id = :id");

        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        $this->db->bind(':admin_notes', $adminNotes);

        return $this->db->execute();
    }

    public function getFeedbackStats() {
        // Get statistics about feedback
        $this->db->query('SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN status = "new" THEN 1 END) as new_count,
            COUNT(CASE WHEN status = "in_progress" THEN 1 END) as in_progress_count,
            COUNT(CASE WHEN status = "resolved" THEN 1 END) as resolved_count,
            AVG(rating) as avg_rating
            FROM feedback');
            
        return $this->db->single();
    }

    public function getFeedbackByType($type, $limit = null) {
        // SQL query to get feedback by type
        $sql = 'SELECT * FROM feedback WHERE feedback_type = :type ORDER BY created_at DESC';
        
        if ($limit !== null && is_numeric($limit)) {
            $sql .= ' LIMIT :limit';
        }
        
        $this->db->query($sql);
        $this->db->bind(':type', $type);
        
        if ($limit !== null && is_numeric($limit)) {
            $this->db->bind(':limit', $limit);
        }
        
        return $this->db->resultSet();
    }

}

// CREATE TABLE `feedback` (
//     `id` int NOT NULL AUTO_INCREMENT,
//     `name` varchar(100) NOT NULL,
//     `email` varchar(100) NOT NULL,
//     `phone` varchar(15) DEFAULT NULL,
//     `feedback_type` enum('general','suggestion','complaint','compliment','inquiry') NOT NULL,
//     `subject` varchar(255) NOT NULL,
//     `message` text NOT NULL,
//     `rating` int DEFAULT NULL,
//     `contact_consent` tinyint NOT NULL DEFAULT 0,
//     `status` enum('new','in_progress','resolved','closed') NOT NULL DEFAULT 'new',
//     `admin_notes` text DEFAULT NULL,
//     `ip_address` varchar(45) DEFAULT NULL,
//     `user_agent` varchar(255) DEFAULT NULL,
//     `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
//     `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
//     PRIMARY KEY (`id`)
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4