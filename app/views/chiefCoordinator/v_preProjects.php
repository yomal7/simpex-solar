<!-- File: app/views/chiefCoordinator/v_preProjects.php -->
<?php require APPROOT.'/views/chiefCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/preProjects.css">
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
            <a href="<?php echo URLROOT ?>/chiefCoordinator/preProjects" class="active">
                <span class="material-icons-sharp">assignment</span>
                <h3>Pre Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/projects">
                <span class="material-icons-sharp">business_center</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/finance">
                <span class="material-icons-sharp">attach_money</span>
                <h3>Finance</h3>
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
                    <h1>Pre-Projects Information</h1>
                    <div class="header-actions">
                        <button id="printReportBtn" class="btn">
                            <span class="material-icons-sharp">print</span> Print Report
                        </button>
                        <form id="generateReportForm" action="<?php echo URLROOT; ?>/chiefCoordinator/generateReport" method="post">
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
                            <h3>Total Pre-Projects</h3>
                            <span class="material-icons-sharp">assignment</span>
                        </div>
                        <h1>
                            <?php 
                                $totalPreProjects = 0;
                                foreach($data['stats']['status_stats'] as $stat) {
                                    $totalPreProjects += $stat->count;
                                }
                                echo $totalPreProjects;
                            ?>
                        </h1>
                    </div>
                    <div class="card active-projects">
                        <div class="card-inner">
                            <h3>Active Pre-Projects</h3>
                            <span class="material-icons-sharp">assignment_turned_in</span>
                        </div>
                        <h1>
                            <?php 
                                $activePreProjects = 0;
                                foreach($data['stats']['status_stats'] as $stat) {
                                    if($stat->status == 'active') {
                                        $activePreProjects = $stat->count;
                                        break;
                                    }
                                }
                                echo $activePreProjects;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Completed Pre-Projects</h3>
                            <span class="material-icons-sharp">task_alt</span>
                        </div>
                        <h1>
                            <?php 
                                $completedPreProjects = 0;
                                foreach($data['stats']['status_stats'] as $stat) {
                                    if($stat->status == 'completed') {
                                        $completedPreProjects = $stat->count;
                                        break;
                                    }
                                }
                                echo $completedPreProjects;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Accepted Quotations</h3>
                            <span class="material-icons-sharp">thumb_up</span>
                        </div>
                        <h1>
                            <?php 
                                $acceptedQuotations = 0;
                                foreach($data['stats']['quotation_stats'] as $stat) {
                                    if($stat->status == 'accepted_by_customer') {
                                        $acceptedQuotations = $stat->count;
                                        break;
                                    }
                                }
                                echo $acceptedQuotations;
                            ?>
                        </h1>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-container">
                    <div class="chart-card">
                        <h3>Pre-Projects by Status</h3>
                        <div class="chart-wrapper">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Active Pre-Projects by Phase</h3>
                        <div class="chart-wrapper">
                            <canvas id="phaseChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Quotations by Status</h3>
                        <div class="chart-wrapper">
                            <canvas id="quotationChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Monthly Pre-Projects (This Year)</h3>
                        <div class="chart-wrapper">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Pre-Projects Table -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Recent Pre-Projects</h3>
                        <div class="search">
                            <input type="text" id="projectSearch" placeholder="Search projects...">
                            <span class="material-icons-sharp">search</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="preProjectsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Current Phase</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Quotations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['pre_projects'] as $project): ?>
                                <tr>
                                    <td><?php echo $project->pre_project_id; ?></td>
                                    <td><?php echo $project->customer_name; ?></td>
                                    <td><span class="status-pill phase-<?php echo $project->current_phase; ?>"><?php echo ucfirst($project->current_phase); ?></span></td>
                                    <td><span class="status-pill status-<?php echo $project->status; ?>"><?php echo ucfirst($project->status); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($project->created_at)); ?></td>
                                    <td><?php echo $project->quotation_count; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for Pre-Projects -->
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['projects_pagination']['page']; ?> of <?php echo $data['projects_pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['projects_pagination']['total']; ?> pre-projects</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['projects_pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=1&quotations_page=<?php echo $data['quotations_pagination']['page']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['page']-1; ?>&quotations_page=<?php echo $data['quotations_pagination']['page']; ?>" class="pagination-item">
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
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $i; ?>&quotations_page=<?php echo $data['quotations_pagination']['page']; ?>" 
                               class="pagination-item <?php echo ($i == $data['projects_pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['projects_pagination']['page'] < $data['projects_pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['page']+1; ?>&quotations_page=<?php echo $data['quotations_pagination']['page']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['total_pages']; ?>&quotations_page=<?php echo $data['quotations_pagination']['page']; ?>" class="pagination-item">
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
                </div>

                <!-- Recent Quotations Section -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Recent Quotations</h3>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Package</th>
                                    <th>Status</th>
                                    <th>Submitted Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['recent_quotations'] as $quotation): ?>
                                <tr>
                                    <td><?php echo $quotation->quotation_id; ?></td>
                                    <td><?php echo $quotation->customer_name; ?></td>
                                    <td><?php echo $quotation->package_name ? $quotation->package_name : 'Custom'; ?></td>
                                    <td><span class="status-pill quotation-<?php echo $quotation->status; ?>"><?php echo str_replace('_', ' ', ucfirst($quotation->status)); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($quotation->created_at)); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for Quotations -->
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['quotations_pagination']['page']; ?> of <?php echo $data['quotations_pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['quotations_pagination']['total']; ?> quotations</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['quotations_pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['page']; ?>&quotations_page=1" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['page']; ?>&quotations_page=<?php echo $data['quotations_pagination']['page']-1; ?>" class="pagination-item">
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
                        $startPage = max(1, $data['quotations_pagination']['page'] - 2);
                        $endPage = min($data['quotations_pagination']['total_pages'], $startPage + 4);
                        // Adjust start page if we're near the end
                        if($endPage - $startPage < 4) {
                            $startPage = max(1, $endPage - 4);
                        }
                        
                        for($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['page']; ?>&quotations_page=<?php echo $i; ?>" 
                               class="pagination-item <?php echo ($i == $data['quotations_pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['quotations_pagination']['page'] < $data['quotations_pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['page']; ?>&quotations_page=<?php echo $data['quotations_pagination']['page']+1; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/preProjects?projects_page=<?php echo $data['projects_pagination']['page']; ?>&quotations_page=<?php echo $data['quotations_pagination']['total_pages']; ?>" class="pagination-item">
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
        
        // Table search functionality
        document.getElementById('projectSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('preProjectsTable');
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
            // Chart 1: Pre-Projects by Status
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
            
            // Chart 2: Active Pre-Projects by Phase
            const phaseCtx = document.getElementById('phaseChart').getContext('2d');
            const phaseChart = new Chart(phaseCtx, {
                type: 'pie',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['phase_stats'] as $stat) {
                                echo "'" . ucfirst($stat->current_phase) . "', ";
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
                            '#FFC107', // Quotation - Amber
                            '#9C27B0', // Site Visit - Purple
                            '#00BCD4'  // Agreement - Cyan
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
            
            // Chart 3: Quotations by Status
            const quotationCtx = document.getElementById('quotationChart').getContext('2d');
            const quotationChart = new Chart(quotationCtx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['quotation_stats'] as $stat) {
                                echo "'" . str_replace('_', ' ', ucfirst($stat->status)) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        label: 'Quotations',
                        data: [
                            <?php 
                                foreach($data['stats']['quotation_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: '#3F51B5',
                        borderWidth: 1
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
            
            // Chart 4: Monthly Pre-Projects
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
                        label: 'Pre-Projects',
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
        });
        
        // Print functionality
        document.getElementById('printReportBtn').addEventListener('click', function() {
            window.print();
        });
    </script>
</body>
</html>