<?php require APPROOT.'/views/pages/header.php';?>
    <!-- Top Navbar -->
     <?php require APPROOT.'/views/inc/components/topnavbar.php'; ?>
     <div class="container">
    <div class="feedback-form-wrapper">
        <div class="form-header">
            <h2>Share Your Feedback</h2>
            <p>We value your opinion and continuously strive to improve our services</p>
        </div>

        <?php flash('feedback_message'); ?>

        <form action="<?php echo URLROOT; ?>/pages/submitFeedback" method="POST" class="feedback-form" id="feedbackForm">
            <!-- Form fields remain the same -->
            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" required>
                <span class="invalid-feedback"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" required>
                <span class="invalid-feedback"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
            </div>
            
            
            <div class="form-group">
                <label for="feedback_type">Feedback Type <span class="required">*</span></label>
                <select id="feedback_type" name="feedback_type" required>
                    <option value="" <?php echo !isset($data['feedback_type']) ? 'selected' : ''; ?>>Select an option</option>
                    <option value="general" <?php echo (isset($data['feedback_type']) && $data['feedback_type'] == 'general') ? 'selected' : ''; ?>>General Feedback</option>
                    <option value="suggestion" <?php echo (isset($data['feedback_type']) && $data['feedback_type'] == 'suggestion') ? 'selected' : ''; ?>>Suggestion</option>
                    <option value="complaint" <?php echo (isset($data['feedback_type']) && $data['feedback_type'] == 'complaint') ? 'selected' : ''; ?>>Complaint</option>
                    <option value="compliment" <?php echo (isset($data['feedback_type']) && $data['feedback_type'] == 'compliment') ? 'selected' : ''; ?>>Compliment</option>
                    <option value="inquiry" <?php echo (isset($data['feedback_type']) && $data['feedback_type'] == 'inquiry') ? 'selected' : ''; ?>>Product Inquiry</option>
                </select>
                <span class="invalid-feedback"><?php echo isset($data['feedback_type_err']) ? $data['feedback_type_err'] : ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="subject">Subject <span class="required">*</span></label>
                <!-- pattern="^07\d{8}$" -->
                <input type="text" id="subject" name="subject" value="<?php echo isset($data['subject']) ? $data['subject'] : ''; ?>" required>
                <span class="invalid-feedback"><?php echo isset($data['subject_err']) ? $data['subject_err'] : ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="message">Your Message <span class="required">*</span></label>
                <textarea id="message" name="message" rows="5" required><?php echo isset($data['message']) ? $data['message'] : ''; ?></textarea>
                <span class="invalid-feedback"><?php echo isset($data['message_err']) ? $data['message_err'] : ''; ?></span>
            </div>
            
            <div class="form-group">
                <label>Rate Your Experience</label>
                <div class="rating-group">
                    <input type="radio" id="star5" name="rating" value="5" <?php echo (isset($data['rating']) && $data['rating'] == 5) ? 'checked' : ''; ?>>
                    <label for="star5" title="5 stars">★</label>
                    <input type="radio" id="star4" name="rating" value="4" <?php echo (isset($data['rating']) && $data['rating'] == 4) ? 'checked' : ''; ?>>
                    <label for="star4" title="4 stars">★</label>
                    <input type="radio" id="star3" name="rating" value="3" <?php echo (isset($data['rating']) && $data['rating'] == 3) ? 'checked' : ''; ?>>
                    <label for="star3" title="3 stars">★</label>
                    <input type="radio" id="star2" name="rating" value="2" <?php echo (isset($data['rating']) && $data['rating'] == 2) ? 'checked' : ''; ?>>
                    <label for="star2" title="2 stars">★</label>
                    <input type="radio" id="star1" name="rating" value="1" <?php echo (isset($data['rating']) && $data['rating'] == 1) ? 'checked' : ''; ?>>
                    <label for="star1" title="1 star">★</label>
                </div>
            </div>
            
            <div class="form-group">
                <input type="submit" value="Submit Feedback" class="btn">
            </div>
        </form>
        
        <div class="back-link">
            <a href="<?php echo URLROOT; ?>/pages/index">&larr; Back to Home</a>
        </div>
    </div>
</div>

<!-- Success Modal Popup -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64">
                <circle cx="12" cy="12" r="11" fill="#4CAF50" />
                <path d="M9.75 15.4l-3.5-3.5-1.75 1.75 5.25 5.25 11-11-1.75-1.75-9.25 9.25z" fill="white" />
            </svg>
        </div>
        <h2>Thank You!</h2>
        <p>Your feedback has been submitted successfully.</p>
        <p>We appreciate your input and will use it to improve our services.</p>
        <button id="closeModalBtn" class="btn">Continue</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if there's a success message in the session flash
        const flashMessage = document.querySelector('.flash-success');
        const modal = document.getElementById('successModal');
        const closeBtn = document.querySelector('.close');
        const continueBtn = document.getElementById('closeModalBtn');
        const form = document.getElementById('feedbackForm');
        
        // Show modal if form was submitted successfully
        if (flashMessage) {
            modal.style.display = 'block';
            // Hide the flash message since we're showing the modal
            flashMessage.style.display = 'none';
        }
        
        // Close modal when clicking the X
        closeBtn.onclick = function() {
            modal.style.display = 'none';
            window.location.href = '<?php echo URLROOT; ?>/pages/index';
        }
        
        // Close modal when clicking the Continue button
        continueBtn.onclick = function() {
            modal.style.display = 'none';
            window.location.href = '<?php echo URLROOT; ?>/pages/index';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                window.location.href = '<?php echo URLROOT; ?>/pages/index';
            }
        }
        
        // Handle form submission to show modal on successful submit
        form.addEventListener('submit', function(e) {
            // Form will submit normally - the modal will show after redirect if successful
        });
    });
</script>

<?php require APPROOT.'/views/inc/components/bottomfooter.php'; ?>
<?php require APPROOT.'/views/pages/footer.php'; ?>