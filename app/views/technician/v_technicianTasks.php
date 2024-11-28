<?php require APPROOT.'/views/technician/header.php';?>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/technician/dashboard.css">

</head>

<body>
<div class="projectTasks-container">

<button class="menu-toggle" onclick="toggleSidebar()">☰</button>        
<div class="sidebar" id="sidebar">
    <img   
        src="<?php echo URLROOT ?>/assets/profile.png"
        alt="technician profile-picture"
        class="profile-picture"
    />
    <a href="#">
        <span class="material-icons-sharp">dashboard</span>
        <h3>Dashboard</h3>
    </a>
    <a href="#" class="active">
        <span class="material-icons-sharp">task</span>
        <h3>Tasks</h3>
    </a>
    <a href="#">
        <span class="material-icons-sharp">event</span>
        <h3>Request Holiday</h3>
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

        <div class="projectTasks-table-container">
            <div class="projectTasks-table-header">
                <h2>Tasks</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Project Name</th>
                        <th>Task</th>
                        <th>Due Date</th>
                        <th>Completion</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody id="projectTasksTableBody"></tbody>
            </table>
        </div>

    </div>
</div>
</div>

<div class="overlay" id="overlay"></div>

<div class="popup" id="completionPopup">
<img src="<?php echo URLROOT ?>/assets/tick.png" alt="Success">
<h2>Change Completion</h2>
<p>Select the new completion status:</p>
<select id="completionSelect">
    <option value="completed">completed</option>
    <option value="not-completed">Not Completed</option>
</select>
<button type="button" onclick="updateCompletionChange()">Update</button>
</div>

<div class="popup" id="addCommentPopup">
<img src="<?php echo URLROOT ?>/assets/addComment.png" alt="addComment">
<h2>Comment</h2>        
<textarea id="addCommentText" name="comment" rows="1" oninput="autoResize(this)"></textarea>
<button type="button" onclick="confirmAddComment()">Add</button>
</div>

</body>
    <script src="<?php echo URLROOT; ?>/js/technician/tasks.js"></script>