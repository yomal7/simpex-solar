<?php require APPROOT . '/views/engineer/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/requestDetails.css">
</head>

<body>
    <div class="request-holiday-container">
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
            <a href="<?php echo URLROOT; ?>/engineer/requestHoliday" class="side-back-button">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>Back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="holiday-details-container">
                    
                    <div class="details">
                        <h2>Leave Request Details</h2>
                        <?php if (isset($data['record']) && $data['record']): ?>
                            <div class="holiday-info">
                                <div class="holiday-info-top">
                                    <div class="info-group">
                                        <label>Leave Type:</label>
                                        <span><?php echo $data['record']->leave_type; ?></span>
                                    </div>

                                    <div class="info-group">
                                        <label>Start Date:</label>
                                        <span><?php echo $data['record']->start_date; ?></span>
                                    </div>

                                    <div class="info-group">
                                        <label>End Date:</label>
                                        <span><?php echo $data['record']->end_date; ?></span>
                                    </div>

                                    <div class="info-group">
                                        <label>Number of Days:</label>
                                        <span><?php echo $data['record']->number_of_days; ?></span>
                                    </div>
                                </div>
                                <div class="holiday-info-bottom">
                                    <div class="info-group">
                                        <label>Reason:</label>
                                        <span><?php echo $data['record']->reason; ?></span>
                                    </div>

                                    <div class="info-group">
                                        <label>Status:</label>
                                        <span class="status-badge <?php echo strtolower($data['record']->status); ?>">
                                            <?php echo ucfirst($data['record']->status); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    
                    <div class="comments">
                        <div class="comments-section">
                            <h3>Comments</h3>
                            <div id="commentsList">
                                <?php if (!empty($data['record']->comment)): ?>
                                    <div class="comment">
                                        <p><?php echo $data['record']->comment; ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="comment">
                                        <p class="no-comments">No comments.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <div class="alert alert-danger">
                            Leave request not found
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/engineer/requestHoliday.js"></script>
    <?php require APPROOT . '/views/engineer/footer.php'; ?>