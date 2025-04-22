<?php
class ChiefCoordinator extends Controller {
    private $feedbackModel;
    private $chiefCoordinatorModel;
    private $projectModel;
    private $shopModel;
    private $employeeModel;
    private $mailer;
    
    public function __construct() {
        // Check if user is logged in and is a chief coordinator
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'chiefCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        
        $this->feedbackModel = $this->model('M_Feedback');
        $this->chiefCoordinatorModel = $this->model('M_ChiefCoordinator');
        $this->projectModel = $this->model('M_CustomerPreProject');
        $this->shopModel = $this->model('M_Shop');
        $this->employeeModel = $this->model('M_Employee');
        require_once APPROOT . '/libraries/Mailer.php';
        $this->mailer = new Mailer();
    }

    public function index() {
        // Get project statistics
        $projectStats = $this->chiefCoordinatorModel->getProjectStats();
        $monthlyProjectStats = $this->chiefCoordinatorModel->getMonthlyProjectStats();
        
        // Get payment statistics
        $paymentStats = $this->chiefCoordinatorModel->getPaymentStats();
        $monthlyPaymentStats = $this->chiefCoordinatorModel->getMonthlyPaymentStats();
        
        // Get store statistics
        $storeStats = $this->chiefCoordinatorModel->getStoreStats();
        $monthlyStoreStats = $this->chiefCoordinatorModel->getMonthlyStoreStats();
        
        // Get employee statistics
        $employeeStats = $this->chiefCoordinatorModel->getEmployeeStats();
        $attendanceStats = $this->chiefCoordinatorModel->getMonthlyAttendanceStats();
        $leaveStats = $this->chiefCoordinatorModel->getMonthlyLeaveStats();
        
        $data = [
            'title' => 'Dashboard',
            'projectStats' => $projectStats,
            'monthlyProjectStats' => $monthlyProjectStats,
            'paymentStats' => $paymentStats,
            'monthlyPaymentStats' => $monthlyPaymentStats,
            'storeStats' => $storeStats,
            'monthlyStoreStats' => $monthlyStoreStats,
            'employeeStats' => $employeeStats,
            'attendanceStats' => $attendanceStats,
            'leaveStats' => $leaveStats
        ];
        
        $this->view('chiefCoordinator/v_dashboard', $data);
    }
    
    public function projectDetails() {
        $monthlyProjectStats = $this->chiefCoordinatorModel->getMonthlyProjectStats();
        $projectStatusStats = $this->chiefCoordinatorModel->getProjectStatusStats();
        $projectPhaseStats = $this->chiefCoordinatorModel->getProjectPhaseStats();
        
        $data = [
            'title' => 'Project Details',
            'monthlyProjectStats' => $monthlyProjectStats,
            'projectStatusStats' => $projectStatusStats,
            'projectPhaseStats' => $projectPhaseStats
        ];
        
        $this->view('chiefCoordinator/v_projectDetails', $data);
    }
    
    public function paymentDetails() {
        $monthlyPaymentStats = $this->chiefCoordinatorModel->getMonthlyPaymentStats();
        $paymentMethodStats = $this->chiefCoordinatorModel->getPaymentMethodStats();
        
        $data = [
            'title' => 'Payment Details',
            'monthlyPaymentStats' => $monthlyPaymentStats,
            'paymentMethodStats' => $paymentMethodStats
        ];
        
        $this->view('chiefCoordinator/v_paymentDetails', $data);
    }
    
    public function storeDetails() {
        $monthlyStoreStats = $this->chiefCoordinatorModel->getMonthlyStoreStats();
        $productCategoryStats = $this->chiefCoordinatorModel->getProductCategoryStats();
        
        $data = [
            'title' => 'Store Details',
            'monthlyStoreStats' => $monthlyStoreStats,
            'productCategoryStats' => $productCategoryStats
        ];
        
        $this->view('chiefCoordinator/v_storeDetails', $data);
    }
    
    public function employeeDetails() {
        $attendanceStats = $this->chiefCoordinatorModel->getMonthlyAttendanceStats();
        $leaveStats = $this->chiefCoordinatorModel->getMonthlyLeaveStats();
        $employeeRoleStats = $this->chiefCoordinatorModel->getEmployeeRoleStats();
        
        $data = [
            'title' => 'Employee Details',
            'attendanceStats' => $attendanceStats,
            'leaveStats' => $leaveStats,
            'employeeRoleStats' => $employeeRoleStats
        ];
        
        $this->view('chiefCoordinator/v_employeeDetails', $data);
    }
    
    public function printDashboard() {
        // Get all stats for printing
        $projectStats = $this->chiefCoordinatorModel->getProjectStats();
        $paymentStats = $this->chiefCoordinatorModel->getPaymentStats();
        $storeStats = $this->chiefCoordinatorModel->getStoreStats();
        $employeeStats = $this->chiefCoordinatorModel->getEmployeeStats();
        
        // Monthly data
        $monthlyProjectStats = $this->chiefCoordinatorModel->getMonthlyProjectStats();
        $monthlyPaymentStats = $this->chiefCoordinatorModel->getMonthlyPaymentStats();
        $monthlyStoreStats = $this->chiefCoordinatorModel->getMonthlyStoreStats();
        $attendanceStats = $this->chiefCoordinatorModel->getMonthlyAttendanceStats();
        $leaveStats = $this->chiefCoordinatorModel->getMonthlyLeaveStats();
        
        $data = [
            'title' => 'Print Dashboard',
            'projectStats' => $projectStats,
            'paymentStats' => $paymentStats,
            'storeStats' => $storeStats,
            'employeeStats' => $employeeStats,
            'monthlyProjectStats' => $monthlyProjectStats,
            'monthlyPaymentStats' => $monthlyPaymentStats,
            'monthlyStoreStats' => $monthlyStoreStats,
            'attendanceStats' => $attendanceStats,
            'leaveStats' => $leaveStats
        ];
        
        $this->view('chiefCoordinator/v_printDashboard', $data);
    }
    
    // View all feedbacks
    public function feedbacks() {
        $stats = $this->feedbackModel->getFeedbackStats();
        $feedbacks = $this->feedbackModel->getAllFeedback();
        
        $data = [
            'title' => 'Manage Feedbacks',
            'feedbacks' => $feedbacks,
            'stats' => $stats
        ];
        
        $this->view('chiefCoordinator/v_feedbacks', $data);
    }
    
    // View a single feedback
    public function viewFeedback($id) {
        $feedback = $this->feedbackModel->getFeedbackById($id);
        
        if(!$feedback) {
            flash('feedback_message', 'Feedback not found', 'alert alert-danger');
            redirect('chiefCoordinator/feedbacks');
        }
        
        $data = [
            'title' => 'View Feedback',
            'feedback' => $feedback
        ];
        
        $this->view('chiefCoordinator/v_viewFeedback', $data);
    }

    public function deleteFeedback($id) {
        // Check if user is authorized
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'chiefCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
            return;
        }
        
        // Get the feedback to verify it exists
        $feedback = $this->feedbackModel->getFeedbackById($id);
        
        if(!$feedback) {
            flash('feedback_message', 'Feedback not found', 'alert alert-danger');
            redirect('chiefCoordinator/feedbacks');
            return;
        }
        
        // Delete the feedback
        if($this->feedbackModel->deleteFeedback($id)) {
            flash('feedback_message', 'Feedback deleted successfully', 'alert alert-success');
        } else {
            flash('feedback_message', 'Unable to delete feedback', 'alert alert-danger');
        }
        
        redirect('chiefCoordinator/feedbacks');
    }
    
    // Update feedback status
    public function updateStatus() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get POST data
            $id = $_POST['id'];
            $status = $_POST['status'];
            $adminNotes = trim($_POST['admin_notes'] ?? '');
            
            if($this->feedbackModel->updateFeedback($id, $status, $adminNotes)) {
                flash('feedback_message', 'Feedback status updated successfully');
            } else {
                flash('feedback_message', 'Failed to update feedback status', 'alert alert-danger');
            }
            
            redirect('chiefCoordinator/viewFeedback/' . $id);
        } else {
            redirect('chiefCoordinator/feedbacks');
        }
    }
    
    // Send response email
    public function sendResponse() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $response = trim($_POST['response']);
            $email = $_POST['email'];
            $name = $_POST['name'];
            $subject = $_POST['subject'];
            
            // Get the feedback for reference
            $feedback = $this->feedbackModel->getFeedbackById($id);
            
            if(!$feedback) {
                flash('feedback_message', 'Feedback not found', 'alert alert-danger');
                redirect('chiefCoordinator/feedbacks');
                return;
            }
            
            // Send email response using Mailer
            $emailSent = $this->mailer->sendFeedbackResponse($email, $name, $subject, $response);
            
            if($emailSent) {
                // Update feedback with response details
                $adminNotes = $feedback->admin_notes ?? '';
                $adminNotes .= "\n\n[" . date('Y-m-d H:i:s') . "] Email response sent:\n" . $response;
                
                $this->feedbackModel->updateFeedback($id, 'in_progress', $adminNotes);
                flash('feedback_message', 'Response sent successfully');
            } else {
                flash('feedback_message', 'Failed to send email response', 'alert alert-danger');
            }
            
            redirect('chiefCoordinator/viewFeedback/' . $id);
        } else {
            redirect('chiefCoordinator/feedbacks');
        }
    }
}