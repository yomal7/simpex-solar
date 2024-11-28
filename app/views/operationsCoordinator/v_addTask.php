<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/tasks.css">
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
                class="profile-picture" />
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/managePackages">
                <span class="material-icons-sharp">solar_power</span>
                <h3>Packages</h3>
            </a>
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks" class="active">
                <span class="material-icons-sharp">task</span>
                <h3>Tasks</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="form-container">

                <h2>Assign New Task</h2>
                <form action="<?php echo URLROOT; ?>/operationsCoordinator/addTask" method="POST" id="userForm" id="addUserForm" onsubmit="handleSubmit(event)">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">Task Title</label>
                            <input type="text" name="title" id="title" placeholder="Task Title" value="<?php $data['title']; ?>">
                            <span class="form-invalid"><?php echo isset($data['title_err']) ? $data['title_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" placeholder="Start Date" value="<?php $data['start_date']; ?>">
                            <span class="form-invalid"><?php echo isset($data['start_date_err']) ? $data['start_date_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" placeholder="End Date" value="<?php $data['end_date']; ?>">
                            <span class="form-invalid"><?php echo isset($data['end_date_err']) ? $data['end_date_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="project_id">Project</label>
                            <input type="text" name="project_id" id="project_id" placeholder="Project" value="<?php $data['project_id']; ?>">
                            <!-- <select id="project_id" name="project_id" required>
                                <option value="">Select Project...</option>
                                options will be populated dynamically
                            </select> -->
                            <span class="form-invalid"><?php echo isset($data['project_id_err']) ? $data['project_id_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="employee_id">Assign To</label>
                            <input type="text" name="employee_id" id="employee_id" placeholder="Employee" value="<?php $data['employee_id']; ?>">
                            <!-- <select id="employee_id" name="employee_id" required>
                                <option value="">Select Employee...</option>
                                options will be populated dynamically
                            </select> -->
                            <span class="form-invalid"><?php echo isset($data['employee_id_err']) ? $data['employee_id_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="">Select Status...</option>
                                <option value="incomplete">Incomplete</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                            <span class="form-invalid"><?php echo isset($data['status_err']) ? $data['status_err'] : ''; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="description">Task Description</label>
                            <textarea name="description" id="description" placeholder="Task Description" rows="10" cols="10"><?php $data['description']; ?></textarea>
                            <span class="form-invalid"><?php echo isset($data['description_err']) ? $data['description_err'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Assign Task
                        </button>
                        <a href="<?php echo URLROOT; ?>/operationsCoordinator/tasks" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>





            </div>
        </div>
    </div>

    <!-- <div class="overlay" id="overlay"></div> -->




    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>