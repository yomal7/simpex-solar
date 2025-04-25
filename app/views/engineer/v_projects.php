<?php require APPROOT . '/views/engineer/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/projects.css">

</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <div class="company-logo">
                <img src="<?php echo URLROOT; ?>/public/assets/simpex-logo-sidebar.png" alt="Simpex Solar Logo">
            </div>
            <!-- User Profile Section -->
            <div class="user-profile">
                <img src="<?php echo isset($_SESSION['user_picture']) && !empty($_SESSION['user_picture']) ? URLROOT . '/public/uploads/profile_pictures/' . $_SESSION['user_picture'] : URLROOT . '/public/assets/profile.png'; ?>" alt="User profile picture" class="profile-picture" />
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User Name'; ?></h4>
                    <p>Engineer</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/engineer/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/projects" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="page-header">
                    <h1>My Installation Projects</h1>
                </div>

                <!-- Status Filter -->
                <div class="status-filter">
                    <button class="filter-btn <?php echo $data['currentFilter'] == 'active' ? 'active' : ''; ?>"
                        data-status="active">
                        Active Projects
                    </button>
                    <button class="filter-btn <?php echo $data['currentFilter'] == 'initial' ? 'active' : ''; ?>"
                        data-status="initial">
                        Initial Projects
                    </button>
                    <button class="filter-btn <?php echo $data['currentFilter'] == 'completed' ? 'active' : ''; ?>"
                        data-status="completed">
                        Completed Projects
                    </button>
                </div>

                <!-- Active Projects -->
                <div class="projects-container" id="active-projects"
                    style="display: <?php echo $data['currentFilter'] == 'active' ? 'block' : 'none'; ?>">
                    <h2>Active Projects</h2>

                    <?php if (empty($data['activeProjects'])): ?>
                        <div class="no-projects">
                            <div class="no-projects-icon">
                                <i class="material-icons-sharp">engineering</i>
                            </div>
                            <p>You don't have any active installation projects at the moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="projects-grid">
                            <?php foreach ($data['activeProjects'] as $project): ?>
                                <div class="project-card">
                                    <div class="project-header">
                                        <h3>Project #<?php echo $project->project_id; ?></h3>
                                        <span class="status-badge active">Active</span>
                                    </div>
                                    <div class="project-details">
                                        <div class="detail-item">
                                            <i class="fas fa-user"></i>
                                            <p><?php echo $project->customer_name; ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <p><?php echo $project->location; ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar"></i>
                                            <p>Starts: <?php echo date('M d, Y', strtotime($project->start_date)); ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-clock"></i>
                                            <p>Time: <?php echo date('h:i A', strtotime($project->start_time)); ?></p>
                                        </div>
                                    </div>
                                    <div class="project-actions">
                                        <a href="<?php echo URLROOT; ?>/engineer/viewInstallation/<?php echo $project->installation_id; ?>" class="btn btn-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Initial Projects -->
                <div class="projects-container" id="initial-projects"
                    style="display: <?php echo $data['currentFilter'] == 'initial' ? 'block' : 'none'; ?>">
                    <h2>Initial Projects</h2>

                    <?php if (empty($data['initialProjects'])): ?>
                        <div class="no-projects">
                            <div class="no-projects-icon">
                                <i class="material-icons-sharp">pending_actions</i>
                            </div>
                            <p>You don't have any projects in initial state.</p>
                        </div>
                    <?php else: ?>
                        <div class="projects-grid">
                            <?php foreach ($data['initialProjects'] as $project): ?>
                                <div class="project-card">
                                    <div class="project-header">
                                        <h3>Project #<?php echo $project->project_id; ?></h3>
                                        <span class="status-badge initial">Initial</span>
                                    </div>
                                    <div class="project-details">
                                        <div class="detail-item">
                                            <i class="fas fa-user"></i>
                                            <p><?php echo $project->customer_name; ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <p><?php echo $project->location; ?></p>
                                        </div>
                                        <?php if (isset($project->start_date)): ?>
                                            <div class="detail-item">
                                                <i class="fas fa-calendar"></i>
                                                <p>Starts: <?php echo date('M d, Y', strtotime($project->start_date)); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="project-actions">
                                        <a href="<?php echo URLROOT; ?>/engineer/viewInstallation/<?php echo $project->installation_id; ?>" class="btn btn-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Completed Projects -->
                <div class="projects-container" id="completed-projects"
                    style="display: <?php echo $data['currentFilter'] == 'completed' ? 'block' : 'none'; ?>">
                    <h2>Completed Projects</h2>

                    <?php if (empty($data['completedProjects'])): ?>
                        <div class="no-projects">
                            <div class="no-projects-icon">
                                <i class="material-icons-sharp">task_alt</i>
                            </div>
                            <p>You don't have any completed installation projects yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="projects-grid">
                            <?php foreach ($data['completedProjects'] as $project): ?>
                                <div class="project-card">
                                    <div class="project-header">
                                        <h3>Project #<?php echo $project->project_id; ?></h3>
                                        <span class="status-badge completed">Completed</span>
                                    </div>
                                    <div class="project-details">
                                        <div class="detail-item">
                                            <i class="fas fa-user"></i>
                                            <p><?php echo $project->customer_name; ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <p><?php echo $project->location; ?></p>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-check"></i>
                                            <p>Completed: <?php echo date('M d, Y', strtotime($project->end_date)); ?></p>
                                        </div>
                                    </div>
                                    <div class="project-actions">
                                        <a href="<?php echo URLROOT; ?>/engineer/viewInstallation/<?php echo $project->installation_id; ?>" class="btn btn-secondary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter projects by status
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Get status from data attribute
                const status = button.getAttribute('data-status');

                // Hide all project containers
                document.querySelectorAll('.projects-container').forEach(container => {
                    container.style.display = 'none';
                });

                // Show selected container
                document.getElementById(status + '-projects').style.display = 'block';

                // Update active class on buttons
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
            });
        });

        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

    <?php require APPROOT . '/views/engineer/footer.php'; ?>
</body>

</html>