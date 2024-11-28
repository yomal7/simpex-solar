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
            <li ><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li class="active"><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
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
                        <div class="timeline-progress-fill" style="height: 60%;"></div>
                    </div>
        
                    <div class="timeline-item completed active" style="animation-delay: 0.2s;">
                        <div class="timeline-card">
                            <h3>Agreemnet phase</h3>
                            <p>Agreemnt approving and signing</p>
                        </div>
                    </div>
        
                    <div class="timeline-item completed active" style="animation-delay: 0.4s;">
                        <div class="timeline-card">
                            <h3>Site visit phase</h3>
                            <p>Shecduel a date for site visit</p>
                        </div>
                    </div>
        
                    <div class="timeline-item active" style="animation-delay: 0.6s;">
                        <div class="timeline-card">
                            <h3>First payment phase</h3>
                            <p>Pay the 25% of total project cost</p>
                            <!-- <button class="proceed-button">Proceed to Next Step</button> -->
                        </div>
                    </div>
        
                    <div class="timeline-item future active" style="animation-delay: 0.8s;">
                        <div class="timeline-card">
                            <h3>Installation phase</h3>
                            <p>Schedule the date for installation</p>
                        </div>
                    </div>
        
                    <div class="timeline-item future active" style="animation-delay: 1s;">
                        <div class="timeline-card">
                            <h3>Final payment phase</h3>
                            <p>Pay the remaining 75% of total project cost</p>
                        </div>
                    </div>
        
                    <div class="timeline-item future" style="animation-delay: 1.2s;">
                        <div class="timeline-card">
                            <h3>Enginaering approval phase</h3>
                            <p>schedule he date for engineer inspection and connecting grid</p>
                        </div>
                    </div>
                </div>
            </div>

        </main>

    </div>

    <script src="<?php echo URLROOT; ?>/js/client/project.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>