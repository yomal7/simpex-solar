<?php require APPROOT.'/views/client/header.php';?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/shop.css">

</head>
<body>
    <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>


    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a></li>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <div class="container">
        <h1 class="page-title">My Orders</h1>
        
        <div class="orders-grid">
            <!-- Order Card 1 -->
            <div class="order-card" style="animation-delay: 0.1s;">
                <div class="order-header">
                    <span class="order-number">#ORD-2024-001</span>
                    <span class="order-date">March 15, 2024</span>
                </div>
                <span class="order-status status-approved">Approved</span>
                <div class="order-items">
                    <p>Solar Panel Kit - 400W</p>
                    <p>Inverter - 1000W</p>
                    <p>Mounting System</p>
                </div>
                <div class="order-total">
                    $1,299.99
                </div>
                <div class="action-buttons">
                    <button class="btn btn-view">View Details</button>
                    <button class="btn btn-delete">Delete</button>
                </div>
            </div>

            <!-- Order Card 2 -->
            <div class="order-card" style="animation-delay: 0.2s;">
                <div class="order-header">
                    <span class="order-number">#ORD-2024-002</span>
                    <span class="order-date">March 18, 2024</span>
                </div>
                <span class="order-status status-pending">Pending</span>
                <div class="order-items">
                    <p>Battery Bank - 5kWh</p>
                    <p>Charge Controller</p>
                </div>
                <div class="order-total">
                    $2,499.99
                </div>
                <div class="action-buttons">
                    <button class="btn btn-view">View Details</button>
                    <button class="btn btn-delete">Delete</button>
                </div>
            </div>

            <!-- Order Card 3 -->
            <div class="order-card" style="animation-delay: 0.3s;">
                <div class="order-header">
                    <span class="order-number">#ORD-2024-003</span>
                    <span class="order-date">March 20, 2024</span>
                </div>
                <span class="order-status status-canceled">Canceled</span>
                <div class="order-items">
                    <p>Solar Panel Kit - 600W</p>
                    <p>Installation Kit</p>
                </div>
                <div class="order-total">
                    $1,899.99
                </div>
                <div class="action-buttons">
                    <button class="btn btn-view">View Details</button>
                    <button class="btn btn-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>



        <script src="<?php echo URLROOT; ?>/js/client/shop.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>