<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsManager/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplier.css">
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
            <a href="<?php echo APPROOT; ?>/views/supplierCoordinator/v_dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="#" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
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
                <div class="table-container">
                    <div class="table-header">
                        <h2>Suppliers</h2>
                        <div class="add-button">
                            <button
                                type="button"
                                class="add-button"
                                onclick="openPopup('userFormPopup')">
                                <span class="button-text">Add </span>
                                <span class="button-icon">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke-linejoin="round"
                                        stroke-linecap="round"
                                        stroke="currentColor"
                                        height="24"
                                        fill="none"
                                        class="svg">
                                        <line y2="19" y1="5" x2="12" x1="12"></line>
                                        <line y2="12" y1="12" x2="19" x1="5"></line>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Supplier Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Table rows will be dynamically added here -->
                        </tbody>
                    </table>
                    <!-- Add this after your table -->
                    <div class="pagination-controls" id="paginationControls">
                        <!-- Pagination controls will be inserted here by JavaScript -->
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

        <script src="<?php echo URLROOT; ?>/js/operationsManager/dashboard.js"></script>
        <script src="<?php echo URLROOT; ?>/js/supplier.js"></script>
        <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>