<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/manageQuotation.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar toggle button -->
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
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
                <h3>Back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

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
                                    <span><?php echo $data['quotation']->customizations; ?></span>
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
                                    <?php 
                                    $equipmentTotal = 0;
                                    foreach ($data['packageEquipment'] as $item): 
                                        $itemTotal = $item->quantity * $item->unit_price;
                                        $equipmentTotal += $itemTotal;
                                    ?>
                                        <div class="equipment-item" data-id="<?php echo $item->item_id; ?>">
                                            <span class="item-name"><?php echo $item->item_name; ?></span>
                                            <input type="hidden" name="equipment[<?php echo $item->item_id; ?>][id]" value="<?php echo $item->item_id; ?>">
                                            <input type="hidden" name="equipment[<?php echo $item->item_id; ?>][quantity]" value="<?php echo $item->quantity; ?>">
                                            <input type="hidden" name="equipment[<?php echo $item->item_id; ?>][unit_price]" value="<?php echo $item->unit_price; ?>">
                                            <span class="quantity"><?php echo number_format($item->quantity); ?> Items</span>
                                            <span class="unit-price">Rs. <?php echo number_format($item->unit_price, 2); ?></span>
                                            <span class="total-price">Rs. <?php echo number_format($itemTotal, 2); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </section>

                        <!-- Calculations Section -->
                        <section class="info-section calculations-section">
                            <h3>System Details</h3>
                            <form id="quotationForm" method="post">
                                <input type="hidden" name="quotation_id" value="<?php echo $data['quotation']->quotation_id; ?>">
                                <input type="hidden" name="base_price" id="basePrice" value="<?php echo $equipmentTotal ?? $data['quotation']->base_price; ?>">
                                
                                <div class="calculations-grid">
                                    <div class="calc-item">
                                        <label for="systemCapacity">System Capacity (kW):</label>
                                        <input type="number" 
                                            id="systemCapacity" 
                                            name="system_capacity" 
                                            step="0.01" 
                                            value="<?php echo $data['quotation']->system_capacity ?? '5.0'; ?>"
                                            required
                                            <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>>
                                    </div>
                                    <div class="calc-item">
                                        <label for="estimatedGeneration">Est. Generation (kWh/year):</label>
                                        <input type="number" 
                                            id="estimatedGeneration" 
                                            name="estimated_generation"
                                            value="<?php echo $data['quotation']->estimated_generation ?? '7300'; ?>"
                                            required
                                            <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>>
                                    </div>
                                    <div class="calc-item">
                                        <label>Equipment Price:</label>
                                        <span id="equipmentPriceDisplay">Rs. <?php echo number_format($equipmentTotal ?? $data['quotation']->base_price, 2); ?></span>
                                    </div>
                                    <div class="calc-item">
                                        <label for="serviceCharge">Service Charge (Rs.):</label>
                                        <input type="number" 
                                            id="serviceCharge" 
                                            name="service_charge" 
                                            value="<?php echo $data['quotation']->service_charge ?? '10000'; ?>" 
                                            onchange="updateTotalPrice()"
                                            <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>>
                                    </div>
                                    <div class="calc-item total">
                                        <label>Total Price:</label>
                                        <span id="totalPrice">Rs. <?php echo number_format(($equipmentTotal ?? $data['quotation']->base_price) + ($data['quotation']->service_charge ?? 10000), 2); ?></span>
                                    </div>
                                </div>
                            
                                <!-- Notes Section -->
                                <div class="notes-section">
                                    <h3>Review Notes</h3>
                                    <textarea id="reviewNotes" 
                                        name="notes" 
                                        rows="4" 
                                        placeholder="Add any important notes about the quotation..."
                                        <?php echo ($data['quotation']->status == 'accepted_by_customer' || $data['quotation']->status == 'reviewed') ? 'readonly' : ''; ?>><?php echo $data['quotation']->notes ?? ''; ?></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <?php if($data['quotation']->status != 'accepted_by_customer' && $data['quotation']->status != 'reviewed'): ?>
                                    <div class="action-buttons">
                                        <button type="button" class="submit-btn" onclick="saveQuotation()">Submit for Review</button>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>   
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function updateTotalPrice() {
            const basePrice = parseFloat(document.getElementById('basePrice').value) || 0;
            const serviceCharge = parseFloat(document.getElementById('serviceCharge').value) || 0;
            const total = basePrice + serviceCharge;
            
            document.getElementById('totalPrice').textContent = `Rs. ${total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;
        }

        function saveQuotation() {
            const form = document.getElementById('quotationForm');
            const formData = new FormData(form);
            
            // Add equipment data
            const equipmentItems = document.querySelectorAll('.equipment-item');
            const equipment = [];
            
            equipmentItems.forEach(item => {
                const id = item.dataset.id;
                const quantity = item.querySelector('input[name="equipment['+id+'][quantity]"]').value;
                const unitPrice = item.querySelector('input[name="equipment['+id+'][unit_price]"]').value;
                
                equipment.push({
                    inventory_id: parseInt(id),
                    quantity: parseInt(quantity),
                    unit_price: parseFloat(unitPrice),
                    total_price: parseInt(quantity) * parseFloat(unitPrice),
                    is_from_package: 1,
                    modification_type: 'modified'
                });
            });
            
            // Create data object
            const data = {
                quotation_id: formData.get('quotation_id'),
                equipment: equipment,
                system_capacity: formData.get('system_capacity'),
                estimated_generation: formData.get('estimated_generation'),
                base_price: parseFloat(document.getElementById('basePrice').value),
                service_charge: parseFloat(document.getElementById('serviceCharge').value),
                total_price: parseFloat(document.getElementById('basePrice').value) + parseFloat(document.getElementById('serviceCharge').value),
                notes: document.getElementById('reviewNotes').value,
                status: 'reviewed'
            };
            
            // Send to server
            fetch(`${URLROOT}/operationsCoordinator/saveReviewedQuotation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showFlashMessage('Quotation saved successfully', 'success');
                    window.location.href = `${URLROOT}/operationsCoordinator/preProjects`;
                } else {
                    showFlashMessage(result.message || 'Error saving quotation', 'error');
                }
            })
            .catch(error => {
                console.error('Save error:', error);
                showFlashMessage('Error saving quotation', 'error');
            });
        }

        function showFlashMessage(message, type) {
            const flash = document.createElement('div');
            flash.className = `flash-message ${type}`;
            flash.textContent = message;
            document.querySelector('.quotation-review').prepend(flash);
            setTimeout(() => flash.remove(), 3000);
        }
    </script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>