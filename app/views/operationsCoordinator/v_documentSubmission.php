<?php require APPROOT . '/views/operationsCoordinator/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/operationsCoordinator/documentSubmission.css">
</head>

<body>
    <div class="dashboard-container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

        <div class="sidebar" id="sidebar">
            <img
                src="<?php echo URLROOT; ?>/public/assets/profile.png"
                alt="manager profile-picture"
                class="profile-picture" />

            <a href="<?php echo URLROOT ?>/operationsCoordinator/projects">
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
                <!-- Document Review Section -->
                <div class="section-header">
                    <h2>Document Submission Review</h2>
                    <div class="project-meta">
                        <span class="project-id">#PRJ<?php echo str_pad($data['project']->project_id, 3, '0', STR_PAD_LEFT); ?></span>
                        <span class="customer-name"><?php echo $data['project']->customer_name; ?></span>
                    </div>
                </div>

                <?php flash('document_message'); ?>

                <div class="document-review-container">
                    <?php if (isset($data['document']) && $data['document']): ?>
                        <div class="document-card">
                            <div class="document-info">
                                <div class="document-header">
                                    <h3>Submitted Document</h3>
                                    <span class="status-badge <?php echo $data['document']->status; ?>">
                                        <?php echo ucfirst($data['document']->status); ?>
                                    </span>
                                </div>

                                <div class="document-details">
                                    <div class="detail-row">
                                        <span class="label">Submission Date:</span>
                                        <span><?php echo date('F j, Y, g:i a', strtotime($data['document']->created_at)); ?></span>
                                    </div>

                                    <?php if ($data['document']->status == 'reject'): ?>
                                        <div class="detail-row">
                                            <span class="label">Rejection Reason:</span>
                                            <p class="rejection-reason"><?php echo nl2br($data['document']->rejection_reason); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="document-preview">
                                <?php
                                $filePath = $data['document']->document;
                                $fileExt = pathinfo($filePath, PATHINFO_EXTENSION);

                                if (in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif'])):
                                ?>
                                    <img src="<?php echo URLROOT; ?>/uploads/documents/<?php echo $filePath; ?>" alt="Document Preview">
                                <?php elseif (strtolower($fileExt) == 'pdf'): ?>
                                    <div class="pdf-preview">
                                        <iframe src="<?php echo URLROOT; ?>/uploads/documents/<?php echo $filePath; ?>" width="100%" height="500px"></iframe>
                                    </div>
                                <?php else: ?>
                                    <div class="file-icon">
                                        <i class="material-icons-sharp">description</i>
                                        <p><?php echo $filePath; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($data['document']->status == 'pending'): ?>
                                <div class="document-actions">
                                    <button class="btn-accept" onclick="showAcceptModal()">
                                        <i class="material-icons-sharp">check_circle</i> Accept Document
                                    </button>
                                    <button class="btn-reject" onclick="showRejectModal()">
                                        <i class="material-icons-sharp">cancel</i> Reject Document
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-document">
                            <i class="material-icons-sharp">description</i>
                            <h3>No Document Submitted</h3>
                            <p>The customer has not submitted any documents yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Document Requirements Section -->
                <div class="requirements-section">
                    <h3>Document Requirements</h3>
                    <div class="requirements-card">
                        <div class="requirement">
                            <div class="requirement-icon">
                                <i class="material-icons-sharp">description</i>
                            </div>
                            <div class="requirement-content">
                                <h4>CEB Rooftop Solar Clearance</h4>
                                <p>The document should be issued by the Ceylon Electricity Board (CEB) and must include the following:</p>
                                <ul>
                                    <li>Customer's name and address</li>
                                    <li>Approval for solar panel installation</li>
                                    <li>Maximum approved capacity</li>
                                    <li>Valid date of issuance (not older than 3 months)</li>
                                    <li>Official CEB stamp or seal</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <!-- Accept Confirmation Modal -->
    <div id="acceptModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Document Acceptance</h3>
            <p>Are you sure you want to accept this document? This will move the project to the next phase.</p>
            <div class="modal-actions">
                <form action="<?php echo URLROOT; ?>/operationsCoordinator/acceptDocument" method="POST">
                    <input type="hidden" name="document_id" value="<?php echo isset($data['document']) ? $data['document']->id : ''; ?>">
                    <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                    <button type="button" class="btn-cancel" onclick="closeAcceptModal()">Cancel</button>
                    <button type="submit" class="btn-confirm">Confirm Acceptance</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Document Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <h3>Reject Document</h3>
            <p>Please provide a reason for rejecting this document:</p>
            <form action="<?php echo URLROOT; ?>/operationsCoordinator/rejectDocument" method="POST">
                <input type="hidden" name="document_id" value="<?php echo isset($data['document']) ? $data['document']->id : ''; ?>">
                <input type="hidden" name="project_id" value="<?php echo $data['project']->project_id; ?>">
                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason:</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                        placeholder="Please explain why this document is being rejected..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="btn-confirm">Submit Rejection</button>
                </div>
            </form>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <script>
        const URLROOT = "<?php echo URLROOT; ?>";

        // Modal functions
        function showAcceptModal() {
            document.getElementById('acceptModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeAcceptModal() {
            document.getElementById('acceptModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function showRejectModal() {
            document.getElementById('rejectModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        // Close modals when clicking overlay
        document.getElementById('overlay').addEventListener('click', function() {
            closeAcceptModal();
            closeRejectModal();
        });
    </script>

    <?php require APPROOT . '/views/operationsCoordinator/footer.php'; ?>