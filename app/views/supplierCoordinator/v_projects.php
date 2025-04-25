<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/projects.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar remains the same -->
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/projects" class="active">
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/settings">
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
                <!-- Page header -->
                <div class="page-header">
                    <h1>Installation Projects</h1>
                    <p>Projects waiting for equipment release</p>
                </div>

                <?php flash('project_message'); ?>

                <!-- Projects grid -->
                <div class="projects-grid" id="projectsGrid">
                    <?php if (empty($data['projects'])): ?>
                        <div class="no-projects">
                            <div class="icon">📦</div>
                            <h3>No Pending Projects</h3>
                            <p>There are no installation projects waiting for equipment release.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['projects'] as $project): ?>
                            <div class="project-card">
                                <div class="card-header">
                                    <h3 class="customer-name"><?php echo $project->customer_name; ?></h3>
                                    <span class="phase-badge installation">Equipment Pending</span>
                                </div>
                                <div class="card-body">
                                    <p class="project-id"><strong>Project ID:</strong> <?php echo $project->project_id; ?></p>
                                    <p class="project-location"><i class="material-icons-sharp">location_on</i> <?php echo $project->location; ?></p>
                                    <p class="project-date"><i class="material-icons-sharp">calendar_today</i> Last Updated: <?php echo date('M d, Y', strtotime($project->updated_at)); ?></p>
                                    <?php if (isset($project->system_capacity)): ?>
                                        <p class="system-details"><i class="material-icons-sharp">bolt</i> <?php echo $project->system_capacity; ?> kW System</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-actions">
                                    <a href="<?php echo URLROOT; ?>/supplierCoordinator/projectEquipments/<?php echo $project->project_id; ?>" class="btn-process">
                                        <span class="material-icons-sharp">inventory</span> Process
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>
</body>

</html>