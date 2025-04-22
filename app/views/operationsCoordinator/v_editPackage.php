<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/editPackage.css">
</head>
<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture"
            />
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages" class="active">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>back</h3>
            </a>

            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">

            <div class="edit-package-container">
            <?php flash('package_message'); ?>

                <form action="<?php echo URLROOT; ?>/operationsCoordinator/editPackage/<?php echo $data['package_id']; ?>" 
                    method="POST" class="edit-form" enctype="multipart/form-data">
                    
                    <div class="form-section">
                        <h3>Package Details</h3>
                        <div class="form-group">
                            <label for="title">Package Title</label>
                            <input type="text" name="title" id="title" class="form-control" 
                                value="<?php echo $data['title']; ?>" required>
                            <span class="error"><?php echo $data['errors']['title'] ?? ''; ?></span>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" 
                                    rows="4"><?php echo $data['description']; ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="warranty_years">Warranty (Years)</label>
                                <input type="number" name="warranty_years" id="warranty_years" 
                                    class="form-control" value="<?php echo $data['warranty_years']; ?>" min="0" max="25" required>
                            </div>

                            <div class="form-group">
                                <label for="type">Package Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="on-grid" <?php echo ($data['type'] === 'on-grid') ? 'selected' : ''; ?>>On Grid</option>
                                    <option value="off-grid" <?php echo ($data['type'] === 'off-grid') ? 'selected' : ''; ?>>Off Grid</option>
                                    <option value="hybrid" <?php echo ($data['type'] === 'hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="service_charge">Service Charge</label>
                                <input type="number" name="service_charge" id="service_charge" 
                                    class="form-control" value="<?php echo $data['service_charge']; ?>" 
                                    min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Equipment Items</h3>
                        <div id="equipment-container">
                            <?php foreach($data['equipment'] as $equipment): ?>
                                <div class="equipment-row">
                                    <div class="form-group">
                                        <label>Select Item</label>
                                        <select name="item_id[]" class="form-control" required>
                                            <option value="">Choose equipment</option>
                                            <?php foreach($data['inventory_items'] as $item): ?>
                                                <?php 
                                                $eqItemId = is_object($equipment) ? $equipment->item_id : $equipment['item_id'];
                                                ?>
                                                <option value="<?php echo $item->item_id; ?>" 
                                                        data-price="<?php echo $item->price; ?>"
                                                        <?php echo ($item->item_id == $eqItemId) ? 'selected' : ''; ?>>
                                                    <?php echo $item->product_name; ?> (Stock: <?php echo $item->quantity; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity[]" class="form-control" 
                                            value="<?php echo $equipment->quantity; ?>" min="1" required>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeEquipment(this)">×</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn-add" onclick="addEquipment()">Add Equipment</button>
                    </div>

                    <div class="form-section">
                        <h3>Package Features</h3>
                        <div id="features-container">
                            <?php if(!empty($data['features'])): ?>
                                <?php foreach($data['features'] as $feature): ?>
                                    <div class="feature-row">
                                        <div class="form-group">
                                            <label>Feature Name</label>
                                            <input type="text" name="feature_name[]" class="form-control" 
                                                value="<?php echo isset($feature->feature_name) ? $feature->feature_name : ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" name="feature_description[]" class="form-control" 
                                                value="<?php echo isset($feature->description) ? $feature->description : ''; ?>" required>
                                        </div>
                                        <button type="button" class="btn-remove" onclick="removeFeature(this)">×</button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="feature-row">
                                    <div class="form-group">
                                        <label>Feature Name</label>
                                        <input type="text" name="feature_name[]" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" name="feature_description[]" class="form-control" required>
                                    </div>
                                    <button type="button" class="btn-remove" onclick="removeFeature(this)">×</button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn-add" onclick="addFeature()">Add Feature</button>
                    </div>

                    <div class="form-actions">
                        <a href="<?php echo URLROOT; ?>/operationsCoordinator/managePackages" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Update Package</button>
                    </div>
                </form>
        </div>    

    </div>
       
        
    
    </div>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/editPackage.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>