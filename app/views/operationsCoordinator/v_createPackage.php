<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/createPackage.css">
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
                    <p>HR Administrator</p>
                </div>
            </div>
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
            <div class="page-header">
                <h2>Create New Package</h2>
            </div>

            <?php flash('package_message'); ?>

            <form action="<?php echo URLROOT; ?>/operationsCoordinator/createPackage" method="POST" class="package-form" enctype="multipart/form-data">
                <div class="form-section">
                    <h3>Package Details</h3>
                    <div class="form-group">
                        <label for="title">Package Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo $data['title']; ?>" required>
                        <span class="error"><?php echo $data['errors']['title'] ?? ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4"><?php echo $data['description']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="package_image">Package Image</label>
                        <div class="image-upload-container">
                            <input type="file" name="package_image" id="package_image" class="form-control" accept="image/*" required>
                            <div id="image-preview"></div>
                        </div>
                        <span class="error"><?php echo $data['errors']['image'] ?? ''; ?></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="warranty_years">Warranty (Years)</label>
                            <input type="number" name="warranty_years" id="warranty_years" class="form-control" value="<?php echo $data['warranty_years']; ?>" min="0" max="100" required>
                        </div>

                        <div class="form-group">
                            <label for="type">Package Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="on-grid">On Grid</option>
                                <option value="off-grid">Off Grid</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="service_charge">Service Charge</label>
                            <input type="number" name="service_charge" id="service_charge" class="form-control" value="<?php echo $data['service_charge']; ?>" min="0" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label>Total Price</label>
                            <div id="total-price" class="form-control-static">0.00</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Equipment Items</h3>
                    <div id="equipment-container">
                        <div class="equipment-row">
                            <div class="form-group">
                            <select name="item_id[]" class="form-control" required>
                                <option value="">Choose equipment</option>
                                <?php if(isset($data['inventory_items']) && !empty($data['inventory_items'])): ?>
                                    <?php foreach($data['inventory_items'] as $item): ?>
                                        <option value="<?php echo $item->item_id; ?>" data-price="<?php echo $item->price; ?>">
                                            <?php echo $item->product_name; ?> (Stock: <?php echo $item->quantity; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity[]" class="form-control" min="1" required>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeEquipment(this)">×</button>
                        </div>
                    </div>
                    <button type="button" class="btn-add" onclick="addEquipment()">Add Equipment</button>
                    <?php if(isset($data['errors']['equipment'])): ?>
                        <span class="error"><?php echo $data['errors']['equipment']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-section">
                    <h3>Package Features</h3>
                    <div id="features-container">
                        <div class="feature-row">
                            <div class="form-group">
                                <label>Feature Name</label>
                                <input type="text" name="feature_name[]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="feature_description[]" class="form-control" required>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeFeature(this)">X</button>
                        </div>
                    </div>
                    <button type="button" class="btn-add" onclick="addFeature()">Add Feature</button>
                </div>

                <div class="form-actions">
                    <a href="<?php echo URLROOT; ?>/operationsCoordinator/managePackages" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">Create Package</button>
                </div>

            </form>
        </div>
    </div>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/createPackage.js"></script>
    
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>