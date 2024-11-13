<?php
    class m_pages{
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getUsers(){
            $this->db->query('SELECT * FROM users');
            return $this->db->resultSet();
        }
    }
?>