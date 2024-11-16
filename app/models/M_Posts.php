<?php
    class M_Posts{
        private $db;
        public function __construct(){
            $this->db= new Database();           
        }

        public function getPosts(){
            $this->db->query('SELECT * FROM v_posts');
            $results= $this->db->resultSet();
            return $results;
        }

        public function create($data)
        {
            $this->db->query('INSERT INTO posts (title, body, user_id) VALUES(:title, :body, :user_id)');
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':body', $data['body']);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }
?>