<?php
class M_Category
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getCategories()
    {
        $query = "SELECT * FROM categories";
        $this->db->query($query);
        return $this->db->resultSet();
    }
}
