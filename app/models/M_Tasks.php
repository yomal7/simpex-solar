<?php
    class M_Tasks{
        private $db;
        public function __construct(){
            $this->db= new Database();           
        }

        // public function getPosts(){
        //     $this->db->query('SELECT * FROM v_posts');
        //     $results= $this->db->resultSet();
        //     return $results;
        // }

        // public function getPostById($postId){
        //     $this->db->query('SELECT * FROM v_posts WHERE post_id = :id');
        //     $this->db->bind(':id', $postId);
        //     $row= $this->db->single();
        //     return $row;
        // }

        public function create($data)
        {
            $this->db->query('INSERT INTO Tasks (title, start_time, end_time, description) VALUES(:title, :start_time, :end_time, :description)');
            //$this->db->bind(':user_id', $_SESSION['user_id']);
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':start_time', $data['start_time']);
            $this->db->bind(':end_time', $data['end_time']);
            $this->db->bind(':description', $data['description']);
            
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

        // public function delete($postId) {
        //     $this->db->query('DELETE FROM posts WHERE id = :id');
        //     $this->db->bind(':id', $postId);
            
        //     if ($this->db->execute()) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }

    }
?>