<?php require APPROOT . '/views/clerk/header.php'; ?>


<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/clerk/dashboard.css">

</head>

<body>
    <div class="dashboard-container">

        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT ?>/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/clerk/dashboard" class="active">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/settings">
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
                            <span class="material-icons card-icon">work_outline</span>
                            <h2 class="card-title">Project 1</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Keels Installation</h2>
                        <h3 class="card-value">Tasks:</h3>
                        <h4>Install 5kW solar panel system</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">work_outline</span>
                            <h2 class="card-title">Project 2</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Watawala Industries Maintenance</h2>
                        <h3 class="card-value">Tasks:</h3>
                        <h4>Solar panel maintenance</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">work_outline</span>
                            <h2 class="card-title">Project 3</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>DB Ltd Repair</h2>
                        <h3 class="card-value">Tasks:</h3>
                        <h4>Repair solar inverter</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">work_outline</span>
                            <h2 class="card-title">Project 4</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>Residential Battery Installation</h2>
                        <h3 class="card-value">Tasks:</h3>
                        <h4>Install battery storage system</h4>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons card-icon">work_outline</span>
                            <h2 class="card-title">Project 5</h2>
                        </div>
                        <h3 class="card-value">Project Name:</h3>
                        <h2>City Mall Site Survey</h2>
                        <h3 class="card-value">Tasks:</h3>
                        <h4>Site survey at City Mall</h4>
                    </div>
                </section>


            </div>
        </div>
    </div>

    <div class="överlay" id="overlay"></div>


<script src="<?php echo URLROOT; ?>/js/clerk/dashboard.js"></script>
<?php require APPROOT . '/views/clerk/footer.php'; ?>