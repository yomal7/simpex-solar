<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/agreement.css">
</head>

<body>
    <?php flash('site_visit_message'); ?>   
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="agreement-form">
                    <?php flash('agreement_message'); ?>
                    
                    <div class="header-section">
                        <h2>Project Agreement</h2>
                        <div class="project-info">
                            <span>Project #<?php echo str_pad($data['project']->pre_project_id, 4, '0', STR_PAD_LEFT); ?></span>
                            <span>Created: <?php echo date('M d, Y'); ?></span>
                        </div>
                    </div>

                    <form id="agreementForm" action="<?php echo URLROOT; ?>/operationsCoordinator/saveAgreement" method="POST" enctype="multipart/form-data">
                        <!-- Hidden inputs -->
                        <input type="hidden" name="review_id" value="<?php echo isset($data['quotation']->review_id) ? $data['quotation']->review_id : ''; ?>">
                        <input type="hidden" name="quotation_id" value="<?php echo isset($data['quotation']->quotation_id) ? $data['quotation']->quotation_id : ''; ?>">
                        <input type="hidden" name="pre_project_id" value="<?php echo $data['project']->pre_project_id; ?>">
                        
                        <div class="form-sections">
                            <!-- Customer Information -->
                            <section class="info-section">
                                <h3>Customer Information</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label>Name:</label>
                                        <span><?php echo isset($data['project']->customer_name) ? $data['project']->customer_name : ''; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>Location:</label>
                                        <span><?php echo isset($data['quotation']->nearest_city) ? $data['quotation']->nearest_city : ''; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>Contact:</label>
                                        <span><?php echo $data['project']->phone; ?></span>
                                    </div>
                                </div>
                            </section>

                            <!-- System Details -->
                            <section class="info-section">
                                <h3>System Details</h3>
                                <div class="detail-item">
                                    <label for="system_capacity">System Capacity (kW):</label>
                                    <input type="number" id="system_capacity" name="system_capacity" 
                                        value="<?php echo isset($data['quotation']->system_capacity) ? $data['quotation']->system_capacity : '0'; ?>" 
                                        step="0.01" required>
                                </div>
                                <div class="detail-item">
                                    <label for="estimated_generation">Est. Generation (kWh/year):</label>
                                    <input type="number" id="estimated_generation" name="estimated_generation" 
                                        value="<?php echo isset($data['quotation']->estimated_generation) ? $data['quotation']->estimated_generation : '0'; ?>" 
                                        required>
                                </div>

                                <h3>Site visit findings: <h3> <span><?php echo $data['siteVisit']-> site_notes; ?></span>
                            </section>

                            <!-- Equipment List -->
                            <section class="info-section">
                                <h3>System Equipment</h3>
                                <div class="equipment-list" id="equipmentList">
                                    <?php if(!empty($data['equipment'])): ?>
                                        <?php foreach($data['equipment'] as $item): ?>
                                            <div class="equipment-item" data-id="<?php echo $item->inventory_id; ?>">
                                                <div class="item-details">
                                                    <span class="item-name"><?php echo htmlspecialchars($item->item_name); ?></span>
                                                    <div class="item-info">
                                                        <input type="number" value="<?php echo $item->quantity; ?>" 
                                                            min="1" class="quantity-input">
                                                        <span class="unit-price">Rs. <?php echo number_format($item->unit_price, 2); ?></span>
                                                        <span class="total-price">Rs. <?php echo number_format($item->quantity * $item->unit_price, 2); ?></span>
                                                    </div>
                                                </div>
                                                <button type="button" class="remove-btn">
                                                    <span class="material-icons-sharp">delete</span>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="button" class="add-equipment-btn" onclick="showInventoryModal()">
                                    <span class="material-icons-sharp">add</span> Add Equipment
                                </button>
                            </section>

                            <!-- Pricing Section -->
                            <section class="info-section">
                                <h3>Pricing Details</h3>
                                <div class="price-item">
                                    <label>Base Price:</label>
                                    <span id="basePrice">Rs. <?php echo number_format($data['quotation']->base_price ?? 0, 2); ?></span>
                                    <input type="hidden" name="base_price" value="<?php echo $data['quotation']->base_price ?? 0; ?>">
                                </div>
                                <div class="price-item">
                                    <label for="service_charge">Service Charge:</label>
                                    <input type="number" id="service_charge" name="service_charge" 
                                        value="<?php echo $data['quotation']->service_charge ?? 0; ?>" 
                                        onchange="updateTotalPrice()">
                                </div>
                                <div class="price-item total">
                                    <label>Total Price:</label>
                                    <span id="totalPrice">Rs. <?php echo number_format($data['quotation']->total_price ?? 0, 2); ?></span>
                                    <input type="hidden" name="total_price" value="<?php echo $data['quotation']->total_price ?? 0; ?>">
                                </div>
                            </section>

                            <!-- Signature Section -->
                            <section class="info-section signature-section">
                                <h3>Coordinator Signature</h3>
                                <?php if(isset($data['coordinator_signature'])): ?>
                                    <div class="existing-signature">
                                        <img src="<?php echo URLROOT . '/public/uploads/signatures/' . $data['coordinator_signature']->signature_image; ?>" 
                                             alt="Existing Signature">
                                        <label for="use_existing_signature">
                                            <input type="checkbox" name="use_existing_signature" checked> 
                                            Use existing signature
                                        </label>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="signature-upload" <?php echo isset($data['coordinator_signature']) ? 'style="display:none;"' : ''; ?>>
                                    <div class="upload-area" onclick="document.getElementById('signature_file').click()">
                                        <span class="material-icons-sharp">upload_file</span>
                                        <p>Click to upload signature</p>
                                    </div>
                                    <input type="file" id="signature_file" name="coordinator_signature" accept="image/*" hidden>
                                </div>
                            </section>

                            <!-- Notes Section -->
                            <section class="info-section">
                                <h3>Agreement Notes</h3>
                                <textarea id="notes" name="notes" rows="4" 
                                    placeholder="Add any important notes about the agreement..."><?php echo $data['quotation']->notes ?? ''; ?></textarea>
                            </section>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" class="submit-btn">
                                <span class="material-icons-sharp">done</span> Generate Agreement
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Equipment Modal -->
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

            
            
            <div class="overlay" id="overlay"></div>

    </div>


    <div id="toast" class="toast"></div>
    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
        const AGREEMENT_ID = '<?php echo $data['agreement']->agreement_id ?? ""; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/agreement.js"></script>

    
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>