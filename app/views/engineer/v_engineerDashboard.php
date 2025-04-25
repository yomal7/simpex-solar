<?php require APPROOT . '/views/engineer/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/dashboard.css">

</head>

<body>
    <div class="dashboard-container">

        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
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
            <a href="<?php echo URLROOT ?>/engineer/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/siteVisits">
                <span class="material-icons-sharp">location_on</span>
                <h3>Site Visits</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/settings">
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
                            <span class="material-icons card-icon">verified</span>
                            <h2 class="card-title">Certification 1</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Keels Installation</h2>
                        <h3 class="card-value">Certification Details:</h3>
                        <h4>Certified solar panel system for installation after successful site inspection.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">verified</span>
                            <h2 class="card-title">Certification 2</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Watawala Industries Maintenance</h2>
                        <h3 class="card-value">Certification Details:</h3>
                        <h4>Certified maintenance tasks after completing site inspection and inverter updates.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">verified</span>
                            <h2 class="card-title">Certification 3</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>DB Ltd Repair</h2>
                        <h3 class="card-value">Certification Details:</h3>
                        <h4>Approved inverter repair solution after site inspection and issue analysis.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">verified</span>
                            <h2 class="card-title">Certification 4</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Residential Battery Installation</h2>
                        <h3 class="card-value">Certification Details:</h3>
                        <h4>Certified battery storage system after inspection of the installation site.</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">verified</span>
                            <h2 class="card-title">Certification 5</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>City Mall Site Survey</h2>
                        <h3 class="card-value">Certification Details:</h3>
                        <h4>Approved site survey results for future solar panel installation after site visit.</h4>
                    </div>
                </section>



            </div>
        </div>
    </div>
    <div class="överlay" id="overlay"></div>



<script src="<?php echo URLROOT; ?>/js/engineer/dashboard.js"></script>

<?php require APPROOT . '/views/engineer/footer.php'; ?>