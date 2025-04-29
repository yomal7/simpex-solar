<?php
class OperationsCoordinator extends Controller
{
    private $tasksModel;
    private $packageModel;
    private $inventoryModel;
    private $employeeModel;
    private $preProjectModel;
    private $projectModel;
    private $operationsCoordinatorModel;
    private $settingsModel;
    private $chatModel;

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
        $this->projectModel = $this->model('M_CustomerProject');
        $this->operationsCoordinatorModel = $this->model('M_OperationsCoordinator');
        $this->settingsModel = $this->model('M_Settings');
        $this->chatModel = $this->model('M_Chat');

        // Check for unread messages on every page load
        $clients = $this->operationsCoordinatorModel->getClientsWithChats();
        $totalUnreadCount = 0;
        if ($clients) {
            foreach ($clients as $client) {
                if (isset($client->unread_count)) {
                    $totalUnreadCount += $client->unread_count;
                }
            }
        }
        // Store the count in session for access across all views
        $_SESSION['total_unread_count'] = $totalUnreadCount;
    }

    public function index()
    {
        redirect('operationsCoordinator/dashboard');
    }

    public function dashboard()
    {
        // Get Project Statistics
        $projectStats = [
            'total_projects' => $this->projectModel->getTotalProjects(),
            'active_projects' => $this->projectModel->getActiveProjects(),
            'completed_projects' => $this->projectModel->getCompletedProjects(),
            'preproject_stats' => $this->preProjectModel->getPreProjectStats(),
            'monthly_projects' => $this->projectModel->getMonthlyProjectCounts(),
            'phase_distribution' => $this->projectModel->getProjectPhaseDistribution()
        ];


        // Get Package Statistics
        $packageStats = [
            'total_packages' => $this->packageModel->getTotalPackages(),
            'package_by_type' => $this->packageModel->getPackageCountByType()
        ];

        $data = [
            'project_stats' => $projectStats,
            'package_stats' => $packageStats
        ];

        $this->view('operationsCoordinator/v_dashboard', $data);
    }



    //################################################################################################
    //-------------------------------------Add signature----------------------------------------------
    //################################################################################################

    public function signature()
    {
        $signature = $this->operationsCoordinatorModel->getSignatureByCoordinatorId($_SESSION['user_id']);

        $data = [
            'title' => 'Signature Upload',
            'signature' => $signature,
            'signature_err' => ''
        ];

        $this->view('operationsCoordinator/v_signature', $data);
    }

    public function uploadSignature()
    {
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

    private function validateSignatureFile($file)
    {
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

    public function deleteSignature()
    {
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


    public function preProjects()
    {
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


    public function managePreProject($preProjectId = null)
    {
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

    public function manageAgreement($preProjectId = null)
    {
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

    public function manageQuotation($preProjectId = null)
    {
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

    public function getPackageEquipment($packageId)
    {
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
        
        // Ensure numeric values are properly formatted
        $postData['base_price'] = (float)$postData['base_price'];
        $postData['service_charge'] = (float)$postData['service_charge'];
        $postData['total_price'] = (float)$postData['total_price'];
        $postData['system_capacity'] = (float)$postData['system_capacity'];
        $postData['estimated_generation'] = (float)$postData['estimated_generation'];
        
        // Ensure each equipment item has proper numeric values
        foreach ($postData['equipment'] as &$item) {
            $item['inventory_id'] = (int)$item['inventory_id'];
            $item['quantity'] = (int)$item['quantity'];
            $item['unit_price'] = (float)$item['unit_price'];
            $item['total_price'] = (float)$item['total_price'];
            $item['is_from_package'] = (int)$item['is_from_package'];
        }
    
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

    public function updateQuotationStatus()
    {
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

    public function getQuotationDetails($preProjectId)
    {
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

    public function manageSiteVisit($preProjectId = null)
    {
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

    public function scheduleSiteVisit()
    {
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

    // public function completeSiteVisit()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         redirect('operationsCoordinator/preProjects');
    //         return;
    //     }

    //     $preProjectId = $_POST['pre_project_id'];
    //     $notes = $_POST['site_notes'];

    //     if ($this->preProjectModel->completeSiteVisit($preProjectId, $notes)) {
    //         flash('site_visit_message', 'Site visit completed successfully', 'success');
    //     } else {
    //         flash('site_visit_message', 'Failed to complete site visit', 'error');
    //     }

    //     redirect('operationsCoordinator/manageSiteVisit/' . $preProjectId);
    // }

    public function handleReschedule()
    {
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

    public function getSiteVisitCalendar()
    {
        header('Content-Type: application/json');
        $visits = $this->preProjectModel->getAllSiteVisits();
        echo json_encode($visits);
    }

    //####################################################################################################
    //-----------------------------------------Agreement phase--------------------------------------------
    //####################################################################################################

    public function createAgreement($preProjectId = null)
    {
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

    public function getInventory()
    {
        header('Content-Type: application/json');
        $inventory = $this->preProjectModel->getAvailableInventory();
        echo json_encode($inventory);
    }

    public function getAgreementEquipment($agreementId)
    {
        header('Content-Type: application/json');
        $equipment = $this->preProjectModel->getAgreementEquipment($agreementId);
        echo json_encode($equipment);
    }

    public function saveAgreement()
    {
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

    private function handleSignatureUpload($file)
    {
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

    public function viewAgreement($preProjectId = null)
    {
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

    public function agreementRevision($agreementId = null)
    {

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


    public function updateAgreement($agreementId)
    {
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

    //####################################################################################################
    //-----------------------------------------PROJECT----------------------------------------------------
    //####################################################################################################

    public function projects()
    {
        // Get project statistics using the exact phase names from the database enum
        $stats = [
            'document_submission' => $this->projectModel->getProjectCountByPhase('document_submission'),
            'first_payment' => $this->projectModel->getProjectCountByPhase('first_payment'),
            'installation' => $this->projectModel->getProjectCountByPhase('installation'),
            'final_payment' => $this->projectModel->getProjectCountByPhase('final_payment'),
            'engineer_approval' => $this->projectModel->getProjectCountByPhase('engineer_approval')
        ];

        // Get all projects with customer details
        $projects = $this->projectModel->getAllProjectsWithCustomerDetails();

        $data = [
            'stats' => $stats,
            'projects' => $projects
        ];

        $this->view('operationsCoordinator/v_projects', $data);
    }

    public function projectDashboard($projectId)
    {

        // Get project details with all required information
        $project = $this->projectModel->getProjectById($projectId);

        // If project doesn't exist, redirect
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
        }

        // Get additional project data like customer details
        $customerDetails = $this->projectModel->getCustomerDetailsByProjectId($projectId);

        // Merge project and customer details
        if ($customerDetails) {
            foreach ($customerDetails as $key => $value) {
                if (!isset($project->$key)) {
                    $project->$key = $value;
                }
            }
        }

        $data = [
            'project' => $project
        ];

        $this->view('operationsCoordinator/v_manageAproject', $data);
    }


    public function manageAproject($projectId)
    {
        // Get project details with all required information
        $project = $this->projectModel->getProjectById($projectId);

        // If project doesn't exist, redirect
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
        }

        // Get additional project data like customer details
        $data = $this->projectModel->getCustomerDetailsByProjectId($projectId);


        $data = [
            'project' => $project
        ];

        // Change this line to load v_manageAproject.php instead of v_projectDashboard.php
        $this->view('operationsCoordinator/v_manageAproject', $data);
    }

    public function documentSubmission($projectId)
    {
        // Get project details
        $project = $this->projectModel->getProjectById($projectId);

        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
        }
        // Get customer details
        $customerDetails = $this->projectModel->getCustomerDetailsByProjectId($projectId);

        // Merge project and customer details
        if ($customerDetails) {
            foreach ($customerDetails as $key => $value) {
                $project->$key = $value;
            }
        }
        // Get document submission if exists
        $document = $this->projectModel->getDocumentSubmission($projectId);

        $data = [
            'project' => $project,
            'document' => $document
        ];

        $this->view('operationsCoordinator/v_documentSubmission', $data);
    }

    public function acceptDocument()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        // Get submitted data
        $documentId = $_POST['document_id'];
        $projectId = $_POST['project_id'];

        // Get document details
        $document = $this->projectModel->getDocumentById($documentId);

        if (!$document) {
            flash('document_message', 'Document not found', 'alert alert-danger');
            redirect('operationsCoordinator/documentSubmission/' . $projectId);
        }

        // Update document status
        if ($this->projectModel->updateDocumentStatus($documentId, 'accept')) {
            // Move project to next phase (first_payment)
            $this->projectModel->updateProjectsPhase($projectId, 'first_payment');
            flash('document_message', 'Document approved successfully', 'alert alert-success');
        } else {
            flash('document_message', 'Failed to approve document', 'alert alert-danger');
        }

        redirect('operationsCoordinator/documentSubmission/' . $projectId);
    }

    /**
     * Reject submitted document
     */
    public function rejectDocument()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        // Get submitted data
        $documentId = $_POST['document_id'];
        $projectId = $_POST['project_id'];
        $rejectionReason = $_POST['rejection_reason'];

        // Validate rejection reason
        if (empty($rejectionReason)) {
            flash('document_message', 'Please provide a reason for rejection', 'alert alert-danger');
            redirect('operationsCoordinator/documentSubmission/' . $projectId);
            return;
        }

        // Get document details
        $document = $this->projectModel->getDocumentById($documentId);

        if (!$document) {
            flash('document_message', 'Document not found', 'alert alert-danger');
            redirect('operationsCoordinator/documentSubmission/' . $projectId);
        }

        // Update document status
        if ($this->projectModel->updateDocumentStatus($documentId, 'reject', $rejectionReason)) {
            flash('document_message', 'Document rejected successfully', 'alert alert-success');
        } else {
            flash('document_message', 'Failed to reject document', 'alert alert-danger');
        }

        redirect('operationsCoordinator/documentSubmission/' . $projectId);
    }

    /**
     * Handle first payment management
     * 
     * @param int $projectId The project ID
     * @return void
     */
    public function firstPayment($projectId = null)
    {
        if ($projectId === null) {
            flash('payment_message', 'Project ID is required', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
        }

        // Get project details
        $project = $this->projectModel->getProjectById($projectId);
        if (!$project) {
            flash('payment_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
        }

        // Get customer details
        $customerDetails = $this->projectModel->getCustomerDetailsByProjectId($projectId);
        if ($customerDetails) {
            foreach ($customerDetails as $key => $value) {
                $project->$key = $value;
            }
        }

        // Get payment details
        $payment = $this->projectModel->getProjectPayment($projectId, 'first_payment');

        // Get bank slip if payment method is bank deposit
        $bankSlip = null;
        if ($payment && $payment->payment_method == 'bank deposit') {
            $bankSlip = $this->projectModel->getProjectBankSlip($projectId, 'first_payment');
        }

        // Get agreement to find pricing details
        $agreement = $this->projectModel->getAgreementById($project->agreement_id);
        $firstPaymentAmount = 0;

        if ($agreement) {
            // Calculate 25% payment amount
            $firstPaymentAmount = $agreement->total_price * 0.25;
        }

        $data = [
            'project' => $project,
            'payment' => $payment,
            'bank_slip' => $bankSlip,
            'first_payment_amount' => $firstPaymentAmount,
            'agreement' => $agreement
        ];

        $this->view('operationsCoordinator/v_firstPayment', $data);
    }

    /**
     * Process cash payment
     */
    public function processPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $projectId = $_POST['project_id'];
        $paymentId = $_POST['payment_id'];
        $amount = $_POST['amount'];

        // Update payment status
        if ($this->projectModel->updateProjectPayment($paymentId, [
            'payment_status' => true,
            'amount' => $amount
        ])) {
            // Update project phase to installation
            $this->projectModel->updateProjectsPhase($projectId, 'installation');

            flash('payment_message', 'Payment processed successfully', 'alert alert-success');
        } else {
            flash('payment_message', 'Failed to process payment', 'alert alert-danger');
        }

        redirect('operationsCoordinator/firstPayment/' . $projectId);
    }


    /**
     * Accept bank slip
     */
    public function acceptBankSlip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $projectId = $_POST['project_id'];
        $paymentId = $_POST['payment_id'];
        $slipId = $_POST['slip_id'];
        $amount = $_POST['amount'];

        // Update bank slip status
        if ($this->projectModel->updateBankSlipStatus($slipId, 'accept')) {
            // Update payment status
            $this->projectModel->updateProjectPayment($paymentId, [
                'payment_status' => true,
                'amount' => $amount
            ]);

            // Update project phase to installation
            $this->projectModel->updateProjectsPhase($projectId, 'installation');

            flash('payment_message', 'Bank slip accepted and payment processed successfully', 'alert alert-success');
        } else {
            flash('payment_message', 'Failed to accept bank slip', 'alert alert-danger');
        }

        redirect('operationsCoordinator/firstPayment/' . $projectId);
    }

    /**
     * Reject bank slip
     */
    public function rejectBankSlip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $projectId = $_POST['project_id'];
        $slipId = $_POST['slip_id'];
        $rejectReason = $_POST['reject_reason'];

        // Update bank slip status
        if ($this->projectModel->updateBankSlipStatus($slipId, 'reject', $rejectReason)) {
            flash('payment_message', 'Bank slip rejected successfully', 'alert alert-success');
        } else {
            flash('payment_message', 'Failed to reject bank slip', 'alert alert-danger');
        }

        redirect('operationsCoordinator/firstPayment/' . $projectId);
    }


    //################################################################################################
    //-------------------------------------Installation----------------------------------------------
    //################################################################################################

    // public function installation($projectId = null)
    // {
    //     if (!$projectId) {
    //         flash('installation_message', 'Project ID is required', 'alert alert-danger');
    //         redirect('operationsCoordinator/projects');

    //  pattern="^\d{9}[vVxX]$|^\d{12}$"
    //     }

    //     // Get project details
    //     $project = $this->projectModel->getProjectById($projectId);
    //     if (!$project) {
    //         flash('installation_message', 'Project not found', 'alert alert-danger');
    //         redirect('operationsCoordinator/projects');
    //     }

    //     $agreement = $this->projectModel->getAgreementById($project->agreement_id);
    // function sortTableByPhone(direction) {
    //     const tbody = table.querySelector('tbody');
    //     const rowsArray = Array.from(tbody.querySelectorAll('tr'));
        
    //     rowsArray.sort((a, b) => {
    //         // Adjust the index to match your phone number column position
    //         const phoneColIndex = 3; 
            
    //         // Remove non-numeric characters for consistent sorting
    //         const phoneA = a.cells[phoneColIndex].textContent.replace(/\D/g, '');
    //         const phoneB = b.cells[phoneColIndex].textContent.replace(/\D/g, '');
            
    //         // Sort numerically
    //         if (direction === 'asc') {
    //             return phoneA - phoneB;
    //         } else {
    //             return phoneB - phoneA;
    //         }
    //     });
        
    //     // Clear and repopulate table body
    //     while (tbody.firstChild) {
    //         tbody.removeChild(tbody.firstChild);
    //     }
        
    //     rowsArray.forEach(row => tbody.appendChild(row));
    // }
    //     // Get customer details
    //     $customerDetails = $this->projectModel->getCustomerDetailsByProjectId($projectId);
    //     if ($customerDetails) {
    //         foreach ($customerDetails as $key => $value) {
    //             $project->$key = $value;
    //         }
    //     }

    //     // Get installation data if exists
    //     $installation = $this->projectModel->getInstallationPhase($projectId);
    //     $schedule = null;
    //     $engineer = null;

    //     if ($installation) {
    //         // Get schedule data
    //         $schedule = $this->projectModel->getInstallationSchedule($installation->installation_id);

    //         // Get engineer data if assigned
    //         $engineer = $this->projectModel->getAssignedEngineer($installation->installation_id);

    //         // Get team members
    //         $teamMembers = $this->projectModel->getInstallationTeamMembers($installation->installation_id);
    //     }

    //     // Get engineers for assignment dropdown
    //     $engineers = $this->employeeModel->getEmployeesByRole('engineer');

    //     // Get technicians for selection
    //     $technicians = $this->employeeModel->getEmployeesByRole('technician');

    //     // Get upcoming installations for the next month
    //     $upcomingInstallations = $this->projectModel->getUpcomingInstallations();

    //     $data = [
    //         'project' => $project,
    //         'installation' => $installation,
    //         'agreement' => $agreement,
    //         'schedule' => $schedule,
    //         'engineer' => $engineer,
    //         'engineers' => $engineers,
    //         'technicians' => $technicians,
    //         'team_members' => $teamMembers ?? [],
    //         'upcoming_installations' => $upcomingInstallations
    //     ];

    //     $this->view('operationsCoordinator/v_installation', $data);
    // }
    // Add a new method to get installation details for the modal
    public function getInstallationDetails($installationId)
    {
        // Check if request is AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            redirect('operationsCoordinator/projects');
        }

        // Get installation details
        $installation = $this->projectModel->getInstallationById($installationId);
        $schedule = $this->projectModel->getInstallationSchedule($installationId);
        $engineer = $this->projectModel->getAssignedEngineer($installationId);
        $teamMembers = $this->projectModel->getInstallationTeamMembers($installationId);

        $response = [
            'success' => true,
            'installation' => $installation,
            'schedule' => $schedule,
            'engineer' => $engineer,
            'team_members' => $teamMembers
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }


    /**
     * Schedule a new installation
     */
    // public function scheduleInstallation()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         redirect('operationsCoordinator/projects');
    //     }

    //     // Get form data
    //     $projectId = $_POST['project_id'];
    //     $startDate = $_POST['start_date'];
    //     $startTime = $_POST['start_time'];
    //     $durationDays = $_POST['duration_days'];
    //     $engineerId = $_POST['engineer_id'];
    //     $technicians = isset($_POST['technicians']) ? $_POST['technicians'] : [];
    //     error_log(print_r($technicians, true));

    //     // Validate technicians count
    //     if (count($technicians) < 3 || count($technicians) > 6) {
    //         flash('installation_message', 'Please select between 3 and 6 technicians', 'alert alert-danger');
    //         redirect('operationsCoordinator/installation/' . $projectId);
    //         return;
    //     }

    //     // Calculate end date
    //     $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $durationDays . ' days'));

    //     // Validate time (8am - 12pm)
    //     $hour = (int)substr($startTime, 0, 2);
    //     if ($hour < 8 || $hour > 12) {
    //         flash('installation_message', 'Installation start time must be between 8:00 AM and 12:00 PM', 'alert alert-danger');
    //         redirect('operationsCoordinator/installation/' . $projectId);
    //         return;
    //     }

    //     // Create installation phase record
    //     $installationId = $this->projectModel->createInstallationPhase([
    //         'project_id' => $projectId,
    //         'status' => 'initial'
    //     ]);

    //     if (!$installationId) {
    //         flash('installation_message', 'Failed to create installation record', 'alert alert-danger');
    //         redirect('operationsCoordinator/installation/' . $projectId);
    //         return;
    //     }

    //     // Assign engineer to installation
    //     $engineerAssigned = $this->projectModel->assignEngineerToInstallation($installationId, $engineerId);
    //     if (!$engineerAssigned) {
    //         flash('installation_message', 'Failed to assign engineer', 'alert alert-warning');
    //     }

    //     // Assign technicians to installation
    //     $techniciansAssigned = true;
    //     foreach ($technicians as $technicianId) {
    //         if (!$this->projectModel->assignTechnicianToInstallation($installationId, $technicianId)) {
    //             $techniciansAssigned = false;
    //         }
    //     }

    //     if (!$techniciansAssigned) {
    //         flash('installation_message', 'Some technicians could not be assigned', 'alert alert-warning');
    //     }

    //     // Create installation schedule
    //     $scheduleCreated = $this->projectModel->createInstallationSchedule([
    //         'installation_id' => $installationId,
    //         'start_date' => $startDate,
    //         'start_time' => $startTime,
    //         'end_date' => $endDate,
    //         'status' => 'pending'
    //     ]);

    //     if ($scheduleCreated) {
    //         flash('installation_message', 'Installation scheduled successfully', 'alert alert-success');
    //     } else {
    //         flash('installation_message', 'Failed to schedule installation', 'alert alert-danger');
    //     }

    //     redirect('operationsCoordinator/installation/' . $projectId);
    // }

    public function addTeamMember()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $installationId = $_POST['installation_id'];
        $employeeId = $_POST['employee_id'];
        $projectId = $_POST['project_id'];

        if ($this->projectModel->addEmployeeToInstallation($installationId, $employeeId)) {
            flash('installation_message', 'Team member added successfully', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to add team member', 'alert alert-danger');
        }

        redirect('operationsCoordinator/installation/' . $projectId);
    }

    /**
     * Reschedule an installation
     */
    // public function rescheduleInstallation()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         redirect('operationsCoordinator/projects');
    //     }

    //     // Get form data
    //     $installationId = $_POST['installation_id'];
    //     $scheduleId = $_POST['schedule_id'];
    //     $projectId = $_POST['project_id'];
    //     $startDate = $_POST['start_date'];
    //     $startTime = $_POST['start_time'];
    //     $durationDays = $_POST['duration_days'];
    //     $engineerId = $_POST['engineer_id'];
    //     $technicians = isset($_POST['technicians']) ? $_POST['technicians'] : [];

    //     // Validate technicians count
    //     if (count($technicians) < 3 || count($technicians) > 6) {
    //         flash('installation_message', 'Please select between 3 and 6 technicians', 'alert alert-danger');
    //         redirect('operationsCoordinator/installation/' . $projectId);
    //         return;
    //     }

    //     // Calculate end date
    //     $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $durationDays . ' days'));

    //     // Create new schedule
    //     $scheduleCreated = $this->projectModel->createInstallationSchedule([
    //         'installation_id' => $installationId,
    //         'start_date' => $startDate,
    //         'start_time' => $startTime,
    //         'end_date' => $endDate,
    //         'status' => 'pending'
    //     ]);

    //     if (!$scheduleCreated) {
    //         flash('installation_message', 'Failed to reschedule installation', 'alert alert-danger');
    //         redirect('operationsCoordinator/installation/' . $projectId);
    //         return;
    //     }

    //     // Update engineer if changed
    //     $engineerAssigned = $this->projectModel->reassignEngineer($installationId, $engineerId);

    //     // Update technicians
    //     // First remove all current technicians
    //     $this->projectModel->removeAllTechnicians($installationId);

    //     // Then add new ones
    //     $techniciansAssigned = true;
    //     foreach ($technicians as $technicianId) {
    //         if (!$this->projectModel->assignTechnicianToInstallation($installationId, $technicianId)) {
    //             $techniciansAssigned = false;
    //         }
    //     }

    //     flash('installation_message', 'Installation rescheduled successfully', 'alert alert-success');
    //     redirect('operationsCoordinator/installation/' . $projectId);
    // }

    public function reassignEngineer()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $installationId = $_POST['installation_id'];
        $engineerId = $_POST['engineer_id'];
        $projectId = $_POST['project_id'];

        if ($this->projectModel->reassignEngineer($installationId, $engineerId)) {
            flash('installation_message', 'Engineer reassigned successfully', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to reassign engineer', 'alert alert-danger');
        }

        redirect('operationsCoordinator/installation/' . $projectId);
    }

    // public function completeInstallation()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         redirect('operationsCoordinator/projects');
    //     }

    //     $installationId = $_POST['installation_id'];
    //     $projectId = $_POST['project_id'];

    //     // Update installation status to completed
    //     $installationUpdated = $this->projectModel->updateInstallationStatus($installationId, 'completed');

    //     if ($installationUpdated) {
    //         // Move project to final payment phase
    //         $projectUpdated = $this->projectModel->updateProjectsPhase($projectId, 'final_payment');

    //         if ($projectUpdated) {
    //             flash('installation_message', 'Installation phase completed successfully', 'alert alert-success');
    //         } else {
    //             flash('installation_message', 'Installation completed but failed to update project phase', 'alert alert-warning');
    //         }
    //     } else {
    //         flash('installation_message', 'Failed to complete installation', 'alert alert-danger');
    //     }

    //     redirect('operationsCoordinator/installation/' . $projectId);
    // }

    // installations new
    public function projectInstallations()
    {
        // Get all projects in installation phase
        $projects = $this->projectModel->getProjectsInInstallationPhase();

        $data = [
            'title' => 'Installation Management',
            'projects' => $projects
        ];

        $this->view('operationsCoordinator/v_projectInstallations', $data);
    }

    public function installation($projectId)
    {
        // Get project details
        $project = $this->projectModel->getProjectWithCustomerInfo($projectId);

        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/projectInstallations');
        }

        // Get installation details if exists
        $installation = $this->projectModel->getInstallationByProjectId($projectId);

        $data = [
            'title' => 'Manage Installation',
            'project' => $project,
            'installation' => $installation
        ];

        $this->view('operationsCoordinator/v_installation', $data);
    }

    public function scheduleInstallation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projectInstallations');
            return;
        }

        // Process form data
        $projectId = $_POST['project_id'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        // Validate dates
        if (strtotime($startDate) < strtotime(date('Y-m-d'))) {
            flash('installation_message', 'Start date cannot be in the past', 'alert alert-danger');
            redirect('operationsCoordinator/installation/' . $projectId);
            return;
        }

        if (strtotime($endDate) < strtotime($startDate)) {
            flash('installation_message', 'End date cannot be before start date', 'alert alert-danger');
            redirect('operationsCoordinator/installation/' . $projectId);
            return;
        }

        // Create installation record
        $installationData = [
            'project_id' => $projectId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'schedule_status' => 'pending',
            'status' => 'initial'
        ];

        if ($this->projectModel->createInstallation($installationData)) {
            flash('installation_message', 'Installation schedule created successfully', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to create installation schedule', 'alert alert-danger');
        }

        redirect('operationsCoordinator/installation/' . $projectId);
    }

    public function rescheduleInstallation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projectInstallations');
            return;
        }

        $installationId = $_POST['installation_id'];
        $projectId = $_POST['project_id'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        // Validate dates
        if (strtotime($startDate) < strtotime(date('Y-m-d'))) {
            flash('installation_message', 'Start date cannot be in the past', 'alert alert-danger');
            redirect('operationsCoordinator/installation/' . $projectId);
            return;
        }

        if (strtotime($endDate) < strtotime($startDate)) {
            flash('installation_message', 'End date cannot be before start date', 'alert alert-danger');
            redirect('operationsCoordinator/installation/' . $projectId);
            return;
        }

        // Use project model to update installation record
        if ($this->projectModel->updateInstallationSchedule($installationId, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'schedule_status' => 'pending'
        ])) {
            flash('installation_message', 'Installation rescheduled successfully', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to reschedule installation', 'alert alert-danger');
        }

        redirect('operationsCoordinator/installation/' . $projectId);
    }

    public function extendInstallation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projectInstallations');
            return;
        }

        $installationId = $_POST['installation_id'];
        $projectId = $_POST['project_id'];
        $endDate = $_POST['end_date'];

        // Validate end date
        $installation = $this->projectModel->getInstallationByProjectId($projectId);

        if (strtotime($endDate) <= strtotime($installation->end_date)) {
            flash('installation_message', 'New end date must be after current end date', 'alert alert-danger');
            redirect('operationsCoordinator/installation/' . $projectId);
            return;
        }

        // Update installation end date
        if ($this->projectModel->updateInstallationEndDate($installationId, $endDate)) {
            flash('installation_message', 'Installation extended successfully', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to extend installation', 'alert alert-danger');
        }

        redirect('operationsCoordinator/installation/' . $projectId);
    }

    public function completeInstallation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projectInstallations');
            return;
        }

        $installationId = $_POST['installation_id'];
        $projectId = $_POST['project_id'];

        // Update installation status to completed
        if ($this->projectModel->updateInstallationStatus($installationId, 'completed')) {
            // Update project phase to next phase (engineer_approval)
            $this->projectModel->updateProjectsPhase($projectId, 'engineer_approval');

            flash('installation_message', 'Installation marked as completed', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to complete installation', 'alert alert-danger');
        }

        redirect('operationsCoordinator/projectInstallations');
    }

    //################################################################################################
    //-------------------------------------Final Payment----------------------------------------------
    //################################################################################################

    public function finalPayment($projectId = null)
    {
        if ($projectId === null) {
            flash('payment_message', 'Project ID is required', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
        }

        // Get project details
        $project = $this->projectModel->getProjectById($projectId);
        if (!$project) {
            flash('payment_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
        }

        // Get customer details
        $customerDetails = $this->projectModel->getCustomerDetailsByProjectId($projectId);
        if ($customerDetails) {
            foreach ($customerDetails as $key => $value) {
                $project->$key = $value;
            }
        }

        // Get payment details
        $payment = $this->projectModel->getProjectPayment($projectId, 'final_payment');

        // Get bank slip if payment method is bank deposit
        $bankSlip = null;
        if ($payment && $payment->payment_method == 'bank deposit') {
            $bankSlip = $this->projectModel->getProjectBankSlip($projectId, 'final_payment');
        }

        // Get first payment details
        $firstPayment = $this->projectModel->getProjectPayment($projectId, 'first_payment');
        $firstPaymentAmount = 0;
        if ($firstPayment && $firstPayment->payment_status) {
            $firstPaymentAmount = $firstPayment->amount;
        }

        // Get agreement to find pricing details
        $agreement = $this->projectModel->getAgreementById($project->agreement_id);
        $finalPaymentAmount = 0;

        if ($agreement) {
            // Calculate remaining amount (typically 75% or the balance)
            $finalPaymentAmount = $agreement->total_price - $firstPaymentAmount;
        }

        $data = [
            'project' => $project,
            'payment' => $payment,
            'bank_slip' => $bankSlip,
            'first_payment_amount' => $firstPaymentAmount,
            'final_payment_amount' => $finalPaymentAmount,
            'agreement' => $agreement
        ];

        $this->view('operationsCoordinator/v_finalPayment', $data);
    }

    /**
     * Process final cash payment
     */
    public function processFinalPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $projectId = $_POST['project_id'];
        $paymentId = $_POST['payment_id'];
        $amount = $_POST['amount'];

        // Update payment status
        if ($this->projectModel->updateProjectPayment($paymentId, [
            'payment_status' => true,
            'amount' => $amount
        ])) {
            // Update project phase to engineer_approval after final payment
            $this->projectModel->updateProjectsPhase($projectId, 'engineer_approval');

            flash('payment_message', 'Final payment processed successfully. Project moved to engineer approval phase.', 'alert alert-success');
        } else {
            flash('payment_message', 'Failed to process payment', 'alert alert-danger');
        }

        redirect('operationsCoordinator/finalPayment/' . $projectId);
    }

    /**
     * Accept final payment bank slip
     */
    public function acceptFinalBankSlip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $projectId = $_POST['project_id'];
        $paymentId = $_POST['payment_id'];
        $slipId = $_POST['slip_id'];
        $amount = $_POST['amount'];

        // Update bank slip status
        if ($this->projectModel->updateBankSlipStatus($slipId, 'accept')) {
            // Update payment status
            $this->projectModel->updateProjectPayment($paymentId, [
                'payment_status' => true,
                'amount' => $amount
            ]);

            // Update project phase to engineer_approval
            $this->projectModel->updateProjectsPhase($projectId, 'engineer_approval');

            flash('payment_message', 'Bank slip accepted and final payment processed successfully. Project moved to engineer approval phase.', 'alert alert-success');
        } else {
            flash('payment_message', 'Failed to accept bank slip', 'alert alert-danger');
        }

        redirect('operationsCoordinator/finalPayment/' . $projectId);
    }

    /**
     * Reject final payment bank slip
     */
    public function rejectFinalBankSlip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
        }

        $projectId = $_POST['project_id'];
        $slipId = $_POST['slip_id'];
        $rejectReason = $_POST['reject_reason'];

        // Update bank slip status
        if ($this->projectModel->updateBankSlipStatus($slipId, 'reject', $rejectReason)) {
            flash('payment_message', 'Bank slip rejected successfully', 'alert alert-success');
        } else {
            flash('payment_message', 'Failed to reject bank slip', 'alert alert-danger');
        }

        redirect('operationsCoordinator/finalPayment/' . $projectId);
    }


    //##################################################################################################
    //-----------------------------------------Engineer Approval----------------------------------------------
    //##################################################################################################

    public function engineerApproval($projectId = null)
    {
        if (!$projectId) {
            redirect('operationsCoordinator/projects');
            return;
        }

        // Get project details
        $project = $this->projectModel->getProjectById($projectId);
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
            return;
        }

        // Get customer information
        $customer = $this->projectModel->getCustomerDetailsByProjectId($projectId);

        // Get certificate information
        $certificate = $this->projectModel->getProjectCertificate($projectId);

        // Get available engineers for assignment
        $engineers = $this->projectModel->getAvailableEngineers();

        $data = [
            'project' => $project,
            'customer' => $customer,
            'certificate' => $certificate,
            'engineers' => $engineers
        ];

        $this->view('operationsCoordinator/v_engineerApproval', $data);
    }

    public function assignEngineerToApproval()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
            return;
        }

        $projectId = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
        $engineerId = filter_input(INPUT_POST, 'engineer_id', FILTER_VALIDATE_INT);

        if (!$projectId || !$engineerId) {
            flash('engineer_approval_message', 'Invalid data provided', 'alert alert-danger');
            redirect('operationsCoordinator/engineerApproval/' . $projectId);
            return;
        }

        $data = [
            'project_id' => $projectId,
            'engineer_id' => $engineerId
        ];

        if ($this->projectModel->createProjectCertificate($data)) {
            flash('engineer_approval_message', 'Engineer assigned successfully', 'alert alert-success');
        } else {
            flash('engineer_approval_message', 'Failed to assign engineer', 'alert alert-danger');
        }

        redirect('operationsCoordinator/engineerApproval/' . $projectId);
    }

    public function completeEngineerApproval()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('operationsCoordinator/projects');
            return;
        }

        $projectId = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);

        if (!$projectId) {
            flash('engineer_approval_message', 'Invalid project ID', 'alert alert-danger');
            redirect('operationsCoordinator/projects');
            return;
        }

        // Get certificate to check if completed
        $certificate = $this->projectModel->getProjectCertificate($projectId);

        if (!$certificate || $certificate->completed_at === null) {
            flash('engineer_approval_message', 'Engineer has not completed the certification process', 'alert alert-danger');
            redirect('operationsCoordinator/engineerApproval/' . $projectId);
            return;
        }

        if ($this->projectModel->completeEngineerApproval($projectId)) {
            flash('engineer_approval_message', 'Engineer approval completed and project moved to Grid Connection phase', 'alert alert-success');
            redirect('operationsCoordinator/projects');
        } else {
            flash('engineer_approval_message', 'Failed to complete engineer approval', 'alert alert-danger');
            redirect('operationsCoordinator/engineerApproval/' . $projectId);
        }
    }









    //--------------------------------------------------------------------------------------------------------







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
                'employees' => $this->employeeModel->getAllEmployees(), // Add employees list
                'projects' => $this->tasksModel->getProjectIdsWithCity(), // Add projects list
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => ''
            ];
    
            // Validation
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
    
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter start date';
            }
    
            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter end date';
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
    
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['start_date_err']) && 
                empty($data['end_date_err']) && empty($data['description_err']) && 
                empty($data['project_id_err']) && empty($data['employee_id_err'])) {
                
                // Validated
                if ($this->tasksModel->create($data)) {
                    flash('task_msg', 'Task assigned successfully');
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
                'employees' => $this->employeeModel->getAllEmployees(), // Add employees list
                'projects' => $this->tasksModel->getProjectIdsWithCity(),
                'title_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'employee_id_err' => ''
            ];
    
            $this->view('operationsCoordinator/v_addTask', $data);
        }
    }

    // Edit Task
    public function editTask($taskId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $taskId,
                'title' => $this->tasksModel->getTaskById($taskId)->title,
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'description' => trim($_POST['description']),
                'project_id' => trim($_POST['project_id']),
                'employee_id' => $this->tasksModel->getTaskById($taskId)->employee_id,
                'status' => trim($_POST['status']),
                'employees' => $this->employeeModel->getAllEmployees(),
                'projects' => $this->tasksModel->getProjectIdsWithCity(),
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
                if ($this->tasksModel->edit($data)) {
                    flash('task_msg', 'Task updated successfully');
                    redirect('operationsCoordinator/tasks');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('operationsCoordinator/v_editTask', $data);
            }
        } else {
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
                'projects' => $this->tasksModel->getProjectIdsWithCity(),
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
        //$task = $this->tasksModel->getTaskById($taskId);

        if ($this->tasksModel->delete($taskId)) {
            flash('task_msg', 'Task removed successfully');
            redirect('operationsCoordinator/tasks');
        } else {
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

    public function settings()
    {
        // Get user data
        $user = $this->settingsModel->getUserById($_SESSION['user_id']);

        // Initialize data array with user info
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'profile_picture' => $user->profile_picture,
            'email_err' => '',
            'phone_err' => '',
            'profile_picture_err' => '',
            'current_password_err' => '',
            'new_password_err' => '',
            'confirm_password_err' => ''
        ];

        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Determine which form was submitted
            if (isset($_POST['form_type']) && $_POST['form_type'] == 'profile_update') {
                // Profile update form submitted
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Get form data
                $data['email'] = trim($_POST['email']);
                $data['phone'] = trim($_POST['phone']);

                // Validate email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter your email';
                } elseif ($this->settingsModel->emailExistsForOtherUser($data['email'], $_SESSION['user_id'])) {
                    $data['email_err'] = 'Email is already taken by another user';
                }

                // Validate phone
                if (empty($data['phone'])) {
                    $data['phone_err'] = 'Please enter your phone number';
                } elseif (!preg_match('/^(0[0-9]{9}|[1-9][0-9]{8})$/', $data['phone'])) {
                    $data['phone_err'] = 'Please enter a valid phone number';
                }

                // Handle profile picture upload
                $profileData = [
                    'user_id' => $_SESSION['user_id'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'profile_picture' => $user->profile_picture // Default to current profile picture
                ];

                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    $maxSize = 2 * 1024 * 1024; // 2MB

                    if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
                        $data['profile_picture_err'] = 'Only JPG, JPEG and PNG files are allowed';
                    } elseif ($_FILES['profile_picture']['size'] > $maxSize) {
                        $data['profile_picture_err'] = 'File size must be less than 2MB';
                    } else {
                        // Generate new filename
                        $filename = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
                        $uploadDir = APPROOT . '/../public/uploads/profile_pictures/';

                        // Create directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        // Upload file
                        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadDir . $filename)) {
                            $profileData['profile_picture'] = $filename;
                        } else {
                            $data['profile_picture_err'] = 'Error uploading file';
                        }
                    }
                }

                // If no errors, update profile
                if (empty($data['email_err']) && empty($data['phone_err']) && empty($data['profile_picture_err'])) {
                    if ($this->settingsModel->updateProfile($profileData)) {
                        // Update session variable with new profile picture if it was changed
                        if ($profileData['profile_picture'] != $user->profile_picture) {
                            $_SESSION['user_picture'] = $profileData['profile_picture'];
                        }

                        flash('profile_message', 'Profile updated successfully', 'alert alert-success');
                        redirect('operationsCoordinator/settings');
                    } else {
                        flash('profile_message', 'Something went wrong', 'alert alert-danger');
                    }
                }
            } elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'password_change') {
                // Password change form submitted
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Get form data
                $currentPassword = trim($_POST['current_password']);
                $newPassword = trim($_POST['new_password']);
                $confirmPassword = trim($_POST['confirm_password']);

                // Validate current password
                if (empty($currentPassword)) {
                    $data['current_password_err'] = 'Please enter your current password';
                } elseif (!$this->settingsModel->verifyPassword($_SESSION['user_id'], $currentPassword)) {
                    $data['current_password_err'] = 'Current password is incorrect';
                } else {
                    // Validate new password
                    if (empty($newPassword)) {
                        $data['new_password_err'] = 'Please enter a new password';
                    } elseif (strlen($newPassword) < 6) {
                        $data['new_password_err'] = 'Password must be at least 6 characters';
                    }

                    // Validate confirm password
                    if (empty($confirmPassword)) {
                        $data['confirm_password_err'] = 'Please confirm your password';
                    } elseif ($newPassword != $confirmPassword) {
                        $data['confirm_password_err'] = 'Passwords do not match';
                    }
                }

                // If no errors, change password
                if (empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                    // Hash new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    if ($this->settingsModel->changePassword($_SESSION['user_id'], $hashedPassword)) {
                        flash('password_message', 'Password changed successfully', 'alert alert-success');
                        redirect('operationsCoordinator/settings');
                    } else {
                        die('Something went wrong');
                    }
                }
            }
        }

        $this->view('/operationsCoordinator/v_settings', $data);
    }


    public function chat()
    {
        // Get clients who have chat history with this coordinator
        $clients = $this->operationsCoordinatorModel->getClientsWithChats();

        // Calculate total unread messages
        $totalUnreadCount = 0;
        foreach ($clients as $client) {
            if (isset($client->unread_count)) {
                $totalUnreadCount += $client->unread_count;
            }
        }
        // Get clients who have chat history with this coordinator
        $data = [
            'title' => 'Client Messages',
            'clients' => $clients,
            'total_unread_count' => $totalUnreadCount
        ];

        $this->view('operationsCoordinator/v_chat', $data);
    }

    public function getClientChats()
    {
        // Get client ID from query string
        $clientId = isset($_GET['client_id']) ? $_GET['client_id'] : null;

        if (!$clientId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Client ID required']);
            return;
        }

        // Get chat history between this coordinator and the specified client
        $messages = $this->chatModel->getClientChats($_SESSION['user_id'], $clientId);

        // Mark messages as read after retrieving them
        $this->chatModel->markMessagesAsRead($clientId, $_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    public function getAllClientChats()
    {
        // Get all clients with chat history and return as JSON
        $clients = $this->operationsCoordinatorModel->getClientsWithChats();
        header('Content-Type: application/json');
        echo json_encode($clients);
    }

    public function saveMessage()
    {
        // Handle AJAX request to save a new message
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['to_user_id']) || empty($data['message'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $messageData = [
            'sender_id' => $_SESSION['user_id'],
            'sender_role' => 'operationsCoordinator',
            'receiver_id' => $data['to_user_id'],
            'receiver_role' => 'customer',
            'message' => $data['message']
        ];

        if ($this->chatModel->saveMessage($messageData)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
        }
    }

    public function markMessagesAsRead()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['client_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Client ID required']);
            return;
        }

        $success = $this->chatModel->markMessagesAsRead($data['client_id'], $_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    public function getUnreadStatus()
    {
        // Get clients who have chat history with this coordinator
        $clients = $this->operationsCoordinatorModel->getClientsWithChats();
        $totalUnreadCount = 0;

        if ($clients) {
            foreach ($clients as $client) {
                if (isset($client->unread_count)) {
                    $totalUnreadCount += $client->unread_count;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['hasUnread' => ($totalUnreadCount > 0)]);
    }
}
