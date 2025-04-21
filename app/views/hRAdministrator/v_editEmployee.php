<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/employees.css">
</head>

<body data-user-role="hRAdministrator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/hRAdministrator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees" class="active">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/chat" class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
                <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
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
        <div class="main-content">
            <div class="form-container">

                <h2>Edit Employee Profile</h2>
                <form action="<?php echo URLROOT; ?>/hRAdministrator/editEmployee/<?php echo $data['employee_id']; ?>" method="POST" id="userForm" id="addUserForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" required placeholder="Name" value="<?php echo $data['name']; ?>">
                            <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role">
                                <option value="">Select Role...</option>
                                <option value="technician" <?php echo $data['role'] == 'technician' ? 'selected' : ''; ?>>Technician</option>
                                <option value="deliveryPerson" <?php echo $data['role'] == 'deliveryPerson' ? 'selected' : ''; ?>>Delivery Person</option>
                                <option value="engineer" <?php echo $data['role'] == 'engineer' ? 'selected' : ''; ?>>Engineer</option>
                                <option value="clerk" <?php echo $data['role'] == 'clerk' ? 'selected' : ''; ?>>Clerk</option>
                            </select>
                            <span class="form-invalid"><?php echo isset($data['role_err']) ? $data['role_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $data['email']; ?>">
                            <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone No</label>
                            <input type="number" name="phone" id="phone" placeholder="Phone No" value="<?php echo $data['phone']; ?>">
                            <span class="form-invalid"><?php echo isset($data['phone_err']) ? $data['phone_err'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="button-group">
                        <input type="submit" value="Update Employee Profile" class="btn btn-primary">
                        <a href="<?php echo URLROOT; ?>/hRAdministrator/viewEmployee/<?php echo $data['employee_id']; ?>"><button type="button" class="btn btn-secondary">Cancel</button></a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- <div class="overlay" id="overlay"></div> -->


    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
<?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>