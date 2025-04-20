<?php require APPROOT.'/views/operationsCoordinator/header.php';?>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/manageAproject.css">
</head>
<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img
                
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture"
            />
            <a href="<?php echo URLROOT ?>/hRAdministrator/dashboard">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/employees">
                <span class="material-icons-sharp">group</span>
                <h3>Employees</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/attendance" class="active">
                <span class="material-icons-sharp">checklist_rtl</span>
                <h3>Attendance</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/holiday">
                <span class="material-icons-sharp">date_range</span>
                <h3>Holiday</h3>
            </a>
            <a href="<?php echo URLROOT ?>/hRAdministrator/chat">
                <span class="material-icons-sharp">chat</span>
                <h3>Chat</h3>
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
            <div class="container">


                <div class="phase-box">
                    <div class="phase-header">
                        <div class="phase-title">
                            <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Quotation Approval
                        </div>
                        <span class="phase-status status-revision">Revision Requested</span>
                        <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="2">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="phase-content">
                        <div class="customer-feedback">
                            <div class="feedback-header">Customer Feedback</div>
                            <p>Please revise the pricing for items 2 and 3. Also, could you include a bulk discount for orders over 100 units?</p>
                        </div>
            
                        <form id="quotationForm" class="phase-content-inner">
                            <div class="form-group">
                                <label>Quotation Title</label>
                                <input type="text" placeholder="Enter quotation title" value="Website Development Project - Q1 2024">
                            </div>
                            
                            <div class="form-group">
                                <label>Description</label>
                                <textarea rows="3" placeholder="Enter quotation description">Complete website development including frontend and backend implementation with 6 months of support.</textarea>
                            </div>
            
                            <div class="form-group">
                                <label>Items</label>
                                <table class="items-table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Frontend Development</td>
                                            <td>1</td>
                                            <td>$5,000</td>
                                            <td>$5,000</td>
                                        </tr>
                                        <tr>
                                            <td>Backend Development</td>
                                            <td>1</td>
                                            <td>$6,000</td>
                                            <td>$6,000</td>
                                        </tr>
                                        <tr>
                                            <td>Support & Maintenance</td>
                                            <td>6</td>
                                            <td>$800</td>
                                            <td>$4,800</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="add-item-btn">+ Add Item</button>
                            </div>
            
                            <div class="quotation-preview">
                                <div class="preview-header">Quotation Preview</div>
                                <div id="quotationPreview">
                                    <!-- PDF preview would be rendered here -->
                                    [PDF Preview of the quotation would be displayed here]
                                </div>
                            </div>
            
                            <div class="action-buttons">
                                <button type="button" class="btn btn-primary">Send to Customer</button>
                                <button type="button" class="btn btn-secondary">Save Draft</button>
                            </div>
                        </form>
                    </div>
                </div>
        
                <div class="phase-box">
                    <div class="phase-header">
                        <div class="phase-title">
                            <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
                            </svg>
                            Planning Phase
                        </div>
                        <span class="phase-status status-pending">In Progress</span>
                        <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="2">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="phase-content">
                        <div class="phase-content-inner">
                            <table class="task-table">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Assignee</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Define project scope</td>
                                        <td>John Doe</td>
                                        <td>Completed</td>
                                    </tr>
                                    <tr>
                                        <td>Create timeline</td>
                                        <td>Jane Smith</td>
                                        <td>In Progress</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Site visit phase -->

                <div class="phase-box">
                    <div class="phase-header" id="phaseHeader">
                        <div class="phase-title">
                            <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Site Visit Phase
                        </div>

                            <span class="phase-status status-pending" id="visitStatus">Pending</span>
                            <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="2">
                                <path d="M19 9l-7 7-7-7"></path>
                            </svg>

                    </div>
                    <div class="phase-content" id="phaseContent">
                        <div class="phase-content-inner">
                            <div class="form-group">
                                <label for="visitDate">Site Visit Date</label>
                                <input type="date" id="visitDate" class="input-field">
                            </div>
                            <div class="form-group">
                                <label for="visitTime">Site Visit Time</label>
                                <input type="time" id="visitTime" class="input-field">
                            </div>
                            <div class="form-group">
                                <label for="visitNotes">Notes</label>
                                <textarea id="visitNotes" rows="3" class="input-field" placeholder="Add any additional notes..."></textarea>
                            </div>
                            <div class="button-group">
                                <button id="scheduleBtn" class="btn btn-primary">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Schedule Visit
                                </button>
                                <button id="completeBtn" class="btn btn-success" disabled>
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                    Mark as Completed
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="toast-container" id="toastContainer"></div>

                

        
                <div class="phase-box">
                    <div class="phase-header">
                        <div class="phase-title">
                            <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
                            </svg>
                            Execution Phase
                        </div>
                        <span class="phase-status status-not-started">Not Started</span>
                        <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="2">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="phase-content">
                        <div class="phase-content-inner">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="phase-box">
                    <div class="phase-header">
                        <div class="phase-title">
                            <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                            </svg>
                            Monitoring Phase
                        </div>
                        <span class="phase-status status-not-started">Not Started</span>
                        <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="2">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="phase-content">
                        <div class="phase-content-inner">
                            <div class="form-group">
                                <label>Progress Report</label>
                                <textarea rows="4" placeholder="Enter progress details"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="phase-box">
                    <div class="phase-header">
                        <div class="phase-title">
                            <svg class="phase-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                <path d="M22 4L12 14.01l-3-3"></path>
                            </svg>
                            Closure Phase
                        </div>
                        <span class="phase-status status-not-started">Not Started</span>
                        <svg class="dropdown-icon" viewBox="0 0 24 24" fill="none" stroke="#4b5563" stroke-width="2">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="phase-content">
                        <div class="phase-content-inner">
                            <div class="form-group">
                                <label>Final Report</label>
                                <textarea rows="4" placeholder="Enter closure report"></textarea>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/dashboard.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/quationPhase.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/siteVisit.js"></script>
    <script src="<?php echo URLROOT; ?>/js/operationsCoordinator/manageAproject.js"></script>
<?php require APPROOT.'/views/operationsCoordinator/footer.php';?>