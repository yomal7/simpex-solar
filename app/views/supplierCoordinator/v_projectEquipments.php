<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/projectEquipments.css">
</head>

<body>
    <div class="dashboard-container">
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

            <a href="<?php echo URLROOT ?>/supplierCoordinator/projects">
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
                <!-- Page header -->
                <div class="page-header">
                    <h1>Project Equipment Release</h1>
                    <span class="project-id">Project ID: <?php echo $data['project']->project_id; ?></span>
                </div>

                <?php flash('project_message'); ?>

                <!-- Customer Information Card -->
                <div class="card customer-card">
                    <div class="card-header">
                        <h2>Customer Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="info-label">Customer Name</p>
                                <p class="info-value"><?php echo $data['project']->customer_name; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Phone</p>
                                <p class="info-value"><?php echo $data['project']->phone; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Email</p>
                                <p class="info-value"><?php echo $data['project']->email; ?></p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Address</p>
                                <p class="info-value"><?php echo $data['project']->address ?? $data['project']->location; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Information Card -->
                <div class="card project-card">
                    <div class="card-header">
                        <h2>Project Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <p class="info-label">System Capacity</p>
                                <p class="info-value"><?php echo $data['agreement']->system_capacity; ?> kW</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Estimated Generation</p>
                                <p class="info-value"><?php echo $data['agreement']->estimated_generation; ?> kWh/month</p>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Total Project Cost</p>
                                <p class="info-value">Rs. <?php echo number_format($data['agreement']->total_price, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipment Table Card -->
                <div class="card equipment-card">
                    <div class="card-header">
                        <h2>Equipment Requirements</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="equipment-table">
                                <thead>
                                    <tr>
                                        <th>Equipment</th>
                                        <th>Unit Price</th>
                                        <th>Required Quantity</th>
                                        <th>Available Quantity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $canRelease = true;
                                    foreach ($data['equipment'] as $item):
                                        $isAvailable = $item->inventory_quantity >= $item->required_quantity;
                                        if (!$isAvailable) {
                                            $canRelease = false;
                                        }
                                    ?>
                                        <tr class="<?php echo $isAvailable ? 'available' : 'unavailable'; ?>">
                                            <td>
                                                <div class="equipment-name"><?php echo $item->name; ?></div>
                                                <div class="equipment-desc"><?php echo $item->description; ?></div>
                                            </td>
                                            <td>Rs. <?php echo number_format($item->unit_price, 2); ?></td>
                                            <td><?php echo $item->required_quantity; ?></td>
                                            <td><?php echo $item->inventory_quantity; ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $isAvailable ? 'available' : 'unavailable'; ?>">
                                                    <?php echo $isAvailable ? 'Available' : 'Insufficient'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="release-section">
                            <?php if ($canRelease): ?>
                                <button type="button" class="btn-release" onclick="showReleaseConfirmation()">
                                    <span class="material-icons-sharp">inventory</span> Release Equipment
                                </button>
                            <?php else: ?>
                                <div class="insufficient-warning">
                                    <i class="material-icons-sharp">warning</i>
                                    <p>Cannot release equipment due to insufficient inventory for some items.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Confirm Equipment Release</h2>
            <p>Are you sure you want to release the following equipment?</p>

            <div class="confirm-equipment-list">
                <table>
                    <thead>
                        <tr>
                            <th>Equipment</th>
                            <th>Required</th>
                            <th>Current Stock</th>
                            <th>Remaining Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['equipment'] as $item): ?>
                            <tr>
                                <td><?php echo $item->name; ?></td>
                                <td><?php echo $item->required_quantity; ?></td>
                                <td><?php echo $item->inventory_quantity; ?></td>
                                <td><?php echo $item->inventory_quantity - $item->required_quantity; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <form action="<?php echo URLROOT; ?>/supplierCoordinator/releaseEquipment" method="post" id="releaseForm">
                <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                <input type="hidden" name="agreement_id" value="<?php echo $data['agreement']->agreement_id; ?>">

                <div class="modal-buttons">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Confirm Release</button>
                </div>
            </form>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        function showReleaseConfirmation() {
            document.getElementById('confirmModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>

    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>
</body>

</html>