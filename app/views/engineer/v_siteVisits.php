<?php require APPROOT . '/views/engineer/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/engineer/siteVisits.css">

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
            <a href="<?php echo URLROOT ?>/engineer/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/engineer/siteVisits" class="active">
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
                <div class="page-header">
                    <h1>Pending Site Visits</h1>
                    <?php flash('site_visit_message'); ?>
                </div>

                <div class="site-visits-container">
                <?php if(empty($data['pendingVisits'])): ?>
                    <div class="no-visits">
                        <span class="material-icons-sharp">calendar_month</span>
                        <p>You don't have any pending site visits at the moment.</p>
                        <div class="info-text">
                            Site visits are scheduled by the Operations Coordinator and will appear here when they're ready for your attention.
                        </div>
                        <button class="refresh-btn" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt"></i> Refresh Page
                        </button>
                    </div>
                    <?php else: ?>
                        <div class="visit-cards">
                            <?php foreach($data['pendingVisits'] as $visit): ?>
                                <div class="visit-card">
                                    <div class="visit-info">
                                        <div class="customer-details">
                                            <h3><?php echo $visit->customer_name; ?></h3>
                                            <p><i class="fas fa-phone"></i> <?php echo $visit->phone; ?></p>
                                            <p><i class="fas fa-map-marker-alt"></i> <?php echo $visit->nearest_city; ?></p>
                                        </div>
                                        <div class="visit-details">
                                            <p><i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($visit->visit_date)); ?></p>
                                            <p><i class="fas fa-clock"></i> <?php echo date('h:i A', strtotime($visit->visit_time)); ?></p>
                                            <span class="status-badge <?php echo $visit->status; ?>">
                                                <?php echo ucfirst($visit->status); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="visit-actions">
                                        <a href="<?php echo URLROOT; ?>/engineer/manageSiteVisit/<?php echo $visit->visit_id; ?>" class="btn-primary">
                                            <i class="fas fa-clipboard-check"></i> Manage Visit
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<script src="<?php echo URLROOT; ?>/js/engineer/siteVisits.js"></script>
<?php require APPROOT . '/views/engineer/footer.php'; ?>