<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/viewEmployee.css">
</head>

<body data-user-role="hRAdministrator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/settings">
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
                        <div class="employee-profile">
                            <div class="profile-image">
                                <?php if(!empty($data['employee']->profile_picture)): ?>
                                    <img src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $data['employee']->profile_picture; ?>" 
                                         alt="<?php echo $data['employee']->name; ?>'s profile picture">
                                <?php else: ?>
                                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png" 
                                         alt="Default profile picture">
                                <?php endif; ?>
                            </div>
                            <div class="employee-info">
                                <h1 class="employee-name"><?php echo $data['employee']->name; ?></h1>
                                <span class="employee-role">
                                    <?php echo $data['employee']->role; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="employee-details">
                        <div class="detail-group">
                            <div class="detail-item">
                                <i class="fa-solid fa-id-card"></i>
                                <div class="detail-content">
                                    <label>Employee ID</label>
                                    <p><?php echo $data['employee']->employee_id; ?></p>
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
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="detail-content">
                                <label>Address</label>
                                <p><?php echo $data['employee']->address ?? 'No address provided'; ?></p>
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

    <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>