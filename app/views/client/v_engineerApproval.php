<?php require APPROOT . '/views/client/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/engineerApproval.css">
</head>

<body>
    <?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="side-menu">
            <li>
                <a href="<?php echo URLROOT; ?>/client/project/<?php echo $data['project']->pre_project_id; ?>" style="background-color: rgb(192, 236, 192);" class="back-buttons">
                    <i class='bx bx-arrow-back'></i>Back
                </a>
            </li>
        </ul>
    </div>

    <div class="content">
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <div class="container">
            <div class="card approval-status-card">
                <div class="card-header">
                    <h2>Engineering Approval</h2>
                    <span class="status-badge <?php echo isset($data['progress']['engineer_approval_date']) ? 'completed' : 'pending'; ?>">
                        <?php echo isset($data['progress']['engineer_approval_date']) ? 'Completed' : 'In Progress'; ?>
                    </span>
                </div>

                <div class="card-body">
                    <?php if (!isset($data['progress']['engineer_approval_date'])): ?>
                        <!-- Certificate process not completed -->
                        <div class="waiting-message">
                            <i class="fas fa-clipboard-check"></i>
                            <h3>Certification Process In Progress</h3>
                            <p>Our engineering team is currently reviewing your installation and preparing the necessary certifications. This process typically takes 3-5 business days to complete.</p>
                            <div class="process-timeline">
                                <div class="timeline-step active">
                                    <div class="step-icon"><i class="fas fa-user-hard-hat"></i></div>
                                    <div class="step-label">Engineer Assigned</div>
                                </div>
                                <div class="timeline-step <?php echo isset($data['certificate']) && !is_null($data['certificate']) ? 'active' : ''; ?>">
                                    <div class="step-icon"><i class="fas fa-clipboard-list"></i></div>
                                    <div class="step-label">Inspection Started</div>
                                </div>
                                <div class="timeline-step">
                                    <div class="step-icon"><i class="fas fa-certificate"></i></div>
                                    <div class="step-label">Approval Complete</div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Certificate process completed -->
                        <div class="completion-details">
                            <i class="fas fa-certificate success-icon"></i>
                            <h3>Certification Process Completed</h3>
                            <p>Your solar installation has been inspected and certified by our engineering team.</p>

                            <div class="completion-info">
                                <div class="info-item">
                                    <span class="label">Approval Date:</span>
                                    <span class="value"><?php echo date('F j, Y', strtotime($data['progress']['engineer_approval_date'])); ?></span>
                                </div>
                                <?php if (isset($data['certificate']) && $data['certificate']->completed_at): ?>
                                    <div class="info-item">
                                        <span class="label">Certified By:</span>
                                        <span class="value"><?php echo isset($data['engineer_name']) ? $data['engineer_name'] : 'Simpex Engineering Team'; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($data['is_off_grid']): ?>
                                <div class="next-steps">
                                    <h4>Next Steps</h4>
                                    <p>Your system is now fully operational! As this is an off-grid system, no further approvals from CEB are required.</p>
                                </div>
                            <?php else: ?>
                                <div class="next-steps">
                                    <h4>Next Steps</h4>
                                    <p>Your system is now ready for grid connection. The next phase involves submitting your certification to CEB for final grid connection approval.</p>

                                    <a href="<?php echo URLROOT; ?>/client/gridConnection/<?php echo $data['pre_project_id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-bolt"></i> Proceed to Grid Connection
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card info-card">
                <div class="card-header">
                    <h3>About Engineering Approval</h3>
                </div>
                <div class="card-body">
                    <div class="info-content">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="info-text">
                                <h4>What is Engineering Approval?</h4>
                                <p>The Engineering Approval phase ensures your solar installation meets all safety standards and operational requirements. Our certified engineers inspect the installation and issue formal certificates.</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div class="info-text">
                                <h4>Certification Process</h4>
                                <p>Our engineers validate that your installation meets electrical safety codes, structural integrity standards, and performance requirements. Once the installation passes inspection, official certifications are issued.</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-text">
                                <h4>Typical Timeline</h4>
                                <p>The certification process typically takes 3-5 business days to complete, depending on engineer availability and complexity of the installation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.bx-menu');
            const sidebar = document.querySelector('.sidebar');

            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('close');
            });
        });
    </script>

    <?php require APPROOT . '/views/client/footer.php'; ?>