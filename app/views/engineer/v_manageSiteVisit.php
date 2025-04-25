<?php require APPROOT . '/views/engineer/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/manageSiteVisit.css">

</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Engineer</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/engineer/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/siteVisits" class="active">
                <span class="material-icons-sharp">location_on</span>
                <h3>Site Visits</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="page-header">
                    <h1>Manage Site Visit</h1>
                    <a href="<?php echo URLROOT; ?>/engineer/siteVisits" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Site Visits
                    </a>
                    <?php flash('site_visit_message'); ?>
                </div>

                <div class="site-visit-details">
                    <div class="card customer-info">
                        <div class="card-header">
                            <h2><i class="fas fa-user"></i> Customer Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <label>Name:</label>
                                <span><?php echo $data['site_visit']->customer_name; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Phone:</label>
                                <span><?php echo $data['site_visit']->phone; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Address:</label>
                                <span><?php echo $data['site_visit']->address; ?></span>
                            </div>
                            <div class="info-item">
                                <label>City:</label>
                                <span><?php echo $data['site_visit']->nearest_city; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="card visit-info">
                        <div class="card-header">
                            <h2><i class="fas fa-calendar-check"></i> Visit Details</h2>
                            <span class="status-badge <?php echo $data['site_visit']->status; ?>">
                                <?php echo ucfirst($data['site_visit']->status); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <label>Visit Date:</label>
                                <span><?php echo date('F j, Y', strtotime($data['site_visit']->visit_date)); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Visit Time:</label>
                                <span><?php echo date('h:i A', strtotime($data['site_visit']->visit_time)); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Package Information Card -->
                    <?php if(!empty($data['site_visit']->package_id)): ?>
                    <div class="card package-info">
                        <div class="card-header">
                            <h2><i class="fas fa-box"></i> Package Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="package-header">
                                <h3><?php echo $data['site_visit']->package_name; ?></h3>
                                <span class="package-type"><?php echo ucfirst(str_replace('-', ' ', $data['site_visit']->package_type)); ?></span>
                            </div>
                            
                            <div class="package-details">
                                <div class="detail-item">
                                    <label>Price:</label>
                                    <span>Rs. <?php echo number_format($data['site_visit']->final_price, 2); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label>Base Price:</label>
                                    <span>Rs. <?php echo number_format($data['site_visit']->package_base_price, 2); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label>Service Charge:</label>
                                    <span>Rs. <?php echo number_format($data['site_visit']->service_charge, 2); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label>Warranty:</label>
                                    <span><?php echo $data['site_visit']->warranty_years; ?> Years</span>
                                </div>
                            </div>
                            
                            <div class="package-description">
                                <h4>Description</h4>
                                <p><?php echo $data['site_visit']->package_description; ?></p>
                            </div>
                            
                            <!-- Package Features -->
                            <?php if(!empty($data['package_features'])): ?>
                            <div class="package-features">
                                <h4>Features</h4>
                                <ul>
                                    <?php foreach($data['package_features'] as $feature): ?>
                                    <li>
                                        <strong><?php echo $feature->feature_name; ?>:</strong>
                                        <?php echo $feature->description; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Package Equipment Card -->
                    <div class="card package-equipment">
                        <div class="card-header">
                            <h2><i class="fas fa-tools"></i> Package Equipment</h2>
                        </div>
                        <div class="card-body">
                            <?php if(empty($data['package_equipment'])): ?>
                                <p class="no-equipment">No equipment information available.</p>
                            <?php else: ?>
                                <table class="equipment-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['package_equipment'] as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="item-info">
                                                    <span class="item-name"><?php echo $item->item_name; ?></span>
                                                    <?php if(!empty($item->item_description)): ?>
                                                    <span class="item-description"><?php echo $item->item_description; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="text-center"><?php echo $item->quantity; ?></td>
                                            <td class="text-right">Rs. <?php echo number_format($item->item_price, 2); ?></td>
                                            <td class="text-right">Rs. <?php echo number_format($item->quantity * $item->item_price, 2); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="card completion-form">
                        <div class="card-header">
                            <h2><i class="fas fa-clipboard-list"></i> Site Visit Completion</h2>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo URLROOT; ?>/engineer/completeSiteVisit" method="POST">
                                <input type="hidden" name="pre_project_id" value="<?php echo $data['site_visit']->pre_project_id; ?>">
                                <input type="hidden" name="visit_id" value="<?php echo $data['site_visit']->visit_id; ?>">
                                
                                <div class="form-group">
                                    <label for="site_notes">Site Visit Notes</label>
                                    <textarea name="site_notes" id="site_notes" rows="6" required 
                                        placeholder="Enter detailed notes about the site visit including measurements, observations, and recommendations..."></textarea>
                                </div>
                                
                                <div class="form-group checklist">
                                    <label>Site Visit Checklist</label>
                                    <div class="checklist-items">
                                        <div class="checklist-item">
                                            <input type="checkbox" id="check1" required>
                                            <label for="check1">I've verified the roof/location is suitable for installation</label>
                                        </div>
                                        <div class="checklist-item">
                                            <input type="checkbox" id="check2" required>
                                            <label for="check2">I've taken all required measurements</label>
                                        </div>
                                        <div class="checklist-item">
                                            <input type="checkbox" id="check3" required>
                                            <label for="check3">I've checked electrical panel and wiring requirements</label>
                                        </div>
                                        <div class="checklist-item">
                                            <input type="checkbox" id="check4" required>
                                            <label for="check4">I've discussed installation details with the customer</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn-success">
                                        <i class="fas fa-check-circle"></i> Complete Site Visit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="<?php echo URLROOT; ?>/js/engineer/manageSiteVisit.js"></script>
<?php require APPROOT . '/views/engineer/footer.php'; ?>