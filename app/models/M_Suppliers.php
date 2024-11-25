<?php
class M_Suppliers {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSuppliers() {
        $this->db->query('SELECT * FROM suppliers ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function addSupplier($data) {
        $this->db->query('INSERT INTO suppliers (name, address, email, contact_number, other_details) 
                         VALUES (:name, :address, :email, :contact_number, :other_details)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':other_details', $data['other_details']);

        // Execute
        return $this->db->execute();
    }

    public function findSupplierByEmail($email) {
        $this->db->query('SELECT * FROM suppliers WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $this->db->single();
        
        return $this->db->rowCount() > 0;
    }

    public function getSupplierById($id) {
        $this->db->query('SELECT * FROM suppliers WHERE supplier_id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
}
?>