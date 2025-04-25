<?php require APPROOT.'/views/chiefCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/employees.css">
</head>

<body>
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
                    <p>Chief Coordinator</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/preProjects">
                <span class="material-icons-sharp">assignment</span>
                <h3>Pre Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/projects">
                <span class="material-icons-sharp">business_center</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/payments">
                <span class="material-icons-sharp">payments</span>
                <h3>Payments</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/employees" class="active">
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
            <a href="<?php echo URLROOT ?>/chiefCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="content-wrapper">
            <div class="main-content">
                <div class="page-header">
                    <h1>Employee Information</h1>
                    <div class="header-actions">
                        <button id="printReportBtn" class="btn">
                            <span class="material-icons-sharp">print</span> Print Report
                        </button>
                        <form id="generateReportForm" action="<?php echo URLROOT; ?>/chiefCoordinator/generateEmployeeReport" method="post">
                            <button type="submit" class="btn">
                                <span class="material-icons-sharp">description</span> Generate Report
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Timeframe Filter -->
                <div class="filter-section">
                    <div class="filter-group">
                        <label for="timeframeFilter">Timeframe:</label>
                        <select id="timeframeFilter" onchange="applyFilters()">
                            <option value="all" <?php echo $data['current_timeframe'] == 'all' ? 'selected' : ''; ?>>All Time</option>
                            <option value="today" <?php echo $data['current_timeframe'] == 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo $data['current_timeframe'] == 'week' ? 'selected' : ''; ?>>This Week</option>
                            <option value="month" <?php echo $data['current_timeframe'] == 'month' ? 'selected' : ''; ?>>This Month</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="roleFilter">Role:</label>
                        <select id="roleFilter" onchange="applyFilters()">
                            <option value="all" <?php echo $data['current_role'] == 'all' ? 'selected' : ''; ?>>All Roles</option>
                            <option value="technician" <?php echo $data['current_role'] == 'technician' ? 'selected' : ''; ?>>Technician</option>
                            <option value="deliveryPerson" <?php echo $data['current_role'] == 'deliveryPerson' ? 'selected' : ''; ?>>Delivery Person</option>
                            <option value="engineer" <?php echo $data['current_role'] == 'engineer' ? 'selected' : ''; ?>>Engineer</option>
                            <option value="clerk" <?php echo $data['current_role'] == 'clerk' ? 'selected' : ''; ?>>Clerk</option>
                        </select>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="summary-cards">
                    <div class="card">
                        <div class="card-inner">
                            <h3>Total Employees</h3>
                            <span class="material-icons-sharp">group</span>
                        </div>
                        <h1><?php echo $data['total_employees']; ?></h1>
                    </div>
                    <div class="card present-employees">
                        <div class="card-inner">
                            <h3>Present Today</h3>
                            <span class="material-icons-sharp">how_to_reg</span>
                        </div>
                        <h1><?php echo $data['present_today']; ?></h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>On Leave</h3>
                            <span class="material-icons-sharp">event_busy</span>
                        </div>
                        <h1>
                            <?php 
                                $totalLeaves = 0;
                                foreach($data['stats']['leave_stats'] as $stat) {
                                    $totalLeaves += $stat->count;
                                }
                                echo $totalLeaves;
                            ?>
                        </h1>
                    </div>

                </div>

                <!-- Charts Section -->
                <div class="charts-container">
                    <div class="chart-card">
                        <h3>Employees by Role</h3>
                        <div class="chart-wrapper">
                            <canvas id="roleChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Daily Attendance</h3>
                        <div class="chart-wrapper">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Leave Types</h3>
                        <div class="chart-wrapper">
                            <canvas id="leaveChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Employees Table -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Employee Directory</h3>
                        <div class="search">
                            <input type="text" id="employeeSearch" placeholder="Search employees...">
                            <span class="material-icons-sharp">search</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="employeesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Attendance</th>
                                    <th>Leaves</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['employees'])): ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        No employees found. Debug info: 
                                        <?php 
                                            echo "Total: " . (isset($data['total_employees']) ? $data['total_employees'] : 'N/A') . ", "; 
                                            echo "Page: " . (isset($data['employees_pagination']['page']) ? $data['employees_pagination']['page'] : 'N/A') . ", ";
                                            echo "Role Filter: " . (isset($data['current_role']) ? $data['current_role'] : 'N/A');
                                        ?>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach($data['employees'] as $employee): ?>
                                <tr>
                                    <td><?php echo $employee->employee_id; ?></td>
                                    <td><?php echo htmlspecialchars($employee->name ?? 'N/A'); ?></td>
                                    <td><span class="role-pill role-<?php echo $employee->employee_role; ?>"><?php echo ucfirst($employee->employee_role); ?></span></td>
                                    <td><?php echo htmlspecialchars($employee->email ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($employee->phone ?? 'N/A'); ?></td>
                                    <td><?php echo $employee->attendance_count ?? 0; ?> days</td>
                                    <td><?php echo $employee->leave_count ?? 0; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for Employees -->
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['employees_pagination']['page']; ?> of <?php echo $data['employees_pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['employees_pagination']['total']; ?> employees</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['employees_pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=1&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']-1; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
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
                        $startPage = max(1, $data['employees_pagination']['page'] - 2);
                        $endPage = min($data['employees_pagination']['total_pages'], $startPage + 4);
                        // Adjust start page if we're near the end
                        if($endPage - $startPage < 4) {
                            $startPage = max(1, $endPage - 4);
                        }
                        
                        for($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $i; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" 
                               class="pagination-item <?php echo ($i == $data['employees_pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['employees_pagination']['page'] < $data['employees_pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']+1; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['total_pages']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
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

                <!-- Recent Attendance Table -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Recent Attendance</h3>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Role</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['attendance'] as $attendance): ?>
                                <tr>
                                    <td><?php echo $attendance->name; ?></td>
                                    <td><span class="role-pill role-<?php echo $attendance->role; ?>"><?php echo ucfirst($attendance->role); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($attendance->date)); ?></td>
                                    <td><?php echo $attendance->time_in; ?></td>
                                    <td><?php echo $attendance->time_out ? $attendance->time_out : 'Not Checked Out'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for Attendance -->
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['attendance_pagination']['page']; ?> of <?php echo $data['attendance_pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['attendance_pagination']['total']; ?> records</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['attendance_pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=1&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']-1; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
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
                        $startPage = max(1, $data['attendance_pagination']['page'] - 2);
                        $endPage = min($data['attendance_pagination']['total_pages'], $startPage + 4);
                        // Adjust start page if we're near the end
                        if($endPage - $startPage < 4) {
                            $startPage = max(1, $endPage - 4);
                        }
                        
                        for($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $i; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" 
                               class="pagination-item <?php echo ($i == $data['attendance_pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['attendance_pagination']['page'] < $data['attendance_pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']+1; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['total_pages']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']; ?>" class="pagination-item">
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
                
                <!-- Leave Records Table -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Recent Leave Requests</h3>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['leaves'] as $leave): ?>
                                <tr>
                                    <td><?php echo $leave->name; ?></td>
                                    <td><?php echo ucfirst($leave->leave_type); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($leave->start_date)); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($leave->end_date)); ?></td>
                                    <td><?php echo $leave->number_of_days; ?></td>
                                    <td><span class="status-pill status-<?php echo strtolower($leave->status); ?>"><?php echo $leave->status; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for Leaves -->
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['leaves_pagination']['page']; ?> of <?php echo $data['leaves_pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['leaves_pagination']['total']; ?> records</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['leaves_pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=1" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']-1; ?>" class="pagination-item">
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
                        $startPage = max(1, $data['leaves_pagination']['page'] - 2);
                        $endPage = min($data['leaves_pagination']['total_pages'], $startPage + 4);
                        // Adjust start page if we're near the end
                        if($endPage - $startPage < 4) {
                            $startPage = max(1, $endPage - 4);
                        }
                        
                        for($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $i; ?>" 
                               class="pagination-item <?php echo ($i == $data['leaves_pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['leaves_pagination']['page'] < $data['leaves_pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['page']+1; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/employees?page=<?php echo $data['employees_pagination']['page']; ?>&role=<?php echo $data['current_role']; ?>&timeframe=<?php echo $data['current_timeframe']; ?>&attendance_page=<?php echo $data['attendance_pagination']['page']; ?>&leave_page=<?php echo $data['leaves_pagination']['total_pages']; ?>" class="pagination-item">
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
        document.getElementById('employeeSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('employeesTable');
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
        
        // Apply filters function
        function applyFilters() {
            const timeframe = document.getElementById('timeframeFilter').value;
            const role = document.getElementById('roleFilter').value;
            
            window.location.href = `<?php echo URLROOT; ?>/chiefCoordinator/employees?page=1&role=${role}&timeframe=${timeframe}&attendance_page=1&leave_page=1`;
        }
        
        // Chart.js implementations
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Employees by Role
            const roleCtx = document.getElementById('roleChart').getContext('2d');
            const roleChart = new Chart(roleCtx, {
                type: 'pie',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['role_stats'] as $stat) {
                                echo "'" . ucfirst($stat->role) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($data['stats']['role_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: [
                            '#4CAF50', // Green
                            '#2196F3', // Blue
                            '#F44336', // Red
                            '#FF9800'  // Orange
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
            
            // Chart 2: Daily Attendance
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php 
                            foreach(array_reverse($data['stats']['attendance_stats']) as $stat) {
                                echo "'" . date('M d', strtotime($stat->date)) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        label: 'Present Employees',
                        data: [
                            <?php 
                                foreach(array_reverse($data['stats']['attendance_stats']) as $stat) {
                                    echo $stat->present_count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: '#8BC34A',
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
            
            // Chart 3: Leave Types
            const leaveCtx = document.getElementById('leaveChart').getContext('2d');
            const leaveChart = new Chart(leaveCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['leave_stats'] as $stat) {
                                echo "'" . ucfirst($stat->leave_type) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($data['stats']['leave_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: [
                            '#9C27B0', // Purple
                            '#00BCD4', // Cyan
                            '#FFEB3B', // Yellow
                            '#607D8B'  // Blue Grey
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