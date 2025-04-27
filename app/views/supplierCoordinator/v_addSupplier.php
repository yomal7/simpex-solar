<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/add_suppliers.css">
</head>

<body data-user-role="supplierCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
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
                    <p>Supplier Coordinator</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/chat" class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
                <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="content-wrapper">
            <div class="page-header">
                <h1>Add New Supplier</h1>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/suppliers" class="btn back-btn">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <?php flash('supplier_message'); ?>

            <form action="<?php echo URLROOT; ?>/supplierCoordinator/addSupplier" method="POST" class="supplier-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Supplier Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name"
                            class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['name']; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email"
                            class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['email']; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number <span class="required">*</span></label>
                        <input type="tel" id="contact_number" name="contact_number"
                            class="form-control <?php echo (!empty($data['contact_number_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $data['contact_number']; ?>" required>
                        <span class="invalid-feedback"><?php echo $data['contact_number_err']; ?></span>
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Address <span class="required">*</span></label>
                        <textarea id="address" name="address" rows="3"
                            class="form-control <?php echo (!empty($data['address_err'])) ? 'is-invalid' : ''; ?>"
                            required><?php echo $data['address']; ?></textarea>
                        <span class="invalid-feedback"><?php echo $data['address_err']; ?></span>
                    </div>

                    <div class="form-group full-width">
                        <label for="other_details">Other Details</label>
                        <textarea id="other_details" name="other_details" rows="4"
                            class="form-control"><?php echo $data['other_details']; ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn reset-btn">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="btn submit-btn">
                        <i class="fas fa-save"></i> Save Supplier
                    </button>
                </div>
            </form>
        </div>

        <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/add_suppliers.js"></script>
        <script src="<?php echo URLROOT; ?>/js/supplier.js"></script>
        <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>