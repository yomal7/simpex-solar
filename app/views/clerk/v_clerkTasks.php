<?php require APPROOT . '/views/clerk/header.php'; ?>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/tasks.css">

</head>

<body>
    <div class="tasks-container">

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
                    <p>Clerk</p>
                </div>
            </div>
            <a href="<?php echo URLROOT ?>/clerk/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/requestHoliday">
                <span class="material-icons-sharp">event</span>
                <h3>Request Leave</h3>
            </a>
            <a href="<?php echo URLROOT ?>/clerk/settings">
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

                <div class="projectTasks-table-container">
                    <div class="projectTasks-table-header">
                        <h2>Tasks</h2>
                    </div>
                    <table>
                        <colgroup>
                            <col style="width: 12%; text-align: center;"> <!-- Task ID -->
                            <col style="width: 12%; text-align: center;"> <!-- Project ID -->
                            <col style="width: 33%;"> <!-- Task -->
                            <col style="width: 20%; text-align: center;"> <!-- Due Date -->
                            <col style="width: 15%; text-align: center;"> <!-- Completion -->
                            <col style="width: 8%; text-align: center;"> <!-- Comment -->
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Task ID</th>
                                <th>Project ID</th>
                                <th>Task</th>
                                <th>Due Date</th>
                                <th>Completion</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="projectTasksTableBody">
                            <?php if (!empty($data['projectTasks'])): ?>
                                <?php foreach ($data['projectTasks'] as $task): ?>
                                    <tr>
                                        <td><?php echo $task->id; ?></td>
                                        <td><?php echo $task->project_id; ?></td>
                                        <td><?php echo strlen($task->title) > 50 ? substr($task->title, 0, 50) . '...' : $task->title; ?></td>
                                        <td><?php echo $task->end_date; ?></td>
                                        <td>
                                            <span class="status-button <?php echo strtolower($task->status); ?>">
                                                <?php echo str_replace('_', ' ', ucfirst($task->status)); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="icon-button view-details-btn" onclick="location.href='<?php echo URLROOT; ?>/clerk/details/<?php echo $task->id; ?>?page=<?php echo isset($data['currentPage']) ? $data['currentPage'] : 1; ?>'"  title="View Details"><i class="fas fa-eye"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">No tasks found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($data['totalTasks'] > 1): ?>
                        <div class="pagination">
                            <?php if ($data['currentPage'] > 1): ?>
                                <a href="?page=<?php echo $data['currentPage'] - 1 ?>" class="page-link">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            <?php endif; ?>

                            <button class="page-info">
                                <?php echo $data['currentPage'] ?>
                            </button>

                            <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                <a href="?page=<?php echo $data['currentPage'] + 1 ?>" class="page-link">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>

<script src="<?php echo URLROOT; ?>/js/clerk/tasks.js"></script>

<?php require APPROOT . '/views/clerk/footer.php'; ?>