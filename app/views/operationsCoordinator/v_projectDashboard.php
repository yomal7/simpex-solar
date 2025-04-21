<!-- v_projectDashboard.php -->

<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/projectDashboard.css">
</head>

<body data-user-role="operationsCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture"
            />
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/chat" class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
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
        <!-- <div class="main-content"> -->
                <div class="container">
                <main class="main">
                    <div class="header">
                        <h1>Project Management</h1>
                        <input type="text" class="search" placeholder="Search projects...">
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-title">Pre-Project Phase</div>
                            <div class="stat-value">12</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-title">Active Projects</div>
                            <div class="stat-value">8</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-title">Pending Site Visits</div>
                            <div class="stat-value">5</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-title">Completed This Month</div>
                            <div class="stat-value">15</div>
                        </div>
                    </div>

                    <div class="projects-header">
                        <h2>Current Projects</h2>
                        <div class="tabs">
                            <div class="tab active">All</div>
                            <div class="tab">Pre-Project</div>
                            <div class="tab">Active</div>
                        </div>
                    </div>

                    <div class="projects-grid">
                        <div class="project-card">
                            <div class="project-header">
                                <span class="project-id">#PRJ001</span>
                                <span class="project-status">Agreement</span>
                            </div>
                            <h3>John Doe</h3>
                            <div class="project-details">
                                <div>New York, NY</div>
                                <div>5.2 kW System</div>
                            </div>
                        </div>
                        <div class="project-card">
                            <div class="project-header">
                                <span class="project-id">#PRJ001</span>
                                <span class="project-status">Agreement</span>
                            </div>
                            <h3>John Doe</h3>
                            <div class="project-details">
                                <div>New York, NY</div>
                                <div>5.2 kW System</div>
                            </div>
                        </div>
                        <div class="project-card">
                            <div class="project-header">
                                <span class="project-id">#PRJ001</span>
                                <span class="project-status">Agreement</span>
                            </div>
                            <h3>John Doe</h3>
                            <div class="project-details">
                                <div>New York, NY</div>
                                <div>5.2 kW System</div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>   

    <!-- </div> -->
    <?php 
        echo URLROOT . '/js/operationsCoordinator/projectDashboard.js';
    ?>
    <div class="overlay" id="overlay"></div>
    
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/projectDashboard.js"></script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>