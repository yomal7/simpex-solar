<?php require APPROOT . '/views/technician/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- <link rel="stylesheet" href="</?php echo URLROOT; ?>/css/technician/dashboard.css"> -->
 

</head>

<body>
    <div class="projectTasks-container">

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
                    <p>HR Administrator</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/technician/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/technician/settings">
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

                <div class="task-details-container">
                    <div class="actions">
                        <button class="back-button" onclick="window.location.href='<?php echo URLROOT; ?>/technician/tasks<?php echo isset($_GET['page']) ? '?page=' . $_GET['page'] : ''; ?>'" class="back-btn">
                            Back to Tasks
                        </button>
                    </div>
                    <div class="details">
                        <h2>Task Details</h2>
                        <?php if (isset($data['task']) && $data['task']): ?>
                            <div class="task-info">
                                <div class="info-group">
                                    <label class="fut">Task ID:</label>
                                    <span>TSK<?php echo str_pad($data['task']->id, 6, '0', STR_PAD_LEFT); ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Project ID:</label>
                                    <span>PRJ<?php echo str_pad($data['task']->project_id, 6, '0', STR_PAD_LEFT); ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Title:</label>
                                    <span><?php echo $data['task']->title; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Description:</label>
                                    <span><?php echo $data['task']->description; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Start Date:</label>
                                    <span><?php echo $data['task']->start_date; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>End Date:</label>
                                    <span><?php echo $data['task']->end_date; ?></span>
                                </div>

                                <div class="info-group">
                                    <label>Status:</label>
                                    <span class="status-badge <?php echo strtolower($data['task']->status); ?>">
                                        <?php echo str_replace('_', ' ', ucfirst($data['task']->status)); ?>
                                    </span>
                                </div>
                            </div>

                    </div>
                    <div class="comments">

                        <div class="comments-section">
                            <h3>Comments</h3>
                            <div id="commentsList">
                                <?php if (!empty($data['task']->comment)): ?>
                                    <div class="comment">
                                        <p><?php echo $data['task']->comment; ?></p>
                                        <div class="comment-actions">                          
                                            <button class="comment-action-button"onclick="deleteComment(<?php echo $data['task']->id; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>

                                <?php endif; ?>
                            </div>
                            <?php if (empty($data['task']->comment)): ?>
                            <div class="add-comment">
                                <textarea id="newComment" maxlength="255" placeholder="Add a comment..."></textarea>
                            <div class="comment-actions">
                                <button class="comment-action-button"onclick="addComment(<?php echo $data['task']->id; ?>)">
                                            <i class="fas fa-paper-plane"></i>
                                            </button>
                            </div>
                            <?php endif; ?>
                        </div>


                    <?php else: ?>
                        <div class="alert alert-danger">
                            Task not found
                        </div>
                    <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="overlay" id="overlay"></div>


<script src="<?php echo URLROOT; ?>/js/technician/tasks.js"></script>

<?php require APPROOT . '/views/technician/footer.php'; ?>