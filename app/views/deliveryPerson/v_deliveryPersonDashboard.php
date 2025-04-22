<?php require APPROOT . '/views/deliveryPerson/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/deliveryPerson/dashboard.css">

</head>

<body>

    <div class="dashboard-container">

        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT ?>/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/deliveryPerson/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/deliveryPerson/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">

            <div class="container">

                <section class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">local_shipping</span>
                            <h2 class="card-title">Delivery 1</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Keels Installation</h2>
                        <h3 class="card-value">Delivery Details:</h3>
                        <h4>Delivered solar panels and inverter for installation.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">local_shipping</span>
                            <h2 class="card-title">Delivery 2</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Watawala Industries Maintenance</h2>
                        <h3 class="card-value">Delivery Details:</h3>
                        <h4>Delivered maintenance kit and inverter update tools.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">local_shipping</span>
                            <h2 class="card-title">Delivery 3</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>DB Ltd Repair</h2>
                        <h3 class="card-value">Delivery Details:</h3>
                        <h4>Replacement part for solar inverter pending delivery.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">local_shipping</span>
                            <h2 class="card-title">Delivery 4</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Residential Battery Installation</h2>
                        <h3 class="card-value">Delivery Details:</h3>
                        <h4>Delivered battery storage system for installation.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">local_shipping</span>
                            <h2 class="card-title">Delivery 5</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>City Mall Site Survey</h2>
                        <h3 class="card-value">Delivery Details:</h3>
                        <h4>Delivered survey equipment and documentation for site survey.</h4>
                    </div>
                </section>


            </div>
        </div>
    </div>

    <div class="överlay" id="overlay"></div>




<script src="<?php echo URLROOT; ?>/js/deliveryPerson/dashboard.js"></script>

<?php require APPROOT . '/views/deliveryPerson/footer.php'; ?>