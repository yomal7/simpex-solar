<?php

class Client extends Controller
{
    private $clientModel;
    private $shopModel;
    private $clientSidePreProjectModel;
    private $customerProjectModel;
    private $chatModel;

    public function __construct()
    {
        // Check if user is logged in and is a customer
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
            redirect('users/index');
        }

        $this->clientModel = $this->model('M_Client');
        $this->clientSidePreProjectModel = $this->model('M_clientSidePreProject');
        $this->customerProjectModel = $this->model('M_CustomerProject');
        $this->shopModel = $this->model('M_Shop');
        $this->chatModel = $this->model('M_Chat');

        // Set total unread count for notification badge
        if (!isset($_GET['getUnreadStatus'])) { // Skip for AJAX requests
            $_SESSION['total_unread_count'] = $this->chatModel->getTotalUnreadMessages($_SESSION['user_id']);
        }
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
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('client/v_clientDashboard', $data);
    }

    // public function operationDashboard() {
    //     $userId = $_SESSION['user_id'];

    //     // Get all pre-projects and projects
    //     $preProjects = $this->clientSidePreProjectModel->getPreProjectsByCustomerId($userId);
    //     $projects = $this->customerProjectModel->getProjectsByCustomerId($userId);

    //     // Get active quotation if exists
    //     $activeQuotation = $this->clientSidePreProjectModel->getActiveQuotationByCustomerId($userId);

    //     // Get stats
    //     $stats = [
    //         'total_projects' => count($projects),
    //         'active_projects' => count(array_filter($projects, function($p) { 
    //             return $p->status === 'active'; 
    //         })),
    //         'pending_quotations' => count(array_filter($preProjects, function($p) { 
    //             return $p->current_phase === 'quotation'; 
    //         }))
    //     ];

    //     $data = [
    //         'title' => 'Dashboard',
    //         'stats' => $stats,
    //         'quotation' => $activeQuotation,
    //         'projects' => $projects
    //     ];


    //     $this->view('client/v_operationsDashboard', $data);

    // }





    // public function operationDashboard() {
    //     $userId = $_SESSION['user_id'];

    //     // Get all pre-projects and projects
    //     $preProjects = $this->clientSidePreProjectModel->getPreProjectsByCustomerId($userId);
    //     $projects = $this->customerProjectModel->getProjectsByCustomerId($userId);

    //     // Get active quotations - changed to plural
    //     $activeQuotations = $this->clientSidePreProjectModel->getActiveQuotationsByCustomerId($userId);

    //     // Get stats
    //     $stats = [
    //         'total_projects' => count($projects),
    //         'active_projects' => count(array_filter($projects, function($p) {
    //             return $p->status === 'active';
    //         })),
    //         'pending_quotations' => count($activeQuotations)  // Updated to use actual count
    //     ];

    //     $data = [
    //         'title' => 'Dashboard',
    //         'stats' => $stats,
    //         'quotations' => $activeQuotations,  // Changed from quotation to quotations
    //         'projects' => $projects
    //     ];

    //     $this->view('client/v_operationsDashboard', $data);
    // }

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







    //#############################################################################################
    //----------------------------------------- End of preproject phase -----------------------------------------
    //#############################################################################################

    public function firstPayment()
    {
        $data = [];
        $this->view('client/v_clientFirstPayment', $data);
    }

    public function finalPayment()
    {
        $data = [];
        $this->view('client/v_clientFinalPayment', $data);
    }

    public function installation()
    {
        $data = [];
        $this->view('client/v_clientInstallation', $data);
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

    public function chat()
    {
        // Get coordinator information
        $coordinators = [
            'operations' => $this->clientModel->getOperationsCoordinator(),
            'supplier' => $this->clientModel->getSupplierCoordinator()
        ];

        // Get unread message counts for each coordinator
        $unreadCounts = [
            'operations' => $this->chatModel->getUnreadCount($coordinators['operations']->user_id, $_SESSION['user_id']),
            'supplier' => $this->chatModel->getUnreadCount($coordinators['supplier']->user_id, $_SESSION['user_id'])
        ];

        $data = [
            'title' => 'Chat with Coordinators',
            'coordinators' => $coordinators,
            'unread_counts' => $unreadCounts
        ];

        $this->view('client/v_chat', $data);
    }

    public function getChatHistory($coordinatorType)
    {
        if (!in_array($coordinatorType, ['operations', 'supplier', 'hr'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid coordinator type']);
            return;
        }

        // Get coordinator ID based on type
        $coordinator = null;
        if ($coordinatorType == 'operations') {
            $coordinator = $this->clientModel->getOperationsCoordinator();
        } elseif ($coordinatorType == 'supplier') {
            $coordinator = $this->clientModel->getSupplierCoordinator();
        }

        if (!$coordinator) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        $messages = $this->chatModel->getClientChats($coordinator->user_id, $_SESSION['user_id']);

        // Mark messages as read after retrieving them
        $this->chatModel->markMessagesAsRead($coordinator->user_id, $_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($messages);
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

        // Get coordinator role based on the coordinator type
        $receiverRole = 'operationsCoordinator'; // Default
        if (isset($data['recipient_type'])) {
            if ($data['recipient_type'] == 'supplier') {
                $receiverRole = 'supplierCoordinator';
            }
        }

        $messageData = [
            'sender_id' => $_SESSION['user_id'],
            'sender_role' => 'customer',
            'receiver_id' => $data['to_user_id'],
            'receiver_role' => $receiverRole,
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

        if (empty($data['coordinator_type'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Coordinator type required']);
            return;
        }

        // Get coordinator based on type
        $coordinator = null;
        $coordinatorType = $data['coordinator_type'];

        if ($coordinatorType == 'operations') {
            $coordinator = $this->clientModel->getOperationsCoordinator();
        } elseif ($coordinatorType == 'supplier') {
            $coordinator = $this->clientModel->getSupplierCoordinator();
        }

        if (!$coordinator) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Coordinator not found']);
            return;
        }

        $success = $this->chatModel->markMessagesAsRead($coordinator->user_id, $_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    // private function getTotalUnreadCount()
    // {
    //     $total = 0;
    //     $coordinators = [
    //         'operations' => $this->clientModel->getOperationsCoordinator(),
    //         'supplier' => $this->clientModel->getSupplierCoordinator(),
    //         'hr' => $this->clientModel->getHRAdministrator()
    //     ];

    //     foreach ($coordinators as $coordinator) {
    //         if ($coordinator) {
    //             $total += $this->chatModel->getUnreadCount($coordinator->user_id, $_SESSION['user_id']);
    //         }
    //     }

    //     return $total;
    // }

    public function getUnreadStatus()
    {
        // Get the total unread count directly from the chat model
        $count = $this->chatModel->getTotalUnreadMessages($_SESSION['user_id']);
        
        // Store the count in session for access across all views
        $_SESSION['total_unread_count'] = $count;
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['hasUnread' => ($count > 0)]);
    }
}

