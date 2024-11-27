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
        $this->db->query('INSERT INTO package (
            title, 
            description, 
            price, 
            warranty_years, 
            type, 
            image, 
            service_charge, 
            final_price
        ) VALUES (
            :title, 
            :description, 
            :price, 
            :warranty_years, 
            :type, 
            :image, 
            :service_charge, 
            :final_price
        )');
       
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':warranty_years', $data['warranty_years']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':service_charge', $data['service_charge']);
        $this->db->bind(':final_price', $data['final_price']);
        
        if (!$this->db->execute()) {
            return false;
        }
        
        $package_id = $this->db->lastInsertId();
        
        // Add equipment
        foreach ($data['equipment'] as $item) {
            if (!$this->addEquipment($package_id, $item['item_id'], $item['quantity'])) {
                return false;
            }
        }
        
        // Add features
        foreach ($data['features'] as $feature) {
            if (!$this->addFeature($package_id, $feature['name'])) {
                return false;
            }
        }
        
        return true;
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

    public function getInventoryItems() {
        $this->db->query('SELECT id, name, price, quantity FROM inventory WHERE quantity > 0');
        return $this->db->resultSet();
    }   
    public function getPackageById($id) {
        $this->db->query('SELECT * FROM package WHERE package_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getPackageEquipment($package_id) {
        $this->db->query('SELECT pe.*, i.name as item_name, i.price as item_price 
                         FROM packageequpment pe 
                         JOIN inventory i ON pe.item_id = i.id 
                         WHERE pe.package_id = :package_id');
        $this->db->bind(':package_id', $package_id);
        return $this->db->resultSet();
    }

    public function getPackageFeatures($package_id) {
        $this->db->query('SELECT * FROM packagefeature WHERE package_id = :package_id');
        $this->db->bind(':package_id', $package_id);
        return $this->db->resultSet();
    }

    public function calculateFinalPrice($equipment, $serviceCharge) {
        $totalPrice = 0;
        
        foreach ($equipment as $item) {
            $this->db->query('SELECT price FROM inventory WHERE id = :id');
            $this->db->bind(':id', $item['item_id']);
            $result = $this->db->single();
            
            if ($result) {
                $itemPrice = $result->price;
                $totalPrice += $itemPrice * $item['quantity'];
            }
        }

        return $totalPrice;
    }
}