<?php
class M_Tasks
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // public function getPosts(){
    //     $this->db->query('SELECT * FROM v_posts');
    //     $results= $this->db->resultSet();
    //     return $results;
    // }

    public function getAllTasks(){
        $this->db->query('SELECT * FROM Tasks');
        $results= $this->db->resultSet();
        return $results;
    }

    // public function getPostById($postId){
    //     $this->db->query('SELECT * FROM v_posts WHERE post_id = :id');
    //     $this->db->bind(':id', $postId);
    //     $row= $this->db->single();
    //     return $row;
    // }

    public function getTaskByID($taskId)
    {   
        $this->db->query('SELECT * FROM Tasks WHERE id = :id');
        $this->db->bind(':id', $taskId);
        $row= $this->db->single();
        return $row;
    }

    public function create($data)
    {
        $this->db->query('INSERT INTO Tasks (title, description, start_date, end_date, project_id, employee_id, status) VALUES(:title, :description, :start_date, :end_date, :project_id, :employee_id, :status)');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':status', $data['status']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // public function edit($data)
    // {
    //     $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
    //     $this->db->bind(':title', $data['title']);
    //     $this->db->bind(':body', $data['body']);
    //     $this->db->bind(':id', $data['post_id']);

    //     if ($this->db->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function edit($data)
    {
        $this->db->query('UPDATE Tasks SET title = :title, description = :description, start_date = :start_date, end_date = :end_date, project_id = :project_id, employee_id = :employee_id, status = :status WHERE id = :id');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // public function delete($postId) {
    //     $this->db->query('DELETE FROM posts WHERE id = :id');
    //     $this->db->bind(':id', $postId);

    //     if ($this->db->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function delete($taskId) {
        $this->db->query('DELETE FROM Tasks WHERE id = :id');
        $this->db->bind(':id', $taskId);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
