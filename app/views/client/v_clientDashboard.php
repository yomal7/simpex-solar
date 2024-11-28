<?php require APPROOT.'/views/client/header.php';?>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/dashboard.css">

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
            <li class="active" ><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li ><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li ><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li ><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href=<?php echo URLROOT; ?>/users/logout class="logout">
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
                    <h1>Dashboard</h1>
                </div>
            </div>

            <!-- Insights -->
                <!-- header bar -->

                <div class="header-container">
                    <div class="profile-card">
                        <div class="background-image"></div>
                        <div class="overlay"></div>
                        <div class="profile-content">
                            <div class="profile-picture">
                                <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Profile Picture">
                                <div class="edit-icon">
                                    ✏️
                                </div>
                            </div>
                            <div class="greeting">
                                <h1 class="greeting-text">
                                    <span id="greeting-message" style="color: black;"></span>,
                                    <span class="user-name">Saman</span>
                                </h1>
                                <p class="welcome-back">Welcome back to your dashboard</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="progress-card">
                        <h3 class="progress-title">Project Progress</h3>
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="progressBar"></div>
                        </div>
                        <p class="progress-text"><span id="progressText">0</span>% Completed</p>
                    </div>
                </div>


            <ul class="insights">
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            4
                        </h3>
                        <p>Upcoming Events</p>
                    </span>
                </li>
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            0
                        </h3>
                        <p>Notifications</p>
                    </span>
                </li>
                <li><i class='bx bx-line-chart'></i>
                    <span class="info">
                        <h3>
                            3
                        </h3>
                        <p>Orders</p>
                    </span>
                </li>
                <!-- <li><i class='bx bx-dollar-circle'></i>
                    <span class="info">
                        <h3>
                            $6,742
                        </h3>
                        <p>Total Sales</p>
                    </span>
                </li> -->
            </ul>
            <!-- End of Insights -->

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Recent Orders</h3>
                        <i class='bx bx-filter'></i>
                        <i class='bx bx-search'></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png">
                                    <p>Saman Thushara</p>
                                </td>
                                <td>14-08-2023</td>
                                <td><span class="status completed">Completed</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png">
                                    <p>Saman Thushara</p>
                                </td>
                                <td>14-08-2023</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="<?php echo URLROOT; ?>/public/assets/profile.png">
                                    <p>Saman Thushara</p>
                                </td>
                                <td>14-08-2023</td>
                                <td><span class="status process">Processing</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Reminders -->
                <div class="reminders">
                    <div class="header">
                        <i class='bx bx-note'></i>
                        <h3>Remiders</h3>
                        <i class='bx bx-filter'></i>
                        <i class='bx bx-plus'></i>
                    </div>
                    <ul class="task-list">
                        <li class="completed">
                            <div class="task-title">
                                <i class='bx bx-check-circle'></i>
                                <p>Upcoming next payment</p>
                            </div>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="completed">
                            <div class="task-title">
                                <i class='bx bx-check-circle'></i>
                                <p>Your site visit is scheduled</p>
                            </div>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="not-completed">
                            <div class="task-title">
                                <i class='bx bx-x-circle'></i>
                                <p>Agreemnet is approved</p>
                            </div>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                    </ul>
                </div>

                <!-- End of Reminders-->

            </div>

        </main>

    </div>
    
    <script src="<?php echo URLROOT; ?>/js/client/dashboard.js"></script>
<?php require APPROOT.'/views/client/footer.php';?> 