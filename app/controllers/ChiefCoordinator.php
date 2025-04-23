<?php
class ChiefCoordinator extends Controller {
    private $feedbackModel;
    private $chiefCoordinatorModel;
    private $mailer;
    
    public function __construct() {
        // Check if user is logged in and is a chief coordinator
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'chiefCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        
        $this->feedbackModel = $this->model('M_Feedback');
        $this->chiefCoordinatorModel = $this->model('M_ChiefCoordinator');
        require_once APPROOT . '/libraries/Mailer.php';
        $this->mailer = new Mailer();
    }

    public function index() {
        $data = [];
        
        $this->view('chiefCoordinator/v_dashboard', $data);
    }
    public function dashboard() {
        $data = [];
        $this->view('chiefCoordinator/v_dashboard', $data);
    }
    
    public function preProjects() {
        // Get pagination parameters
        $projectsPage = isset($_GET['projects_page']) ? (int)$_GET['projects_page'] : 1;
        $quotationsPage = isset($_GET['quotations_page']) ? (int)$_GET['quotations_page'] : 1;
        
        // Get pre-project statistics and data
        $preProjectStats = $this->chiefCoordinatorModel->getPreProjectStats();
        $preProjectsData = $this->chiefCoordinatorModel->getAllPreProjects($projectsPage, 10);
        $quotationsData = $this->chiefCoordinatorModel->getRecentQuotations($quotationsPage, 5);
        
        $data = [
            'stats' => $preProjectStats,
            'pre_projects' => $preProjectsData['pre_projects'],
            'projects_pagination' => [
                'page' => $preProjectsData['page'],
                'total_pages' => $preProjectsData['total_pages'],
                'total' => $preProjectsData['total']
            ],
            'recent_quotations' => $quotationsData['quotations'],
            'quotations_pagination' => [
                'page' => $quotationsData['page'],
                'total_pages' => $quotationsData['total_pages'],
                'total' => $quotationsData['total']
            ]
        ];
        
        $this->view('chiefCoordinator/v_preProjects', $data);
    }
    
    public function generateReport() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get data for the report
            $preProjectStats = $this->chiefCoordinatorModel->getPreProjectStats();
            $preProjectsData = $this->chiefCoordinatorModel->getAllPreProjects(1, 100); // Get more records for report
            
            $data = [
                'stats' => $preProjectStats,
                'pre_projects' => $preProjectsData['pre_projects'],
                'report_date' => date('Y-m-d H:i:s')
            ];
            
            // Generate the report view
            $this->view('chiefCoordinator/v_preProjectsReport', $data);
        } else {
            redirect('chiefCoordinator/preProjects');
        }
    }

    public function projects() {
        // Get filter params or set defaults
        $timeframe = $_GET['timeframe'] ?? 'all';
        $projectsPage = $_GET['projects_page'] ?? 1;
        $phase = $_GET['phase'] ?? 'all';
        $status = $_GET['status'] ?? 'all';
        
        // Get projects data with pagination and filters
        $projectsData = $this->chiefCoordinatorModel->getProjects($projectsPage, 10, $timeframe, $phase, $status);
        
        // Get stats for charts
        $stats = $this->chiefCoordinatorModel->getProjectStats($timeframe);
        
        $data = [
            'projects_pagination' => [
                'page' => $projectsPage,
                'total' => $projectsData['total'],
                'total_pages' => $projectsData['total_pages'],
                'limit' => $projectsData['limit']
            ],
            'projects' => $projectsData['projects'],
            'stats' => $stats,
            'timeframe' => $timeframe,
            'phase_filter' => $phase,
            'status_filter' => $status
        ];
        
        $this->view('chiefCoordinator/v_projects', $data);
    }
    
    public function generateProjectsReport() {
        // Get filter params or set defaults
        $timeframe = $_POST['timeframe'] ?? 'all';
        $phase = $_POST['phase'] ?? 'all';
        $status = $_POST['status'] ?? 'all';
        
        // Get all projects for the report (no pagination)
        $projectsData = $this->chiefCoordinatorModel->getProjects(1, 1000, $timeframe, $phase, $status);
        
        // Get stats for charts
        $stats = $this->chiefCoordinatorModel->getProjectStats($timeframe);
        
        $data = [
            'projects' => $projectsData['projects'],
            'stats' => $stats,
            'report_date' => date('Y-m-d H:i:s'),
            'timeframe' => $timeframe,
            'phase_filter' => $phase,
            'status_filter' => $status
        ];
        
        $this->view('chiefCoordinator/v_projectsReport', $data);
    }


    //------------------------------------------------------------------------------------------------------------------------
    

    
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