<!-- v_settings.php -->
<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/setting.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <img src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/chat">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
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

        <!-- Main Content -->
        <div class="main-content">
            <!-- Profile Settings Form -->
            <div class="settings-card">
                <h2>
                    <span class="material-icons-sharp">person</span>
                    Profile Information
                </h2>
                <form action="<?php echo URLROOT ?>/supplierCoordinator/updateProfile" method="POST" class="settings-form">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel"
                            name="contact"
                            value="<?php echo $data['contact'] ?? ''; ?>"
                            pattern="[0-9]{10}"
                            placeholder="Enter your 10-digit phone number"
                            title="Please enter a valid 10-digit phone number"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email"
                            name="email"
                            value="<?php echo $data['email'] ?? ''; ?>"
                            placeholder="Enter your email address"
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                            title="Please enter a valid email address"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">

                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Password Change Form -->
            <div class="settings-card">
                <h2>
                    <span class="material-icons-sharp">lock</span>
                    Change Password
                </h2>
                <form action="<?php echo URLROOT ?>/supplierCoordinator/updatePassword" method="POST" class="settings-form">


                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password"
                            name="new_password"
                            placeholder="Enter your new password"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Must contain at least one number, one uppercase and lowercase letter, and at least 8 characters"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password"
                            name="confirm_password"
                            placeholder="Confirm your new password"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">

                        Update Password
                    </button>
                </form>
            </div>
        </div>
</body>

</html>