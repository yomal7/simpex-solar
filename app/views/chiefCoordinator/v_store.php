<!-- File: app/views/chiefCoordinator/v_store.php -->
<?php require APPROOT.'/views/chiefCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/store.css">
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
            <a href="<?php echo URLROOT ?>/chiefCoordinator/payments">
                <span class="material-icons-sharp">payments</span>
                <h3>Payments</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/employees">
                <span class="material-icons-sharp">people</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/store" class="active">
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
                    <h1>Store Information</h1>
                    <div class="header-actions">
                        <button id="printReportBtn" class="btn">
                            <span class="material-icons-sharp">print</span> Print Report
                        </button>
                        <form id="generateReportForm" action="<?php echo URLROOT; ?>/chiefCoordinator/generateStoreReport" method="post">
                            <input type="hidden" name="timeframe" value="<?php echo $data['timeframe']; ?>">
                            <button type="submit" class="btn">
                                <span class="material-icons-sharp">description</span> Generate Report
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Time Frame Filter -->
                <div class="filter-section">
                    <label for="timeframe">Time Frame:</label>
                    <select id="timeframe" onchange="changeTimeframe(this.value)">
                        <option value="all" <?php echo $data['timeframe'] == 'all' ? 'selected' : ''; ?>>All Time</option>
                        <option value="today" <?php echo $data['timeframe'] == 'today' ? 'selected' : ''; ?>>Today</option>
                        <option value="week" <?php echo $data['timeframe'] == 'week' ? 'selected' : ''; ?>>This Week</option>
                        <option value="month" <?php echo $data['timeframe'] == 'month' ? 'selected' : ''; ?>>This Month</option>
                        <option value="year" <?php echo $data['timeframe'] == 'year' ? 'selected' : ''; ?>>This Year</option>
                    </select>
                </div>

                <!-- Summary Cards -->
                <div class="summary-cards">
                    <div class="card">
                        <div class="card-inner">
                            <h3>Total Orders</h3>
                            <span class="material-icons-sharp">shopping_cart</span>
                        </div>
                        <h1>
                            <?php 
                                $totalOrders = 0;
                                foreach($data['stats']['order_status_stats'] as $stat) {
                                    $totalOrders += $stat->count;
                                }
                                echo $totalOrders;
                            ?>
                        </h1>
                    </div>
                    <div class="card active-projects">
                        <div class="card-inner">
                            <h3>Total Income</h3>
                            <span class="material-icons-sharp">payments</span>
                        </div>
                        <h1>
                            Rs. <?php 
                            // Make sure total_income is not null
                            $totalIncome = $data['stats']['income_stats']->total_income ?? 0;
                            echo number_format($totalIncome, 2); 
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Delivered Orders</h3>
                            <span class="material-icons-sharp">local_shipping</span>
                        </div>
                        <h1>
                            <?php 
                                $deliveredOrders = 0;
                                foreach($data['stats']['order_status_stats'] as $stat) {
                                    if($stat->status == 'delivered') {
                                        $deliveredOrders = $stat->count;
                                        break;
                                    }
                                }
                                echo $deliveredOrders;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <h3>Pending Orders</h3>
                            <span class="material-icons-sharp">pending_actions</span>
                        </div>
                        <h1>
                            <?php 
                                $pendingOrders = 0;
                                foreach($data['stats']['order_status_stats'] as $stat) {
                                    if($stat->status == 'pending') {
                                        $pendingOrders = $stat->count;
                                        break;
                                    }
                                }
                                echo $pendingOrders;
                            ?>
                        </h1>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-container">
                    <div class="chart-card">
                        <h3>Orders by Status</h3>
                        <div class="chart-wrapper">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Payment Methods</h3>
                        <div class="chart-wrapper">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <h3>Monthly Orders (This Year)</h3>
                        <div class="chart-wrapper">
                            <canvas id="monthlyOrdersChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="table-card">
                    <div class="card-header">
                        <h3>Recent Orders</h3>
                        <div class="search">
                            <input type="text" id="orderSearch" placeholder="Search orders...">
                            <span class="material-icons-sharp">search</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="ordersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['orders'] as $order): ?>
                                <tr>
                                    <td><?php echo $order->id; ?></td>
                                    <td><?php echo $order->customer_name; ?></td>
                                    <td><?php echo $order->product_name; ?></td>
                                    <td><?php echo $order->quantity; ?></td>
                                    <td>Rs. <?php echo number_format(($order->price * $order->quantity) + $order->delivery_fee - ($order->discount ?? 0), 2); ?></td>
                                    <td><span class="status-pill status-<?php echo str_replace(' ', '-', $order->status); ?>"><?php echo ucwords($order->status); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-info">
                        <span>Showing page <?php echo $data['pagination']['page']; ?> of <?php echo $data['pagination']['total_pages']; ?></span>
                        <span>Total: <?php echo $data['pagination']['total']; ?> orders</span>
                    </div>
                    <div class="pagination">
                        <?php if($data['pagination']['page'] > 1): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/store?page=1&timeframe=<?php echo $data['timeframe']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">first_page</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/store?page=<?php echo $data['pagination']['page']-1; ?>&timeframe=<?php echo $data['timeframe']; ?>" class="pagination-item">
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
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/store?page=<?php echo $i; ?>&timeframe=<?php echo $data['timeframe']; ?>" 
                               class="pagination-item <?php echo ($i == $data['pagination']['page']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if($data['pagination']['page'] < $data['pagination']['total_pages']): ?>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/store?page=<?php echo $data['pagination']['page']+1; ?>&timeframe=<?php echo $data['timeframe']; ?>" class="pagination-item">
                                <span class="material-icons-sharp">chevron_right</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/chiefCoordinator/store?page=<?php echo $data['pagination']['total_pages']; ?>&timeframe=<?php echo $data['timeframe']; ?>" class="pagination-item">
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
        document.getElementById('orderSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('ordersTable');
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
        
        // Change timeframe and reload page
        function changeTimeframe(timeframe) {
            window.location.href = '<?php echo URLROOT; ?>/chiefCoordinator/store?timeframe=' + timeframe;
        }
        
        // Chart.js implementations
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Orders by Status
            // Chart 1: Orders by Status
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStatusLabels = [
                <?php 
                    if(!empty($data['stats']['order_status_stats'])) {
                        foreach($data['stats']['order_status_stats'] as $stat) {
                            echo "'" . ucwords(str_replace('_', ' ', $stat->status)) . "', ";
                        }
                    } else {
                        echo "'No Data'";
                    }
                ?>
            ];
            const orderStatusData = [
                <?php 
                    if(!empty($data['stats']['order_status_stats'])) {
                        foreach($data['stats']['order_status_stats'] as $stat) {
                            echo $stat->count . ", ";
                        }
                    } else {
                        echo "1"; // Show a placeholder value if no data
                    }
                ?>
            ];

            const orderStatusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: orderStatusLabels,
                    datasets: [{
                        data: orderStatusData,
                        backgroundColor: [
                            '#4CAF50', // Delivered - Green
                            '#2196F3', // Processing - Blue
                            '#FFC107', // Ready for pickup - Amber
                            '#9C27B0', // Out for delivery - Purple
                            '#F44336', // Cancelled - Red
                            '#FF9800', // Pending - Orange
                            '#607D8B'  // Rejected - Grey
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
            
            // Chart 2: Payment Methods
            const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
            const paymentLabels = [
                <?php 
                    if(!empty($data['stats']['payment_method_stats'])) {
                        foreach($data['stats']['payment_method_stats'] as $stat) {
                            echo "'" . ucwords(str_replace('_', ' ', $stat->payment_method)) . "', ";
                        }
                    } else {
                        echo "'No Data'";
                    }
                ?>
            ];
            const paymentData = [
                <?php 
                    if(!empty($data['stats']['payment_method_stats'])) {
                        foreach($data['stats']['payment_method_stats'] as $stat) {
                            echo $stat->count . ", ";
                        }
                    } else {
                        echo "1"; // Show a placeholder value if no data
                    }
                ?>
            ];

            const paymentMethodChart = new Chart(paymentCtx, {
                type: 'pie',
                data: {
                    labels: paymentLabels,
                    datasets: [{
                        data: paymentData,
                        backgroundColor: [
                            '#00BCD4', // Online - Cyan
                            '#8BC34A', // Cash - Light Green
                            '#FF5722'  // Bank Deposit - Deep Orange
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
            // Chart 3: Monthly Orders
            const monthlyCtx = document.getElementById('monthlyOrdersChart').getContext('2d');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Prepare data array for all 12 months (with 0 as default)
            const monthlyData = Array(12).fill(0);
            
            <?php foreach($data['stats']['monthly_stats'] as $stat): ?>
                // Adjust month value (database returns 1-12, array is 0-11)
                monthlyData[<?php echo $stat->month - 1; ?>] = <?php echo $stat->count; ?>;
            <?php endforeach; ?>
            
            const monthlyOrdersChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Orders',
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