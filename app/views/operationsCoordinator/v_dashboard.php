<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/dashboard" class="active">
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
            <a href="<?php echo URLROOT ?>/operationsCoordinator/tasks">
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
                                <th>Manager</th>
                                <th>Project ID</th>
                                <th>Start Date</th>
                                <th>Stage</th>
                                <th>Location</th>
                                <th>Action</th>
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


    <!-- ********** -->
    <!-- Add users popup -->
     <!-- ************* -->

    <div class="popup" class="form-container" id="userFormPopup">
        <h2>Add New User</h2>
        <form id="userForm" id="addUserForm" onsubmit="handleSubmit(event)">
            <div class="form-grid">
                <div class="form-group">
                    <!-- <label for="firstName">First Name</label> -->
                    <input type="text" id="firstName" name="firstName" required placeholder="First Name">
                </div>
                <div class="form-group">
                    <!-- <label for="lastName">Last Name</label> -->
                    <input type="text" id="lastName" name="lastName" required placeholder="Last Name">
                </div>
                <div class="form-group">
                    <!-- <label for="email">Email</label> -->
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <!-- <label for="phone">Phone Number</label> -->
                    <input type="tel" id="phone" name="phone" required placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <!-- <label for="role">Role</label> -->
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
                <div class="form-group">
                    <!-- <label for="department">Department</label> -->
                    <select id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="it">IT</option>
                        <option value="hr">HR</option>
                        <option value="sales">Sales</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-primary">Add User</button>
                <button type="button" class="btn btn-secondary" style="background-color: red;" onclick="closePopup('userFormPopup')">Cancel</button>
            </div>
        </form>
    </div>




    <!-- ********* -->
    <!-- Change Availability popup -->
    <!-- ************** -->


    <div class="popup" id="availabilityPopup">
        <img src="tick.png" alt="Success">
        <h2>Change Availability</h2>
        <p>Select the new availability status:</p>
        <select id="availabilitySelect">
            <option value="in-stock">In Stock</option>
            <option value="low-stock">Low Stock</option>
            <option value="out-of-stock">Out of Stock</option>
        </select>
        <button type="button" onclick="confirmAvailabilityChange()">Confirm</button>
    </div>


    <!-- *************** -->
    <!-- Edit popup -->
    <!-- **************** -->

    <div class="popup" id="editPopup">
        <img src="edit.png" alt="Edit">
        <h2>Edit User</h2>
        <label for="editName">Name</label>
        <input type="text" id="editName" placeholder="Name">
        
        <label for="editAddress">Address</label>
        <input type="text" id="editAddress" placeholder="Address">
        
        <label for="editEmail">Email</label>
        <input type="text" id="editEmail" placeholder="Email">
        
        <button type="button" onclick="confirmEdit()">Update User</button>
    </div>
    

    <!-- *************** -->
    <!-- Delete popup -->
     <!-- **************** -->

    <div class="popup" id="deletePopup">
        <img src="delete.png" alt="Delete">
        <h2>Confirm Delete</h2>
        <p>Are you sure you want to delete this user?</p>
        <button type="button" onclick="confirmDelete()">Delete User</button>
    </div>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>