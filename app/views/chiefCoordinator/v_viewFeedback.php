<?php require APPROOT.'/views/chiefCoordinator/header.php';?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/dashboard.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/chiefCoordinator/viewFeedback.css">

<div class="dashboard-container">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <img src="<?php echo URLROOT; ?>/public/assets/profile.png" alt="manager profile-picture" class="profile-picture" />
        <a href="<?php echo URLROOT; ?>/chiefCoordinator/dashboard">
            <span class="material-icons-sharp">dashboard</span>
            <h3>Dashboard</h3>
        </a>
        <a href="<?php echo URLROOT; ?>/chiefCoordinator/feedbacks" class="active">
            <span class="material-icons-sharp">feedback</span>
            <h3>Feedback</h3>
        </a>
        <!-- Add other menu items as needed -->
    </div>

    <div class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <a href="<?php echo URLROOT; ?>/chiefCoordinator/feedbacks" class="back-button">
                    <i class="material-icons-sharp">arrow_back</i> Back to Feedbacks
                </a>

                <?php flash('feedback_message'); ?>

                <div class="feedback-container">
                    <div class="feedback-header">
                        <h2>Feedback #<?php echo $data['feedback']->id; ?></h2>
                        <span class="status-badge" style="background-color: 
                            <?php 
                            switch($data['feedback']->status) {
                                case 'new': echo '#2196F3'; break;
                                case 'in_progress': echo '#FF9800'; break;
                                case 'resolved': echo '#4CAF50'; break;
                                case 'closed': echo '#607D8B'; break;
                                default: echo '#607D8B';
                            }
                            ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $data['feedback']->status)); ?>
                        </span>
                    </div>

                    <div class="feedback-content">
                        <h3 class="feedback-subject"><?php echo htmlspecialchars($data['feedback']->subject); ?></h3>

                        <div class="feedback-meta">
                            <div class="feedback-meta-item">
                                <span class="feedback-meta-label">Feedback Type</span>
                                <span class="feedback-meta-value">
                                    <span class="type-badge" style="background-color: 
                                        <?php 
                                        switch($data['feedback']->feedback_type) {
                                            case 'complaint': echo '#F44336'; break;
                                            case 'compliment': echo '#4CAF50'; break;
                                            case 'suggestion': echo '#2196F3'; break;
                                            case 'inquiry': echo '#FF9800'; break;
                                            default: echo '#607D8B';
                                        }
                                        ?>">
                                        <?php echo ucfirst($data['feedback']->feedback_type); ?>
                                    </span>
                                </span>
                            </div>

                            <div class="feedback-meta-item">
                                <span class="feedback-meta-label">Submitted By</span>
                                <span class="feedback-meta-value"><?php echo htmlspecialchars($data['feedback']->name); ?></span>
                            </div>

                            <div class="feedback-meta-item">
                                <span class="feedback-meta-label">Email</span>
                                <span class="feedback-meta-value"><?php echo htmlspecialchars($data['feedback']->email); ?></span>
                            </div>

                            <div class="feedback-meta-item">
                                <span class="feedback-meta-label">Phone</span>
                                <span class="feedback-meta-value"><?php echo $data['feedback']->phone ? htmlspecialchars($data['feedback']->phone) : 'Not provided'; ?></span>
                            </div>

                            <div class="feedback-meta-item">
                                <span class="feedback-meta-label">Submission Date</span>
                                <span class="feedback-meta-value"><?php echo date('F j, Y, g:i a', strtotime($data['feedback']->created_at)); ?></span>
                            </div>

                            <div class="feedback-meta-item">
                                <span class="feedback-meta-label">Rating</span>
                                <span class="feedback-meta-value stars">
                                    <?php 
                                    for($i = 1; $i <= 5; $i++) {
                                        if($i <= $data['feedback']->rating) {
                                            echo '★';
                                        } else {
                                            echo '<span class="gray-star">★</span>';
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <h4>Feedback Message</h4>
                        <div class="feedback-message">
                            <?php echo nl2br(htmlspecialchars($data['feedback']->message)); ?>
                        </div>

                        <?php if (!empty($data['feedback']->admin_notes)): ?>
                            <h4>Admin Notes</h4>
                            <div class="admin-notes">
                                <?php echo nl2br(htmlspecialchars($data['feedback']->admin_notes)); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Status Update Form -->
                        <h4>Update Status</h4>
                        <form action="<?php echo URLROOT; ?>/chiefCoordinator/updateStatus" method="POST" class="status-form">
                            <input type="hidden" name="id" value="<?php echo $data['feedback']->id; ?>">
                            
                            <div class="status-selector">
                                <div class="status-option <?php if($data['feedback']->status === 'new') echo 'selected'; ?>" data-value="new">
                                    <i class="material-icons-sharp">fiber_new</i> New
                                </div>
                                <div class="status-option <?php if($data['feedback']->status === 'in_progress') echo 'selected'; ?>" data-value="in_progress">
                                    <i class="material-icons-sharp">hourglass_empty</i> In Progress
                                </div>
                                <div class="status-option <?php if($data['feedback']->status === 'resolved') echo 'selected'; ?>" data-value="resolved">
                                    <i class="material-icons-sharp">task_alt</i> Resolved
                                </div>
                                <div class="status-option <?php if($data['feedback']->status === 'closed') echo 'selected'; ?>" data-value="closed">
                                    <i class="material-icons-sharp">check_circle</i> Closed
                                </div>
                                <input type="hidden" id="status" name="status" value="<?php echo $data['feedback']->status; ?>">
                            </div>
                            
                            <div>
                                <label for="admin_notes">Add Notes (optional)</label>
                                <textarea id="admin_notes" name="admin_notes" class="textarea-field" placeholder="Add internal notes about this feedback..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn-submit">Update Status</button>
                        </form>

                        <!-- Email Response Form -->
                        <h4>Send Email Response</h4>
                        <form action="<?php echo URLROOT; ?>/chiefCoordinator/sendResponse" method="POST" class="email-response">
                            <input type="hidden" name="id" value="<?php echo $data['feedback']->id; ?>">
                            <input type="hidden" name="email" value="<?php echo $data['feedback']->email; ?>">
                            <input type="hidden" name="name" value="<?php echo $data['feedback']->name; ?>">
                            
                            <div>
                                <label for="subject">Email Subject</label>
                                <input type="text" id="subject" name="subject" class="input-field" value="RE: <?php echo htmlspecialchars($data['feedback']->subject); ?>">
                            </div>
                            
                            <div>
                                <label for="response">Email Response</label>
                                <textarea id="response" name="response" class="textarea-field" placeholder="Type your response here...">Dear <?php echo htmlspecialchars($data['feedback']->name); ?>,

                                    Thank you for your feedback regarding "<?php echo htmlspecialchars($data['feedback']->subject); ?>".

                                    [Your response here]

                                    Best regards,
                                    The SimplEx Solar Team</textarea>
                            </div>
                            
                            <button type="submit" class="btn-submit">Send Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Status option selection
    document.addEventListener('DOMContentLoaded', function() {
        const statusOptions = document.querySelectorAll('.status-option');
        const statusInput = document.getElementById('status');
        
        statusOptions.forEach(option => {
           option.addEventListener('click', function() {
               // Remove selected class from all options
               statusOptions.forEach(opt => opt.classList.remove('selected'));
               
               // Add selected class to clicked option
               this.classList.add('selected');
               
               // Update hidden input value
               statusInput.value = this.getAttribute('data-value');
           });
       });
   });
   
   function toggleSidebar() {
       document.getElementById('sidebar').classList.toggle('active');
   }
</script>
<?php require APPROOT.'/views/chiefCoordinator/footer.php';?>