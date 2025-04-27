<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/projects.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/holidayDetails.css">
</head>

<body>
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
            <a href="<?php echo URLROOT; ?>/hRAdministrator/holiday" class="side-back-button">
                <span class="material-icons-sharp">arrow_back</span>
                <h3>Back</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="container">
                <div class="record-details-container">
                    <div class="details">

                        <?php if (isset($data['record']) && $data['record']): ?>
                            <div class="record-info">
                                <div class="indetail">
                                    <div class="employee-details">
                                        <h3>Employee Details</h3>
                                        <div class="content-in">
                                            <div class="top">

                                                <div class="info-group">
                                                    <label class="fut">Employee ID :</label>
                                                    <span>EMP<?php echo str_pad($data['record']->employee_id, 4, '0', STR_PAD_LEFT); ?></span>
                                                </div>

                                                <div class="info-group">
                                                    <label>Role :</label>
                                                    <span><?php echo $data['employeeDetails']->role; ?></span>
                                                </div>

                                                <div class="info-group">
                                                    <label>Contact No. :</label>
                                                    <span><?php echo $data['employeeDetails']->phone; ?></span>
                                                </div>
                                            
                                            </div>

                                            <div class="bottom">
                                                
                                                <div class="info-group">
                                                    <label>Name :</label>
                                                    <span><?php echo $data['employeeDetails']->name; ?></span>
                                                </div>

                                                <div class="info-group">
                                                    <label>Email :</label>
                                                    <span><?php echo $data['employeeDetails']->email; ?></span>
                                                </div>

                                            </div>
                                        </div>
                                
                                    </div>
                                </div>
                                <div class="indetail">
                                    <h3>Assigned Tasks Details</h3>

                                    <?php if (isset($data['assignedTasksDetails']) && !empty($data['assignedTasksDetails'])): ?>
                                        <?php foreach ($data['assignedTasksDetails'] as $index => $task): ?>
                                            <div class="task-details">
                                                <?php if ($index > 0): ?>
                                                    <hr class="task-divider">
                                                <?php endif; ?>
                                                <div class="task-info">
                                                    <div class="task-info-top">
                                                        <div class="info-group">
                                                            <label>Project ID :</label>
                                                            <span>PRJ<?php echo str_pad($task->project_id, 6, '0', STR_PAD_LEFT); ?></span>
                                                        </div>

                                                        <div class="info-group">
                                                            <label>Task ID :</label>
                                                            <span>TSK<?php echo str_pad($task->id, 6, '0', STR_PAD_LEFT); ?></span>
                                                        </div>

                                                        <div class="info-group">
                                                            <label>Start Date :</label>
                                                            <span><?php echo $task->start_date; ?></span>
                                                        </div>

                                                        <div class="info-group">
                                                            <label>End Date :</label>
                                                            <span><?php echo $task->end_date; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="task-info-bottom">
                                                        <div class="info-group">
                                                            <label>Title :</label>
                                                            <span><?php echo $task->title; ?></span>
                                                        </div>

                                                        <div class="info-group">
                                                            <label>Description :</label>
                                                            <span><?php echo $task->description; ?></span>
                                                        </div>

                                                        <div class="info-group">
                                                            <span class="completion-button <?php echo strtolower($task->status); ?>">
                                                                <?php echo str_replace('_', ' ', ucfirst($task->status)); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="info-group">
                                            <p>No tasks assigned during this period.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="indetail">
                                    <div class="request-details">
                                        <h3>Leave Request Details</h3>
                                        <div class="content">
                                            <div class="line">
                                                <div class="info-group">
                                                    <label>Leave Type :</label>
                                                    <span><?php echo $data['record']->leave_type; ?></span>
                                                </div>

                                                <div class="info-group">
                                                    <label>Number of Days :</label>
                                                    <span><?php echo $data['record']->number_of_days; ?></span>
                                                </div>

                                                <div class="info-group">
                                                    <label>Start Date :</label>
                                                    <span><?php echo $data['record']->start_date; ?></span>
                                                </div>

                                                <div class="info-group">
                                                    <label>End Date :</label>
                                                    <span><?php echo $data['record']->end_date; ?></span>
                                                </div>
                                            </div>

                                            <div class="info-group">
                                                <label>Reason :</label>
                                                <span><?php echo $data['record']->reason; ?></span>
                                            </div>

                                            <div class="info-group">
                                                <span class="status-button <?php echo strtolower($data['record']->status); ?>">
                                                    <?php echo (ucfirst($data['record']->status)); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                        
                                </div>
                                <div class="approval">

                                    <form method="post" action="<?php echo URLROOT; ?>/hRAdministrator/details/<?php echo $data['record']->id; ?>" style="display: inline;">
                                        <input type="hidden" name="recordId" value="<?php echo $data['record']->id; ?>">
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="approve-button">Approve</button>
                                    </form>

                                    <form method="post" action="<?php echo URLROOT; ?>/hRAdministrator/details/<?php echo $data['record']->id; ?>" style="display: inline;">
                                        <input type="hidden" name="recordId" value="<?php echo $data['record']->id; ?>">
                                        <input type="hidden" name="status" value="Not Approved">
                                        <button type="submit" class="reject-button">Reject</button>
                                    </form>
            
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
                                        <div class="comment-actions">
                                            <button class="comment-action-button" onclick="deleteComment(<?php echo $data['record']->id; ?>)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>

                                <?php endif; ?>
                            </div>
                            <?php if (empty($data['record']->comment)): ?>
                                <div class="add-comment">
                                    <textarea id="newComment" maxlength="255" placeholder="Add a comment..."></textarea>
                                    <div class="comment-actions">
                                        <button class="comment-action-button" onclick="addComment(<?php echo $data['record']->id; ?>)">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                </div>


                            <?php else: ?>
                                <div class="alert alert-danger">
                                    record not found
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="overlay" id="overlay"></div>

            <script src="<?php echo URLROOT; ?>/js/hRAdministrator/holiday.js"></script>
            <?php require APPROOT . '/views/hRAdministrator/footer.php'; ?>