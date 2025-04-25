<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/companyUsrSettings.css">
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/settings" class="active">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="settings-container">
                <h1>Account Settings</h1>
                
                <div class="settings-sections">
                    <!-- Profile Update Form -->
                    <div class="settings-card">
                        <div class="settings-title">
                            <h2>Update Profile</h2>
                            <p>Manage your profile information</p>
                        </div>
                        
                        <?php flash('profile_message'); ?>
                        
                        <form action="<?php echo URLROOT; ?>/supplierCoordinator/settings" method="POST" enctype="multipart/form-data" class="settings-form">
                            <input type="hidden" name="form_type" value="profile_update">
                            
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="<?php echo $data['name']; ?>" disabled class="readonly-field">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" required placeholder="Email" value="<?php echo $data['email']; ?>">
                                <span class="form-error"><?php echo $data['email_err']; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" placeholder="Phone No" value="<?php echo $data['phone']; ?>">
                                <span class="form-error"><?php echo isset($data['phone_err']) ? $data['phone_err'] : ''; ?></span>
                            </div>
                            
                            <div class="form-group profile-picture-group">
                                <label>Profile Picture</label>
                                <div class="profile-picture-container">
                                    <?php if(!empty($data['profile_picture'])): ?>
                                        <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $data['profile_picture']; ?>" alt="Profile Picture" class="profile-preview">
                                    <?php else: ?>
                                        <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Default Profile" class="profile-preview">
                                    <?php endif; ?>
                                    <div class="upload-btn-wrapper">
                                        <button type="button" class="btn">Choose Image</button>
                                        <input type="file" name="profile_picture" id="profile_picture" accept=".png, .jpg, .jpeg">
                                    </div>
                                </div>
                                <span class="form-error"><?php echo isset($data['profile_picture_err']) ? $data['profile_picture_err'] : ''; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Password Change Form -->
                    <div class="settings-card">
                        <div class="settings-title">
                            <h2>Change Password</h2>
                            <p>Update your password for increased security</p>
                        </div>
                        
                        <?php flash('password_message'); ?>
                        
                        <form action="<?php echo URLROOT; ?>/supplierCoordinator/settings" method="POST" class="settings-form">
                            <input type="hidden" name="form_type" value="password_change">
                            
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" id="current_password" placeholder="Enter your current password">
                                <span class="form-error"><?php echo isset($data['current_password_err']) ? $data['current_password_err'] : ''; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" placeholder="Enter new password">
                                <span class="form-error"><?php echo isset($data['new_password_err']) ? $data['new_password_err'] : '' ; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">
                                <span class="form-error"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview profile picture before upload
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.profile-preview').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>