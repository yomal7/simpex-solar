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

                <h2>Create Employee Profile</h2>
                <form action="<?php echo URLROOT; ?>/hRAdministrator/addEmployee" method="POST" id="userForm" id="addUserForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="Name" value="<?php $data['name']; ?>">
                            <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role">
                                <option value="">Select Role...</option>
                                <option value="technician">Technician</option>
                                <option value="deliveryPerson">Delivery Person</option>
                                <option value="engineer">Engineer</option>
                                <option value="clerk">Clerk</option>
                            </select>
                            <span class="form-invalid"><?php echo isset($data['role_err']) ? $data['role_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" value="<?php $data['email']; ?>">
                            <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone No</label>
                            <input type="number" name="phone" id="phone" placeholder="Phone No" value="<?php $data['phone']; ?>">
                            <span class="form-invalid"><?php echo isset($data['phone_err']) ? $data['phone_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="Password" value="<?php $data['password']; ?>">
                            <span class="form-invalid"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="<?php $data['confirm_password']; ?>">
                            <span class="form-invalid"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="button-group">
                        <input type="submit" value="Create Employee" class="btn btn-primary">
                        <a href="<?php echo URLROOT; ?>/hRAdministrator/employees"><button type="button" class="btn btn-secondary">Cancel</button></a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- <div class="overlay" id="overlay"></div> -->


    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
<?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>