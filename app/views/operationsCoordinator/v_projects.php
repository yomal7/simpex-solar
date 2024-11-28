<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/projects.css">
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
                <div class="container">
                <nav class="phase-filter">
                    <button class="filter-btn active" data-phase="all">All Projects</button>
                    <button class="filter-btn" data-phase="agreement">Agreement</button>
                    <button class="filter-btn" data-phase="site-visit">Site Visit</button>
                    <button class="filter-btn" data-phase="first-payment">First Payment</button>
                    <button class="filter-btn" data-phase="installation">Installation</button>
                    <button class="filter-btn" data-phase="final-payment">Final Payment</button>
                    <button class="filter-btn" data-phase="engineer-approval">Engineer Approval</button>
                </nav>

                <div class="projects-grid" id="projectsGrid">
                    <!-- Projects will be dynamically inserted here -->
                </div>
            </div>
        </div>   

    </div>

    <div class="overlay" id="overlay"></div>

    <!-- <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script> -->
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/projects.js"></script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>