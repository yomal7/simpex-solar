<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/tasks.css">
</head>

<body data-user-role="operationsCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/manageAproject">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/chat" class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
                <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/settings">
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
            <div class="container task-view">
                <div class="header-actions">
                    <a href="<?php echo URLROOT; ?>/operationsCoordinator/tasks" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Tasks
                    </a>
                </div>

                <div class="task-card">
                    <div class="task-header">
                        <h1 class="task-title"><?php echo $data['task']->title; ?></h1>
                        <span class="task-status <?php echo strtolower($data['task']->status); ?>">
                            <?php echo $data['task']->status; ?>
                        </span>
                    </div>

                    <div class="task-details">
                        <div class="detail-group">
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <div class="detail-content">
                                    <label>Start Date</label>
                                    <p><?php echo $data['task']->start_date; ?></p>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-calendar-check"></i>
                                <div class="detail-content">
                                    <label>End Date</label>
                                    <p><?php echo $data['task']->end_date; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="detail-item description">
                            <i class="fas fa-align-left"></i>
                            <div class="detail-content">
                                <label>Description</label>
                                <p><?php echo $data['task']->description; ?></p>
                            </div>
                        </div>

                        <div class="detail-group">
                            <div class="detail-item">
                                <i class="fas fa-file-invoice"></i>
                                <div class="detail-content">
                                    <label>Project ID</label>
                                    <p><?php echo $data['task']->project_id; ?></p>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-user"></i>
                                <div class="detail-content">
                                    <label>Assigned To</label>
                                    <p><?php echo $data['task']->employee_id . ' - ' . $data['task']->employee_name . ' (' . $data['task']->employee_role . ' )'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="<?php echo URLROOT; ?>/operationsCoordinator/editTask/<?php echo $data['task']->id; ?>"
                            class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit Task
                        </a>
                        <!-- <a href="<?php echo URLROOT; ?>/operationsCoordinator/deleteTask/<?php echo $data['task']->id; ?>" onclick="return confirm('Are you sure you want to delete this task?');">
                            <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete Task</button>
                        </a> -->
                        <a href="<?php echo URLROOT; ?>/operationsCoordinator/deleteTask/<?php echo $data['task']->id; ?>"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this task?');">
                            <i class="fas fa-trash"></i> Delete Task
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>