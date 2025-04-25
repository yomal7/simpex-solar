<!-- v_projectDashboard.php -->

<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/signature.css">
</head>

<body data-user-role="operationsCoordinator" data-user-id="<?php echo $_SESSION['user_id']; ?>" data-urlroot="<?php echo URLROOT; ?>">
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <!-- ************ -->
        <!-- Sidebar -->
        <!-- ************ -->

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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/chat" class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'chat') !== false) ? 'active' : ''; ?>">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
                <span class="notification-dot" style="display: <?php echo (isset($_SESSION['total_unread_count']) && $_SESSION['total_unread_count'] > 0) ? 'block' : 'none'; ?>;"></span>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <!-- <div class="main-content"> -->
                <div class="container">
                    <div class="content-wrapper">
                        <div class="page-header">
                            <h1>Signature Management</h1>
                        </div>

                        <?php flash('signature_msg'); ?>

                        <div class="approot-info">
                            <p>Application Root Path: <?php echo APPROOT; ?></p>
                        </div>

                        <div class="signature-container">
                            <div class="current-signature">
                                <h2>Current Signature</h2>
                                <?php if (isset($data['signature']) && $data['signature']): ?>
                                    <div class="signature-preview">
                                        <img src="<?php echo URLROOT; ?>/public/uploads/signatures/<?php echo $data['signature']->signature_image; ?>" 
                                            alt="Current Signature">
                                    </div>
                                    <form action="<?php echo URLROOT; ?>/operationsCoordinator/deleteSignature" 
                                        method="POST" class="delete-form">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete Signature
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <p class="no-signature">No signature uploaded yet</p>
                                <?php endif; ?>
                            </div>

                            <div class="upload-section">
                                <h2>Upload New Signature</h2>
                                <form action="<?php echo URLROOT; ?>/operationsCoordinator/uploadSignature" 
                                    method="POST" enctype="multipart/form-data" class="upload-form">
                                    <div class="form-group">
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="signature" id="signature" 
                                                accept="image/png,image/jpeg,image/jpg" required>
                                            <label for="signature">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <span>Choose a file</span>
                                            </label>
                                        </div>
                                        <div id="file-name"></div>
                                        <?php if (!empty($data['signature_err'])): ?>
                                            <span class="error"><?php echo $data['signature_err']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Upload Signature
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>   

    <!-- </div> -->

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/signature.js"></script>

<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>