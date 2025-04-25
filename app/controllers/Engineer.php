<?php

class engineer extends Controller {
    private $employeeModel;
    private $engineerModel;
    private $leavesModel;
    private $tasksModel;
    private $projectModel;
    private $preProjectModel;
    private $settingsModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'engineer') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');
        $this->engineerModel = $this->model('M_Engineer');
        $this->leavesModel = $this->model('M_Leaves');
        $this->tasksModel = $this->model('M_Tasks');
        $this->projectModel = $this->model('M_CustomerProject');
        $this->preProjectModel = $this->model('M_CustomerPreProject');
        $this->settingsModel = $this->model('M_Settings');
    }

    public function index()
    {
        //$engineer = $this->employeeModel->getEngineerByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('engineer/v_engineerDashboard', $data);
    }

    public function dashboard() {
        //$engineer = $this->employeeModel->getEngineerByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('engineer/v_engineerDashboard', $data);
    }

    public function requestHoliday()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 5;
            $offset = ($page - 1) * $limit;

            if (!$employee) {
                flash('error_msg', 'Employee not found');
                redirect('users/login');
            }

            $data = [
                'employee' => $employee,
                'holidayRecords' => $this->leavesModel->getHolidayRecords($employee->employee_id, $limit, $offset),
                'totalRecords' => $this->leavesModel->getTotalHolidayRecords($employee->employee_id),
                'currentPage' => $page,
                'totalPages' => ceil($this->leavesModel->getTotalHolidayRecords($employee->employee_id) / $limit),
                'start_date' => '',
                'end_date' => '',
                'number_of_days' => '',
                'reason' => '',
                'leave_type' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'days_err' => '',
                'reason_err' => '',
                'leave_type_err' => ''
            ];

            $this->view('engineer/v_engineerRequestHoliday', $data);
        } else {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

            $data = [
                'employee' => $employee,
                'holidayRecords' => $this->leavesModel->getHolidayRecords($employee->employee_id),
                'employee_id' => $employee->employee_id,
                'start_date' => trim($_POST['startDate']),
                'end_date' => trim($_POST['endDate']),
                'number_of_days' => trim($_POST['numberOfDays']),
                'reason' => trim($_POST['reason']),
                'leave_type' => trim($_POST['leaveType']),
                'status' => 'pending',
                // Initialize error fields
                'start_date_err' => '',
                'end_date_err' => '',
                'days_err' => '',
                'reason_err' => '',
                'leave_type_err' => ''
            ];

            // Validation
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please select start date';
            }

            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please select end date';
            }

            //if (empty($data['number_of_days'])) {
            //  $data['days_err'] = 'Please enter number of days';
            //}

            if (empty($data['reason'])) {
                $data['reason_err'] = 'Please enter reason for leave';
            } else {
                if (strlen($data['reason']) > 75) {
                    $data['reason_err'] = 'Reason must be less than 75 characters';
                }
            }

            if (empty($data['leave_type'])) {
                $data['leave_type_err'] = 'Please select leave type';
            }

            // Make sure no errors
            if (
                empty($data['start_date_err']) &&
                empty($data['end_date_err']) &&
                empty($data['days_err']) &&
                empty($data['reason_err']) &&
                empty($data['leave_type_err'])
            ) {
                // Prepare holiday data
                $holidayData = [
                    'employee_id' => $data['employee_id'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'number_of_days' => $data['number_of_days'],
                    'reason' => $data['reason'],
                    'leave_type' => $data['leave_type'],
                    'status' => $data['status']
                ];

                if ($this->leavesModel->addHolidayRecords($holidayData)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Holiday request submitted successfully'
                    ]);
                    return;
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to submit request'
                    ]);
                    return;
                }
            } else {
                // Load view with errors
                $this->view('engineer/v_engineerRequestHoliday', $data);
            }
        }
    }

    public function holidayDetails($recordId = null)
    {
        if (!$recordId) {
            redirect('engineer/requestHoliday');
        }

        $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

        if (!$employee) {
            flash('error_msg', 'Employee not found');
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $holidayDetails = $this->leavesModel->getHolidayRecordById($recordId);

            // Check if this holiday request belongs to this employee
            if (!$holidayDetails || $holidayDetails->employee_id != $employee->employee_id) {
                flash('error_msg', 'Holiday request not found or access denied');
                redirect('engineer/requestHoliday');
            }

            $data = [
                'record' => $holidayDetails
            ];

            $this->view('engineer/v_requestDetails', $data);
        }
    }

    // public function tasks() {
    //     //$engineer = $this->tasksModel->getEngineerByUserId($_SESSION['employee_id']);
    //     $data = [];
    //     $this->view('engineer/v_engineerTasks', $data);
    // }

    public function siteVisits() {
        $pendingSiteVisits = $this->engineerModel->getPendingSiteVisits();
        $data = [
            'pendingVisits' => $pendingSiteVisits
        ];
        $this->view('engineer/v_siteVisits', $data);
    }
    
    public function manageSiteVisit($visitId) {
        $siteVisit = $this->engineerModel->getSiteVisitById($visitId);
        
        if (!$siteVisit) {
            flash('site_visit_message', 'Site visit not found', 'error');
            redirect('engineer/siteVisits');
        }
        
        $project = $this->preProjectModel->getPreProjectById($siteVisit->pre_project_id);
        
        if (!$project) {
            flash('site_visit_message', 'Project not found', 'error');
            redirect('engineer/siteVisits');
        }
        
        // Get package information if available
        $packageEquipment = [];
        $packageFeatures = [];
        if ($siteVisit->package_id) {
            $packageEquipment = $this->engineerModel->getPackageEquipment($siteVisit->package_id);
            $packageFeatures = $this->engineerModel->getPackageFeatures($siteVisit->package_id);
        }
        
        $data = [
            'project' => $project,
            'site_visit' => $siteVisit,
            'package_equipment' => $packageEquipment,
            'package_features' => $packageFeatures,
            'title' => 'Manage Site Visit'
        ];
        
        $this->view('engineer/v_manageSiteVisit', $data);
    }
    
    public function completeSiteVisit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('engineer/siteVisits');
            return;
        }
        
        $preProjectId = $_POST['pre_project_id'];
        $visitId = $_POST['visit_id'];
        $notes = $_POST['site_notes'];
        
        if ($this->engineerModel->completeSiteVisit($preProjectId, $notes)) {
            flash('site_visit_message', 'Site visit completed successfully', 'success');
        } else {
            flash('site_visit_message', 'Failed to complete site visit', 'error');
        }
        
        redirect('engineer/siteVisits');
    }   

    public function projects()
    {
        // Get the engineer's employee ID from the session user ID
        $employee = $this->projectModel->getEngineerId($_SESSION['user_id']);

        if (!$employee) {
            // Handle case where employee record not found
            $data = [
                'title' => 'My Projects',
                'activeProjects' => [],
                'initialProjects' => [],
                'completedProjects' => [],
                'currentFilter' => 'active',
                'message' => 'No engineer profile found'
            ];
            $this->view('engineer/v_projects', $data);
            return;
        }

        $engineerId = $employee->employee_id;

        // Get assigned installations for this engineer
        $assignedInstallations = $this->projectModel->getAssignedInstallations($engineerId);

        $installationIds = [];
        foreach ($assignedInstallations as $installation) {
            $installationIds[] = $installation->installation_id;
        }

        // If no installations are assigned, return empty result
        if (empty($installationIds)) {
            $data = [
                'title' => 'My Projects',
                'activeProjects' => [],
                'initialProjects' => [],
                'completedProjects' => [],
                'currentFilter' => 'active',
                'message' => 'No projects are currently assigned to you'
            ];
            $this->view('engineer/v_projects', $data);
            return;
        }

        // Get projects with their installation details
        $projects = $this->projectModel->getEngineerProjects($installationIds);

        // Separate projects by status
        $activeProjects = [];
        $initialProjects = [];
        $completedProjects = [];

        foreach ($projects as $project) {
            if ($project->installation_status === 'active') {
                $activeProjects[] = $project;
            } elseif ($project->installation_status === 'initial') {
                $initialProjects[] = $project;
            } elseif ($project->installation_status === 'completed') {
                $completedProjects[] = $project;
            }
        }

        $data = [
            'title' => 'My Projects',
            'activeProjects' => $activeProjects,
            'initialProjects' => $initialProjects,
            'completedProjects' => $completedProjects,
            'currentFilter' => 'active'
        ];

        $this->view('engineer/v_projects', $data);
    }

    public function viewInstallation($installationId)
    {
        // Get installation details
        $installation = $this->projectModel->getInstallationById($installationId);

        if (!$installation) {
            flash('installation_message', 'Installation not found', 'alert alert-danger');
            redirect('engineer/projects');
            return;
        }

        // Get project details with customer information
        $project = $this->projectModel->getProjectWithCustomerInfo($installation->project_id);

        if (!$project) {
            // Create a default project object with minimal information
            $project = (object)[
                'project_id' => $installation->project_id,
                'customer_name' => 'Not available',
                'location' => 'No location data',
                'phone' => 'Not available',
                'system_capacity' => 'N/A',
                'estimated_generation' => 'N/A'
            ];
        }

        // Get schedule details
        $schedule = $this->projectModel->getInstallationSchedule($installationId);
        if (!$schedule) {
            $schedule = (object)[
                'start_date' => date('Y-m-d'),
                'start_time' => '09:00:00',
                'end_date' => date('Y-m-d', strtotime('+1 day'))
            ];
        }

        // Get engineer data
        $engineer = $this->projectModel->getAssignedEngineer($installationId);
        if (!$engineer) {
            $engineer = (object)[
                'name' => $_SESSION['user_name'] ?? 'Current Engineer'
            ];
        }

        // Get team members
        $teamMembers = $this->projectModel->getInstallationTeamMembers($installationId) ?? [];

        $data = [
            'installation' => $installation,
            'project' => $project,
            'schedule' => $schedule,
            'engineer' => $engineer,
            'team_members' => $teamMembers
        ];

        $this->view('engineer/v_projectInstallation', $data);
    }

    public function startInstallation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'));

        if (!$data || !isset($data->installation_id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        $result = $this->projectModel->updateInstallationStatus($data->installation_id, 'active');

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update installation status']);
        }
    }

    public function completeInstallationStep()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'));

        if (!$data || !isset($data->installation_id) || !isset($data->step)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        $result = $this->projectModel->completeInstallationStep($data->installation_id, $data->step);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to complete installation step']);
        }
    }

    public function saveInstallationNotes()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON data
        $data = json_decode(file_get_contents('php://input'));

        if (!$data || !isset($data->installation_id) || !isset($data->notes)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        // Validate engineer has access to this installation
        $engineerId = $this->projectModel->getEngineerId($_SESSION['user_id'])->employee_id;
        $assignedInstallations = $this->projectModel->getAssignedInstallations($engineerId);

        $hasAccess = false;
        foreach ($assignedInstallations as $installation) {
            if ($installation->installation_id == $data->installation_id) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            echo json_encode(['success' => false, 'message' => 'You do not have access to this installation']);
            return;
        }

        $result = $this->projectModel->saveInstallationNotes($data->installation_id, $data->notes);
      
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save notes']);
        }
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
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Determine which form was submitted
            if(isset($_POST['form_type']) && $_POST['form_type'] == 'profile_update') {
                // Profile update form submitted
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                // Get form data
                $data['email'] = trim($_POST['email']);
                $data['phone'] = trim($_POST['phone']);
                
                // Validate email
                if(empty($data['email'])) {
                    $data['email_err'] = 'Please enter your email';
                } elseif($this->settingsModel->emailExistsForOtherUser($data['email'], $_SESSION['user_id'])) {
                    $data['email_err'] = 'Email is already taken by another user';
                }
                
                // Validate phone
                if(empty($data['phone'])) {
                    $data['phone_err'] = 'Please enter your phone number';
                }
                
                // Handle profile picture upload
                $profileData = [
                    'user_id' => $_SESSION['user_id'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'profile_picture' => $user->profile_picture // Default to current profile picture
                ];
                
                if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    
                    if(!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
                        $data['profile_picture_err'] = 'Only JPG, JPEG and PNG files are allowed';
                    } elseif($_FILES['profile_picture']['size'] > $maxSize) {
                        $data['profile_picture_err'] = 'File size must be less than 2MB';
                    } else {
                        // Generate new filename
                        $filename = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
                        $uploadDir = APPROOT . '/../public/uploads/profile_pictures/';
                        
                        // Create directory if it doesn't exist
                        if(!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        // Upload file
                        if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadDir . $filename)) {
                            $profileData['profile_picture'] = $filename;
                        } else {
                            $data['profile_picture_err'] = 'Error uploading file';
                        }
                    }
                }
                
                // If no errors, update profile
                if(empty($data['email_err']) && empty($data['phone_err']) && empty($data['profile_picture_err'])) {
                    if($this->settingsModel->updateProfile($profileData)) {
                        // Update session variable with new profile picture if it was changed
                        if($profileData['profile_picture'] != $user->profile_picture) {
                            $_SESSION['user_picture'] = $profileData['profile_picture'];
                        }
                        
                        flash('profile_message', 'Profile updated successfully', 'alert alert-success');
                        redirect('engineer/settings');
                    } else {
                        flash('profile_message', 'Something went wrong', 'alert alert-danger');
                    }
                }
            } elseif(isset($_POST['form_type']) && $_POST['form_type'] == 'password_change') {
                // Password change form submitted
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                // Get form data
                $currentPassword = trim($_POST['current_password']);
                $newPassword = trim($_POST['new_password']);
                $confirmPassword = trim($_POST['confirm_password']);
                
                // Validate current password
                if(empty($currentPassword)) {
                    $data['current_password_err'] = 'Please enter your current password';
                } elseif(!$this->settingsModel->verifyPassword($_SESSION['user_id'], $currentPassword)) {
                    $data['current_password_err'] = 'Current password is incorrect';
                } else {
                                    // Validate new password
                if(empty($newPassword)) {
                    $data['new_password_err'] = 'Please enter a new password';
                    } elseif(strlen($newPassword) < 6) {
                        $data['new_password_err'] = 'Password must be at least 6 characters';
                    }
                    
                    // Validate confirm password
                    if(empty($confirmPassword)) {
                        $data['confirm_password_err'] = 'Please confirm your password';
                    } elseif($newPassword != $confirmPassword) {
                        $data['confirm_password_err'] = 'Passwords do not match';
                    }
                }
                
                // If no errors, change password
                if(empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                    // Hash new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    
                    if($this->settingsModel->changePassword($_SESSION['user_id'], $hashedPassword)) {
                        flash('password_message', 'Password changed successfully', 'alert alert-success');
                        redirect('engineer/settings');
                    } else {
                        die('Something went wrong');
                    }
                }
            }
        }
        
        $this->view('engineer/v_settings', $data);
    }
}
