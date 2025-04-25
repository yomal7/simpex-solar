<?php require APPROOT . '/views/hRAdministrator/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/hRAdministrator/editEmployees.css">
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
            <a href="<?php echo URLROOT ?>/hRAdministrator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees" class="active">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/payroll">
                <span class="material-icons-sharp">money</span>
                <h3>Payroll</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="main-content">
            <div class="employee-form-container">
                <h2 class="form-title">Edit Employee Profile</h2>
                <form action="<?php echo URLROOT; ?>/hRAdministrator/editEmployee/<?php echo $data['employee_id']; ?>" method="POST" id="employeeForm" enctype="multipart/form-data">
                    <div class="profile-image-section">
                        <div class="image-preview-container">
                            <?php if(!empty($data['profile_image'])): ?>
                                <img id="profile-preview" src="<?php echo URLROOT; ?>/public/uploads/profile_pictures/<?php echo $data['profile_image']; ?>" alt="Profile Preview">
                            <?php else: ?>
                                <img id="profile-preview" src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="Profile Preview">
                            <?php endif; ?>
                        </div>
                        <div class="image-upload-controls">
                            <label for="profile_image">Profile Picture</label>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*">
                            <span class="form-invalid"><?php echo isset($data['profile_image_err']) ? $data['profile_image_err'] : ''; ?></span>
                            <p class="help-text">Recommended: Square image, max 2MB. Leave blank to keep current image.</p>
                        </div>
                    </div>
                    <div class="employee-form-grid">
                        <div class="form-field">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" required placeholder="Name" value="<?php echo $data['name']; ?>">
                            <span class="form-invalid"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
                        </div>
                        <div class="form-field">
                            <label for="role">Role</label>
                            <select id="role" name="role">
                                <option value="">Select Role...</option>
                                <option value="technician" <?php echo $data['role'] == 'technician' ? 'selected' : ''; ?>>Technician</option>
                                <option value="deliveryPerson" <?php echo $data['role'] == 'deliveryPerson' ? 'selected' : ''; ?>>Delivery Person</option>
                                <option value="engineer" <?php echo $data['role'] == 'engineer' ? 'selected' : ''; ?>>Engineer</option>
                                <option value="clerk" <?php echo $data['role'] == 'clerk' ? 'selected' : ''; ?>>Clerk</option>
                            </select>
                            <span class="form-invalid"><?php echo isset($data['role_err']) ? $data['role_err'] : ''; ?></span>
                        </div>
                        <div class="form-field">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $data['email']; ?>">
                            <span class="form-invalid"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                        </div>
                        <div class="form-field">
                            <label for="phone">Phone No</label>
                            <input type="number" name="phone" id="phone" placeholder="Phone No" value="<?php echo $data['phone']; ?>">
                            <span class="form-invalid"><?php echo isset($data['phone_err']) ? $data['phone_err'] : ''; ?></span>
                        </div>
                        <div class="form-field">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" placeholder="Address" rows="3"><?php echo isset($data['address']) ? $data['address'] : ''; ?></textarea>
                            <span class="form-invalid"><?php echo isset($data['address_err']) ? $data['address_err'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="Update Employee Profile" class="btn btn-primary">
                        <a href="<?php echo URLROOT; ?>/hRAdministrator/viewEmployee/<?php echo $data['employee_id']; ?>" class="btn-link"><button type="button" class="btn btn-secondary">Cancel</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <div class="overlay" id="overlay"></div> -->

    <script>
        // Preview profile image before upload
        document.getElementById('profile_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>