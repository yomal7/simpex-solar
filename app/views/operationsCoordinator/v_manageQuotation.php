<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/manageQuotation.css">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <input type="hidden" id="packageId" value="<?php echo $data['quotation']->package_id; ?>">
        <input type="hidden" id="initialBasePrice" value="<?php echo $data['quotation']->base_price; ?>">
        <input type="hidden" id="initialServiceCharge" value="<?php echo $data['quotation']->package_price; ?>">

        <div class="main-content">
            <div class="container">
                <div class="quotation-review">
                <?php flash('quotation_message'); ?>
                
                <div class="header-section">
                    <h2>Quotation Review 
                        <span class="status-badge <?php echo $data['quotation']->status; ?>">
                            <?php echo ucfirst($data['quotation']->status); ?>
                        </span>
                    </h2>
                    <div class="project-info">
                        <span>Project #<?php echo str_pad($data['project']->pre_project_id, 4, '0', STR_PAD_LEFT); ?></span>
                        <span>Created: <?php echo date('M d, Y', strtotime($data['quotation']->created_at)); ?></span>
                    </div>
                </div>

                <div class="review-sections">
                    <!-- Customer Section -->
                    <section class="info-section customer-section">
                        <h3>Customer Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Name:</label>
                                <span><?php echo $data['project']->customer_name; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Location:</label>
                                <span><?php echo $data['quotation']->nearest_city; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Monthly Usage:</label>
                                <span><?php echo $data['quotation']->monthly_consumption; ?> kWh</span>
                            </div>
                        </div>
                    </section>

                    <section class="info-section package-section">
                        <h3>Package Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Package Name:</label>
                                <span><?php echo $data['quotation']->package_name; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Package Type:</label>
                                <span><?php echo ucfirst($data['quotation']->package_type); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Initial Price:</label>
                                <span>Rs. <?php echo number_format($data['quotation']->package_price, 2); ?></span>
                            </div>
                        </div>
                    </section>

                    <!-- Equipment Section -->
                    <section class="info-section equipment-section">
                        <h3>System Equipment</h3>
                        
                        <!-- Package Equipment List -->
                        <div class="equipment-list" id="equipmentList">
                            <?php if (!empty($data['packageEquipment'])): ?>
                                <?php foreach ($data['packageEquipment'] as $item): ?>
                                    <div class="equipment-item" data-id="<?php echo $item->item_id; ?>">
                                        <span class="item-name"><?php echo $item->item_name; ?></span>
                                        <input type="number" 
                                            value="<?php echo $item->quantity; ?>" 
                                            min="1" 
                                            class="quantity-input"
                                            onchange="updateQuantity(this)">
                                        <span class="unit-price">Rs. <?php echo number_format($item->unit_price, 2); ?></span>
                                        <span class="total-price">Rs. <?php echo number_format($item->quantity * $item->unit_price, 2); ?></span>
                                        <button class="remove-btn" onclick="removeEquipment(this)">
                                            <span class="material-icons-sharp">delete</span>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <button class="add-equipment-btn" onclick="showInventoryModal()">
                            <span class="material-icons-sharp">add</span> Add Equipment
                        </button>
                    </section>

                    <!-- Calculations Section -->
                    <section class="info-section calculations-section">
                        <h3>System Details</h3>
                        <div class="calculations-grid">
                            <div class="calc-item">
                                <label for="systemCapacity">System Capacity (kW):</label>
                                <input type="number" id="systemCapacity" name="system_capacity" step="0.01" required>
                            </div>
                            <div class="calc-item">
                                <label for="estimatedGeneration">Est. Generation (kWh/year):</label>
                                <input type="number" id="estimatedGeneration" name="estimated_generation" required>
                            </div>
                            <div class="calc-item">
                                <label>Base Price:</label>
                                <span id="basePrice">Rs. 0.00</span>
                            </div>
                            <div class="calc-item">
                                <label for="serviceCharge">Service Charge:</label>
                                <input type="number" id="serviceCharge" name="service_charge" onchange="updateTotalPrice()">
                            </div>
                            <div class="calc-item total">
                                <label>Total Price:</label>
                                <span id="totalPrice">Rs. 0.00</span>
                            </div>
                        </div>
                    </section>

                    <!-- Notes Section -->
                    <section class="info-section notes-section">
                        <h3>Review Notes</h3>
                        <textarea id="reviewNotes" name="notes" rows="4" placeholder="Add any important notes about the quotation..."></textarea>
                    </section>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="save-draft-btn" onclick="saveQuotation('draft')">Save Draft</button>
                        <button class="submit-btn" onclick="saveQuotation('submit')">Submit for Review</button>
                    </div>
                </div>

                <div id="inventoryModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Add Equipment</h2>
                        <div class="inventory-list">
                            <!-- Populated via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>   

    </div>

    <div class="overlay" id="overlay"></div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
        const QUOTATION_ID = '<?php echo $data['quotation']->quotation_id; ?>';
    </script>

    <!-- <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script> -->
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/manageQuotation.js"></script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>