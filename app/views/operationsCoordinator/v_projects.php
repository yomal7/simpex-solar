<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/projects.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

        <div class="sidebar" id="sidebar">
            <img
                
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture"
            />
            <a href="./dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="./manageAproject"  class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="./managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="./tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
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
                <div class="card-container">
                    <div class="card" id="total-projects">
                        <svg class="card-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3H21V21H3V3Z" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 9H21" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 21V9" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2 class="card-title">Total Projects</h2>
                        <p class="card-value">0</p>
                        <p class="card-subtitle">All-time projects</p>
                    </div>
                    <div class="card" id="ongoing-projects">
                        <svg class="card-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2V6" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 18V22" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.93 4.93L7.76 7.76" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.24 16.24L19.07 19.07" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 12H6" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18 12H22" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.93 19.07L7.76 16.24" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.24 7.76L19.07 4.93" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2 class="card-title">Ongoing Projects</h2>
                        <p class="card-value">0</p>
                        <p class="card-subtitle">Projects in progress</p>
                    </div>
                    <div class="card" id="completed-projects">
                        <svg class="card-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 11.08V12C21.9988 14.1564 21.3005 16.2547 20.0093 17.9818C18.7182 19.709 16.9033 20.9725 14.8354 21.5839C12.7674 22.1953 10.5573 22.1219 8.53447 21.3746C6.51168 20.6273 4.78465 19.2461 3.61096 17.4371C2.43727 15.628 1.87979 13.4881 2.02168 11.3363C2.16356 9.18455 2.99721 7.13631 4.39828 5.49706C5.79935 3.85781 7.69279 2.71537 9.79619 2.24013C11.8996 1.7649 14.1003 1.98232 16.07 2.85999" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 4L12 14.01L9 11.01" stroke="#0066cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2 class="card-title">Completed Projects</h2>
                        <p class="card-value">0</p>
                        <p class="card-subtitle">Successfully finished</p>
                    </div>
                </div>
                <!-- /* Project table */ -->
                <div class="project-table-section">
                   
                    <table class="project-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Name</th>
                                <th>Project ID</th>
                                <th>Start Date</th>
                                <th>Stage</th>
                                <th>Location</th>
                                <th>Quotation</th>
                            </tr>
                        </thead>
                        <tbody id="projectTableBody">
                            <!-- Table content will be populated by JavaScript -->
                        </tbody>
                    </table>
                    <div class="pagination" id="pagination">
                        <!-- Pagination will be populated by JavaScript -->
                    </div>

                </div>



            </div>

        </div>

    </div>

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/projects.js"></script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>