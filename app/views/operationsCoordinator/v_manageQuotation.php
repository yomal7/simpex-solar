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
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Operations Coordinator</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePreProject/<?php echo $data['project']->pre_project_id; ?>" class="active">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <input type="hidden" id="packageId" value="<?php echo $data['quotation']->package_id; ?>">
        <input type="hidden" id="initialBasePrice" value="<?php echo $data['quotation']->base_price; ?>">
        <input type="hidden" id="initialServiceCharge" value="<?php echo $data['quotation']->package_price; ?>">
        <?php error_log("Base Price: " . $data['quotation']->base_price); ?>

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
                            <div class="info-item">
                                <label>Customer Notes:</label>
                                <span><?php echo $data['quotation']->customizations; ?> kWh</span>
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
                                        <input type="hidden" 
                                            value="<?php echo $item->quantity; ?>" 
                                            min="1" 
                                            class="quantity-input"
                                            onchange="updateQuantity(this)">
                                        <span class="quantity"> <?php echo number_format($item->quantity); ?> Items </span>
                                        <span class="unit-price">Rs. <?php echo number_format($item->unit_price, 2); ?></span>
                                        <span class="total-price">Rs. <?php echo number_format($item->quantity * $item->unit_price, 2); ?></span>
                                        <!-- <button class="remove-btn" onclick="removeEquipment(this)">
                                            <span class="material-icons-sharp">delete</span>
                                        </button> -->
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        

                    </section>

                    <!-- Calculations Section -->
                    <section class="info-section calculations-section">
                        <h3>System Details</h3>
                        <div class="calculations-grid">
                            <div class="calc-item">
                                <label for="systemCapacity">System Capacity (kW):</label>
                                <input type="number" 
                                    id="systemCapacity" 
                                    name="system_capacity" 
                                    step="0.01" 
                                    required
                                    <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>>
                            </div>
                            <div class="calc-item">
                                <label for="estimatedGeneration">Est. Generation (kWh/year):</label>
                                <input type="number" 
                                    id="estimatedGeneration" 
                                    name="estimated_generation" 
                                    required
                                    <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>>
                            </div>
                            <div class="calc-item">
                                <label>Base Price:</label>
                                <span id="basePrice">Rs. <?php echo number_format($data['quotation']->base_price, 2); ?></span>
                            </div>
                            <div class="calc-item">
                                <label for="serviceCharge">Service Charge (Rs.):</label>
                                <input type="text" 
                                    id="serviceCharge" 
                                    name="service_charge" 
                                    value="<?php echo number_format($data['quotation']->service_charge, 2); ?>" 
                                    onchange="updateTotalPrice()"
                                    <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>>
                            </div>
                            <div class="calc-item total">
                                <label>Total Price:</label>
                                <span id="totalPrice">Rs. <?php echo number_format($data['quotation']->base_price, 2); ?></span>
                            </div>
                        </div>
                    </section>

                    <!-- Notes Section -->
                    <section class="info-section notes-section">
                        <h3>Review Notes</h3>
                        <textarea id="reviewNotes" 
                            name="notes" 
                            rows="4" 
                            placeholder="Add any important notes about the quotation..."
                            <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>></textarea>
                    </section>

                    <!-- Action Buttons -->
                    <?php if($data['quotation']->status != 'accepted_by_customer' && $data['quotation']->status != 'reviewed'): ?>
                        <div class="action-buttons">
                            <button class="submit-btn" onclick="saveQuotation('submit')">Submit for Review</button>
                        </div>
                    <?php endif; ?>
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