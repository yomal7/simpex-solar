<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/employees.css">
</head>

<body>
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/payroll">
                <span class="material-icons-sharp">money</span>
                <h3>Payroll</h3>
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
            <div class="container employee-view">
                <div class="header-actions">
                    <a href="<?php echo URLROOT; ?>/hRAdministrator/employees" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Employees
                    </a>
                </div>

                <div class="employee-card">
                    <div class="employee-header">
                        <h1 class="employee-name"><?php echo $data['employee']->name; ?></h1>
                        <span class="employee-role">
                            <?php echo $data['employee']->role; ?>
                        </span>
                    </div>

                    <div class="employee-details">
                        <div class="detail-group">
                            <div class="detail-item">
                                <i class="fa-solid fa-id-card"></i>
                                <div class="detail-content">
                                    <label>Employee ID</label>
                                    <p><?php echo $data['employee']->employee_id;; ?></p>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-id-badge"></i>
                                <div class="detail-content">
                                    <label>User ID</label>
                                    <p><?php echo $data['employee']->user_id; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <div class="detail-content">
                                    <label>Email Address</label>
                                    <p><?php echo $data['employee']->email; ?></p>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <div class="detail-content">
                                    <label>Phone Number</label>
                                    <p><?php echo $data['employee']->phone; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <div class="detail-content">
                                <label>Created Time</label>
                                <p><?php echo $data['employee']->created_at; ?></p>
                            </div>
                        </div>

                    </div>

                    <div class="action-buttons">
                        <a href="<?php echo URLROOT; ?>/hRAdministrator/editEmployee/<?php echo $data['employee']->employee_id; ?>"
                            class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit Employee Profile
                        </a>
                        <a href="<?php echo URLROOT; ?>/hRAdministrator/deleteEmployee/<?php echo $data['employee']->employee_id; ?>"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this Employee Profile?');">
                            <i class="fas fa-trash"></i> Delete Employee Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="overlay" id="overlay"></div> -->




    <script src="<?php echo URLROOT; ?>/js/hRAdministrator/dashboard.js"></script>
    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>