<?php

class Client extends Controller
{
    private $clientModel;
    private $shopModel;
    private $clientSidePreProjectModel;
    private $clientSideProjectModel;
    private $customerProjectModel;

    public function __construct()
    {
        // Check if user is logged in and is a customer
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
            redirect('users/index');
        }

        $this->clientModel = $this->model('M_Client');
        $this->clientSidePreProjectModel = $this->model('M_clientSidePreProject');
        $this->clientSideProjectModel = $this->model('M_clientSideProject');
        $this->customerProjectModel = $this->model('M_CustomerProject');
        $this->shopModel = $this->model('M_Shop');
    }

    public function index()
    {
        $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [
            'customer' => $client
        ];
        $this->view('client/v_clientDashboard', $data);
    }

    public function dashboard()
    {
        $userId = $_SESSION['user_id'];
        
        // Get customer info
        $customer = $this->clientModel->getClientByUserId($userId);
        
        // Get active quotations
        $activeQuotations = $this->clientSidePreProjectModel->getActiveQuotationsByCustomerId($userId);
        
        // Get ongoing projects
        $ongoingProjects = $this->clientModel->getOngoingProjects($userId);

        // For debugging, uncomment this line to check what data is being returned
        // echo '<pre>'; print_r($ongoingProjects); echo '</pre>'; die();

        // Get project statistics
        $stats = $this->clientModel->getProjectStats($userId);
        
        $data = [
            'customer' => $customer,
            'quotations' => $activeQuotations,
            'ongoingProjects' => $ongoingProjects,
            'stats' => $stats,
            'notification_count' => 0 // You can update this with actual notification count
        ];
        
        $this->view('client/v_clientDashboard', $data);
    }


    public function operationDashboard()
    {
        $userId = $_SESSION['user_id'];

        // Get active quotations
        $activeQuotations = $this->clientSidePreProjectModel->getActiveQuotationsByCustomerId($userId);

        // Get ongoing projects (pre-projects with accepted quotations)
        $ongoingProjects = $this->clientModel->getOngoingProjects($userId);

        // Calculate stats
        $stats = [
            'active_projects' => count($ongoingProjects),
            'pending_quotations' => count($activeQuotations),
            'total_projects' => count($ongoingProjects)
        ];

        $data = [
            'title' => 'Dashboard',
            'stats' => $stats,
            'quotations' => $activeQuotations,
            'ongoingProjects' => $ongoingProjects
        ];

        $this->view('client/v_operationsDashboard', $data);
    }

    public function viewQuotation($quotationId)
    {
        $quotation = $this->clientSidePreProjectModel->getQuotationById($quotationId);

        if (!$quotation || $quotation->user_id !== $_SESSION['user_id']) {
            flash('quotation_message', 'Quotation not found', 'error');
            redirect('client/operationDashboard/' . $_SESSION['user_id']);
        }

        // Get reviewed quotation details if exists
        $reviewedQuotation = null;
        $reviewedEquipment = null;
        if ($quotation->status === 'reviewed') {
            $reviewedQuotation = $this->clientSidePreProjectModel->getReviewedQuotation($quotationId);
            if ($reviewedQuotation) {
                $reviewedEquipment = $this->clientSidePreProjectModel->getReviewedEquipment($reviewedQuotation->review_id);
            }
        }

        $data = [
            'quotation' => $quotation,
            'reviewed_quotation' => $reviewedQuotation,
            'equipment' => $reviewedEquipment
        ];

        $this->view('client/v_viewQuotation', $data);
    }

    // public function acceptQuotation($quotationId) {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $quotation = $this->clientSidePreProjectModel->getQuotationById($quotationId);
    //         error_log("Quotation data: " . print_r($quotation, true));
    //         if ($this->clientSidePreProjectModel->acceptQuotation($quotationId)) {
    //             flash('quotation_message', 'Quotation accepted successfully');
    //             redirect('client/sitevisit');
    //         } else {
    //             flash('quotation_message', 'Failed to accept quotation', 'error');
    //             redirect('client/sitevisit/' . $quotation->pre_project_id);
    //         }
    //     } else {
    //         redirect('client/operationDashboard/' . $_SESSION['user_id']);
    //     }
    // }


    public function acceptQuotation($quotationId)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('client/operationDashboard/' . $_SESSION['user_id']);
        }

        $quotation = $this->clientSidePreProjectModel->getQuotationById($quotationId);

        if (!$quotation || !$quotation->pre_project_id) {
            flash('quotation_message', 'Invalid quotation data');
            redirect('client/operationDashboard/' . $_SESSION['user_id']);
        }

        if ($this->clientSidePreProjectModel->acceptQuotation($quotationId)) {
            flash('quotation_message', 'Quotation accepted successfully');
            redirect('client/siteVisit/' . $quotation->pre_project_id);  // Note the capital V in siteVisit
        }

        flash('quotation_message', 'Failed to accept quotation');
        redirect('client/operationDashboard/' . $_SESSION['user_id']);
    }

    // public function rejectQuotation($quotationId) {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         if ($this->clientSidePreProjectModel->rejectQuotation($quotationId)) {
    //             flash('quotation_message', 'Quotation rejected successfully');
    //             redirect('client/operationDashboard/' . $_SESSION['user_id']);
    //         } else {
    //             flash('quotation_message', 'Failed to reject quotation', 'error');
    //             redirect('client/operationDashboard/' . $_SESSION['user_id']);
    //         }
    //     } else {
    //         redirect('client/operationDashboard/' . $_SESSION['user_id']);
    //     }
    // }


    public function rejectQuotation($quotationId)
    {
        // First verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            flash('quotation_message', 'Please login first', 'error');
            redirect('users/login');
        }

        // Check request method
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('client/operationDashboard/' . $_SESSION['user_id']);
        }

        // Verify the quotation belongs to this user before rejecting
        $quotation = $this->clientSidePreProjectModel->getQuotationById($quotationId);
        if (!$quotation || $quotation->user_id != $_SESSION['user_id']) {
            flash('quotation_message', 'Invalid quotation', 'error');
            redirect('client/operationDashboard/' . $_SESSION['user_id']);
        }

        // Process rejection
        if ($this->clientSidePreProjectModel->rejectQuotation($quotationId)) {
            flash('quotation_message', 'Quotation rejected successfully');
        } else {
            flash('quotation_message', 'Failed to reject quotation', 'error');
        }

        redirect('client/operationDashboard/' . $_SESSION['user_id']);
    }

    public function downloadQuotation($quotationId)
    {

        error_log("Attempting to download quotation ID: " . $quotationId);
        error_log("User ID: " . $_SESSION['user_id']);
        // Verify user has access to this quotation
        $quotation = $this->clientSidePreProjectModel->getReviewedQuotationDetails($quotationId);

        if (!$quotation || $quotation->user_id !== $_SESSION['user_id']) {
            flash('quotation_message', 'Quotation not found', 'error');
            redirect('client/operationDashboard');
            return;
        }

        try {
            $equipment = $this->clientSidePreProjectModel->getReviewedQuotationEquipment($quotationId);

            // Create PDF Generator instance
            require_once APPROOT . '/libraries/PdfGenerator.php';
            $pdfGenerator = new PdfGenerator();

            // Generate and output PDF
            $pdf = $pdfGenerator->generateQuotationPDF($quotation, $equipment);

            // Output headers
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Quotation_QT' .
                str_pad($quotationId, 5, '0', STR_PAD_LEFT) . '.pdf"');

            // Output PDF content
            echo $pdf;
            exit();
        } catch (Exception $e) {
            error_log("PDF Generation Error: " . $e->getMessage());
            flash('quotation_message', 'Error generating PDF', 'error');
            redirect('client/viewQuotation/' . $quotationId);
        }
    }

    public function viewProject($projectId)
    {
        $project = $this->customerProjectModel->getProjectById($projectId);

        if (!$project || $project->customer_id !== $_SESSION['user_id']) {
            redirect('customer');
        }

        $data = [
            'project' => $project,
            'title' => 'Project Details'
        ];

        $this->view('client/v_clientProject', $data);
    }

    public function project($preProjectId = null, $phase = null)
    {
        if ($preProjectId === null) {
            if (isset($_SESSION['current_project_id'])) {
                $preProjectId = $_SESSION['current_project_id'];
            } else {
                $userId = $_SESSION['user_id'];
                $activeProjects = $this->clientSidePreProjectModel->getActiveProjects($userId);

                if (empty($activeProjects)) {
                    flash('project_message', 'No active projects found', 'info');
                    redirect('client/dashboard');
                }

                $preProjectId = $activeProjects[0]->pre_project_id;
            }
        }

        $_SESSION['current_project_id'] = $preProjectId;

        $progress = $this->clientSidePreProjectModel->getProjectProgress($preProjectId);

        if (!$progress) {
            flash('project_message', 'Project not found', 'error');
            redirect('client/dashboard');
        }

        if ($progress['pre_project']->customer_id != $_SESSION['user_id']) {
            flash('project_message', 'Unauthorized access', 'error');
            redirect('client/dashboard');
        }

        if ($phase !== null) {
            $data = [
                'progress' => $progress,
                'pre_project_id' => $preProjectId,
                'phase' => $phase
            ];

            $this->view('client/v_' . $phase, $data);
            return;
        }

        // Load main project view
        $data = [
            'progress' => $progress,
            'pre_project_id' => $preProjectId
        ];

        $this->view('client/v_clientProject', $data);
    }


    //#############################################################################################
    //----------------------------------------- site visit-----------------------------------------
    //#############################################################################################

    public function siteVisit($preProjectId)
    {
        $siteVisit = $this->clientSidePreProjectModel->getSiteVisit($preProjectId);

        if (!$siteVisit) {
            redirect('client/dashboard');
        }

        $data = [
            'site_visit' => $siteVisit
        ];

        $this->view('client/v_clientsitevisit', $data);
    }

    public function confirmSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $visitId = $_POST['visit_id'];

            if ($this->clientSidePreProjectModel->confirmSchedule($visitId)) {
                flash('message', 'Schedule confirmed successfully');
            } else {
                flash('message', 'Something went wrong', 'error');
            }
        }
        redirect('client/siteVisit/' . $_POST['pre_project_id']);
    }

    public function requestReschedule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/dashboard');
        }

        $visitId = $_POST['visit_id'];
        $reason = $_POST['reason'];
        $preProjectId = $_POST['pre_project_id'];

        if ($this->clientSidePreProjectModel->requestReschedule($visitId, $reason)) {
            flash('site_visit_message', 'Reschedule request submitted successfully', 'alert-success');
        } else {
            flash('site_visit_message', 'Failed to submit reschedule request', 'alert-danger');
        }

        redirect('client/siteVisit/' . $preProjectId);
    }


    // public function project() {
    //     // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
    //     $data = [];
    //     $this->view('client/v_clientProject', $data);
    // }

    //###################################################################################################
    //----------------------------------------- Agreement ----------------------------------------------
    //###################################################################################################

    public function agreement($preProjectId = null)
    {
        if ($preProjectId === null) {
            redirect('client/project');
        }

        $agreement = $this->clientSidePreProjectModel->getPendingAgreement($preProjectId);

        if (!$agreement) {
            flash('agreement_message', 'No pending agreement found', 'alert alert-info');
            redirect('client/project');
        }

        // Get equipment list
        $equipment = $this->clientSidePreProjectModel->getAgreementEquipment($agreement->agreement_id);

        $data = [
            'agreement' => $agreement,
            'equipment' => $equipment
        ];

        $this->view('client/v_clientAgreement', $data);
    }

    public function requestRevision()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/project');
        }

        $agreementId = $_POST['agreement_id'];
        $revisionNote = $_POST['revision_note'];

        if (empty($revisionNote)) {
            $response = ['success' => false, 'message' => 'Please provide revision details'];
        } else {
            $success = $this->clientSidePreProjectModel->submitRevisionRequest($agreementId, $revisionNote);
            $response = [
                'success' => $success,
                'message' => $success ? 'Revision request submitted successfully' : 'Failed to submit revision request'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    private function handleSignatureUpload($file)
    {
        // Use the absolute server path
        $uploadDir = dirname(APPROOT) . '/public/uploads/signatures/customers/';

        // Debug log
        error_log("Upload directory: " . $uploadDir);

        // Create directory if doesn't exist
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                error_log("Failed to create directory");
                return false;
            }
        }

        // Generate filename
        $fileName = uniqid() . '_' . $file['name'];
        $uploadPath = $uploadDir . $fileName;

        // Try to move the file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            error_log("File uploaded successfully to: " . $uploadPath);
            return $fileName;  // Return just the filename for database storage
        } else {
            error_log("Failed to move uploaded file. Error code: " . $file['error']);
            return false;
        }
    }

    public function submitSignature()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/project');
        }

        $agreementId = $_POST['agreement_id'];
        $signature = $_FILES['signature'] ?? null;

        // Debug log
        error_log("Received signature upload request for agreement: " . $agreementId);
        error_log("File data: " . print_r($signature, true));

        if (!$signature || $signature['error'] !== UPLOAD_ERR_OK) {
            $response = ['success' => false, 'message' => 'Please provide a signature'];
        } else {
            $signaturePath = $this->handleSignatureUpload($signature);
            if ($signaturePath) {
                $success = $this->clientSidePreProjectModel->uploadSignature($agreementId, $signaturePath);
                $response = [
                    'success' => $success,
                    'message' => $success ? 'Agreement signed successfully' : 'Failed to sign agreement'
                ];
            } else {
                $response = ['success' => false, 'message' => 'Failed to upload signature'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function cancelProject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/project');
        }

        $preProjectId = $_POST['pre_project_id'];
        $success = $this->clientSidePreProjectModel->cancelProject($preProjectId);

        $response = [
            'success' => $success,
            'message' => $success ? 'Project cancelled successfully' : 'Failed to cancel project'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }


    //###################################################################################################
    //----------------------------------------- Project phase ----------------------------------------------
    //###################################################################################################

    //###################################################################################################
    //----------------------------------------- Document Submission ----------------------------------------------
    //###################################################################################################

    public function documents($preProjectId = null)
    {
        if ($preProjectId === null) {
            redirect('client/project');
        }

        // Get the project using pre_project_id
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);

        if (!$project) {
            flash('document_message', 'Project not found', 'alert alert-danger');
            redirect('client/project');
        }

        // Check if this project belongs to the logged-in user
        if ($project->customer_id != $_SESSION['user_id']) {
            flash('document_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/project');
        }

        // Get document submission if exists
        $documentSubmission = $this->clientSideProjectModel->getDocumentSubmission($project->project_id);

        $data = [
            'project_id' => $project->project_id,
            'pre_project_id' => $preProjectId
        ];

        // If document submission exists, add its data
        if ($documentSubmission) {
            $data['document_status'] = $documentSubmission->status || 'NULL';
            $data['submission_date'] = $documentSubmission->created_at;

            if ($documentSubmission->status == 'reject') {
                $data['rejection_reason'] = $documentSubmission->rejection_reason ?? 'Document did not meet requirements.';
            }

            if ($documentSubmission->status == 'accept') {
                $data['approval_date'] = $documentSubmission->updated_at;
            }
        }

        $this->view('client/v_clientDocument', $data);
    }

    public function submitDocument()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/project');
        }

        // Return JSON response
        header('Content-Type: application/json');

        if (!isset($_FILES['document']) || !isset($_POST['project_id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing document or project ID']);
            return;
        }

        $file = $_FILES['document'];
        $projectId = $_POST['project_id'];

        // Validate file
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Please upload a PDF, JPEG, or PNG file.']);
            return;
        }

        if ($file['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds the 5MB limit.']);
            return;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = dirname(APPROOT) . '/public/uploads/documents/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Save document in database
            $documentData = [
                'project_id' => $projectId,
                'document' => $fileName,
                'status' => 'pending' // Default status after submission
            ];

            if ($this->clientSideProjectModel->submitDocument($documentData)) {
                echo json_encode(['success' => true, 'message' => 'Document uploaded successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save document information']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload document']);
        }
    }

    /**
     * Display the first payment page
     * 
     * @param int $preProjectId The pre-project ID
     * @return void
     */
    public function firstPayment($preProjectId = null)
    {
        if ($preProjectId === null) {
            redirect('client/project');
        }

        // Get project data
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);

        if (!$project) {
            flash('payment_message', 'Project not found', 'alert alert-danger');
            redirect('client/project');
        }

        // Check if the project belongs to the logged-in user
        if ($project->customer_id != $_SESSION['user_id']) {
            flash('payment_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/project');
        }

        // Get agreement data (for pricing information)
        $agreement = $this->clientSideProjectModel->getAgreementByPreProjectId($preProjectId);
        if (!$agreement) {
            flash('payment_message', 'Agreement not found', 'alert alert-danger');
            redirect('client/project/' . $preProjectId);
        }

        // Get payment details (if any)
        $payment = $this->clientSideProjectModel->getProjectPayment($project->project_id, 'first_payment');

        // Get bank slip details (if any)
        $bankSlip = $this->clientSideProjectModel->getProjectBankSlip($project->project_id, 'first_payment');

        // Calculate first payment amount (25% of total)
        $paymentAmount = $agreement->total_price * 0.25;

        $data = [
            'pre_project_id' => $preProjectId,
            'project_id' => $project->project_id,
            'base_price' => $agreement->base_price,
            'service_charge' => $agreement->service_charge,
            'total_price' => $agreement->total_price,
            'payment_amount' => $paymentAmount,
            'payment' => $payment,
            'bank_slip' => $bankSlip
        ];

        $this->view('client/v_clientFirstPayment', $data);
    }

    /**
     * Generate bank deposit slip
     * 
     * @return void
     */
    public function generateBankSlip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/dashboard');
        }

        $projectId = $_POST['project_id'];
        $preProjectId = $_POST['pre_project_id'];
        $paymentPhase = $_POST['payment_phase'];
        $amount = $_POST['amount'];
        $bankAccountIndex = $_POST['bank_account'];

        // Validate inputs
        if (
            empty($projectId) || empty($preProjectId) || empty($paymentPhase) ||
            empty($amount) || !isset($bankAccountIndex)
        ) {
            flash('payment_message', 'Missing required fields', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }

        // Verify project belongs to the user
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);
        if (!$project || $project->customer_id != $_SESSION['user_id']) {
            flash('payment_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/dashboard');
        }

        // Get the agreement to verify amount limits
        $agreement = $this->clientSideProjectModel->getAgreementByPreProjectId($preProjectId);
        if (!$agreement) {
            flash('payment_message', 'Agreement details not found', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }

        // Validate amount (min: 25% of total, max: total price)
        $minAmount = $agreement->total_price * 0.25;
        if ($amount < $minAmount || $amount > $agreement->total_price) {
            flash('payment_message', 'Invalid payment amount', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }

        // Get the selected bank account
        if (!isset(BANK_ACCOUNTS[$bankAccountIndex])) {
            flash('payment_message', 'Invalid bank account selected', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }
        $bankAccount = BANK_ACCOUNTS[$bankAccountIndex];

        // Customer details
        $customer = $this->clientModel->getClientByUserId($_SESSION['user_id']);

        // Generate PDF
        require_once APPROOT . '/libraries/PdfGenerator.php';
        $pdfGenerator = new PdfGenerator();
        $pdf = $pdfGenerator->generateBankDepositSlip([
            'bank_account' => $bankAccount,
            'amount' => $amount,
            'reference' => 'PR' . str_pad($project->project_id, 5, '0', STR_PAD_LEFT),
            'customer_name' => $customer->name,
            'customer_id' => $_SESSION['user_id']
        ]);

        // Record that a slip was downloaded
        $this->clientSideProjectModel->recordSlipDownloaded($projectId, $paymentPhase, $amount);

        // Output PDF to browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Bank_Deposit_Slip.pdf"');
        echo $pdf;
        exit();
    }

    /**
     * Upload bank slip for payment
     * 
     * @return void
     */
    public function uploadBankSlip()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/dashboard');
        }

        $projectId = $_POST['project_id'];
        $preProjectId = $_POST['pre_project_id'];
        $paymentPhase = $_POST['payment_phase'];
        $amount = $_POST['amount'];
        $slipId = $_POST['slip_id']; // Get the existing slip ID

        // Verify project belongs to the user
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);
        if (!$project || $project->customer_id != $_SESSION['user_id']) {
            flash('payment_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/dashboard');
        }

        // Check if file was uploaded
        if (!isset($_FILES['payment_slip']) || $_FILES['payment_slip']['error'] !== UPLOAD_ERR_OK) {
            flash('payment_message', 'Please upload a valid payment slip', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }

        // Handle file upload
        $file = $_FILES['payment_slip'];
        $uploadDir = 'uploads/projectbankslips/';
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('slip_') . '.' . $fileExt;

        // Absolute path for file operations
        $absoluteUploadDir = dirname(APPROOT) . '/public/' . $uploadDir;
        $absoluteUploadPath = $absoluteUploadDir . $fileName;



        // Create directory if it doesn't exist
        if (!file_exists($absoluteUploadDir)) {
            mkdir($absoluteUploadDir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $absoluteUploadPath)) {
            // Update the existing bank slip record
            if ($this->clientSideProjectModel->updateBankSlipFile($slipId, $fileName)) {
                flash('payment_message', 'Payment slip uploaded successfully. It is now under review.', 'alert alert-success');
            } else {
                flash('payment_message', 'Failed to update payment slip record', 'alert alert-danger');
            }

            redirect('client/firstPayment/' . $preProjectId);
        } else {
            flash('payment_message', 'Failed to upload payment slip', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }
    }

    /**
     * Process online payment
     * 
     * @return void
     */
    public function processOnlinePayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/dashboard');
        }

        $projectId = $_POST['project_id'];
        $preProjectId = $_POST['pre_project_id'];
        $paymentPhase = $_POST['payment_phase'];
        $amount = $_POST['amount'];

        // Validate inputs
        if (empty($projectId) || empty($preProjectId) || empty($paymentPhase) || empty($amount)) {
            flash('payment_message', 'Invalid request data', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }

        // Verify project belongs to the user
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);
        if (!$project || $project->customer_id != $_SESSION['user_id']) {
            flash('payment_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/dashboard');
        }

        // Create pending payment record
        $paymentId = $this->clientSideProjectModel->createProjectPayment([
            'project_id' => $projectId,
            'payment_method' => 'online',
            'amount' => $amount,
            'payment_phase' => $paymentPhase,
            'payment_status' => false // payment pending
        ]);

        if (!$paymentId) {
            flash('payment_message', 'Error initiating payment', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }

        // Redirect to payment gateway
        $data = [
            'project_id' => $projectId,
            'pre_project_id' => $preProjectId,
            'payment_id' => $paymentId,
            'payment_phase' => $paymentPhase,
            'amount' => $amount
        ];

        $this->view('client/v_paymentGateway', $data);
    }

    /**
     * Complete online payment
     * 
     * @return void
     */
    public function completeOnlinePayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/dashboard');
        }

        $projectId = $_POST['project_id'];
        $preProjectId = $_POST['pre_project_id'];
        $paymentId = $_POST['payment_id'];
        $paymentPhase = $_POST['payment_phase'];
        $amount = $_POST['amount'];

        // Verify project belongs to the user
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);
        if (!$project || $project->customer_id != $_SESSION['user_id']) {
            flash('payment_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/dashboard');
        }

        // Simulate successful payment
        // In a real application, this would verify the payment with a payment gateway

        // Generate transaction ID
        $transactionId = 'TRANS_' . uniqid();

        // Update payment status
        $paymentUpdated = $this->clientSideProjectModel->updateProjectPayment($paymentId, [
            'payment_status' => true,
            'transaction_id' => $transactionId
        ]);

        if ($paymentUpdated) {
            flash('payment_message', 'Payment completed successfully!', 'alert alert-success');
        } else {
            flash('payment_message', 'Failed to update payment status', 'alert alert-danger');
        }

        redirect('client/firstPayment/' . $preProjectId);
    }

    /**
     * Record cash payment intent
     * 
     * @return void
     */
    public function recordCashPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/dashboard');
        }

        $projectId = $_POST['project_id'];
        $preProjectId = $_POST['pre_project_id'];
        $paymentPhase = $_POST['payment_phase'];
        $amount = $_POST['amount'];

        // Validate inputs
        if (empty($projectId) || empty($preProjectId) || empty($paymentPhase) || empty($amount)) {
            flash('payment_message', 'Invalid request data', 'alert alert-danger');
            redirect('client/firstPayment/' . $preProjectId);
        }

        // Verify project belongs to the user
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);
        if (!$project || $project->customer_id != $_SESSION['user_id']) {
            flash('payment_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/dashboard');
        }

        // Create pending payment record for cash
        $paymentId = $this->clientSideProjectModel->createProjectPayment([
            'project_id' => $projectId,
            'payment_method' => 'cash',
            'amount' => $amount,
            'payment_phase' => $paymentPhase,
            'payment_status' => false // Cash payment pending
        ]);

        if ($paymentId) {
            flash('payment_message', 'Your cash payment intention has been recorded. Please visit our office at ' . address . ' to complete the payment.', 'alert alert-success');
        } else {
            flash('payment_message', 'Error recording payment intention', 'alert alert-danger');
        }

        redirect('client/firstPayment/' . $preProjectId);
    }

    /**
     * Cancel the current payment method selection
     */
    public function cancelPayment($preProjectId)
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Verify project belongs to the user
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);
        if (!$project || $project->customer_id != $_SESSION['user_id']) {
            flash('payment_message', 'Unauthorized access', 'alert alert-danger');
            redirect('client/dashboard');
        }

        // Delete any pending payment records
        $this->clientSideProjectModel->deletePendingPayment($project->project_id, 'first_payment');

        flash('payment_message', 'Payment method reset. You can now choose a different payment method.', 'alert alert-success');
        redirect('client/firstPayment/' . $preProjectId);
    }

    public function installation($preProjectId = null)
    {
        if (!$preProjectId) {
            flash('installation_message', 'Project ID is required', 'alert alert-danger');
            redirect('client/operationDashboard');
        }

        // Get project ID from pre-project ID
        $project = $this->clientSideProjectModel->getProjectByPreProjectId($preProjectId);
        if (!$project) {
            flash('installation_message', 'Project not found', 'alert alert-danger');
            redirect('client/operationDashboard');
        }

        // Get installation details
        $installation = $this->clientSideProjectModel->getInstallationPhase($project->project_id);
        $schedule = null;
        $engineer = null;

        if ($installation) {
            // Get schedule data
            $schedule = $this->clientSideProjectModel->getInstallationSchedule($installation->installation_id);

            // Get engineer data if assigned
            $engineer = $this->clientSideProjectModel->getAssignedEngineer($installation->installation_id);
        }

        $data = [
            'pre_project_id' => $preProjectId,
            'project' => $project,
            'installation' => $installation,
            'schedule' => $schedule,
            'engineer' => $engineer
        ];

        $this->view('client/v_clientInstallation', $data);
    }

    public function acceptInstallationSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/operationDashboard');
        }

        $scheduleId = $_POST['schedule_id'];
        $preProjectId = $_POST['pre_project_id'];

        // Update schedule status to accepted
        if ($this->clientSideProjectModel->acceptInstallationSchedule($scheduleId)) {
            flash('installation_message', 'Installation schedule accepted', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to accept schedule', 'alert alert-danger');
        }

        redirect('client/installation/' . $preProjectId);
    }

    public function requestInstallationReschedule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/operationDashboard');
        }

        $scheduleId = $_POST['schedule_id'];
        $preProjectId = $_POST['pre_project_id'];
        $reason = $_POST['reschedule_reason'];

        if (empty($reason)) {
            flash('installation_message', 'Please provide a reason for rescheduling', 'alert alert-danger');
            redirect('client/installation/' . $preProjectId);
        }

        // Update schedule status to reschedule requested
        if ($this->clientSideProjectModel->requestInstallationReschedule($scheduleId, $reason)) {
            flash('installation_message', 'Reschedule request submitted successfully', 'alert alert-success');
        } else {
            flash('installation_message', 'Failed to request reschedule', 'alert alert-danger');
        }

        redirect('client/installation/' . $preProjectId);
    }


    public function finalPayment()
    {
        $data = [];
        $this->view('client/v_clientFinalPayment', $data);
    }

    public function settings()
    {
        // Get user data using session user_id
        $userId = $_SESSION['user_id'];
        $userData = $this->clientModel->getUserById($userId);

        if (!$userData) {
            flash('profile_message', 'User data not found', 'alert alert-danger');
            redirect('client/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle profile update
            if (isset($_POST['update_profile'])) {
                $phone = trim($_POST['phone']);

                $updateData = [
                    'phone' => $phone,
                    'phone_err' => ''
                ];

                // Validate phone
                if (empty($phone)) {
                    $updateData['phone_err'] = 'Please enter phone number';
                }

                // If no errors, update profile
                if (empty($updateData['phone_err'])) {
                    if ($this->clientModel->updateProfile($userId, $updateData)) {
                        flash('profile_message', 'Profile Updated Successfully', 'alert alert-success');
                        redirect('client/settings');
                    } else {
                        flash('profile_message', 'Something went wrong', 'alert alert-danger');
                    }
                }
            }

            // Handle password change
            if (isset($_POST['change_password'])) {
                $currentPassword = trim($_POST['current_password']);
                $newPassword = trim($_POST['new_password']);

                if (empty($currentPassword) || empty($newPassword)) {
                    flash('password_message', 'Both password fields are required', 'alert alert-danger');
                } else {
                    if ($this->clientModel->verifyPassword($userId, $currentPassword)) {
                        if ($this->clientModel->updatePassword($userId, $newPassword)) {
                            flash('password_message', 'Password Updated Successfully', 'alert alert-success');
                            redirect('client/settings');
                        } else {
                            flash('password_message', 'Failed to update password', 'alert alert-danger');
                        }
                    } else {
                        flash('password_message', 'Current password is incorrect', 'alert alert-danger');
                    }
                }
            }

            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_picture'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
                    $fileName = uniqid() . '_' . basename($file['name']);
                    $uploadDir = APPROOT . '/../public/uploads/profile_pictures/';

                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $uploadPath = $uploadDir . $fileName;

                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        if ($this->clientModel->updateProfilePicture($userId, $fileName)) {
                            flash('profile_picture_message', 'Profile Picture Updated Successfully', 'alert alert-success');
                            redirect('client/settings');
                        } else {
                            flash('profile_picture_message', 'Failed to update database', 'alert alert-danger');
                        }
                    } else {
                        flash('profile_picture_message', 'Failed to upload file', 'alert alert-danger');
                    }
                } else {
                    flash('profile_picture_message', 'Invalid file type or size', 'alert alert-danger');
                }
            }
        }

        // Prepare view data
        $viewData = [
            'name' => $userData->name,
            'email' => $userData->email,
            'phone' => $userData->phone ?? '',
            'profile_picture' => $userData->profile_picture ?? '',
            'title' => 'Settings'
        ];

        $this->view('client/v_clientSettings', $viewData);
    }

    public function shop()
    {
        $userId = $_SESSION['user_id'];
        $orders = $this->shopModel->getUserOrders($userId);
        $data = [
            'orders' => $orders
        ];
        $this->view('client/v_clientShop', $data);
    }

    public function confirmOrder($orderId)
    {
        $order = $this->shopModel->getOrderDetailsByID($orderId);
        if ($order) {
            $data = [
                'order' => $order
            ];
            $this->view('client/v_clientConfirmOrder', $data);
        } else {
            redirect('client/shop');
        }
    }

    public function cancelOrder($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->shopModel->cancelOrder($id)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to cancel order']);
            }
        }
    }

    public function processOrder()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = json_decode(file_get_contents("php://input"));

                if (!isset($data->orderId) || !isset($data->paymentMethod)) {
                    throw new Exception("Invalid request data");
                }

                switch ($data->paymentMethod) {
                    case 'cash':
                        if ($this->shopModel->processPayment($data->orderId, 'cash')) {
                            echo json_encode([
                                'success' => true,
                                'redirect' => 'shop'
                            ]);
                        } else {
                            throw new Exception('Failed to process payment');
                        }
                        break;

                    case 'online':
                        echo json_encode([
                            'success' => true,
                            'redirect' => 'checkout/' . $data->orderId
                        ]);
                        break;

                    case 'bank':
                        echo json_encode([
                            'success' => true,
                            'redirect' => 'bankDeposit/' . $data->orderId
                        ]);
                        break;

                    default:
                        throw new Exception('Invalid payment method');
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit;
        }
    }

    public function checkout($orderId)
    {
        $order = $this->shopModel->getOrderDetailsByID($orderId);
        if ($order) {
            $data = [
                'order' => $order
            ];
            $this->view('client/v_clientCheckout', $data);
        } else {
            redirect('client/shop');
        }
    }

    public function bankDeposit($orderId)
    {
        $order = $this->shopModel->getOrderDetailsByID($orderId);
        if ($order) {
            $data = [
                'order' => $order
            ];
            $this->view('client/v_clientBankDeposit', $data);
        } else {
            redirect('client/shop');
        }
    }

    public function processBankDeposit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            try {
                if (!isset($_FILES['slip']) || !isset($_POST['orderId'])) {
                    throw new Exception('Invalid request data');
                }

                $file = $_FILES['slip'];
                $orderId = $_POST['orderId'];

                // Validate file
                $allowedTypes = ['image/jpeg', 'image/png'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if (!in_array($file['type'], $allowedTypes)) {
                    throw new Exception('Invalid file type');
                }

                if ($file['size'] > $maxSize) {
                    throw new Exception('File too large');
                }

                // Create upload directory if it doesn't exist
                $uploadDir = 'uploads/bank_slips/';
                $fullUploadDir = APPROOT . '/../public/' . $uploadDir;

                if (!file_exists($fullUploadDir)) {
                    mkdir($fullUploadDir, 0777, true);
                }

                // Generate unique filename
                $fileName = uniqid() . '_' . basename($file['name']);
                $filePath = $fullUploadDir . $fileName;
                $dbFilePath = $uploadDir . $fileName; // Path to store in database

                // Upload file
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    if ($this->shopModel->uploadBankSlip($orderId, $dbFilePath)) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Bank slip uploaded successfully'
                        ]);
                    } else {
                        throw new Exception('Failed to save payment record');
                    }
                } else {
                    throw new Exception('Failed to upload file');
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit;
        }
    }
}
