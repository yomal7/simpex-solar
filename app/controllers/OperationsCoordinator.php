<?php
class OperationsCoordinator extends Controller
{
    private $clientModel;
    private $tasksModel;
    private $packageModel;
    private $inventoryModel;
    private $employeeModel;
    private $preProjectModel;
    private $operationsCoordinatorModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operationsCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->tasksModel = $this->model('M_Tasks');
        $this->packageModel = $this->model('M_Packages');
        $this->inventoryModel = $this->model('M_Inventory');
        $this->employeeModel = $this->model('M_Employee');
        $this->preProjectModel = $this->model('M_CustomerPreProject');
        $this->operationsCoordinatorModel = $this->model('M_OperationsCoordinator');
    }

    public function index()
    {
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function dashboard()
    {
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function projects()
    {
        $data = [];
        $this->view('operationsCoordinator/v_projects', $data);
    }

    public function projectDashboard()
    {
        $data = [];
        $this->view('operationsCoordinator/v_projectDashboard', $data);
    }

    //################################################################################################
    //-------------------------------------Add signature----------------------------------------------
    //################################################################################################

    public function signature() {
        $signature = $this->operationsCoordinatorModel->getSignatureByCoordinatorId($_SESSION['user_id']);
        
        $data = [
            'title' => 'Signature Upload',
            'signature' => $signature,
            'signature_err' => ''
        ];
        
        $this->view('operationsCoordinator/v_signature', $data);
    }

    public function uploadSignature() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/signature');
        }

        // Basic validation
        if (!isset($_FILES['signature']) || $_FILES['signature']['error'] !== UPLOAD_ERR_OK) {
            flash('signature_msg', 'Please select a signature image', 'error');
            redirect('operationsCoordinator/signature');
            return;
        }

        $file = $_FILES['signature'];
        
        // Validate file
        if (!$this->validateSignatureFile($file)) {
            redirect('operationsCoordinator/signature');
            return;
        }

        // Generate filename and set paths
        $filename = uniqid() . '_' . time() . '_' . basename($file['name']);
        $uploadDir = dirname(APPROOT) . '/public/uploads/signatures';
        $uploadPath = $uploadDir . '/' . $filename;

        // Create directory if needed
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            if ($this->operationsCoordinatorModel->updateSignature($_SESSION['user_id'], $filename)) {
                flash('signature_msg', 'Signature uploaded successfully', 'success');
            } else {
                unlink($uploadPath);
                flash('signature_msg', 'Failed to update signature in database', 'error');
            }
        } else {
            flash('signature_msg', 'Failed to upload signature file', 'error');
        }

        redirect('operationsCoordinator/signature');
    }

    private function validateSignatureFile($file) {
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            flash('signature_msg', 'Only PNG, JPEG, and JPG files are allowed', 'error');
            return false;
        }

        if ($file['size'] > $maxSize) {
            flash('signature_msg', 'File size must be less than 5MB', 'error');
            return false;
        }

        return true;
    }

    public function deleteSignature() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/signature');
        }

        $signature = $this->operationsCoordinatorModel->getSignatureByCoordinatorId($_SESSION['user_id']);
        
        if ($signature) {
            $filePath = dirname(APPROOT) . '/public/uploads/signatures/' . $signature->signature_image;
            
            if (file_exists($filePath) && unlink($filePath)) {
                if ($this->operationsCoordinatorModel->deleteSignature($_SESSION['user_id'])) {
                    flash('signature_msg', 'Signature deleted successfully', 'success');
                } else {
                    flash('signature_msg', 'Failed to update database', 'error');
                }
            } else {
                flash('signature_msg', 'Failed to delete signature file', 'error');
            }
        }

        redirect('operationsCoordinator/signature');
    }



    //PreProjects


    public function preProjects() {
        $currentPhase = isset($_GET['phase']) ? $_GET['phase'] : 'all';
        
        // Get project statistics
        $stats = $this->preProjectModel->getPreProjectStats();
        
        // Get pre-projects
        $preProjects = $this->preProjectModel->getAllPreProjects($currentPhase);
        
        $data = [
            'stats' => $stats,
            'preProjects' => $preProjects,
            'current_phase' => $currentPhase
        ];
        
        $this->view('operationsCoordinator/v_preProjects', $data);
    }

    
    public function managePreProject($preProjectId = null) {
        if ($preProjectId === null) {
            redirect('operationsCoordinator/preProjects');
        }
    
        // Get project details
        $project = $this->preProjectModel->getPreProjectById($preProjectId);
        
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
    
        $data = [
            'project' => $project,
            'title' => 'Manage Project'
        ];
    
        $this->view('operationsCoordinator/v_manageApreProject', $data);
    }

    public function manageAgreement($preProjectId = null) {
        if ($preProjectId === null) {
            redirect('operationsCoordinator/preProjects');
        }
    
        // Check if there's a revision requested agreement
        $revisionAgreement = $this->preProjectModel->getRevisionRequestedAgreement($preProjectId);
        
        if ($revisionAgreement) {
            // If there's a revision requested agreement, redirect to revision page
            redirect("operationsCoordinator/agreementRevision/{$revisionAgreement->agreement_id}");
        } else {
            // If no revision requested agreement, redirect to create agreement
            redirect("operationsCoordinator/createAgreement/$preProjectId");
        }


        
    }
//-------------------------------------------------------------------------------------------
    //Review Quotation
    
    public function manageQuotation($preProjectId = null) {
        if ($preProjectId === null) {
            redirect('operationsCoordinator/preProjects');
        }
    
        $project = $this->preProjectModel->getPreProjectById($preProjectId);
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
    
        // Get quotation with package details
        $quotation = $this->preProjectModel->getQuotationWithPackageDetails($preProjectId);
        
        // Get package equipment
        $packageEquipment = [];
        if ($quotation && $quotation->package_id) {
            $packageEquipment = $this->preProjectModel->getPackageEquipmentDetails($quotation->package_id);
        }
        
        error_log(print_r($quotation, true));
        $data = [
            'project' => $project,
            'quotation' => $quotation,
            'packageEquipment' => $packageEquipment,
            'title' => 'Manage Quotation'
        ];
    
        $this->view('operationsCoordinator/v_manageQuotation', $data);
    }
    
    public function getPackageEquipment($packageId) {
        if (!$packageId) {
            echo json_encode([]);
            return;
        }
        
        $this->preProjectModel->query('SELECT 
            pe.equipment_id,
            pe.package_id,
            pe.quantity,
            i.id as item_id,
            i.name as item_name,
            i.price as unit_price
            FROM packageequipment pe
            JOIN inventory i ON pe.item_id = i.id
            WHERE pe.package_id = :package_id
            AND i.deleted_at IS NULL');
            
        $this->preProjectModel->bind(':package_id', $packageId);
        $equipment = $this->preProjectModel->resultSet();
        
        header('Content-Type: application/json');
        echo json_encode($equipment);
    }


    public function saveReviewedQuotation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/preProjects');
        }

        $postData = json_decode(file_get_contents("php://input"), true);
        
        if ($this->preProjectModel->createReviewedQuotation($postData)) {
            $response = ['success' => true, 'message' => 'Quotation saved successfully'];
            
            // Update quotation status
            $this->preProjectModel->updateQuotationStatus(
                $postData['quotation_id'], 
                $postData['status']
            );
        } else {
            $response = ['success' => false, 'message' => 'Error saving quotation'];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function updateQuotationStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/preProjects');
        }

        $postData = json_decode(file_get_contents("php://input"), true);
        
        if ($this->preProjectModel->updateQuotationStatus($postData['quotation_id'], $postData['status'])) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function getQuotationDetails($preProjectId) {
        if (!$preProjectId) {
            echo json_encode(['success' => false, 'message' => 'Project ID is required']);
            return;
        }
    
        $quotation = $this->preProjectModel->getQuotationWithPackageDetails($preProjectId);
        
        if ($quotation) {
            echo json_encode([
                'success' => true,
                'quotation' => [
                    'package_name' => $quotation->package_name,
                    'total_cost' => number_format($quotation->total_cost, 2),
                    'status' => ucfirst($quotation->status),
                    'review_date' => date('F j, Y', strtotime($quotation->review_date))
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Quotation not found']);
        }
    }

    //##################################################################################################
    //-----------------------------------------Site Visit----------------------------------------------
    //##################################################################################################

    public function manageSiteVisit($preProjectId = null) {
        if ($preProjectId === null) {
            redirect('operationsCoordinator/preProjects');
        }
    
        $project = $this->preProjectModel->getPreProjectById($preProjectId);
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
    
        $siteVisit = $this->preProjectModel->getSiteVisitByPreProjectId($preProjectId);
    
        $data = [
            'project' => $project,
            'site_visit' => $siteVisit,
            'title' => 'Manage Site Visit'
        ];
    
        $this->view('operationsCoordinator/v_manageSiteVisit', $data);
    }
    
    public function scheduleSiteVisit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/preProjects');
            return;
        }
    
        $preProjectId = $_POST['pre_project_id'];
        $visitDate = $_POST['visit_date'];
        $visitTime = $_POST['visit_time'];
    
        // Validate time (8 AM to 4 PM)
        $timeValue = strtotime($visitTime);
        $startTime = strtotime('08:00:00');
        $endTime = strtotime('16:00:00');
    
        if ($timeValue < $startTime || $timeValue > $endTime) {
            flash('site_visit_message', 'Site visit time must be between 8 AM and 4 PM', 'error');
            redirect('operationsCoordinator/manageSiteVisit/' . $preProjectId);
            return;
        }
    
        if ($this->preProjectModel->scheduleSiteVisit($preProjectId, $visitDate, $visitTime)) {
            flash('site_visit_message', 'Site visit scheduled successfully', 'success');
        } else {
            flash('site_visit_message', 'Failed to schedule site visit', 'error');
        }
    
        redirect('operationsCoordinator/manageSiteVisit/' . $preProjectId);
    }
    
    public function completeSiteVisit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/preProjects');
            return;
        }
    
        $preProjectId = $_POST['pre_project_id'];
        $notes = $_POST['site_notes'];
    
        if ($this->preProjectModel->completeSiteVisit($preProjectId, $notes)) {
            flash('site_visit_message', 'Site visit completed successfully', 'success');
        } else {
            flash('site_visit_message', 'Failed to complete site visit', 'error');
        }
    
        redirect('operationsCoordinator/manageSiteVisit/' . $preProjectId);
    }

    public function handleReschedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/preProjects');
            return;
        }
    
        $visitId = $_POST['visit_id'];
        $preProjectId = $_POST['pre_project_id'];
        $newDate = $_POST['new_date'];
        $newTime = $_POST['new_time'];
    
        // Validate time (8 AM to 4 PM)
        $timeValue = strtotime($newTime);
        $startTime = strtotime('08:00:00');
        $endTime = strtotime('16:00:00');
    
        if ($timeValue < $startTime || $timeValue > $endTime) {
            flash('site_visit_message', 'Site visit time must be between 8 AM and 4 PM', 'error');
            redirect('operationsCoordinator/manageSiteVisit/' . $preProjectId);
            return;
        }
    
        if ($this->preProjectModel->handleReschedule($visitId, $newDate, $newTime)) {
            flash('site_visit_message', 'Site visit rescheduled successfully', 'success');
        } else {
            flash('site_visit_message', 'Failed to reschedule site visit', 'error');
        }
    
        redirect('operationsCoordinator/manageSiteVisit/' . $preProjectId);
    }

    public function getSiteVisitCalendar() {
        header('Content-Type: application/json');
        $visits = $this->preProjectModel->getAllSiteVisits();
        echo json_encode($visits);
    }

     //####################################################################################################
    //-----------------------------------------Agreement phase--------------------------------------------
    //####################################################################################################

    public function createAgreement($preProjectId = null) {
        if ($preProjectId === null) {
            redirect('operationsCoordinator/preProjects');
        }
    
        // Get project details
        $project = $this->preProjectModel->getPreProjectById($preProjectId);
        $siteVisit = $this->preProjectModel->getSiteVisitByPreProjectId($preProjectId);
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
    
        // Get reviewed quotation
        $quotation = $this->preProjectModel->getReviwedQuotationByPreProjectId($preProjectId);
        
        // Initialize equipment array
        $equipment = [];
    
        if ($quotation && !empty($quotation->review_id)) {
            // Get equipment from reviewed quotation
            $equipment = $this->preProjectModel->getReviewedEquipment($quotation->review_id);
        }
        
        // Get existing agreement if any
        $agreement = $this->preProjectModel->getAgreementByPreProjectId($preProjectId);
        if ($agreement) {
            $equipment = $this->preProjectModel->getAgreementEquipment($agreement->agreement_id);
        }
    
        // Get coordinator signature
        $coordinatorSignature = $this->preProjectModel->getCoordinatorSignature($_SESSION['user_id']);
    
        $data = [
            'project' => $project,
            'quotation' => $quotation,
            'agreement' => $agreement,
            'equipment' => $equipment,
            'coordinator_signature' => $coordinatorSignature,
            'title' => 'Create Agreement',
            'siteVisit' => $siteVisit
        ];
    
        $this->view('operationsCoordinator/v_manageAgreement', $data);
    }

    public function getInventory() {
        header('Content-Type: application/json');
        $inventory = $this->preProjectModel->getAvailableInventory();
        echo json_encode($inventory);
    }

    public function getAgreementEquipment($agreementId) {
        header('Content-Type: application/json');
        $equipment = $this->preProjectModel->getAgreementEquipment($agreementId);
        echo json_encode($equipment);
    }

    public function saveAgreement() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/preProjects');
        }
    
        // Get reviewed quotation
        $reviewedQuotation = $this->preProjectModel->getReviewedQuotationByPreProjectId($_POST['pre_project_id']);
        if (!$reviewedQuotation) {
            echo json_encode([
                'success' => false,
                'message' => 'No reviewed quotation found for this project'
            ]);
            return;
        }
    
        // Handle signature
        $signatureId = null;
        if (isset($_FILES['coordinator_signature']) && $_FILES['coordinator_signature']['error'] === 0) {
            // New signature uploaded
            $signaturePath = $this->handleSignatureUpload($_FILES['coordinator_signature']);
            if ($signaturePath) {
                $saved = $this->preProjectModel->saveCoordinatorSignature($_SESSION['user_id'], $signaturePath);
                if ($saved) {
                    $signature = $this->preProjectModel->getCoordinatorSignature($_SESSION['user_id']);
                    $signatureId = $signature->signature_id;
                }
            }
        } elseif (isset($_POST['use_existing_signature'])) {
            // Use existing signature
            $signature = $this->preProjectModel->getCoordinatorSignature($_SESSION['user_id']);
            if ($signature) {
                $signatureId = $signature->signature_id;
            }
        }
    
        // Prepare data with proper review_id and signature
        $data = [
            'pre_project_id' => $_POST['pre_project_id'],
            'review_id' => $reviewedQuotation->review_id,
            'quotation_id' => $reviewedQuotation->quotation_id,
            'system_capacity' => $_POST['system_capacity'],
            'estimated_generation' => $_POST['estimated_generation'],
            'base_price' => $_POST['base_price'],
            'service_charge' => $_POST['service_charge'],
            'total_price' => $_POST['total_price'],
            'notes' => $_POST['notes'],
            'coordinator_signature_id' => $signatureId,
            'equipment' => json_decode($_POST['equipment'], true)
        ];
    
        $success = $this->preProjectModel->createAgreement($data);
    
        header('Content-Type: application/json');
        if ($success) {
            // Update project phase
            $this->preProjectModel->updatePreProjectPhase($data['pre_project_id'], 'agreement');
            
            echo json_encode([
                'success' => true,
                'message' => 'Agreement saved successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error saving agreement'
            ]);
        }
    }

    private function handleSignatureUpload($file) {
        $uploadDir = 'public/uploads/signatures/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid('sig_') . '_' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $uploadPath;
        }
        return false;
    }

    public function viewAgreement($preProjectId = null) {
        if ($preProjectId === null) {
            redirect('operationsCoordinator/preProjects');
        }

        $agreement = $this->preProjectModel->getAgreementByPreProjectId($preProjectId);
        if (!$agreement) {
            flash('project_message', 'Agreement not found', 'alert alert-danger');
   
         redirect('operationsCoordinator/preProjects');
        }

        $project = $this->preProjectModel->getPreProjectById($preProjectId);
        $equipment = $this->preProjectModel->getAgreementEquipment($agreement->agreement_id);

        $data = [
            'project' => $project,
            'agreement' => $agreement,
            'equipment' => $equipment,
            'title' => 'View Agreement'
        ];

        $this->view('operationsCoordinator/v_viewAgreement', $data);
    }

    public function agreementRevision($agreementId = null) {
        
        // Check if agreement ID is provided
        if ($agreementId === null) {
            error_log("Agreement revision failed: No agreement ID provided");
            flash('agreement_message', 'Invalid agreement ID', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
        
        // Get agreement details
        $agreement = $this->preProjectModel->getAgreementById($agreementId);
        
        // Check if agreement exists
        if (!$agreement) {
            flash('agreement_message', 'Agreement not found', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
        
        // Check if agreement status is revision_requested
        if ($agreement->status !== 'revision_requested') {
            error_log("Agreement revision failed: Invalid status. Expected 'revision_requested', got: " . $agreement->status);
            flash('agreement_message', 'Access denied. Agreement is not pending revision.', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
        
        // Get project details
        $project = $this->preProjectModel->getPreProjectById($agreement->pre_project_id);
        
        if (!$project) {
            error_log("Agreement revision failed: Project not found for pre_project_id: " . $agreement->pre_project_id);
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/preProjects');
        }
        
        // Get equipment from existing agreement
        $equipment = $this->preProjectModel->getAgreementEquipment($agreementId);
        
        // Get coordinator signature
        $coordinatorSignature = $this->preProjectModel->getCoordinatorSignature($_SESSION['user_id']);

        
        $data = [
            'project' => $project,
            'agreement' => $agreement,
            'equipment' => $equipment,
            'coordinator_signature' => $coordinatorSignature,
            'title' => 'Revise Agreement'
        ];
        $this->view('operationsCoordinator/v_reviseAgreement', $data);
    }


    public function updateAgreement($agreementId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/preProjects');
        }
    
        // First cancel the old agreement
        if (!$this->preProjectModel->cancelAgreement($agreementId)) {
            flash('agreement_message', 'Error updating old agreement', 'error');
            redirect("operationsCoordinator/agreementRevision/$agreementId");
            return;
        }
    
        // Get original agreement for reference
        $originalAgreement = $this->preProjectModel->getAgreementById($agreementId);
        
        // Prepare data for new agreement
        $data = [
            'pre_project_id' => $originalAgreement->pre_project_id,
            'review_id' => $originalAgreement->review_id,
            'quotation_id' => $originalAgreement->quotation_id,
            'system_capacity' => $_POST['system_capacity'],
            'estimated_generation' => $_POST['estimated_generation'],
            'base_price' => $_POST['base_price'],
            'service_charge' => $_POST['service_charge'],
            'total_price' => $_POST['total_price'],
            'notes' => $_POST['notes'],
            'equipment' => json_decode($_POST['equipment'], true),
            'coordinator_signature_id' => null
        ];
    
        // Handle signature
        if (isset($_POST['use_existing_signature'])) {
            $signature = $this->preProjectModel->getCoordinatorSignature($_SESSION['user_id']);
            if ($signature) {
                $data['coordinator_signature_id'] = $signature->signature_id;
            }
        }
    
        // Create new agreement
        $newAgreementId = $this->preProjectModel->createRevisedAgreement($data);
        
        if ($newAgreementId) {
            flash('agreement_message', 'Revised agreement created successfully', 'success');
            redirect('operationsCoordinator/preProjects');
        } else {
            // If new agreement creation fails, revert old agreement status
            $this->preProjectModel->updateAgreementStatus($agreementId, 'revision_requested');
            flash('agreement_message', 'Error creating revised agreement', 'error');
            redirect("operationsCoordinator/agreementRevision/$agreementId");
        }
    }



















    

//--------------------------------------------------------------------------------------------------------




    public function manageAproject()
    {
        $data = [];
        $this->view('operationsCoordinator/v_manageAproject', $data);
    }

    // View all tasks
    public function tasks()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $tasks = $this->tasksModel->getAllTasks();
        $data = [
            'tasks' => $tasks
        ];
        $this->view('operationsCoordinator/v_tasks', $data);
    }

    // View a task by ID
    public function viewTask($taskId)
    {
        // Fetch the task from the model
        $task = $this->tasksModel->getTaskById($taskId);

        // Check if task exists
        if ($task) {
            $data = [
                'task' => $task
            ];
            // Load the view with task data
            $this->view('operationsCoordinator/v_viewTask', $data);
        } else {
            // Task not found, redirect to tasks list
            flash('task_msg', 'Task not found', 'alert alert-danger');
            redirect('operationsCoordinator/tasks');
        }
    }

    // Add Task
    public function addTask()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description']),
                'project_id' => trim($_POST['project_id']),
                'employee_id' => trim($_POST['employee_id']),
                'status' => trim($_POST['status']),
                'employees' => $this->employeeModel->getAllEmployees(), // Add employees list
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            // Validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }

            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter start time';
            }

            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter end time';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter description';
            }

            if (empty($data['project_id'])) {
                $data['project_id_err'] = 'Please select project';
            }

            if (empty($data['employee_id'])) {
                $data['employee_id_err'] = 'Please select employee';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Please select status';
            }

            // Make sure no errors
            if (empty($data['title_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['description_err']) && empty($data['project_id_err']) && empty($data['employee_id_err']) && empty($data['status_err'])) {
                // Validated
                if ($this->tasksModel->create($data)) {
                    flash('task_msg', 'Task added successfully');
                    redirect('operationsCoordinator/tasks');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('operationsCoordinator/v_addTask', $data);
            }
        } else {
            $data = [
                'title' => '',
                'start_date' => '',
                'end_date' => '',
                'description' => '',
                'project_id' => '',
                'employee_id' => '',
                'status' => '',
                'employees' => $this->employeeModel->getAllEmployees(), // Add employees list
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            $this->view('operationsCoordinator/v_addTask', $data);
        }
    }

    // Edit Task
    public function editTask($taskId)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $taskId,
                'title' => trim($_POST['title']),   
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description']),
                'project_id' => trim($_POST['project_id']),
                'employee_id' => trim($_POST['employee_id']),
                'status' => trim($_POST['status']),
                'employees' => $this->employeeModel->getAllEmployees(),
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            // Validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }

            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter start time';
            }

            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter end time';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter description';
            }

            if (empty($data['project_id'])) {
                $data['project_id_err'] = 'Please select project';
            }

            if (empty($data['employee_id'])) {
                $data['employee_id_err'] = 'Please select employee';
            }

            if (empty($data['status'])) {
                $data['status_err'] = 'Please select status';
            }

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['description_err']) && empty($data['project_id_err']) && empty($data['employee_id_err']) && empty($data['status_err'])){
                // Validated
                if($this->tasksModel->edit($data)){
                    flash('task_msg', 'Task updated successfully'); 
                    redirect('operationsCoordinator/tasks');
                }else{
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('operationsCoordinator/v_editTask', $data);
            }
        }else{
            $task = $this->tasksModel->getTaskById($taskId);

            $data = [
                'id' => $taskId,
                'title' => $task->title,
                'start_date' => $task->start_date,
                'end_date' => $task->end_date,
                'description' => $task->description,
                'project_id' => $task->project_id,
                'employee_id' => $task->employee_id,
                'status' => $task->status,
                'employees' => $this->employeeModel->getAllEmployees(),
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => '',
                'status_err' => ''
            ];

            $this->view('operationsCoordinator/v_editTask', $data);

        }
        
    }
 
    // Delete TaskA
    public function deleteTask($taskId)
    {
        $task = $this->tasksModel->getTaskById($taskId);
        
        if($this->tasksModel->delete($taskId)){
            flash('task_msg', 'Task removed successfully');
            redirect('operationsCoordinator/tasks');
        }else{
            die('Something went wrong');
        }
    }

    public function packages()
    {
        // Get inventory items through packageModel
        $inventoryItems = $this->packageModel->getInventoryItems();

        $data = [
            'title' => '',
            'description' => '',
            'warranty_years' => '',
            'type' => 'on-grid',
            'service_charge' => '',
            'inventory_items' => $inventoryItems,
            'errors' => []
        ];

        $this->view('operationsCoordinator/v_createPackage', $data);
    }

    public function createPackage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Handle file upload
            $image = null;
            if (isset($_FILES['package_image']) && $_FILES['package_image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                $file = $_FILES['package_image'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (in_array($file_ext, $allowed)) {
                    $file_name = uniqid('package_') . '.' . $file_ext;
                    $file_destination = APPROOT . '/../public/uploads/packages/' . $file_name;

                    if (move_uploaded_file($file['tmp_name'], $file_destination)) {
                        $image = 'uploads/packages/' . $file_name;
                    }
                }
            }

            // Process equipment items
            $equipment = [];
            if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
                foreach ($_POST['item_id'] as $key => $item_id) {
                    if (!empty($item_id) && isset($_POST['quantity'][$key])) {
                        $equipment[] = [
                            'item_id' => intval($item_id),
                            'quantity' => intval($_POST['quantity'][$key])
                        ];
                    }
                }
            }

            // Process features
            $features = [];
            if (isset($_POST['feature_name']) && is_array($_POST['feature_name'])) {
                foreach ($_POST['feature_name'] as $key => $name) {
                    if (!empty($name) && isset($_POST['feature_description'][$key])) {
                        $features[] = [
                            'feature_name' => trim($name),
                            'description' => trim($_POST['feature_description'][$key])
                        ];
                    }
                }
            }

            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'warranty_years' => intval($_POST['warranty_years']),
                'type' => trim($_POST['type']),
                'service_charge' => floatval($_POST['service_charge']) ?: 0.00,
                'equipment' => $equipment,
                'features' => $features,
                'image' => $image,
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];

            $totalEquipmentPrice = $this->packageModel->calculateFinalPrice($equipment, $data['service_charge']);
            $data['price'] = $totalEquipmentPrice;
            $data['final_price'] = $totalEquipmentPrice + floatval($data['service_charge']);

            // Validation
            if (empty($data['title'])) {
                $data['errors']['title'] = 'Please enter package title';
            }
            if (!in_array($data['type'], ['on-grid', 'off-grid', 'hybrid'])) {
                $data['type'] = str_replace('onGrid', 'on-grid', $data['type']);
                $data['type'] = str_replace('offGrid', 'off-grid', $data['type']);
            }

            if (empty($equipment)) {
                $data['errors']['equipment'] = 'Please add at least one equipment item';
            }

            if (empty($data['errors'])) {
                $result = $this->packageModel->createPackage($data);

                if ($result) {
                    flash('package_message', 'Package created successfully');
                    redirect('operationsCoordinator/managePackages');
                } else {
                    flash('package_message', 'Failed to create package', 'alert alert-danger');
                    $this->view('operationsCoordinator/v_createPackage', $data);
                }
            } else {
                $this->view('operationsCoordinator/v_createPackage', $data);
            }
        } else {
            $data = [
                'title' => '',
                'description' => '',
                'warranty_years' => '',
                'type' => 'on-grid',
                'service_charge' => '',
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];

            $this->view('operationsCoordinator/v_createPackage', $data);
        }
    }

    public function managePackages()
    {
        $data = [
            'packages' => $this->packageModel->getAllPackagesWithDetails(),
            'title' => 'Manage Packages'
        ];

        $this->view('operationsCoordinator/v_managePackages', $data);
    }

    public function editPackage($id)
    {
        // First verify if package exists and user has permission
        $package = $this->packageModel->getPackageById($id);
        if (!$package) {
            flash('package_message', 'Package not found', 'alert alert-danger');
            redirect('operationsCoordinator/managePackages');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Process equipment items
            $equipment = [];
            if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
                foreach ($_POST['item_id'] as $key => $item_id) {
                    if (!empty($item_id) && isset($_POST['quantity'][$key])) {
                        // Verify if item exists in inventory and has sufficient quantity
                        $inventoryItem = $this->inventoryModel->getItemById($item_id);
                        if ($inventoryItem && $inventoryItem->quantity >= $_POST['quantity'][$key]) {
                            $equipment[] = [
                                'item_id' => intval($item_id),
                                'quantity' => intval($_POST['quantity'][$key])
                            ];
                        }
                    }
                }
                
                // Convert all elements of equipment array to objects
                foreach ($equipment as &$eq) {
                    $eq = (object) $eq;
                }
                unset($eq); // Unset reference to avoid unintended modifications
            }

            // Process features
            $features = [];
            if (isset($_POST['feature_name']) && is_array($_POST['feature_name'])) {
                foreach ($_POST['feature_name'] as $key => $name) {
                    if (!empty($name) && isset($_POST['feature_description'][$key])) {
                        $features[] = [
                            'name' => trim($name),
                            'description' => trim($_POST['feature_description'][$key])
                        ];
                    }
                }
            }

            $data = [
                'package_id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'warranty_years' => intval($_POST['warranty_years']),
                'type' => trim($_POST['type']),
                'service_charge' => floatval($_POST['service_charge']),
                'equipment' => $equipment,
                'features' => $features,
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];


            // Validation
            if (empty($data['title'])) {
                $data['errors']['title'] = 'Please enter package title';
            }
            if (strlen($data['title']) > 100) {
                $data['errors']['title'] = 'Title cannot exceed 100 characters';
            }
            if (!in_array($data['type'], ['on-grid', 'off-grid', 'hybrid'])) {
                $data['errors']['type'] = 'Invalid package type selected';
            }
            if (empty($equipment)) {
                $data['errors']['equipment'] = 'Please add at least one equipment item';
            }
            if ($data['warranty_years'] < 0) {
                $data['errors']['warranty_years'] = 'Warranty years cannot be negative';
            }
            if ($data['service_charge'] < 0) {
                $data['errors']['service_charge'] = 'Service charge cannot be negative';
            }

            if (empty($data['errors'])) {
                try {
                    if ($this->packageModel->updatePackage($id, $data)) {
                        flash('package_message', 'Package updated successfully');
                        redirect('operationsCoordinator/managePackages');
                    } else {
                        flash('package_message', 'Failed to update package', 'alert alert-danger');
                        $this->view('operationsCoordinator/v_editPackage', $data);
                    }
                } catch (Exception $e) {
                    error_log("Error updating package: " . $e->getMessage());
                    flash('package_message', 'An error occurred while updating the package', 'alert alert-danger');
                    $this->view('operationsCoordinator/v_editPackage', $data);
                }
            } else {
                $this->view('operationsCoordinator/v_editPackage', $data);
            }
        } else {
            // GET request - display edit form
            $data = [
                'package_id' => $id,
                'title' => $package->title,
                'description' => $package->description,
                'warranty_years' => $package->warranty_years,
                'type' => $package->type,
                'service_charge' => $package->service_charge,
                'final_price' => $package->final_price,
                'equipment' => $this->packageModel->getPackageEquipment($id),
                'features' => $this->packageModel->getPackageFeatures($id),
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];

            $this->view('operationsCoordinator/v_editPackage', $data);
        }
    }

    public function deletePackage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->packageModel->deletePackage($id)) {
                flash('package_message', 'Package deleted successfully');
            } else {
                flash('package_message', 'Failed to delete package', 'alert alert-danger');
            }
        }
        redirect('operationsCoordinator/managePackages');
    }

   
}
