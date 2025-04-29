<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/preprojects">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/services">
                <span class="material-icons-sharp">build</span>
                <h3>Services</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">

            <div class="dashboard-header">
                <h1>Operations Dashboard</h1>
                <div class="date-filter">
                    <p><?php echo date('l, F j, Y'); ?></p>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="material-icons-sharp">solar_power</span>
                    </div>
                    <div class="stat-info">
                        <h3>Total Projects</h3>
                        <h2><?php echo $data['project_stats']['total_projects']; ?></h2>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="material-icons-sharp">engineering</span>
                    </div>
                    <div class="stat-info">
                        <h3>Active Projects</h3>
                        <h2><?php echo $data['project_stats']['active_projects']; ?></h2>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="material-icons-sharp">pending_actions</span>
                    </div>
                    <div class="stat-info">
                        <h3>Pre-Projects</h3>
                        <h2><?php 
                            $preprojectCount = 0;
                            foreach ($data['project_stats']['preproject_stats'] as $count) {
                                $preprojectCount += $count;
                            }
                            echo $preprojectCount;
                        ?></h2>
                    </div>
                </div>
            
            </div>
            
            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-container">
                    <h2>Project Status</h2>
                    <canvas id="projectStatusChart"></canvas>
                </div>
                
                <div class="chart-container">
                    <h2>Project Phases</h2>
                    <canvas id="projectPhasesChart"></canvas>
                </div>
            </div>
            
            <!-- Bottom Section -->
            <div class="bottom-section">
                <!-- Recent Projects -->
                <div class="recent-section">
                    <div class="section-header">
                        <h2>Pre-Project Status</h2>
                        <a href="<?php echo URLROOT ?>/operationsCoordinator/preprojects" class="view-all">View All</a>
                    </div>
                    <div class="preproject-stats">
                        <div class="preproject-stat">
                            <span class="phase-indicator quotation-phase"></span>
                            <p>Quotation Phase: <?php echo $data['project_stats']['preproject_stats']['quotation']; ?></p>
                        </div>
                        <div class="preproject-stat">
                            <span class="phase-indicator site-visit-phase"></span>
                            <p>Site Visit Phase: <?php echo $data['project_stats']['preproject_stats']['site_visit']; ?></p>
                        </div>
                        <div class="preproject-stat">
                            <span class="phase-indicator agreement-phase"></span>
                            <p>Agreement Phase: <?php echo $data['project_stats']['preproject_stats']['agreement']; ?></p>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Package Stats -->
            <div class="package-section">
                <div class="section-header">
                    <h2>Package Distribution</h2>
                    <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages" class="view-all">Manage Packages</a>
                </div>
                <div class="package-container">
                    <div class="package-chart-container">
                        <canvas id="packageTypeChart"></canvas>
                    </div>
                    <div class="package-info">
                        <div class="package-type-stats">
                            <div class="package-type">
                                <span class="type-indicator on-grid"></span>
                                <p>On-Grid: <?php echo $data['package_stats']['package_by_type']['on-grid']; ?></p>
                            </div>
                            <div class="package-type">
                                <span class="type-indicator off-grid"></span>
                                <p>Off-Grid: <?php echo $data['package_stats']['package_by_type']['off-grid']; ?></p>
                            </div>
                            <div class="package-type">
                                <span class="type-indicator hybrid"></span>
                                <p>Hybrid: <?php echo $data['package_stats']['package_by_type']['hybrid']; ?></p>
                            </div>
                            <div class="package-type total">
                                <p>Total Packages: <?php echo $data['package_stats']['total_packages']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toggle sidebar function
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        // Chart Data
        const projectStatusData = {
            labels: ['Active', 'Completed'],
            datasets: [{
                data: [
                    <?php echo $data['project_stats']['active_projects']; ?>, 
                    <?php echo $data['project_stats']['completed_projects']; ?>
                ],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB'
                ],
                borderWidth: 0
            }]
        };
        
        const projectPhasesData = {
            labels: [
                'Document Submission', 
                'First Payment', 
                'Installation', 
                'Final Payment', 
                'Engineer Approval', 
                'Grid Connection'
            ],
            datasets: [{
                data: [
                    <?php echo $data['project_stats']['phase_distribution']['document_submission']; ?>,
                    <?php echo $data['project_stats']['phase_distribution']['first_payment']; ?>,
                    <?php echo $data['project_stats']['phase_distribution']['installation']; ?>,
                    <?php echo $data['project_stats']['phase_distribution']['final_payment']; ?>,
                    <?php echo $data['project_stats']['phase_distribution']['engineer_approval']; ?>,
                    <?php echo $data['project_stats']['phase_distribution']['grid_connection']; ?>
                ],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ],
                borderWidth: 0
            }]
        };

        const packageTypeData = {
            labels: ['On-Grid', 'Off-Grid', 'Hybrid'],
            datasets: [{
                data: [
                    <?php echo $data['package_stats']['package_by_type']['on-grid']; ?>,
                    <?php echo $data['package_stats']['package_by_type']['off-grid']; ?>,
                    <?php echo $data['package_stats']['package_by_type']['hybrid']; ?>
                ],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56'
                ],
                borderWidth: 0
            }]
        };
        
        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Project Status Chart
            new Chart(
                document.getElementById('projectStatusChart'),
                {
                    type: 'doughnut',
                    data: projectStatusData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                }
            );
            
            // Project Phases Chart
            new Chart(
                document.getElementById('projectPhasesChart'),
                {
                    type: 'bar',
                    data: projectPhasesData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                }
            );

            // Package Type Chart
            new Chart(
                document.getElementById('packageTypeChart'),
                {
                    type: 'pie',
                    data: packageTypeData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                }
            );
        });

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Update all elements with the class 'format-number'
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.format-number');
            elements.forEach(function(element) {
                const num = parseInt(element.textContent);
                if (!isNaN(num)) {
                    element.textContent = formatNumber(num);
                }
            });
        });
    </script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>

