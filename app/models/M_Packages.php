<?php
class M_Packages {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllPackages() {
        $this->db->query('SELECT * FROM Package WHERE deleted_at IS NULL');
        return $this->db->resultSet();
    }

    public function createSlug($title) {
        // Convert the title to lowercase and replace spaces with hyphens
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Remove multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Check if slug already exists
        $this->db->query('SELECT slug FROM package WHERE slug = :slug');
        $this->db->bind(':slug', $slug);
        
        if ($this->db->single()) {
            // If exists, append a number
            $i = 1;
            do {
                $new_slug = $slug . '-' . $i;
                $this->db->query('SELECT slug FROM package WHERE slug = :slug');
                $this->db->bind(':slug', $new_slug);
                $i++;
            } while ($this->db->single());
            $slug = $new_slug;
        }
        
        return $slug;
    }

    public function createPackage($data) {
        try {
            $data['slug'] = $this->createSlug($data['title']);
            // Calculate the total price from equipment
            $equipmentPrice = $this->calculateFinalPrice($data['equipment'], $data['service_charge']);
            $finalPrice = $equipmentPrice + floatval($data['service_charge']);
    
            $this->db->query('INSERT INTO package (
                title, 
                slug,
                description, 
                price, 
                warranty_years, 
                type, 
                image, 
                service_charge, 
                final_price
            ) VALUES (
                :title,
                :slug, 
                :description, 
                :price, 
                :warranty_years, 
                :type, 
                :image, 
                :service_charge, 
                :final_price
            )');
           
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':slug', $data['slug']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':price', $equipmentPrice);
            $this->db->bind(':warranty_years', $data['warranty_years']);
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':image', $data['image']);
            $this->db->bind(':service_charge', $data['service_charge']);
            $this->db->bind(':final_price', $finalPrice);
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to insert package");
            }
            
            $package_id = $this->db->lastInsertId();
            
            // Add equipment
            foreach ($data['equipment'] as $item) {
                if (!$this->addEquipment($package_id, $item['item_id'], $item['quantity'])) {
                    throw new Exception("Failed to add equipment");
                }
            }
            
            // Add features
            foreach ($data['features'] as $feature) {
                if (!$this->addFeature($package_id, $feature['feature_name'], $feature['description'])) {
                    throw new Exception("Failed to add feature");
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error creating package: " . $e->getMessage());
            return false;
        }
    }

    public function getPackageBySlug($slug) {
        $this->db->query('SELECT p.*, GROUP_CONCAT(pf.feature_name) as features 
                         FROM package p 
                         LEFT JOIN packagefeature pf ON p.package_id = pf.package_id 
                         WHERE p.slug = :slug AND p.deleted_at IS NULL 
                         GROUP BY p.package_id');
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }
    
    public function calculateFinalPrice($equipment, $serviceCharge) {
        $totalPrice = 0;
        
        foreach ($equipment as $item) {
            $this->db->query('SELECT price FROM inventory WHERE id = :id AND deleted_at IS NULL');
            $this->db->bind(':id', $item->item_id); // Change ['item_id'] to ->item_id
            $result = $this->db->single();

            if ($result) {
                $totalPrice += $result->price * $item->quantity; // Change ['quantity'] to ->quantity
            }
        }
    
        return $totalPrice;
    }

    public function addEquipment($packageId, $itemId, $quantity) {
        $this->db->query('INSERT INTO packageequipment (package_id, item_id, quantity) 
                          VALUES (:package_id, :item_id, :quantity)');
        $this->db->bind(':package_id', $packageId);
        $this->db->bind(':item_id', $itemId);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    public function addFeature($packageId, $featureName, $description) {
        try {
            $this->db->query('INSERT INTO packagefeature (package_id, feature_name, description) 
                              VALUES (:package_id, :feature_name, :description)');
            $this->db->bind(':package_id', $packageId);
            $this->db->bind(':feature_name', $featureName);
            $this->db->bind(':description', $description);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Error adding feature: " . $e->getMessage());
            return false;
        }
    }

    public function getInventoryItems() {
        // Update column names to match inventory table structure
        $this->db->query('SELECT id as item_id, name as product_name, price, quantity 
                          FROM inventory 
                          WHERE quantity > 0 
                          AND deleted_at IS NULL');
        return $this->db->resultSet();
    }

    public function getPackageById($id) {
        $this->db->query('SELECT * FROM package 
                          WHERE package_id = :id 
                          AND deleted_at IS NULL');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getPackageEquipment($package_id) {
        $this->db->query('SELECT pe.*, i.name as item_name, i.price as item_price 
                         FROM packageequipment pe 
                         JOIN inventory i ON pe.item_id = i.id 
                         WHERE pe.package_id = :package_id');
        $this->db->bind(':package_id', $package_id);
        return $this->db->resultSet();
    }
    public function updatePackage($id, $data) {
        try {
            // Ensure that equipment and features are always arrays of objects
            $equipmentList = is_array($data['equipment']) ? array_map(function ($eq) {
                return is_object($eq) ? $eq : (object) $eq;
            }, $data['equipment']) : [];
    
            $featureList = is_array($data['features']) ? array_map(function ($ft) {
                return is_object($ft) ? $ft : (object) $ft;
            }, $data['features']) : [];
    
            // Debugging logs
            error_log("Equipment Data: " . print_r($equipmentList, true));
            error_log("Features Data: " . print_r($featureList, true));
    
            if (!is_array($equipmentList) || !is_array($featureList)) {
                throw new Exception("Invalid data format for equipment or features.");
            }
    
            // Calculate total price
            $equipmentPrice = $this->calculateFinalPrice($equipmentList, $data['service_charge']);
            $finalPrice = $equipmentPrice + floatval($data['service_charge']);
    
            // Update package details
            $this->db->query('UPDATE package SET 
                title = :title,
                description = :description,
                price = :price,
                warranty_years = :warranty_years,
                type = :type,
                service_charge = :service_charge,
                final_price = :final_price
                WHERE package_id = :package_id');
    
            $this->db->bind(':package_id', $id);
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':price', $equipmentPrice);
            $this->db->bind(':warranty_years', $data['warranty_years']);
            $this->db->bind(':type', $data['type']);
            $this->db->bind(':service_charge', $data['service_charge']);
            $this->db->bind(':final_price', $finalPrice);
    
            if (!$this->db->execute()) {
                throw new Exception("Failed to update package");
            }
    
            // Remove existing equipment and features
            $this->db->query('DELETE FROM packageequipment WHERE package_id = :package_id');
            $this->db->bind(':package_id', $id);
            $this->db->execute();
    
            $this->db->query('DELETE FROM packagefeature WHERE package_id = :package_id');
            $this->db->bind(':package_id', $id);
            $this->db->execute();
    
            // Add updated equipment
            foreach ($equipmentList as $item) {
                if (!isset($item->item_id) || !isset($item->quantity)) {
                    throw new Exception("Invalid equipment data structure.");
                }
                if (!$this->addEquipment($id, $item->item_id, $item->quantity)) {
                    throw new Exception("Failed to add equipment");
                }
            }
    
            // Add updated features
            foreach ($featureList as $feature) {
                if (!isset($feature->name) || !isset($feature->description)) {
                    throw new Exception("Invalid feature data structure.");
                }
                if (!$this->addFeature($id, $feature->name, $feature->description)) {
                    throw new Exception("Failed to add feature");
                }
            }
    
            return true;
        } catch (Exception $e) {
            error_log("Error updating package: " . $e->getMessage());
            return false;
        }
    }
    

    public function deletePackage($id) {
        try {
            // Soft delete the package first
            $this->db->query('UPDATE package 
                             SET deleted_at = CURRENT_TIMESTAMP 
                             WHERE package_id = :id');
            $this->db->bind(':id', $id);
            
            if (!$this->db->execute()) {
                return false;
            }
    
            // Delete features
            $this->db->query('DELETE FROM packagefeature WHERE package_id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();
    
            // Delete equipment associations
            $this->db->query('DELETE FROM packageequipment WHERE package_id = :id');
            $this->db->bind(':id', $id);
            $this->db->execute();
    
            return true;
    
        } catch (Exception $e) {
            error_log("Error deleting package: " . $e->getMessage());
            return false;
        }
    }


    public function getAllPackagesWithDetails() {
        $this->db->query('
            SELECT p.*,
                0 as equipment_count,
                "" as features
            FROM package p
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC
        ');
        
        return $this->db->resultSet();
    }
    
    
    public function getPackageFeatures($package_id) {
        try {
            $this->db->query('SELECT feature_id, package_id, feature_name, description 
                              FROM packagefeature 
                              WHERE package_id = :package_id');
            $this->db->bind(':package_id', $package_id);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log("Error getting package features: " . $e->getMessage());
            return [];
        }
        
    }

    //To package showcase

    public function getPackagesByType($type) {
        $this->db->query('SELECT p.*, GROUP_CONCAT(pf.feature_name) as features 
                         FROM package p 
                         LEFT JOIN packagefeature pf ON p.package_id = pf.package_id 
                         WHERE p.type = :type AND p.deleted_at IS NULL 
                         GROUP BY p.package_id');
        $this->db->bind(':type', $type);
        
        return $this->db->resultSet();
        // error_log("Packages Retrieved for Type '$type': " . json_encode($result, JSON_PRETTY_PRINT));
    }

    public function getAllPackagesWithFeatures() {
        $this->db->query('SELECT p.*, GROUP_CONCAT(pf.feature_name) as features 
                         FROM package p 
                         LEFT JOIN packagefeature pf ON p.package_id = pf.package_id 
                         WHERE p.deleted_at IS NULL 
                         GROUP BY p.package_id');
        return $this->db->resultSet();
    }

    public function getPackageEquipmentDetails($package_id) {
        $this->db->query('SELECT pe.*, i.name as item_name, i.description as item_description, 
                          i.price as item_price, i.blog_link, i.image as item_image
                          FROM packageequipment pe 
                          JOIN inventory i ON pe.item_id = i.id 
                          WHERE pe.package_id = :package_id AND i.deleted_at IS NULL');
        $this->db->bind(':package_id', $package_id);
        return $this->db->resultSet();
    }

    
    public function submitQuotation($data) {
        // Log the incoming data
        error_log("Attempting to submit quotation with data: " . print_r($data, true));
        
        $this->db->query('INSERT INTO customerquotation (
            user_id,
            package_id,
            address,
            monthly_consumption,
            nearest_city,
            customizations,
            package_type,
            status
        ) VALUES (
            :user_id,
            :package_id,
            :address,
            :monthly_consumption,
            :nearest_city,
            :customizations,
            :package_type,
            "pending"
        )');

        // Log the SQL query for debugging
        error_log("SQL Query prepared");

        try {
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':package_id', $data['package_id']);
            $this->db->bind(':address', $data['address']);
            $this->db->bind(':monthly_consumption', $data['monthly_consumption']);
            $this->db->bind(':nearest_city', $data['nearest_city']);
            $this->db->bind(':customizations', $data['customizations']);
            $this->db->bind(':package_type', $data['package_type']);
            
            // Log before execution
            error_log("All parameters bound, attempting execution");
            
            if($this->db->execute()) {
                error_log("Query executed successfully");
                return true;
            } else {
                error_log("Query execution failed");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Error in submitQuotation: " . $e->getMessage());
            return false;
        }
    }

}