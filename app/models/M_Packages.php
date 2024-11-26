<?php
class M_Packages {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllPackages() {
        $this->db->query('SELECT * FROM Package');
        return $this->db->resultSet();
    }

    public function createPackage($data) {
        $this->db->query('INSERT INTO Package 
            (title, type, description, price, service_charge, warranty_years, image_path) 
            VALUES (:title, :type, :description, :price, :service_charge, :warranty_years, :image_path)');
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':service_charge', $data['service_charge']);
        $this->db->bind(':warranty_years', $data['warranty_years']);
        $this->db->bind(':image_path', $data['image_path'] ?? null);

        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function updatePackage($packageId, $data) {
        $this->db->query('UPDATE Package 
            SET title = :title, type = :type, description = :description, 
            price = :price, service_charge = :service_charge, 
            warranty_years = :warranty_years 
            WHERE package_id = :package_id');
        
        $this->db->bind(':package_id', $packageId);
        // Bind other parameters similarly
    }

    public function addFeature($packageId, $feature) {
        $this->db->query('INSERT INTO PackageFeature (package_id, feature_name) VALUES (:package_id, :feature)');
        $this->db->bind(':package_id', $packageId);
        $this->db->bind(':feature', $feature);
        $this->db->execute();
    }

    public function addEquipment($packageId, $itemId, $quantity) {
        $this->db->query('INSERT INTO PackageEquipment (package_id, item_id, quantity) VALUES (:package_id, :item_id, :quantity)');
        $this->db->bind(':package_id', $packageId);
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':quantity', $quantity);
        $this->db->execute();
    }
}