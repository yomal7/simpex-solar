<?php require APPROOT.'/views/client/header.php';?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/clientShop.css">
</head>

<body>

    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <ul class="side-menu">
            <li ><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li ><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li class="active" ><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li ><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <!-- End of Navbar -->
         
        <main>
            
            <div class="header">
                <div class="left">
                    <h1>Project</h1>
                    <!-- <ul class="breadcrumb">
                        <li><a href="#">
                                Analytics
                            </a></li>
                        /
                        <li><a href="#" class="active">Shop</a></li>
                    </ul> -->
                </div>
            </div>


            <div class="container">
                <div class="dashboard-container">

                    <!-- Filters -->
                    <div class="order-filters">
                        <input type="text" class="filter-input" placeholder="Search orders..." id="orderSearch">
                        <select class="filter-input" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="pending_approval">Pending Approval</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <select class="filter-input" id="dateFilter">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>

                    <!-- Orders Table -->
                    <div class="orders-table-container">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Products</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Pending Approval Order -->
                                <tr>
                                    <td class="order-id">#ORD-2024-001</td>
                                    <td>Feb 25, 2024</td>
                                    <td>
                                        <div class="order-products">
                                            Premium Solar Panel 400W (x2)<br>
                                            Solar Inverter 5kW
                                        </div>
                                    </td>
                                    <td>Rs2,499.99</td>
                                    <td>
                                        <span class="status-badge status-pending">Pending Approval</span>
                                    </td>
                                    <td>
                                        <div class="order-actions">
                                            <button class="action-button action-view" onclick="viewOrder('ORD-2024-001')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button class="action-button action-cancel" onclick="cancelOrder('ORD-2024-001')">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Approved Order -->
                                <tr>
                                    <td class="order-id">#ORD-2024-002</td>
                                    <td>Feb 24, 2024</td>
                                    <td>
                                        <div class="order-products">
                                            Solar Panel Kit 600W<br>
                                            Mounting System
                                        </div>
                                    </td>
                                    <td>Rs. 3,299.99</td>
                                    <td>
                                        <span class="status-badge status-approved">Approved</span>
                                    </td>
                                    <td>
                                        <div class="order-actions">
                                            <button class="action-button action-pay" onclick="processPayment('ORD-2024-002')">
                                                <i class="fas fa-credit-card"></i> Pay Now
                                            </button>
                                            <button class="action-button action-view" onclick="viewOrder('ORD-2024-002')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button class="action-button action-download" onclick="downloadQuotation('ORD-2024-002')">
                                                <i class="fas fa-download"></i> Quotation
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Completed Order -->
                                <tr>
                                    <td class="order-id">#ORD-2024-003</td>
                                    <td>Feb 20, 2024</td>
                                    <td>
                                        <div class="order-products">
                                            Solar Inverter 3kW<br>
                                            Battery System
                                        </div>
                                    </td>
                                    <td>Rs. 4,199.99</td>
                                    <td>
                                        <span class="status-badge status-completed">Completed</span>
                                    </td>
                                    <td>
                                        <div class="order-actions">
                                            <button class="action-button action-view" onclick="viewOrder('ORD-2024-003')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button class="action-button action-download" onclick="downloadInvoice('ORD-2024-003')">
                                                <i class="fas fa-file-invoice"></i> Invoice
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="pagination">
                            <button class="page-button">Previous</button>
                            <button class="page-button active">1</button>
                            <button class="page-button">2</button>
                            <button class="page-button">3</button>
                            <button class="page-button">Next</button>
                        </div>
                    </div>
                </div>

                <!-- Cancel Order Modal -->
                <div class="modal" id="cancelModal">
                    <div class="modal-content">
                        <button class="modal-close" onclick="closeModal('cancelModal')">&times;</button>
                        <h2 class="modal-title">Cancel Order</h2>
                        <div class="modal-body">
                            <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                        </div>
                        <div class="modal-actions">
                            <button class="action-button" onclick="closeModal('cancelModal')">No, Keep Order</button>
                            <button class="action-button action-cancel" onclick="confirmCancel()">Yes, Cancel Order</button>
                        </div>
                    </div>
                </div>
        
            </div>

        </main>

    </div>

    <script src="<?php echo URLROOT; ?>/js/client/clientShop.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>