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
            <li ><a href="Dashboard.html"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="active"><a href="Project.html"><i class='bx bx-analyse'></i>Project</a></li>
            <li ><a href="#"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li><a href="#"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#" class="logout">
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
                    <h1>Project</h1>
                    <!-- <ul class="breadcrumb">
                        <li><a href="#">
                                Analytics
                            </a></li>
                        /
                        <li><a href="#" class="active">Shop</a></li>
                    </ul> -->
                </div>
                <a href="#" class="report">
                    <i class='bx bx-cloud-download'></i>
                    <span>Download CSV</span>
                </a>
            </div>


            <div class="container">
                <button class="back-button">← Back</button>
                
        
                <div class="timeline-container">
                    <div class="timeline-progress-bar">
                        <div class="timeline-progress-fill" style="height: 60%;"></div>
                    </div>
        
                    <div class="timeline-item completed" style="animation-delay: 0.2s;">
                        <div class="timeline-card">
                            <h3>Project Initiation</h3>
                            <p>Initial planning and resource allocation completed</p>
                        </div>
                    </div>
        
                    <div class="timeline-item completed" style="animation-delay: 0.4s;">
                        <div class="timeline-card">
                            <h3>Requirements Gathering</h3>
                            <p>All stakeholder requirements documented</p>
                        </div>
                    </div>
        
                    <div class="timeline-item active" style="animation-delay: 0.6s;">
                        <div class="timeline-card">
                            <h3>Design Phase</h3>
                            <p>Create detailed project designs and mockups</p>
                            <!-- <button class="proceed-button">Proceed to Next Step</button> -->
                        </div>
                    </div>
        
                    <div class="timeline-item future" style="animation-delay: 0.8s;">
                        <div class="timeline-card">
                            <h3>Development</h3>
                            <p>Begin implementation of approved designs</p>
                        </div>
                    </div>
        
                    <div class="timeline-item future" style="animation-delay: 1s;">
                        <div class="timeline-card">
                            <h3>Testing</h3>
                            <p>Quality assurance and user acceptance testing</p>
                        </div>
                    </div>
        
                    <div class="timeline-item future" style="animation-delay: 1.2s;">
                        <div class="timeline-card">
                            <h3>Deployment</h3>
                            <p>Project launch and implementation</p>
                        </div>
                    </div>
                </div>
            </div>

        </main>

    </div>
</body>
    <script src="<?php echo URLROOT; ?>/js/client/project.js"></script>
<?php require APPROOT.'/views/packages/footer.php';?>