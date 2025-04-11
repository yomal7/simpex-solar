<?php

class M_clientSideProject
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getProjectByPreProjectId($preProjectId)
    {
        $this->db->query('SELECT * FROM projects WHERE pre_project_id = :pre_project_id');
        $this->db->bind(':pre_project_id', $preProjectId);
        return $this->db->single();
    }

    public function getDocumentSubmission($projectId)
    {
        $this->db->query('SELECT * FROM documentSubmission WHERE project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        return $this->db->single();
    }

    public function submitDocument($data)
    {
        // Check if document already exists for this project
        $this->db->query('SELECT id FROM documentSubmission WHERE project_id = :project_id');
        $this->db->bind(':project_id', $data['project_id']);
        $existingDoc = $this->db->single();

        if ($existingDoc) {
            // Update existing document
            $this->db->query('UPDATE documentSubmission 
                             SET document = :document, 
                                 status = :status, 
                                 updated_at = NOW() 
                             WHERE project_id = :project_id');
        } else {
            // Create new document submission
            $this->db->query('INSERT INTO documentSubmission 
                             (project_id, document, status, created_at, updated_at) 
                             VALUES 
                             (:project_id, :document, :status, NOW(), NOW())');
        }

        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':document', $data['document']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }
}
