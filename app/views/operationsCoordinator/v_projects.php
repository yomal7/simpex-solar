<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/projects.css">
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
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/preprojects">
                <span class="material-icons-sharp">pending_actions</span>
                <h3>Pre-Projects</h3>
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/settings">
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
                <!-- Project Phase Stats -->
                <div class="stats-grid">
                    <div class="stat-card" data-phase="document_submission">
                        <h3>Document Submission</h3>
                        <p class="count"><?php echo $data['stats']['document_submission']; ?></p>
                    </div>
                    <div class="stat-card" data-phase="first_payment">
                        <h3>First Payment</h3>
                        <p class="count"><?php echo $data['stats']['first_payment']; ?></p>
                    </div>
                    <div class="stat-card" data-phase="installation">
                        <h3>Installation</h3>
                        <p class="count"><?php echo $data['stats']['installation']; ?></p>
                    </div>
                    <div class="stat-card" data-phase="final_payment">
                        <h3>Final Payment</h3>
                        <p class="count"><?php echo $data['stats']['final_payment']; ?></p>
                    </div>
                    <div class="stat-card" data-phase="engineer_approval">
                        <h3>Engineer Approval</h3>
                        <p class="count"><?php echo $data['stats']['engineer_approval']; ?></p>
                    </div>
                </div>

                <!-- Search and Sort Controls -->
                <div class="control-bar">
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Search projects...">
                        <span class="material-icons-sharp search-icon">search</span>
                    </div>
                    <select class="sort-dropdown">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>

                <!-- Phase filter buttons -->
                <nav class="phase-filter">
                    <button class="filter-btn active" data-phase="all">All Projects</button>
                    <button class="filter-btn" data-phase="document_submission">Document Submission</button>
                    <button class="filter-btn" data-phase="first_payment">First Payment</button>
                    <button class="filter-btn" data-phase="installation">Installation</button>
                    <button class="filter-btn" data-phase="final_payment">Final Payment</button>
                    <button class="filter-btn" data-phase="engineer_approval">Engineer Approval</button>
                </nav>

                <div class="projects-grid" id="projectsGrid">
                    <?php if (isset($data['projects']) && !empty($data['projects'])): ?>
                        <?php foreach ($data['projects'] as $project): ?>
                            <div class="project-card" data-phase="<?php echo $project->current_phase; ?>"
                                onclick="window.location.href='<?php echo URLROOT; ?>/operationsCoordinator/manageAproject/<?php echo $project->project_id; ?>'">
                                <div class="project-content">
                                    <div class="project-id">#PRJ<?php echo str_pad($project->project_id, 3, '0', STR_PAD_LEFT); ?></div>
                                    <div class="customer-name"><?php echo $project->customer_name; ?></div>
                                    <div class="project-location">
                                        📍 <?php echo $project->location; ?>
                                    </div>
                                    <div class="project-date">
                                        📅 <?php echo date('M d, Y', strtotime($project->created_at)); ?>
                                    </div>
                                    <div class="project-phase">
                                        <?php echo strtoupper(str_replace('_', ' ', $project->current_phase)); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No projects found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script>
        const URLROOT = "<?php echo URLROOT; ?>";
    </script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/projects.js"></script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>