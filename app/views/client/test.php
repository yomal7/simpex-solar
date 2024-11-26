<?php require APPROOT.'/views/client/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/settings.css">
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
            
            <li ><a href="#" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a></li>
            <li class="active"><a href="Dashboard.html"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li ><a href="Project.html"><i class='bx bx-analyse'></i>Project</a></li>
            <li><a href="#"><i class='bx bx-store-alt'></i>Shop</a></li>
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

    <div class="content">
            <!-- Navbar -->
            <nav>
                <i class='bx bx-menu'></i>
            </nav>

            <!-- End of Navbar -->
            <div class="container">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-img-container">
                            <img src="https://via.placeholder.com/150" alt="Profile" id="profile-img">
                            <div class="img-overlay">
                                <label for="img-upload" class="upload-btn" style="cursor: pointer; display: block; width: 100%; height: 100%;">
                                    <!-- Add your SVG or any other overlay content here -->
                                    <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                                            <line x1="16" y1="5" x2="22" y2="5"></line>
                                            <line x1="19" y1="2" x2="19" y2="8"></line>
                                            <circle cx="9" cy="9" r="3"></circle>
                                        </svg>
                                    </div>
                                    <!-- Hidden input field -->
                                    <input type="file" id="img-upload" accept="image/*" hidden>
                                </label>
                            </div>
                            
                        </div>
                        <p>Edit your profile</p>
                    </div>
        
                    <div class="profile-content">
                        <div class="info-group">
                            <label>Name</label>
                            <input type="text" value="John Doe" disabled>
                        </div>
        
                        <div class="info-group">
                            <label>Email</label>
                            <input type="email" value="john@example.com" disabled>
                        </div>
        
                        <div class="info-group">
                            <label>Phone</label>
                            <input type="tel" id="phone" value="+1234567890">
                        </div>

                        <div class="info-group">
                            <label>Address</label>
                            <input type="tel" id="phone" value="test">
                        </div>
        
                        <div class="password-section">
                            <div class="info-group">
                                <label>Current Password</label>
                                <input type="password" id="current-password">
                            </div>
        
                            <div class="info-group">
                                <label>New Password</label>
                                <input type="password" id="new-password">
                            </div>
                        </div>
        
                        <button class="save-btn" onclick="saveChanges()">
                            <span>Save Changes</span>
                            <div class="success-animation">
                                <svg viewBox="0 0 24 24">
                                    <path d="M1 14l7 7L23 5"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
    </div>


    <script src="<?php echo URLROOT; ?>/js/client/settings.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>