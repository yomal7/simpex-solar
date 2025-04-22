<?php require APPROOT . '/views/supplierCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/supplierCoordinator/suppliers.css">
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
            <a href="<?php echo URLROOT ?>/supplierCoordinator/shop">
                <span class="material-icons-sharp">person</span>
                <h3>Shop</h3>
            </a>
            <a href="<?php echo URLROOT ?>/supplierCoordinator/projects">
                <span class="material-icons-sharp">receipt_long</span>
                <h3>Projects</h3>
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

        <div class="main-content">
            <div class="page-header">
                <h1><?php echo $data['title']; ?></h1>
                <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/addSupplier'" class="add-button">
                    <span class="material-icons-sharp">add</span>
                    Supplier
                </button>
            </div>

            <div class="message"><?php flash('supplier_message'); ?></div>

            <!-- Add search bar after page header -->
            <div class="search-container">
                <div class="search-wrapper">
                    <span class="material-icons-sharp">search</span>
                    <input type="text" id="supplierSearch" placeholder="supplier name...">
                    <span class="material-icons-sharp clear-search" id="clearSearchIcon">close</span>
                </div>
            </div>

            <div class="suppliers-container">
                <div class="table-responsive">
                    <table class="suppliers-table" id="suppliersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['suppliers'] as $supplier) : ?>
                                <tr>
                                    <td><?php echo $supplier->name; ?></td>
                                    <td><?php echo $supplier->email; ?></td>
                                    <td><?php echo $supplier->contact_number; ?></td>
                                    <td><?php echo $supplier->address; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($supplier->created_at)); ?></td>
                                    <td class="actions">
                                        <button onclick="viewSupplier(<?php echo $supplier->id; ?>)" class="btn-icon view view-btn">
                                            <span class="material-icons-sharp">visibility</span>
                                        </button>
                                        <button onclick="location.href='<?php echo URLROOT; ?>/supplierCoordinator/edit/<?php echo $supplier->id ?>'" class="btn-icon edit edit-btn">
                                            <span class="material-icons-sharp">edit</span>
                                        </button>
                                        <button onclick="deleteSupplier(<?php echo $supplier->id; ?>)" class="btn-icon delete delete-btn">
                                            <span class="material-icons-sharp">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Supplier Details</h2>
            <div class="supplier-info">
                <div class="info-row">
                    <label>Name:</label>
                    <span id="view-name"></span>
                </div>
                <div class="info-row">
                    <label>Email:</label>
                    <span id="view-email"></span>
                </div>
                <div class="info-row">
                    <label>Contact:</label>
                    <span id="view-contact"></span>
                </div>
                <div class="info-row">
                    <label>Address:</label>
                    <span id="view-address"></span>
                </div>
                <div class="info-row">
                    <label>Other Details:</label>
                    <span id="view-other-details"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Supplier</h2>
            <form id="editSupplierForm">
                <input type="hidden" id="edit-id" name="id">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" id="edit-name" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" id="edit-email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Contact:</label>
                    <input type="text" id="edit-contact" name="contact_number" required>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <textarea id="edit-address" name="address" required></textarea>
                </div>
                <div class="form-group">
                    <label>Other Details:</label>
                    <textarea id="edit-other-details" name="other_details"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Delete Supplier</h2>
            <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
            <div class="form-actions">
                <button class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="delete-btn" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('supplierSearch');
            const clearIcon = document.getElementById('clearSearchIcon');
            const table = document.getElementById('suppliersTable');

            function filterSuppliers() {
                const filter = searchInput.value.toLowerCase();
                const rows = table.getElementsByTagName('tr');
                let visibleCount = 0;

                // Show/hide clear button
                clearIcon.style.display = filter ? 'block' : 'none';

                // Skip header row (i=1)
                for (let i = 1; i < rows.length; i++) {
                    const nameCell = rows[i].getElementsByTagName('td')[0]; // First column is supplier name
                    if (nameCell) {
                        const supplierName = nameCell.textContent || nameCell.innerText;
                        const shouldShow = supplierName.toLowerCase().includes(filter);
                        rows[i].style.display = shouldShow ? '' : 'none';
                        if (shouldShow) visibleCount++;
                    }
                }

                // Show/hide no results message
                updateNoResults(visibleCount === 0 && filter !== '');
            }

            function updateNoResults(show) {
                let message = document.getElementById('noResultsMessage');
                if (show) {
                    if (!message) {
                        message = document.createElement('div');
                        message.id = 'noResultsMessage';
                        message.className = 'no-results';
                        message.textContent = 'No supplier found with that name';
                        table.parentNode.insertBefore(message, table.nextSibling);
                    }
                    message.style.display = 'block';
                } else if (message) {
                    message.style.display = 'none';
                }
            }

            function clearSearch() {
                searchInput.value = '';
                filterSuppliers();
                searchInput.focus();
            }

            // Event listeners
            searchInput.addEventListener('input', filterSuppliers);
            clearIcon.addEventListener('click', clearSearch);
            clearIcon.style.display = 'none';
        });
    </script>

    <style>
        .search-container {
            margin: 20px 0;
            display: flex;
            justify-content: flex-start;
        }

        .search-wrapper {
            display: flex;
            align-items: center;
            background: white;
            padding: 8px 15px;
            border-radius: 25px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 300px;
            margin-left: 0;
        }

        .search-wrapper input {
            flex: 1;
            border: none;
            outline: none;
            padding: 5px;
            margin: 0 10px;
            font-size: 14px;
        }

        .search-wrapper .material-icons-sharp {
            color: #666;
            cursor: pointer;
        }

        .search-wrapper .clear-search {
            display: none;
        }

        .no-results {
            text-align: center;
            padding: 15px;
            color: #666;
            background: #f8f9fa;
            border-radius: 4px;
            margin-top: 10px;
        }

        .message {
            display: flex;
            justify-content: center;
            width: 100%;
            text-align: center;
            margin: 10px 0;
        }
    </style>

    <script src="<?php echo URLROOT; ?>/js/supplierCoordinator/suppliers.js"></script>
    <script src="<?php echo URLROOT; ?>/js/supplier.js"></script>
    <?php require APPROOT . '/views/supplierCoordinator/footer.php'; ?>