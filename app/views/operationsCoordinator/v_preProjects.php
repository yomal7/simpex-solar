<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/preProjects.css">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/preprojects" class="active">
                <span class="material-icons-sharp">pending_actions</span>
                <h3>Pre-Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects">
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
        <div class="main-content">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Quotation Phase</h3>
                        <h2><?php echo $data['stats']['quotation']; ?></h2>
                    </div>
                    <div class="stat-card">
                        <h3>Site Visit Phase</h3>
                        <h2><?php echo $data['stats']['site_visit']; ?></h2>
                    </div>
                    <div class="stat-card">
                        <h3>Agreement Phase</h3>
                        <h2><?php echo $data['stats']['agreement']; ?></h2>
                    </div>
                </div>

                <!-- Control Bar -->
                <div class="control-bar">
                    <div class="search-box">
                        <span class="material-icons search-icon">search</span>
                        <input type="text" class="search-input" placeholder="Search quotations...">
                    </div>
                    <select class="sort-dropdown">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>

                <!-- Phase Filter -->
                <div class="phase-filter">
                    <button class="phase-btn <?php echo $data['current_phase'] === 'all' ? 'active' : ''; ?>" 
                            data-phase="all">All Projects</button>
                    <button class="phase-btn <?php echo $data['current_phase'] === 'quotation' ? 'active' : ''; ?>" 
                            data-phase="quotation">Quotation Phase</button>
                    <button class="phase-btn <?php echo $data['current_phase'] === 'site_visit' ? 'active' : ''; ?>" 
                            data-phase="site_visit">Site Visit Phase</button>
                    <button class="phase-btn <?php echo $data['current_phase'] === 'agreement' ? 'active' : ''; ?>" 
                            data-phase="agreement">Agreement Phase</button>
                </div>

                <!-- Projects Grid -->
                <div class="projects-grid" id="projectsGrid">
                    <?php foreach ($data['preProjects'] as $project): ?>
                        <div class="project-card" data-phase="<?php echo $project->current_phase; ?>">
                            <div class="card-header">
                                <div class="project-id">#PP<?php echo str_pad($project->pre_project_id, 3, '0', STR_PAD_LEFT); ?></div>
                                <div class="phase-badge <?php echo $project->current_phase; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $project->current_phase)); ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="project-title"><?php echo $project->customer_name; ?></h3>
                                <div class="project-details">
                                    <p>📍 <?php echo $project->nearest_city ?? 'Not specified'; ?></p>
                                    <p>⚡ <?php echo $project->monthly_consumption ?? 'Not specified'; ?> kWh/month</p>
                                    <p>📅 <?php echo date('M d, Y', strtotime($project->created_at)); ?></p>
                                    <?php if ($project->current_phase === 'quotation'): ?>
                                        <p>📋 Status: <?php echo ucfirst($project->quotation_status); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script>
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/preProjects.js"></script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>
