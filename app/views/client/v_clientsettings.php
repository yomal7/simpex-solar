<?php require APPROOT.'/views/client/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/settings.css">
</head>
<body>
<div id="toast-container"></div>
<?php flash('profile_message'); ?>
<?php flash('password_message'); ?>
<?php flash('profile_picture_message'); ?>
<?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- <a href="#" class="logo">
            <i class='bx bx-code-alt'></i>
            <div class="logo-name"><span>Asmr</span>Prog</div>
        </a> -->
        <ul class="side-menu">
            <li ><a href="<?php echo URLROOT; ?>/client/dashboard"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li ><a href="<?php echo URLROOT; ?>/client/project"><i class='bx bx-analyse'></i>Project</a></li>
            <li  ><a href="<?php echo URLROOT; ?>/client/shop"><i class='bx bx-store-alt'></i>Shop</a></li>
            <li><a href="#"><i class='bx bx-message-square-dots'></i>Chat</a></li>
            <!-- <li><a href="#"><i class='bx bx-group'></i>Users</a></li> -->
            <li class="active"><a href="<?php echo URLROOT; ?>/client/settings"><i class='bx bx-cog'></i>Settings</a></li>
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

    <div class="content">
            <!-- Navbar -->
            <nav>
                <i class='bx bx-menu'></i>
            </nav>

            <!-- End of Navbar -->
            <div class="container">
    <div class="profile-card">
        <!-- Flash Messages -->
        <?php flash('profile_message'); ?>
        <?php flash('password_message'); ?>
        <?php flash('profile_picture_message'); ?>

        <div class="profile-header">
            <div class="profile-img-container">
                <img src="<?php echo empty($data['profile_picture']) ? 
                    "https://via.placeholder.com/150" : 
                    URLROOT . '/uploads/profile_pictures/' . $data['profile_picture']; ?>" 
                    alt="Profile" id="profile-img">
                
                <div class="img-overlay">
                    <form action="<?php echo URLROOT; ?>/client/settings" method="POST" enctype="multipart/form-data" id="profile-pic-form">
                        <label for="img-upload" class="upload-btn" style="cursor: pointer; display: block; width: 100%; height: 100%;">
                            <div class="upload-icon-container" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                                    <line x1="16" y1="5" x2="22" y2="5"></line>
                                    <line x1="19" y1="2" x2="19" y2="8"></line>
                                    <circle cx="9" cy="9" r="3"></circle>
                                </svg>
                            </div>
                            <input type="file" name="profile_picture" id="img-upload" accept="image/*" hidden 
                                onchange="document.getElementById('profile-pic-form').submit();">
                        </label>
                    </form>
                </div>
            </div>
            <p>Edit your profile</p>
        </div>

        <div class="profile-content">
            <!-- Profile Information Form -->
            <form action="<?php echo URLROOT; ?>/client/settings" method="POST" id="profile-form" class="profile-info">
                <div class="info-group">
                    <label>Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($data['name']); ?>" disabled>
                </div>

                <div class="info-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($data['email']); ?>" disabled>
                </div>

                <div class="info-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                    <span class="invalid-feedback"><?php echo $data['phone_err'] ?? ''; ?></span>
                </div>

                <button type="submit" name="update_profile" class="save-btn">
                    <span>Save Changes</span>
                    <div class="success-animation">
                        <svg viewBox="0 0 24 24">
                            <path d="M1 14l7 7L23 5"></path>
                        </svg>
                    </div>
                </button>
            </form>

            <!-- Password Change Form -->
            <form action="<?php echo URLROOT; ?>/client/settings" method="POST" class="password-section">
                <div class="info-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" id="current-password">
                    <span class="invalid-feedback"><?php echo $data['current_password_err'] ?? ''; ?></span>
                </div>

                <div class="info-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" id="new-password">
                    <span class="invalid-feedback"><?php echo $data['new_password_err'] ?? ''; ?></span>
                </div>

                <button type="submit" name="change_password" class="save-btn">
                    <span>Update Password</span>
                    <div class="success-animation">
                        <svg viewBox="0 0 24 24">
                            <path d="M1 14l7 7L23 5"></path>
                        </svg>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>

    <script src="<?php echo URLROOT; ?>/js/client/settings.js"></script>
<?php require APPROOT.'/views/client/footer.php';?>

