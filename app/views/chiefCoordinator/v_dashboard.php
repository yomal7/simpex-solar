<?php require APPROOT . '/views/chiefCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/dashboard.css">

<div class="dashboard-container">
    <h1>Chief Coordinator Dashboard</h1>
    
    <div class="action-buttons">
        <button id="printAllBtn" class="print-btn">
            <i class="icon-print"></i> Print All Reports
        </button>
    </div>

    <!-- Stats Overview -->
    <div class="stats-overview">
        <div class="stats-card">
            <div class="stats-icon project-icon">
                <i class="icon-project"></i>
            </div>
            <div class="stats-content">
                <h3>Projects</h3>
                <div class="stats-numbers">
                    <p class="stats-main"><?php echo $data['projectStats']['total']; ?></p>
                    <p class="stats-sub"><?php echo $data['projectStats']['active']; ?> Active</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-icon payment-icon">
                <i class="icon-payment"></i>
            </div>
            <div class="stats-content">
                <h3>Payments</h3>
                <div class="stats-numbers">
                    <p class="stats-main">Rs. <?php echo number_format($data['paymentStats']['total']); ?></p>
                    <p class="stats-sub">Rs. <?php echo number_format($data['paymentStats']['this_month']); ?> This Month</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-icon store-icon">
                <i class="icon-store"></i>
            </div>
            <div class="stats-content">
                <h3>Store</h3>
                <div class="stats-numbers">
                    <p class="stats-main"><?php echo $data['storeStats']['total_orders']; ?> Orders</p>
                    <p class="stats-sub">Rs. <?php echo number_format($data['storeStats']['total_revenue']); ?> Revenue</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-icon employee-icon">
                <i class="icon-employee"></i>
            </div>
            <div class="stats-content">
                <h3>Employees</h3>
                <div class="stats-numbers">
                    <p class="stats-main"><?php echo $data['employeeStats']['total_employees']; ?> Total</p>
                    <p class="stats-sub"><?php echo $data['employeeStats']['present_today']; ?> Present Today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-grid">
        <!-- Projects Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Projects</h2>
                <div class="header-actions">
                    <button class="view-more-btn" onclick="location.href='<?php echo URLROOT; ?>/chiefCoordinator/projectDetails'">
                        View More
                    </button>
                    <button class="print-btn" onclick="printChart('projectsChart')">
                        <i class="icon-print"></i>
                    </button>
                </div>
            </div>
            <div class="section-content">
                <div class="chart-container">
                    <canvas id="projectsChart"></canvas>
                </div>
                <div class="project-stats">
                    <div class="stat-item">
                        <span class="stat-label">Active:</span>
                        <span class="stat-value"><?php echo $data['projectStats']['active']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Completed:</span>
                        <span class="stat-value"><?php echo $data['projectStats']['completed']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Cancelled:</span>
                        <span class="stat-value"><?php echo $data['projectStats']['cancelled']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payments Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Payments</h2>
                <div class="header-actions">
                    <button class="view-more-btn" onclick="location.href='<?php echo URLROOT; ?>/chiefCoordinator/paymentDetails'">
                        View More
                    </button>
                    <button class="print-btn" onclick="printChart('paymentsChart')">
                        <i class="icon-print"></i>
                    </button>
                </div>
            </div>
            <div class="section-content">
                <div class="chart-container">
                    <canvas id="paymentsChart"></canvas>
                </div>
                <div class="payment-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total:</span>
                        <span class="stat-value">Rs. <?php echo number_format($data['paymentStats']['total']); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Pending:</span>
                        <span class="stat-value">Rs. <?php echo number_format($data['paymentStats']['pending']); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">This Month:</span>
                        <span class="stat-value">Rs. <?php echo number_format($data['paymentStats']['this_month']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Store Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Store</h2>
                <div class="header-actions">
                    <button class="view-more-btn" onclick="location.href='<?php echo URLROOT; ?>/chiefCoordinator/storeDetails'">
                        View More
                    </button>
                    <button class="print-btn" onclick="printChart('storeChart')">
                        <i class="icon-print"></i>
                    </button>
                </div>
            </div>
            <div class="section-content">
                <div class="chart-container">
                    <canvas id="storeChart"></canvas>
                </div>
                <div class="store-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total Orders:</span>
                        <span class="stat-value"><?php echo $data['storeStats']['total_orders']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Pending:</span>
                        <span class="stat-value"><?php echo $data['storeStats']['pending_orders']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Revenue:</span>
                        <span class="stat-value">Rs. <?php echo number_format($data['storeStats']['total_revenue']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Employees Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Employees</h2>
                <div class="header-actions">
                    <button class="view-more-btn" onclick="location.href='<?php echo URLROOT; ?>/chiefCoordinator/employeeDetails'">
                        View More
                    </button>
                    <button class="print-btn" onclick="printChart('employeesChart')">
                        <i class="icon-print"></i>
                    </button>
                </div>
            </div>
            <div class="section-content">
                <div class="chart-container">
                    <canvas id="employeesChart"></canvas>
                </div>
                <div class="employee-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total:</span>
                        <span class="stat-value"><?php echo $data['employeeStats']['total_employees']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Present Today:</span>
                        <span class="stat-value"><?php echo $data['employeeStats']['present_today']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Leave Requests:</span>
                        <span class="stat-value"><?php echo $data['employeeStats']['pending_leaves']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data from PHP
    const monthlyProjectStats = <?php echo json_encode(array_map(function($item) {
        return [
            'month' => $item->month,
            'count' => $item->count
        ];
    }, $data['monthlyProjectStats'])); ?>;
    
    const monthlyPaymentStats = <?php echo json_encode(array_map(function($item) {
        return [
            'month' => $item->month,
            'amount' => $item->amount
        ];
    }, $data['monthlyPaymentStats'])); ?>;
    
    const monthlyStoreStats = <?php echo json_encode(array_map(function($item) {
        return [
            'month' => $item->month,
            'order_count' => $item->order_count,
            'revenue' => $item->revenue
        ];
    }, $data['monthlyStoreStats'])); ?>;
    
    const attendanceStats = <?php echo json_encode(array_map(function($item) {
        return [
            'month' => $item->month,
            'employee_count' => $item->employee_count
        ];
    }, $data['attendanceStats'])); ?>;
    
    const leaveStats = <?php echo json_encode(array_map(function($item) {
        return [
            'month' => $item->month,
            'leave_count' => $item->leave_count
        ];
    }, $data['leaveStats'])); ?>;

    // Chart Functions
    function formatMonth(monthStr) {
        const [year, month] = monthStr.split('-');
        const date = new Date(year, month - 1);
        return date.toLocaleString('default', { month: 'short', year: 'numeric' });
    }
    
    function extractLabels(data, key = 'month') {
        return data.map(item => formatMonth(item[key]));
    }
    
    function extractValues(data, key) {
        return data.map(item => item[key]);
    }
    
    // Add this code right after your data declarations to debug
document.addEventListener('DOMContentLoaded', function() {
    // Debug data
    console.log('Monthly Project Stats:', monthlyProjectStats);
    console.log('Monthly Payment Stats:', monthlyPaymentStats);
    console.log('Monthly Store Stats:', monthlyStoreStats);
    console.log('Attendance Stats:', attendanceStats);
    console.log('Leave Stats:', leaveStats);
    
    // Check if canvas elements exist
    console.log('Projects Chart Canvas:', document.getElementById('projectsChart'));
    console.log('Payments Chart Canvas:', document.getElementById('paymentsChart'));
    console.log('Store Chart Canvas:', document.getElementById('storeChart'));
    console.log('Employees Chart Canvas:', document.getElementById('employeesChart'));
    
    // Modified chart initialization with error handling
    try {
        // Projects Chart
        if (document.getElementById('projectsChart')) {
            const projectCtx = document.getElementById('projectsChart').getContext('2d');
            
            // Make sure data is in the right format
            const projectLabels = monthlyProjectStats.map(item => formatMonth(item.month));
            const projectData = monthlyProjectStats.map(item => item.count);
            
            console.log('Project Chart Labels:', projectLabels);
            console.log('Project Chart Data:', projectData);
            
            const projectChart = new Chart(projectCtx, {
                type: 'line',
                data: {
                    labels: projectLabels,
                    datasets: [{
                        label: 'Projects Created',
                        data: projectData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        tension: 0.2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Project Creation'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Projects'
                            }
                        }
                    }
                }
            });
        }
        
        // Similar approach for other charts...
        // Payments Chart
        if (document.getElementById('paymentsChart')) {
            const paymentCtx = document.getElementById('paymentsChart').getContext('2d');
            
            const paymentLabels = monthlyPaymentStats.map(item => formatMonth(item.month));
            const paymentData = monthlyPaymentStats.map(item => item.amount);
            
            const paymentChart = new Chart(paymentCtx, {
                type: 'bar',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        label: 'Monthly Payments',
                        data: paymentData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Payment Revenue'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount (Rs.)'
                            }
                        }
                    }
                }
            });
        }
        
        // Store Chart
        if (document.getElementById('storeChart')) {
            const storeCtx = document.getElementById('storeChart').getContext('2d');
            
            const storeLabels = monthlyStoreStats.map(item => formatMonth(item.month));
            const storeOrderData = monthlyStoreStats.map(item => item.order_count);
            const storeRevenueData = monthlyStoreStats.map(item => item.revenue);
            
            const storeChart = new Chart(storeCtx, {
                type: 'line',
                data: {
                    labels: storeLabels,
                    datasets: [{
                        label: 'Order Count',
                        data: storeOrderData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        yAxisID: 'y',
                        tension: 0.2
                    }, {
                        label: 'Revenue',
                        data: storeRevenueData,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1',
                        tension: 0.2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Store Performance'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Order Count'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Revenue (Rs.)'
                            }
                        }
                    }
                }
            });
        }
        
        // Employees Chart
        if (document.getElementById('employeesChart')) {
            const employeeCtx = document.getElementById('employeesChart').getContext('2d');
            
            const employeeLabels = attendanceStats.map(item => formatMonth(item.month));
            const attendanceData = attendanceStats.map(item => item.employee_count);
            const leaveData = leaveStats.map(item => item.leave_count);
            
            const employeeChart = new Chart(employeeCtx, {
                type: 'bar',
                data: {
                    labels: employeeLabels,
                    datasets: [{
                        label: 'Attendance',
                        data: attendanceData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Leave Requests',
                        data: leaveData,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Employee Attendance & Leaves'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
});
    
    // Print all charts
    document.getElementById('printAllBtn').addEventListener('click', function() {
        window.location.href = '<?php echo URLROOT; ?>/chiefCoordinator/printDashboard';
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>