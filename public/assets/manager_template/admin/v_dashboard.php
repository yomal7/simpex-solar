<?php require APPROOT.'/views/admin/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                
                src="userimg.jpg"
                alt="manager profile-picture"
                class="profile-picture"
            />
            <a href="#" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">person</span>
                <h3>Customers</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="#">
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
            <div class="container">

            </div>
        </div>
    </div>

    <script src="<?php echo URLROOT; ?>/js/admin/dashboard.js"></script>
<?php require APPROOT.'/views/admin/footer.php';?>