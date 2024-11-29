<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/inventory.css"> -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/view_products.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="manager profile-picture" class="profile-picture" />
            <a href="<?php echo APPROOT; ?>/views/supplierCoordinator/v_dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="#" class="active">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="page-header">

                <a href="<?php echo URLROOT; ?>/supplierCoordinator/inventory" class="btn back-button">
                    <!-- <span class="material-icons-sharp">arrow_back</span> -->
                    <i class="fas fa-arrow-left"></i> Back to Inventory
                </a>
                <h1>Product Details</h1>
            </div>


            <div class="product-details-container">
                <div class="product-details">
                    <div class="info-row">
                        <label>Name:</label>
                        <span><?php echo $data['product']->product_name; ?></span>
                    </div>
                    <div class="info-row">
                        <label>Supplier:</label>
                        <span><?php echo $data['product']->supplier_name; ?></span>
                    </div>
                    <div class="info-row">
                        <label>Price:</label>
                        <span><?php echo $data['product']->price; ?></span>
                    </div>
                    <div class="info-row">
                        <label>Quantity:</label>
                        <span><?php echo $data['product']->quantity; ?></span>
                    </div>
                    <div class="info-row">
                        <label>Description:</label>
                        <span><?php echo $data['product']->description; ?></span>
                    </div>
                    <div class="info-row">
                        <label>Blog Link:</label>
                        <span><a href="<?php echo $data['product']->blog_link; ?>" target="_blank"><?php echo $data['product']->blog_link; ?></a></span>
                    </div>
                </div>

                <div class="product-image">
                    <img src="<?php echo URLROOT . '/public/assets/images/' . $data['product']->image; ?>" alt="Product Image">
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/suppliers.js"></script>
    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/status.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>