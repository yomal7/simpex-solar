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
                    <p>Engineer</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/engineer/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/siteVisits">
                <span class="material-icons-sharp">home</span>
                <h3>Site Visits</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/approvalProjects">
                <span class="material-icons-sharp">fact_check</span>
                <h3>Approvals</h3>
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
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <!-- Task Status Cards -->
               
                <h2>My Tasks Overview</h2>
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-inner">
                            <div class="card-status-button <?php echo strtolower($data['not_started']); ?>">
                                <?php echo str_replace('_', ' ', ucfirst($data['not_started'])); ?>
                            </div>
                            <span class="material-icons-sharp">new_releases</span>
                        </div>
                        <h1><?php echo $data['notStartedCount']; ?></h1>
                    </div>
                    
                    <div class="card">
                        <div class="card-inner">
                            <div class="card-status-button <?php echo strtolower($data['in_progress']); ?>">
                                <?php echo str_replace('_', ' ', ucfirst($data['in_progress'])); ?>
                            </div>
                            <span class="material-icons-sharp">sync</span>
                        </div>
                        <h1><?php echo $data['inProgressCount']; ?></h1>
                    </div>
                    
                    <div class="card">
                        <div class="card-inner">
                            <div class="card-status-button <?php echo strtolower($data['completed']); ?>">
                                <?php echo str_replace('_', ' ', ucfirst($data['completed'])); ?>
                            </div>
                            <span class="material-icons-sharp">check_circle</span>
                        </div>
                        <h1><?php echo $data['completedCount']; ?></h1>
                    </div>
                </div>

        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/engineer/dashboard.js"></script>

    <?php require APPROOT . '/views/engineer/footer.php'; ?>