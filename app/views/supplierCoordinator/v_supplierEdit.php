<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/edit_suppliers.css">
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/suppliers" class="active">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Suppliers</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/inventory">
                <span class="material-icons-sharp">inventory</span>
                <h3>Inventory</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>

        <div class="content-wrapper">
            <div class="container">
                <h1>Edit Supplier</h1>
                <a href="<?php echo URLROOT; ?>/supplierCoordinator/suppliers" class="btn back-btn">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>

                <?php flash('supplier_message'); ?>

                <form method="post" action="<?= URLROOT ?>/supplierCoordinator/update">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">

                    <div class="form-group <?= !empty($data['name_err']) ? 'has-error' : '' ?>">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name"
                            value="<?= $data['name'] ?>"
                            class="form-control <?= !empty($data['name_err']) ? 'is-invalid' : '' ?>">
                        <span class="invalid-feedback"><?= $data['name_err'] ?></span>
                    </div>

                    <div class="form-group <?= !empty($data['address_err']) ? 'has-error' : '' ?>">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address"
                            value="<?= $data['address'] ?>"
                            class="form-control <?= !empty($data['address_err']) ? 'is-invalid' : '' ?>">
                        <span class="invalid-feedback"><?= $data['address_err'] ?></span>
                    </div>

                    <div class="form-group <?= !empty($data['email_err']) ? 'has-error' : '' ?>">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email"
                            value="<?= $data['email'] ?>"
                            class="form-control <?= !empty($data['email_err']) ? 'is-invalid' : '' ?>">
                        <span class="invalid-feedback"><?= $data['email_err'] ?></span>
                    </div>

                    <div class="form-group <?= !empty($data['contact_number_err']) ? 'has-error' : '' ?>">
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number"
                            value="<?= $data['contact_number'] ?>"
                            class="form-control <?= !empty($data['contact_number_err']) ? 'is-invalid' : '' ?>">
                        <span class="invalid-feedback"><?= $data['contact_number_err'] ?></span>
                    </div>

                    <div class="form-group">
                        <label for="other_details">Other Details:</label>
                        <textarea id="other_details" name="other_details"
                            class="form-control"><?= $data['other_details'] ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                    <a href="<?= URLROOT ?>/supplierCoordinator/suppliers" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
        <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/edit_suppliers.js"></script>
        <script src="<?php echo URLROOT; ?>/js/supplier.js"></script>
        <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>