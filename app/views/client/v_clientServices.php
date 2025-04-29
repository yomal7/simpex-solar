<?php require APPROOT . '/views/client/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/services.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/operationDashboard" style="background-color: rgb(192, 236, 192);" class="back-buttons"><i class='bx bx-arrow-back'></i>Back</a>
</li>
            </li>
        </ul>
    </div>

    <div class="content">
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <div class="container">
            <div class="section-header">
                <h2>Service Request</h2>
            </div>
            
            <?php flash('service_message'); ?>
            <?php flash('service_msg'); ?>
            
            <div class="service-container">
                <!-- Warranty Information Card -->
                <div class="card warranty-card">
                    <div class="card-header">
                        <h2>Warranty Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="warranty-info">
                            <div class="info-row">
                                <div class="info-label">Project Status:</div>
                                <div class="info-value">
                                    <span class="status-badge <?php echo strtolower($data['projectDetails']->status); ?>">
                                        <?php echo ucfirst($data['projectDetails']->status); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Installation Completed:</div>
                                <div class="info-value">
                                    <?php echo date('d M Y', strtotime($data['projectDetails']->completed_date)); ?>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Warranty Period:</div>
                                <div class="info-value highlight">
                                    <?php echo $data['warrantyPeriod']; ?> Years
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Warranty Valid Until:</div>
                                <div class="info-value highlight-date">
                                    <?php echo date('d M Y', strtotime($data['warranty_end_date'])); ?>
                                </div>
                            </div>
                            
                            <div class="warranty-note">
                                <i class='bx bx-info-circle'></i>
                                <p>Service requests are only valid during the warranty period. Please submit your request before your warranty expires.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Request Form -->
                <div class="card service-form-card">
                    <div class="card-header">
                        <h2>Submit Service Request</h2>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo URLROOT; ?>/client/services/<?php echo $data['pre_project_id']; ?>" method="POST">
                            <div class="form-group">
                                <label for="issue_type">Issue Type:</label>
                                <select name="issue_type" id="issue_type" class="form-control <?php echo (!empty($data['issue_type_err'])) ? 'is-invalid' : ''; ?>">
                                    <option value="" <?php echo empty($data['issue_type']) ? 'selected' : ''; ?>>-- Select Issue Type --</option>
                                    <option value="electrical" <?php echo ($data['issue_type'] === 'electrical') ? 'selected' : ''; ?>>Electrical Issues</option>
                                    <option value="mechanical" <?php echo ($data['issue_type'] === 'mechanical') ? 'selected' : ''; ?>>Mechanical Issues</option>
                                    <option value="performance" <?php echo ($data['issue_type'] === 'performance') ? 'selected' : ''; ?>>Performance Issues</option>
                                    <option value="other" <?php echo ($data['issue_type'] === 'other') ? 'selected' : ''; ?>>Other Issues</option>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['issue_type_err']; ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Describe the Issue:</label>
                                <textarea name="description" id="description" rows="5" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" placeholder="Please provide detailed information about the issue you're experiencing"><?php echo $data['description']; ?></textarea>
                                <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                                <div class="char-count"><span id="charCount">0</span> / 500 characters</div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Service Request Guidelines -->
            <div class="card guidelines-card">
                <div class="card-header">
                    <h2>Service Request Guidelines</h2>
                </div>
                <div class="card-body">
                    <div class="guidelines-content">
                        <div class="guideline-item">
                            <div class="guideline-icon">
                                <i class='bx bx-check-circle'></i>
                            </div>
                            <div class="guideline-text">
                                <h4>Be Specific</h4>
                                <p>Provide clear details about the issue including when it started, what components are affected, and any error messages you may have seen.</p>
                            </div>
                        </div>

                        
                        <div class="guideline-item">
                            <div class="guideline-icon">
                                <i class='bx bx-check-circle'></i>
                            </div>
                            <div class="guideline-text">
                                <h4>Response Time</h4>
                                <p>Our team typically responds to service requests within 1-2 business days. For urgent issues affecting system operation, please call our support hotline.</p>
                            </div>
                        </div>
                        
                        <div class="guideline-item">
                            <div class="guideline-icon">
                                <i class='bx bx-check-circle'></i>
                            </div>
                            <div class="guideline-text">
                                <h4>Warranty Coverage</h4>
                                <p>Your warranty covers manufacturing defects and system failures under normal operating conditions. Damage from external factors may not be covered.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/js/client/services.js"></script>

<?php require APPROOT . '/views/client/footer.php'; ?>
