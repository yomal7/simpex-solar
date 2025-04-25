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
        // Get timeframe filter or set default
        $timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : 'all';
        
        // Get statistics for each section
        $projectStats = $this->chiefCoordinatorModel->getProjectStats($timeframe);
        $storeStats = $this->chiefCoordinatorModel->getStoreStats($timeframe);
        $paymentStats = $this->chiefCoordinatorModel->getPaymentStats($timeframe);
        $employeeStats = $this->chiefCoordinatorModel->getEmployeeStats($timeframe);
        
        // Get counts for summary cards
        $totalEmployees = $this->chiefCoordinatorModel->getTotalEmployeeCount();
        $presentToday = $this->chiefCoordinatorModel->getTodayAttendanceCount();
        $attendanceRate = ($totalEmployees > 0) ? round(($presentToday / $totalEmployees) * 100) : 0;
        
        // Get recent data for tables with pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $projectsData = $this->chiefCoordinatorModel->getProjects($page, 5, $timeframe);
        $paymentsData = $this->chiefCoordinatorModel->getPayments($page, 5, $timeframe);
        $storeOrdersData = $this->chiefCoordinatorModel->getStoreOrders($page, 5, $timeframe);
        
        $data = [
            'timeframe' => $timeframe,
            'project_stats' => $projectStats,
            'store_stats' => $storeStats,
            'payment_stats' => $paymentStats,
            'employee_stats' => $employeeStats,
            'total_employees' => $totalEmployees,
            'present_today' => $presentToday,
            'attendance_rate' => $attendanceRate,
            'projects' => $projectsData['projects'],
            'projects_pagination' => [
                'page' => $projectsData['page'],
                'total' => $projectsData['total'],
                'total_pages' => $projectsData['total_pages']
            ],
            'payments' => $paymentsData['payments'],
            'payments_pagination' => [
                'page' => $paymentsData['page'],
                'total' => $paymentsData['total'],
                'total_pages' => $paymentsData['total_pages']
            ],
            'store_orders' => $storeOrdersData['orders'],
            'store_pagination' => [
                'page' => $storeOrdersData['page'],
                'total' => $storeOrdersData['total'],
                'total_pages' => $storeOrdersData['total_pages']
            ]
        ];
        
        $this->view('chiefCoordinator/v_dashboard', $data);
    }
    
    public function dashboard() {
        // Redirect to index since it's the same functionality
        redirect('chiefCoordinator/index');
    }
    
    public function generateDashboardReport() {
        // Get timeframe filter
        $timeframe = isset($_POST['timeframe']) ? $_POST['timeframe'] : 'all';
        
        // Get statistics for each section
        $projectStats = $this->chiefCoordinatorModel->getProjectStats($timeframe);
        $storeStats = $this->chiefCoordinatorModel->getStoreStats($timeframe);
        $paymentStats = $this->chiefCoordinatorModel->getPaymentStats($timeframe);
        $employeeStats = $this->chiefCoordinatorModel->getEmployeeStats($timeframe);
        
        // Get counts for summary
        $totalEmployees = $this->chiefCoordinatorModel->getTotalEmployeeCount();
        $presentToday = $this->chiefCoordinatorModel->getTodayAttendanceCount();
        $attendanceRate = ($totalEmployees > 0) ? round(($presentToday / $totalEmployees) * 100) : 0;
        
        // Get more records for the report (no pagination)
        $projectsData = $this->chiefCoordinatorModel->getProjects(1, 20, $timeframe);
        $paymentsData = $this->chiefCoordinatorModel->getPayments(1, 20, $timeframe);
        $storeOrdersData = $this->chiefCoordinatorModel->getStoreOrders(1, 20, $timeframe);
        
        $data = [
            'report_date' => date('Y-m-d H:i:s'),
            'timeframe' => $timeframe,
            'project_stats' => $projectStats,
            'store_stats' => $storeStats,
            'payment_stats' => $paymentStats,
            'employee_stats' => $employeeStats,
            'total_employees' => $totalEmployees,
            'present_today' => $presentToday,
            'attendance_rate' => $attendanceRate,
            'projects' => $projectsData['projects'],
            'payments' => $paymentsData['payments'],
            'store_orders' => $storeOrdersData['orders']
        ];
        
        $this->view('chiefCoordinator/v_dashboardReport', $data);
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

    public function store() {
        $timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : 'all';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $chiefCoordinatorModel = $this->model('M_ChiefCoordinator');
        
        // Get store statistics
        $storeStats = $chiefCoordinatorModel->getStoreStats($timeframe);
        
        // Get paginated orders
        $ordersData = $chiefCoordinatorModel->getStoreOrders($page, 10, $timeframe);
        
        $data = [
            'title' => 'Store Information',
            'stats' => $storeStats,
            'orders' => $ordersData['orders'],
            'pagination' => [
                'page' => $ordersData['page'],
                'total_pages' => $ordersData['total_pages'],
                'total' => $ordersData['total']
            ],
            'timeframe' => $timeframe
        ];
        
        $this->view('chiefCoordinator/v_store', $data);
    }
    
    public function generateStoreReport() {
        $timeframe = isset($_POST['timeframe']) ? $_POST['timeframe'] : 'all';
        
        $chiefCoordinatorModel = $this->model('M_ChiefCoordinator');
        
        // Get store statistics
        $storeStats = $chiefCoordinatorModel->getStoreStats($timeframe);
        
        // Get all orders for the report (limited to 100 for practical reasons)
        $ordersData = $chiefCoordinatorModel->getStoreOrders(1, 100, $timeframe);
        
        $data = [
            'title' => 'Store Report',
            'stats' => $storeStats,
            'orders' => $ordersData['orders'],
            'report_date' => date('Y-m-d H:i:s'),
            'timeframe' => $timeframe
        ];
        
        $this->view('chiefCoordinator/v_storeReport', $data);
    }


    public function payments() {
        // Get parameters from URL for filtering and pagination
        $timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : 'all';
        $payment_type = isset($_GET['payment_type']) ? $_GET['payment_type'] : 'all';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Load model
        $chiefCoordinatorModel = $this->model('M_ChiefCoordinator');
        
        // Get payments with pagination
        $paymentData = $chiefCoordinatorModel->getPayments($page, 10, $timeframe, $payment_type);
        
        // Get statistics for charts
        $stats = $chiefCoordinatorModel->getPaymentStats($timeframe);
        
        // Prepare data for the view
        $data = [
            'payments' => $paymentData['payments'],
            'pagination' => [
                'page' => $paymentData['page'],
                'limit' => $paymentData['limit'],
                'total' => $paymentData['total'],
                'total_pages' => $paymentData['total_pages']
            ],
            'stats' => $stats,
            'filters' => [
                'timeframe' => $timeframe,
                'payment_type' => $payment_type
            ]
        ];
        
        // Load view
        $this->view('chiefCoordinator/v_payments', $data);
    }
    
    // Method to generate payment report
    public function generatePaymentReport() {
        // Get parameters for filtering
        $timeframe = isset($_POST['timeframe']) ? $_POST['timeframe'] : 'all';
        $payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : 'all';
        
        // Load model
        $chiefCoordinatorModel = $this->model('M_ChiefCoordinator');
        
        // Get all payments for the report (without pagination)
        $reportData = $chiefCoordinatorModel->getPayments(1, 1000, $timeframe, $payment_type);
        
        // Get statistics for charts
        $stats = $chiefCoordinatorModel->getPaymentStats($timeframe);
        
        // Prepare data for the report view
        $data = [
            'payments' => $reportData['payments'],
            'stats' => $stats,
            'report_date' => date('Y-m-d H:i:s'),
            'filters' => [
                'timeframe' => $timeframe,
                'payment_type' => $payment_type
            ]
        ];
        
        // Load report view
        $this->view('chiefCoordinator/v_paymentsReport', $data);
    }
public function employees() {
    // Check for query parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $role = isset($_GET['role']) ? $_GET['role'] : 'all';
    $timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : 'all';
    
    // Debug parameters
    error_log('Parameters: page=' . $page . ', role=' . $role . ', timeframe=' . $timeframe);
    
    // Get employee stats
    $stats = $this->chiefCoordinatorModel->getEmployeeStats($timeframe);
    
    // Get specific counts for attendance rate calculation
    $totalEmployees = $this->chiefCoordinatorModel->getTotalEmployeeCount();
    $presentToday = $this->chiefCoordinatorModel->getTodayAttendanceCount();
    $attendanceRate = ($totalEmployees > 0) ? round(($presentToday / $totalEmployees) * 100) : 0;
    
    // Debug attendance rate calculation
    error_log('Attendance rate calculation: ' . $presentToday . ' / ' . $totalEmployees . ' = ' . $attendanceRate . '%');
    
    // Get all employees with pagination
    $employeesData = $this->chiefCoordinatorModel->getAllEmployees($page, 10, $role);
    
    // Debug employee data
    error_log('Employees returned: ' . count($employeesData['employees']));
    if (count($employeesData['employees']) > 0) {
        error_log('First employee data: ' . print_r($employeesData['employees'][0], true));
    } else {
        error_log('No employees found in database query result');
    }
    
    // Get attendance data with pagination
    $attendancePage = isset($_GET['attendance_page']) ? (int)$_GET['attendance_page'] : 1;
    $attendanceData = $this->chiefCoordinatorModel->getRecentAttendance($attendancePage, 10);
    
    // Get leave data with pagination
    $leavePage = isset($_GET['leave_page']) ? (int)$_GET['leave_page'] : 1;
    $leaveData = $this->chiefCoordinatorModel->getLeaveRecords($leavePage, 10);
    
    $data = [
        'stats' => $stats,
        'total_employees' => $totalEmployees,
        'present_today' => $presentToday,
        'attendance_rate' => $attendanceRate,
        'employees' => $employeesData['employees'],
        'employees_pagination' => [
            'page' => $page,
            'total' => $employeesData['total'],
            'total_pages' => $employeesData['total_pages']
        ],
        'attendance' => $attendanceData['attendance'],
        'attendance_pagination' => [
            'page' => $attendancePage,
            'total' => $attendanceData['total'],
            'total_pages' => $attendanceData['total_pages']
        ],
        'leaves' => $leaveData['leaves'],
        'leaves_pagination' => [
            'page' => $leavePage,
            'total' => $leaveData['total'],
            'total_pages' => $leaveData['total_pages']
        ],
        'current_timeframe' => $timeframe,
        'current_role' => $role
    ];
    
    $this->view('chiefCoordinator/v_employees', $data);
}
    
    public function generateEmployeeReport() {
        // Generate report logic here
        $stats = $this->chiefCoordinatorModel->getEmployeeStats();
        $employeesData = $this->chiefCoordinatorModel->getAllEmployees(1, 100); // Get more employees for the report
        
        $data = [
            'report_date' => date('Y-m-d H:i:s'),
            'stats' => $stats,
            'employees' => $employeesData['employees']
        ];
        
        $this->view('chiefCoordinator/v_employeesReport', $data);
    }
}