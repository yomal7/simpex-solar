<?php require APPROOT . '/views/client/header.php'; ?>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/client/document.css">
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
            <div class="card document-status-card">
                <div class="card-header">
                    <h2>Document Submission</h2>
                    <span class="status-badge <?php echo isset($data['document_status']) ? $data['document_status'] : 'pending'; ?>">
                        <?php echo isset($data['document_status']) ? ucfirst($data['document_status']) : 'Pending'; ?>
                    </span>
                </div>

                <div class="card-body">
                    <?php if (!isset($data['document_status'])): ?>
                        <!-- No document submitted yet -->
                        <div class="waiting-message">
                            <i class="fas fa-file-upload"></i>
                            <p>Please submit your CEB rooftop solar clearance document to proceed with your solar installation project.</p>
                        </div>
                    <?php elseif ($data['document_status'] === 'pending'): ?>
                        <!-- Document submitted, waiting for approval -->
                        <div class="submitted-details">
                            <i class="fas fa-check-circle"></i>
                            <h3>Document Submitted</h3>
                            <p>Your document has been submitted on <?php echo date('F j, Y', strtotime($data['submission_date'])); ?> and is currently under review. We will notify you once it has been approved.</p>
                        </div>
                    <?php elseif ($data['document_status'] === 'accept'): ?>
                        <!-- Document approved -->
                        <div class="approval-details">
                            <i class="fas fa-check-circle"></i>
                            <h3>Document Approved</h3>
                            <p>Your document has been reviewed and approved. Your project is now moving to the next phase.</p>
                            <div class="document-info">
                                <div class="detail-row">
                                    <span class="label">Submission Date:</span>
                                    <span><?php echo date('F j, Y', strtotime($data['submission_date'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($data['document_status'] === 'reject'): ?>
                        <!-- Document rejected -->
                        <div class="rejection-details">
                            <i class="fas fa-times-circle"></i>
                            <h3>Document Rejected</h3>
                            <p>Unfortunately, your document has been rejected. Please review the rejection reason below and submit a new document.</p>

                            <div class="rejection-notes">
                                <p><strong>Rejection Reason:</strong></p>
                                <p><?php echo nl2br(htmlspecialchars($data['rejection_reason'])); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Instructions Card -->
                <?php if (!isset($data['document_status'])): ?>
                    <div class="card instructions-card">
                        <div class="card-header">
                            <h3>How to Get Your Rooftop Solar Clearance Document</h3>
                        </div>
                        <div class="card-body">
                            <div class="instruction-steps">
                                <div class="instruction-step">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h4>Visit the Official CEB Website</h4>
                                        <p>Go to the CEB online portal: <a href="https://cebcare.ceb.lk/" target="_blank">https://cebcare.ceb.lk/</a></p>
                                    </div>
                                </div>
                                <div class="instruction-step">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h4>Log In or Register</h4>
                                        <p>If you already have an account: Click the login button and enter your credentials.</p>
                                        <p>If you do not have an account: Register by filling in the required details to create your new account.</p>
                                    </div>
                                </div>
                                <div class="instruction-step">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h4>Access the Solar PV Connection Section</h4>
                                        <p>Once logged in, locate and click on the New Solar PV Connection option.</p>
                                    </div>
                                </div>
                                <div class="instruction-step">
                                    <div class="step-number">4</div>
                                    <div class="step-content">
                                        <h4>Fill Out the Form</h4>
                                        <p>Complete the form by providing accurate and up-to-date information as required. Ensure all the necessary fields are filled in to avoid delays in processing.</p>
                                    </div>
                                </div>
                                <div class="instruction-step">
                                    <div class="step-number">5</div>
                                    <div class="step-content">
                                        <h4>Submit Your Application</h4>
                                        <p>After reviewing your details, submit the form. Follow any additional on-screen instructions to finalize your clearance document request.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Upload Section - Only show if no document or rejected document -->
                <?php if (!isset($data['document_status']) || $data['document_status'] === 'reject'): ?>
                    <div class="card upload-card">
                        <div class="card-header">
                            <h3>Upload Clearance Document</h3>
                        </div>
                        <div class="card-body">
                            <div class="upload-section">
                                <div class="file-drop-area" id="dropArea">
                                    <!-- Upload UI -->
                                    <div class="upload-ui" id="uploadUI">
                                        <svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                        <p>Drag & drop your document here or</p>
                                        <label for="fileInput" class="choose-file-btn">Choose PDF file</label>
                                        <input type="file" id="fileInput" accept=".pdf" style="display: none;">
                                        <input type="hidden" id="projectId" value="<?php echo $data['project_id']; ?>">
                                        <p class="file-requirements">Only PDF files. Maximum size: 5MB</p>
                                    </div>

                                    <!-- Preview Container -->
                                    <div class="preview-container" id="previewContainer">
                                        <img id="dropAreaPreview" src="" alt="Preview">
                                        <div class="file-info">
                                            <span id="fileName"></span>
                                            <span id="fileSize"></span>
                                        </div>
                                        <button type="button" class="remove-preview" id="removeFile">×</button>
                                    </div>
                                </div>

                                <button id="submitButton" class="btn submit-button" disabled>Submit Document</button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            const URLROOT = '<?php echo URLROOT; ?>';
        </script>
        <script src="<?php echo URLROOT; ?>/js/client/document.js"></script>

        <?php require APPROOT . '/views/client/footer.php'; ?>