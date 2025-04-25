<?php require APPROOT.'/views/chiefCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/payments.css">
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
                    <p>HR Administrator</p>
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
            <a href="<?php echo URLROOT ?>/chiefCoordinator/payments" class="active">
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
                    <h1>Payment Information</h1>
                    <div class="header-actions">
                        <button id="printReportBtn" class="btn">
                            <span class="material-icons-sharp">print</span> Print Report
                        </button>
                        <form id="generateReportForm" action="<?php echo URLROOT; ?>/chiefCoordinator/generatePaymentReport" method="post">
                            <input type="hidden" name="timeframe" value="<?php echo $data['filters']['timeframe']; ?>">
                            <input type="hidden" name="payment_type" value="<?php echo $data['filters']['payment_type']; ?>">
                            <button type="submit" class="btn">
                                <span class="material-icons-sharp">description</span> Generate Report
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="filter-options">
                    <div class="filter-group">
                        <label for="timeframe">Time Period:</label>
                        <select id="timeframe" onchange="applyFilters()">
                            <option value="all" <?php echo $data['filters']['timeframe'] == 'all' ? 'selected' : ''; ?>>All Time</option>
                            <option value="today" <?php echo $data['filters']['timeframe'] == 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo $data['filters']['timeframe'] == 'week' ? 'selected' : ''; ?>>This Week</option>
                            <option value="month" <?php echo $data['filters']['timeframe'] == 'month' ? 'selected' : ''; ?>>This Month</option>
                            <option value="year" <?php echo $data['filters']['timeframe'] == 'year' ? 'selected' : ''; ?>>This Year</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="paymentType">Payment Type:</label>
                        <select id="paymentType" onchange="applyFilters()">
                            <option value="all" <?php echo $data['filters']['payment_type'] == 'all' ? 'selected' : ''; ?>>All Types</option>
                            <option value="project first payment" <?php echo $data['filters']['payment_type'] == 'project first payment' ? 'selected' : ''; ?>>Project First Payment</option>
                            <option value="project final payment" <?php echo $data['filters']['payment_type'] == 'project final payment' ? 'selected' : ''; ?>>Project Final Payment</option>
                            <option value="store payment" <?php echo $data['filters']['payment_type'] == 'store payment' ? 'selected' : ''; ?>>Store Payment</option>
                        </select>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="summary-cards">
                    <?php
                        // Initialize counters
                        $totalPayments = 0;
                        $totalAmount = 0;
                        $projectFirstPayments = 0;
                        $projectFinalPayments = 0;
                        $storePayments = 0;
                        
                        // Calculate totals from type stats
                        foreach($data['stats']['type_stats'] as $stat) {
                            $totalPayments += $stat->count;
                            $totalAmount += $stat->total_amount;
                            
                            if($stat->payment_type == 'project first payment') {
                                $projectFirstPayments = $stat->count;
                            } elseif($stat->payment_type == 'project final payment') {
                                $projectFinalPayments = $stat->count;
                            } elseif($stat->payment_type == 'store payment') {
                                $storePayments = $stat->count;
                            }
                        }
                    ?>
                    
                    <div class="card">
                        <div class="card-inner">
                            <h3>Total Payments</h3>
                            <span class="material-icons-sharp">payments</span>
                        </div>
                        <h1><?php echo $totalPayments; ?></h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Total Amount</h3>
                            <span class="material-icons-sharp">attach_money</span>
                        </div>
                        <h1>$<?php echo number_format($totalAmount, 2); ?></h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Project First Payments</h3>
                            <span class="material-icons-sharp">start</span>
                        </div>
                        <h1><?php echo $projectFirstPayments; ?></h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Project Final Payments</h3>
                            <span class="material-icons-sharp">done_all</span>
                        </div>
                        <h1><?php echo $projectFinalPayments; ?></h1>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-container">
                    <div class="chart-card">
                        <h3>Payments by Type</h3>
                        <div class="chart-wrapper">
                            <canvas id="paymentTypeChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Daily Payments (Last 7 Days)</h3>
                        <div class="chart-wrapper">
                            <canvas id="dailyPaymentsChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Monthly Payments (This Year)</h3>
                        <div class="chart-wrapper">
                            <canvas id="monthlyPaymentsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Payment History</h3>
                        <div class="search">
                            <input type="text" id="paymentSearch" placeholder="Search payments...">
                            <span class="material-icons-sharp">search</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Related Project/Order</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['payments'] as $payment): ?>
                                <tr>
                                    <td><?php echo $payment->id; ?></td>
                                    <td><?php echo $payment->customer_name; ?></td>
                                    <td>$<?php echo number_format($payment->amount, 2); ?></td>
                                    <td><span class="payment-type <?php echo str_replace(' ', '-', $payment->payment_type); ?>"><?php echo ucwords($payment->payment_type); ?></span></td>
                                    <td>
                                        <?php if($payment->payment_type == 'store payment'): ?>
                                            Order #<?php echo $payment->order_id; ?>
                                        <?php else: ?>
                                            Project #<?php echo $payment->project_id; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($payment->created_at)); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['pagination']['page']; ?> of <?php echo $data['pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['pagination']['total']; ?> payments</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/payments?page=1&timeframe=<?php echo $data['filters']['timeframe']; ?>&payment_type=<?php echo $data['filters']['payment_type']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/payments?page=<?php echo $data['pagination']['page']-1; ?>&timeframe=<?php echo $data['filters']['timeframe']; ?>&payment_type=<?php echo $data['filters']['payment_type']; ?>" class="pagination-item">
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
                        $startPage = max(1, $data['pagination']['page'] - 2);
                        $endPage = min($data['pagination']['total_pages'], $startPage + 4);
                        // Adjust start page if we're near the end
                        if($endPage - $startPage < 4) {
                            $startPage = max(1, $endPage - 4);
                        }
                        
                        for($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/payments?page=<?php echo $i; ?>&timeframe=<?php echo $data['filters']['timeframe']; ?>&payment_type=<?php echo $data['filters']['payment_type']; ?>" 
                               class="pagination-item <?php echo ($i == $data['pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['pagination']['page'] < $data['pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/payments?page=<?php echo $data['pagination']['page']+1; ?>&timeframe=<?php echo $data['filters']['timeframe']; ?>&payment_type=<?php echo $data['filters']['payment_type']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/payments?page=<?php echo $data['pagination']['total_pages']; ?>&timeframe=<?php echo $data['filters']['timeframe']; ?>&payment_type=<?php echo $data['filters']['payment_type']; ?>" class="pagination-item">
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
        
        // Apply filters function
        function applyFilters() {
            const timeframe = document.getElementById('timeframe').value;
            const paymentType = document.getElementById('paymentType').value;
            
            // Update hidden form fields for report generation
            document.querySelector('input[name="timeframe"]').value = timeframe;
            document.querySelector('input[name="payment_type"]').value = paymentType;
            
            // Redirect with filter parameters
            window.location.href = `<?php echo URLROOT; ?>/chiefCoordinator/payments?page=1&timeframe=${timeframe}&payment_type=${paymentType}`;
        }
        
        // Table search functionality
        document.getElementById('paymentSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('paymentsTable');
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
        
        // Print functionality
        document.getElementById('printReportBtn').addEventListener('click', function() {
            window.print();
        });
        
        // Chart.js implementations
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Payments by Type
            const typeCtx = document.getElementById('paymentTypeChart').getContext('2d');
            const typeChart = new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['type_stats'] as $stat) {
                                echo "'" . ucwords($stat->payment_type) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        data: [
                            <?php 
                                foreach($data['stats']['type_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: [
                            '#4CAF50', // Project First Payment - Green
                            '#2196F3', // Project Final Payment - Blue
                            '#FF9800'  // Store Payment - Orange
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
            
            // Chart 2: Daily Payments (Last 7 Days)
            const dailyCtx = document.getElementById('dailyPaymentsChart').getContext('2d');
            const dailyChart = new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php 
                            foreach($data['stats']['daily_stats'] as $stat) {
                                echo "'" . date('M d', strtotime($stat->date)) . "', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        label: 'Payment Count',
                        data: [
                            <?php 
                                foreach($data['stats']['daily_stats'] as $stat) {
                                    echo $stat->count . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: '#3F51B5',
                        borderWidth: 1
                    }, {
                        label: 'Payment Amount ($)',
                        data: [
                            <?php 
                                foreach($data['stats']['daily_stats'] as $stat) {
                                    echo $stat->total_amount . ", ";
                                }
                            ?>
                        ],
                        backgroundColor: '#E91E63',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount ($)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
            
            // Chart 3: Monthly Payments (This Year)
            const monthlyCtx = document.getElementById('monthlyPaymentsChart').getContext('2d');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Prepare data arrays for all 12 months (with 0 as default)
            const monthlyCountData = Array(12).fill(0);
            const monthlyAmountData = Array(12).fill(0);
            
            <?php foreach($data['stats']['monthly_stats'] as $stat): ?>
                // Adjust month value (database returns 1-12, array is 0-11)
                monthlyCountData[<?php echo $stat->month - 1; ?>] = <?php echo $stat->count; ?>;
                monthlyAmountData[<?php echo $stat->month - 1; ?>] = <?php echo $stat->total_amount; ?>;
            <?php endforeach; ?>
            
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Payment Count',
                        data: monthlyCountData,
                        backgroundColor: 'rgba(63, 81, 181, 0.2)',
                        borderColor: '#3F51B5',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true,
                        yAxisID: 'y'
                    }, {
                        label: 'Payment Amount ($)',
                        data: monthlyAmountData,
                        backgroundColor: 'rgba(233, 30, 99, 0.2)',
                        borderColor: '#E91E63',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount ($)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>