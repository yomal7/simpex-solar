<?php
    class Pages extends Controller {
        private $pagesModel;
        private $feedbackModel;

        public function __construct() {
            // Initialize the M_Pages model
            $this->pagesModel = $this->model("M_Pages");
            $this->feedbackModel = $this->model("M_feedback");
        }

        public function index() {
            $data = [];
            $this->view('pages/v_index', $data); // Correct view path
        }
    
        public function about() {
            // Correct method call on $pagesModel
            $users = $this->pagesModel->getUsers();
            
            $data = [
                'users' => $users
            ];

            $this->view('pages/v_about', $data);
        }


        public function feedback(){
            $data = [
                'name' => '',
                'email' => '',
                'phone' => '',
                'feedback_type' => '',
                'subject' => '',
                'message' => '',
                'rating' => '',
                'name_err' => '',
                'email_err' => '',
                'feedback_type_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];

            $this->view('pages/v_feedback', $data);
    }

    public function submitFeedback(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (isset($this-> feedbackModel)){
                $this->feedbackModel = $this-> model('M_Feedback');
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : null,
                'feedback_type' => trim($_POST['feedback_type']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'rating' => isset($_POST['rating']) ? (int)$_POST['rating'] : null,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => date('Y-m-d H:i:s'),
                // Error fields
                'name_err' => '',
                'email_err' => '',
                'feedback_type_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email address';
            }
            
            // Validate feedback type
            if (empty($data['feedback_type'])) {
                $data['feedback_type_err'] = 'Please select a feedback type';
            }
            
            // Validate subject
            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject';
            }
            
            // Validate message
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter your message';
            }
            
            // Check for errors
            if (
                empty($data['name_err']) && 
                empty($data['email_err']) && 
                empty($data['feedback_type_err']) && 
                empty($data['subject_err']) && 
                empty($data['message_err'])
            ) {
                // No errors, save feedback to database
                if ($this->feedbackModel->saveFeedback($data)) {
                    // Successfully saved, send email notification
                    // $this->sendFeedbackNotification($data);
                    
                    // Set success message
                    flash('feedback_message', 'Thank you for your feedback! We appreciate your input and will review it shortly.', 'alert alert-success');
                    
                    // Redirect to feedback page
                    redirect('pages/feedback');
                } else {
                    // Database error occurred
                    flash('feedback_message', 'Sorry, there was a problem saving your feedback. Please try again later.', 'alert alert-danger');
                    
                    // Reload the form with user data
                    $this->view('pages/v_submitFeedback', $data);
                }
            } else {
                // Validation errors, reload form with errors and user input
                $this->view('pages/v_submitFeedback', $data);
            }
        } else {
            // Not a POST request, redirect to the feedback form
            redirect('pages/feedback');
        }
    }

    private function sendFeedbackNotification($data) {
        // Email recipient
        $to = 'admin@simpexsolar.com'; // Change to your admin email
        
        // Email subject
        $subject = 'New Feedback: ' . $data['subject'];
        
        // Build email message
        $message = "A new feedback has been submitted on your website:\n\n";
        $message .= "From: " . $data['name'] . "\n";
        $message .= "Email: " . $data['email'] . "\n";
        
        if ($data['phone']) {
            $message .= "Phone: " . $data['phone'] . "\n";
        }
        
        $message .= "Feedback Type: " . ucfirst($data['feedback_type']) . "\n";
        
        if ($data['rating']) {
            $message .= "Rating: " . $data['rating'] . " stars\n";
        }
        
        $message .= "Contact Consent: " . ($data['contact_consent'] ? 'Yes' : 'No') . "\n\n";
        $message .= "Message:\n" . $data['message'] . "\n\n";
        $message .= "Submitted from: " . $_SERVER['HTTP_REFERER'] . "\n";
        $message .= "Date: " . date('Y-m-d H:i:s') . "\n";
        
        // Email headers
        $headers = 'From: noreply@simpexsolar.com' . "\r\n" .
                   'Reply-To: ' . $data['email'] . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        
        // Send email
        mail($to, $subject, $message, $headers);
    }
}

