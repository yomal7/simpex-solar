<?php require APPROOT.'/views/client/header.php';?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/project.css">
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
            <li  class="active"><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li ><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
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
        <div class="container">
            <div class="timeline-container">
                <div class="timeline-progress-bar">
                    <div class="timeline-progress-fill"></div>
                </div>

                <div class="timeline-item completed" style="animation-delay: 0.2s;">
                    <div class="timeline-card">
                        <h3>Agreement Phase</h3>
                        <p>Agreement approving and signing</p>
                        <a href="<?php echo URLROOT; ?>/client/agreement" class="proceed-button">View Details</a>
                    </div>
                </div>

                <div class="timeline-item completed" style="animation-delay: 0.4s;">
                    <div class="timeline-card">
                        <h3>Site Visit Phase</h3>
                        <p>Schedule a date for site visit</p>
                        <a href="<?php echo URLROOT; ?>/client/sitevisit" class="proceed-button">View Details</a>
                    </div>
                </div>

                <div class="timeline-item active" style="animation-delay: 0.6s;">
                    <div class="timeline-card">
                        <h3>First Payment Phase</h3>
                        <p>Pay the 25% of total project cost</p>
                        <a href="<?php echo URLROOT; ?>/client/firstpayment" class="proceed-button">Proceed Now</a>
                    </div>
                </div>

                <div class="timeline-item future" style="animation-delay: 0.8s;">
                    <div class="timeline-card">
                        <h3>Installation Phase</h3>
                        <p>Schedule the date for installation</p>
                        <a href="<?php echo URLROOT; ?>/client/installation" class="proceed-button">View Phase</a>
                    </div>
                </div>

                <div class="timeline-item future" style="animation-delay: 1s;">
                    <div class="timeline-card">
                        <h3>Final Payment Phase</h3>
                        <p>Pay the remaining 75% of total project cost</p>
                        <a href="<?php echo URLROOT; ?>/client/finalpayment" class="proceed-button">View Phase</a>
                    </div>
                </div>
            </div>
        </div>
        </main>
    <script src="<?php echo URLROOT; ?>/js/client/project.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>