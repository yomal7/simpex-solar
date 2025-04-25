<?php require APPROOT.'/views/chiefCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/feedback.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <a href="<?php echo URLROOT ?>/chiefCoordinator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/preProjects">
                <span class="material-icons-sharp">assignment</span>
                <h3>Pre Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/projects">
                <span class="material-icons-sharp">business_center</span>
                <h3>Projects</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/finance">
                <span class="material-icons-sharp">attach_money</span>
                <h3>Finance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/employees">
                <span class="material-icons-sharp">people</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/store">
                <span class="material-icons-sharp">store</span>
                <h3>Store</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/feedbacks" class="active">
                <span class="material-icons-sharp">feedback</span>
                <h3>Feedbacks</h3>
            </a>
            <a href="<?php echo URLROOT ?>/chiefCoordinator/settings">
                <span class="material-icons-sharp">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div class="content-wrapper" style="width: calc(100% - 250px);">
            <div class="main-content">
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1>Manage Customer Feedback</h1>
                </div>

                <?php flash('feedback_message'); ?>

                <!-- Stats Cards -->
                <div class="stats-container" style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stats-info">
                        <h4>Total Feedbacks</h4>
                        <h2><?php echo $data['stats']->total; ?></h2>
                    </div>
                    </div>
                    
                    <div class="stats-card">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="stats-info">
                        <h4>New</h4>
                        <h2><?php echo $data['stats']->new_count; ?></h2>
                    </div>
                    </div>
                    
                    <div class="stats-card">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="stats-info">
                        <h4>In Progress</h4>
                        <h2><?php echo $data['stats']->in_progress_count; ?></h2>
                    </div>
                    </div>
                    
                    <div class="stats-card">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-info">
                        <h4>Resolved</h4>
                        <h2><?php echo $data['stats']->resolved_count; ?></h2>
                    </div>
                    </div>
                    
                    <div class="stats-card">
                    <div class="stats-icon bg-secondary">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stats-info">
                        <h4>Avg. Rating</h4>
                        <h2><?php echo number_format($data['stats']->avg_rating, 1); ?></h2>
                    </div>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="filter-controls mb-3" style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                    <select id="statusFilter" class="form-control" style="width: 200px;">
                    <option value="all">All Statuses</option>
                    <option value="new">New</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                    </select>
                    
                    <select id="typeFilter" class="form-control" style="width: 200px;">
                    <option value="all">All Types</option>
                    <option value="general">General</option>
                    <option value="suggestion">Suggestion</option>
                    <option value="complaint">Complaint</option>
                    <option value="compliment">Compliment</option>
                    <option value="inquiry">Inquiry</option>
                    </select>
                    
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width: 300px;">
                </div>

                <!-- Feedback Table -->
                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="table table-striped table-hover" id="feedbackTable">
                    <thead class="table-success">
                        <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['feedbacks'] as $feedback): ?>
                        <tr data-status="<?php echo $feedback->status; ?>" data-type="<?php echo $feedback->feedback_type; ?>">
                        <td><?php echo $feedback->id; ?></td>
                        <td><?php echo date('M d, Y', strtotime($feedback->created_at)); ?></td>
                        <td><?php echo htmlspecialchars($feedback->name); ?></td>
                        <td>
                            <span class="badge 
                            <?php 
                            switch($feedback->feedback_type) {
                                case 'complaint': echo 'bg-danger'; break;
                                case 'compliment': echo 'bg-success'; break;
                                case 'suggestion': echo 'bg-info'; break;
                                case 'inquiry': echo 'bg-warning'; break;
                                default: echo 'bg-secondary';
                            }
                            ?>">
                            <?php echo ucfirst($feedback->feedback_type); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($feedback->subject); ?></td>
                        <td>
                            <div class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star <?php echo ($i <= $feedback->rating) ? 'filled' : ''; ?>"></i>
                            <?php endfor; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                            <?php 
                            switch($feedback->status) {
                                case 'new': echo 'bg-primary'; break;
                                case 'in_progress': echo 'bg-warning'; break;
                                case 'resolved': echo 'bg-success'; break;
                                default: echo 'bg-secondary';
                            }
                            ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $feedback->status)); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo URLROOT; ?>/chiefCoordinator/viewFeedback/<?php echo $feedback->id; ?>" class="btn btn-sm btn-primary" style="text-decoration: none; margin-right: 5px;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $feedback->id; ?>" onclick="confirmDelete(<?php echo $feedback->id; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
                </div>
                <div id="deleteModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h2>
                            <span class="close">&times;</span>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this feedback?</p>
                            <p><strong>This action cannot be undone.</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-cancel" id="cancelDelete">Cancel</button>
                            <button class="btn btn-delete" id="confirmDelete">Delete</button>
                        </div>
                    </div>
                </div>
        </div>

    </div>


<script>
    // Simple filtering/search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('feedbackTable');
        const rows = table.querySelectorAll('tbody tr');
        
        // Apply filters
        function applyFilters() {
            const statusValue = statusFilter.value;
            const typeValue = typeFilter.value;
            const searchValue = searchInput.value.toLowerCase();
            
            rows.forEach(row => {
                const status = row.dataset.status;
                const type = row.dataset.type;
                const text = row.textContent.toLowerCase();
                
                const statusMatch = statusValue === 'all' || status === statusValue;
                const typeMatch = typeValue === 'all' || type === typeValue;
                const searchMatch = searchValue === '' || text.includes(searchValue);
                
                if (statusMatch && typeMatch && searchMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Set up event listeners
        statusFilter.addEventListener('change', applyFilters);
        typeFilter.addEventListener('change', applyFilters);
        searchInput.addEventListener('input', applyFilters);
    });
    
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }


    // Delete confirmation
    const modal = document.getElementById('deleteModal');
        
        // Get the <span> element that closes the modal
        const closeBtn = document.getElementsByClassName('close')[0];
        
        // Get the button elements
        const cancelBtn = document.getElementById('cancelDelete');
        const confirmBtn = document.getElementById('confirmDelete');
        
        // Store the ID of the item to be deleted
        let itemToDelete = null;
        
        // Function to open modal with the item ID
        function confirmDelete(id) {
            itemToDelete = id;
            modal.style.display = 'flex';
        }
        
        // When the user clicks on <span> (x), close the modal
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }
        
        // When the user clicks on Cancel, close the modal
        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        }
        
        // When the user clicks on Delete, perform the delete action
        confirmBtn.onclick = function() {
            if (itemToDelete) {
                window.location.href = '<?php echo URLROOT; ?>/chiefCoordinator/deleteFeedback/' + itemToDelete;
            }
            modal.style.display = 'none';
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }

        // Replace your existing confirmDelete function with this one
        function confirmDelete(id) {
            itemToDelete = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }
</script>

<?php require APPROOT.'/views/chiefCoordinator/footer.php'; ?>