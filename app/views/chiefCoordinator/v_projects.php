<!-- File: app/views/chiefCoordinator/v_projects.php -->
<?php require APPROOT.'/views/chiefCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/projects.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="manager profile-picture" class="profile-picture" />
            <a href="<?php echo URLROOT ?>/chiefCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/preProjects">
                <span class="material-icons-sharp">assignment</span>
                <h3>Pre Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/projects" class="active">
                <span class="material-icons-sharp">business_center</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/payments" >
                <span class="material-icons-sharp">payments</span>
                <h3>Payments</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/employees">
                <span class="material-icons-sharp">people</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/store">
                <span class="material-icons-sharp">store</span>
                <h3>Store</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/feedbacks">
                <span class="material-icons-sharp">feedback</span>
                <h3>Feedbacks</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="content-wrapper">
            <div class="main-content">
                <div class="page-header">
                    <h1>Projects Information</h1>
                    <div class="header-actions">
                        <div class="time-filter">
                            <form action="" method="get" id="timeFrameForm">
                                <select name="timeframe" id="timeframeSelect" onchange="this.form.submit()">
                                    <option value="all" <?php echo $data['timeframe'] == 'all' ? 'selected' : ''; ?>>All Time</option>
                                    <option value="today" <?php echo $data['timeframe'] == 'today' ? 'selected' : ''; ?>>Today</option>
                                    <option value="week" <?php echo $data['timeframe'] == 'week' ? 'selected' : ''; ?>>This Week</option>
                                    <option value="month" <?php echo $data['timeframe'] == 'month' ? 'selected' : ''; ?>>This Month</option>
                                    <option value="year" <?php echo $data['timeframe'] == 'year' ? 'selected' : ''; ?>>This Year</option>
                                </select>
                                <input type="hidden" name="projects_page" value="1">
                                <input type="hidden" name="phase" value="<?php echo $data['phase_filter']; ?>">
                                <input type="hidden" name="status" value="<?php echo $data['status_filter']; ?>">
                            </form>
                        </div>
                        <button id="printReportBtn" class="btn">
                            <span class="material-icons-sharp">print</span> Print Report
                        </button>
                        <form id="generateReportForm" action="<?php echo URLROOT; ?>/chiefCoordinator/generateProjectsReport" method="post">
                            <input type="hidden" name="timeframe" value="<?php echo $data['timeframe']; ?>">
                            <input type="hidden" name="phase" value="<?php echo $data['phase_filter']; ?>">
                            <input type="hidden" name="status" value="<?php echo $data['status_filter']; ?>">
                            <button type="submit" class="btn">
                                <span class="material-icons-sharp">description</span> Generate Report
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="summary-cards">
                    <div class="card">
                        <div class="card-inner">
                            <h3>Total Projects</h3>
                            <span class="material-icons-sharp">business_center</span>
                        </div>
                        <h1>
                            <?php 
                                $totalProjects = 0;
                                foreach($data['stats']['status_stats'] as $stat) {
                                    $totalProjects += $stat->count;
                                }
                                echo $totalProjects;
                            ?>
                        </h1>
                    </div>
                    <div class="card active-projects">
                        <div class="card-inner">
                            <h3>Active Projects</h3>
                            <span class="material-icons-sharp">engineering</span>
                        </div>
                        <h1>
                            <?php 
                                $activeProjects = 0;
                                foreach($data['stats']['status_stats'] as $stat) {
                                    if($stat->status == 'active') {
                                        $activeProjects = $stat->count;
                                        break;
                                    }
                                }
                                echo $activeProjects;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Completed Projects</h3>
                            <span class="material-icons-sharp">task_alt</span>
                        </div>
                        <h1>
                            <?php 
                                $completedProjects = 0;
                                foreach($data['stats']['status_stats'] as $stat) {
                                    if($stat->status == 'completed') {
                                        $completedProjects = $stat->count;
                                        break;
                                    }
                                }
                                echo $completedProjects;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Equipment Released</h3>
                            <span class="material-icons-sharp">inventory</span>
                        </div>
                        <h1>
                            <?php 
                                $equipmentReleased = 0;
                                foreach($data['stats']['equipment_stats'] as $stat) {
                                    if($stat->equipment_released == 1) {
                                        $equipmentReleased = $stat->count;
                                        break;
                                    }
                                }
                                echo $equipmentReleased;
                            ?>
                        </h1>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filters-section">
                    <h3>Filter Projects</h3>
                    <form action="" method="get" id="filterForm" class="filter-form">
                        <input type="hidden" name="timeframe" value="<?php echo $data['timeframe']; ?>">
                        <input type="hidden" name="projects_page" value="1">
                        
                        <div class="filter-group">
                            <label for="phaseFilter">Phase:</label>
                            <select name="phase" id="phaseFilter" onchange="this.form.submit()">
                                <option value="all" <?php echo $data['phase_filter'] == 'all' ? 'selected' : ''; ?>>All Phases</option>
                                <option value="document_submission" <?php echo $data['phase_filter'] == 'document_submission' ? 'selected' : ''; ?>>Document Submission</option>
                                <option value="first_payment" <?php echo $data['phase_filter'] == 'first_payment' ? 'selected' : ''; ?>>First Payment</option>
                                <option value="installation" <?php echo $data['phase_filter'] == 'installation' ? 'selected' : ''; ?>>Installation</option>
                                <option value="final_payment" <?php echo $data['phase_filter'] == 'final_payment' ? 'selected' : ''; ?>>Final Payment</option>
                                <option value="engineer_approval" <?php echo $data['phase_filter'] == 'engineer_approval' ? 'selected' : ''; ?>>Engineer Approval</option>
                                <option value="grid_connection" <?php echo $data['phase_filter'] == 'grid_connection' ? 'selected' : ''; ?>>Grid Connection</option>
                                <option value="completed" <?php echo $data['phase_filter'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="statusFilter">Status:</label>
                            <select name="status" id="statusFilter" onchange="this.form.submit()">
                                <option value="all" <?php echo $data['status_filter'] == 'all' ? 'selected' : ''; ?>>All Status</option>
                                <option value="active" <?php echo $data['status_filter'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="completed" <?php echo $data['status_filter'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $data['status_filter'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="button" class="btn clear-filter" onclick="clearFilters()">
                            <span class="material-icons-sharp">clear</span> Clear Filters
                        </button>
                    </form>
                </div>

                <!-- Charts Section -->
                <div class="charts-container">
                    <div class="chart-card">
                        <h3>Projects by Status</h3>
                        <div class="chart-wrapper">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Active Projects by Phase</h3>
                        <div class="chart-wrapper">
                            <canvas id="phaseChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Monthly Projects (This Year)</h3>
                        <div class="chart-wrapper">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Equipment Release Status</h3>
                        <div class="chart-wrapper">
                            <canvas id="equipmentChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Projects Table -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Projects List</h3>
                        <div class="search">
                            <input type="text" id="projectSearch" placeholder="Search projects...">
                            <span class="material-icons-sharp">search</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <?php if (empty($data['projects'])): ?>
                            <div class="no-data-message">
                                <span class="material-icons-sharp">info</span>
                                <p>No projects found with the current filters. Try changing your filter options or add new projects to get started.</p>
                                <button class="btn" onclick="clearFilters()">
                                    <span class="material-icons-sharp">refresh</span> Clear Filters
                                </button>
                            </div>
                        <?php else: ?>
                            <table id="projectsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Package</th>
                                        <th>Current Phase</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Equipment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['projects'] as $project): ?>
                                    <tr>
                                        <td><?php echo $project->project_id; ?></td>
                                        <td><?php echo isset($project->customer_name) ? $project->customer_name : 'Unknown'; ?></td>
                                        <td><?php echo isset($project->package_name) ? $project->package_name : 'N/A'; ?></td>
                                        <td><span class="status-pill phase-<?php echo $project->current_phase; ?>"><?php echo str_replace('_', ' ', ucfirst($project->current_phase)); ?></span></td>
                                        <td><span class="status-pill status-<?php echo $project->status; ?>"><?php echo ucfirst($project->status); ?></span></td>
                                        <td><?php echo date('M d, Y', strtotime($project->created_at)); ?></td>
                                        <td>
                                            <?php if($project->equipment_released == 1): ?>
                                                <span class="status-pill status-active">Released</span>
                                            <?php else: ?>
                                                <span class="status-pill status-cancelled">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (!empty($data['projects'])): ?>
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['projects_pagination']['page']; ?> of <?php echo $data['projects_pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['projects_pagination']['total']; ?> projects</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['projects_pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/projects?projects_page=1&timeframe=<?php echo $data['timeframe']; ?>&phase=<?php echo $data['phase_filter']; ?>&status=<?php echo $data['status_filter']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/projects?projects_page=<?php echo $data['projects_pagination']['page']-1; ?>&timeframe=<?php echo $data['timeframe']; ?>&phase=<?php echo $data['phase_filter']; ?>&status=<?php echo $data['status_filter']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_left</span>
                            </a>
                        <?php else: ?>
                            <span class="pagination-item disabled">
                                <span class="material-icons-sharp">first_page</span>
                            </span>
                            <span class="pagination-item disabled">
                                <span class="material-icons-sharp">chevron_left</span>
                            </span>
                        <?php endif; ?>
                        
                        <?php
                        // Show 5 pagination links centered around current page
                        $startPage = max(1, $data['projects_pagination']['page'] - 2);
                        $endPage = min($data['projects_pagination']['total_pages'], $startPage + 4);
                        // Adjust start page if we're near the end
                        if($endPage - $startPage < 4) {
                            $startPage = max(1, $endPage - 4);
                        }
                        
                        for($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/projects?projects_page=<?php echo $i; ?>&timeframe=<?php echo $data['timeframe']; ?>&phase=<?php echo $data['phase_filter']; ?>&status=<?php echo $data['status_filter']; ?>" 
                               class="pagination-item <?php echo ($i == $data['projects_pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['projects_pagination']['page'] < $data['projects_pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/projects?projects_page=<?php echo $data['projects_pagination']['page']+1; ?>&timeframe=<?php echo $data['timeframe']; ?>&phase=<?php echo $data['phase_filter']; ?>&status=<?php echo $data['status_filter']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/projects?projects_page=<?php echo $data['projects_pagination']['total_pages']; ?>&timeframe=<?php echo $data['timeframe']; ?>&phase=<?php echo $data['phase_filter']; ?>&status=<?php echo $data['status_filter']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">last_page</span>
                            </a>
                        <?php else: ?>
                            <span class="pagination-item disabled">
                                <span class="material-icons-sharp">chevron_right</span>
                            </span>
                            <span class="pagination-item disabled">
                                <span class="material-icons-sharp">last_page</span>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        // Clear all filters
        function clearFilters() {
            window.location.href = '<?php echo URLROOT; ?>/chiefCoordinator/projects?timeframe=all&projects_page=1&phase=all&status=all';
        }
        
        // Table search functionality
        document.getElementById('projectSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('projectsTable');
            if (!table) return; // Exit if table doesn't exist
            
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.indexOf(searchValue) > -1) {
                        found = true;
                        break;
                    }
                }
                
                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
        
        // Chart.js implementations
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Projects by Status
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['status_stats'] as $stat) {
                                echo "'" . ucfirst($stat->status) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($data['stats']['status_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: [
                            '#4CAF50', // Active - Green
                            '#2196F3', // Completed - Blue
                            '#F44336'  // Cancelled - Red
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
            
            // Chart 2: Active Projects by Phase
            const phaseCtx = document.getElementById('phaseChart').getContext('2d');
            const phaseChart = new Chart(phaseCtx, {
                type: 'pie',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['phase_stats'] as $stat) {
                                echo "'" . str_replace('_', ' ', ucfirst($stat->current_phase)) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($data['stats']['phase_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: [
                            '#FFC107', // Document Submission - Amber
                            '#9C27B0', // First Payment - Purple
                            '#00BCD4', // Installation - Cyan
                            '#FF5722', // Final Payment - Deep Orange
                            '#795548', // Engineer Approval - Brown
                            '#607D8B', // Grid Connection - Blue Grey
                            '#8BC34A'  // Completed - Light Green
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
            
            // Chart 3: Monthly Projects
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Prepare data array for all 12 months (with 0 as default)
            const monthlyData = Array(12).fill(0);
            
            <?php foreach($data['stats']['monthly_stats'] as $stat): ?>
                // Adjust month value (database returns 1-12, array is 0-11)
                monthlyData[<?php echo $stat->month - 1; ?>] = <?php echo $stat->count; ?>;
            <?php endforeach; ?>
            
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Projects',
                        data: monthlyData,
                        backgroundColor: 'rgba(233, 30, 99, 0.2)',
                        borderColor: '#E91E63',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
            
            // Chart 4: Equipment Release Status
            const equipmentCtx = document.getElementById('equipmentChart').getContext('2d');
            const equipmentChart = new Chart(equipmentCtx, {
                type: 'pie',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['equipment_stats'] as $stat) {
                                echo "'" . ($stat->equipment_released == 1 ? 'Released' : 'Pending') . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($data['stats']['equipment_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: [
                            '#4CAF50', // Released - Green
                            '#FF9800'  // Pending - Orange
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        });
        
        // Print functionality
        document.getElementById('printReportBtn').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>