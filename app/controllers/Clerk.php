<?php
class Clerk extends Controller {
    private $attendanceModel;
    private $employeeModel;
    private $settingsModel;
    private $leavesModel;
    private $tasksModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'clerk') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->attendanceModel = $this->model('M_Attendance');
        $this->employeeModel = $this->model('M_Employee');
        $this->settingsModel = $this->model('M_Settings');
        $this->leavesModel = $this->model('M_Leaves');
        $this->tasksModel = $this->model('M_Tasks');
    }

    public function index() {
        redirect('clerk/dashboard');
    }

    public function dashboard() {
        $data = [];
        $this->view('clerk/v_dashboard', $data);
    }

    // Mark today attendance for employees
    public function attendance() {
        $date = date('Y-m-d'); // Current date in YYYY-MM-DD format

        // Fetch attendance records for the selected date or today's date
        $attendanceRecords = $this->attendanceModel->getAttendanceByDate($date);
        
        // Add status to each record based on time_in
        foreach ($attendanceRecords as $record) {
            $record->status = !empty($record->time_in) ? 'Present' : 'Absent';
        }
        
        $data = [
            'attendanceRecords' => $attendanceRecords,
        ];
        $this->view('clerk/v_markAttendance', $data);
    }  

    
    public function markClockIn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the data from the AJAX request
            $employee_id = $_POST['employee_id'];
            $date = $_POST['date'];
            $time_in = date('H:i:s'); // Current time in 24-hour format for DB
            
            // Call the model method to mark attendance
            if ($this->attendanceModel->markClockIn($employee_id, $date, $time_in)) {
                // Format time for display (12-hour format with AM/PM)
                $formatted_time = date('h:i A', strtotime($time_in));
                
                // Return success response
                $response = [
                    'status' => 'success',
                    'message' => 'Clock in recorded successfully',
                    'time' => $formatted_time  // Send formatted time to client
                ];
            } else {
                // Return error response
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to record clock in'
                ];
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    public function markClockOut() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the data from the AJAX request
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $employee_id = $_POST['employee_id'];
            $date = $_POST['date'];
            $time_out = date('H:i:s'); // Current time in 24-hour format for DB
            
            // Call the model method to mark clock out
            if ($this->attendanceModel->markClockOut($employee_id, $date, $time_out)) {
                // Format time for display (12-hour format with AM/PM)
                $formatted_time = date('h:i A', strtotime($time_out));
                
                // Return success response
                $response = [
                    'status' => 'success',
                    'message' => 'Clock out recorded successfully',
                    'time' => $formatted_time  // Send formatted time to client
                ];
            } else {
                // Return error response
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to record clock out'
                ];
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    //View attendance records by date
    public function viewAttendance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $date = trim($_POST['attendance_date']);
        } else {
            // Default to today's date when first accessing the page
            $date = date('Y-m-d'); // Current date in YYYY-MM-DD format
        }

        // Fetch attendance records for the selected date or today's date
        $attendanceRecords = $this->attendanceModel->getAttendanceByDate($date);
        
        // Add status to each record based on time_in
        foreach ($attendanceRecords as $record) {
            $record->status = !empty($record->time_in) ? 'Present' : 'Absent';
        }
        
        $data = [
            'attendanceRecords' => $attendanceRecords,
            'date' => $date
        ];
        $this->view('clerk/v_viewAttendance', $data);
    }

    // public function viewAttendance() {
    //     $attendanceRecords = this->attendanceModel->getAttendanceByDate($date)
    //     $data = [
    //         'attendanceRecords' => $attendanceRecords
    //     ];
    //     $this->view('clerk/v_viewAttendance', $data);
    // }

    // public function tasks() {
    //     $data = [];
    //     $this->view('clerk/v_tasks', $data);
    // }

//     public function requestHoliday() {
//         $data = [
// //            'holidayRecords' => $holidayRecords
//         ];
//         $this->view('clerk/v_requestHoliday', $data);
//     }

    // public function addRequests() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $data = [
    //             'employee_id' => $_SESSION['employee_id'],
    //             'start_date' => $_POST['startDate'],
    //             'end_date' => $_POST['endDate'],
    //             'number_of_days' => $_POST['numberOfDays'],
    //             'reason' => $_POST['reason'],
    //             'leave_type' => $_POST['leaveType']
    //         ];

    //         if ($this->leavesModel->addHolidayRecords($data)) {
    //             header('Location: ' . URLROOT . '/clerk');
    //         } else {
    //             die('Something went wrong');
    //         }
    //     }
    // }

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
                        redirect('clerk/settings');
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
                        redirect('clerk/settings');
                    } else {
                        die('Something went wrong');
                    }
                }
            }
        }
        
        
        $this->view('clerk/v_settings', $data);
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

            $this->view('clerk/v_clerkRequestHoliday', $data);
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
                $this->view('clerk/v_clerkRequestHoliday', $data);
            }
        }
    }

    public function holidayDetails($recordId = null)
    {
        if (!$recordId) {
            redirect('clerk/requestHoliday');
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
                redirect('clerk/requestHoliday');
            }

            $data = [
                'record' => $holidayDetails
            ];

            $this->view('clerk/v_requestDetails', $data);
        }
    }

    public function tasks()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            if (!$employee) {
                flash('error_msg', 'Employee not found');
                redirect('users/login');
            }

            $data = [
                'employee' => $employee,
                'projectTasks' => $this->tasksModel->getProjectTasks($employee->employee_id, $limit, $offset),
                'totalTasks' => $this->tasksModel->getTotalProjectTasks($employee->employee_id),
                'currentPage' => $page,
                'totalPages' => ceil($this->tasksModel->getTotalProjectTasks($employee->employee_id) / $limit),
                'id' => '',
                'start_date' => '',
                'end_date' => '',
                'title' => '',
                'description' => '',
                'project_id' => '',
                'status' => '',
                'comment' => '',
                'id_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'title_err' => '',
                'description_err' => '',
                'project_id_err' => '',
                'status_err' => '',
                'comment_err' => ''
            ];

            $data['currentPage'] = $page;
            $this->view('clerk/v_clerkTasks', $data);
        } else {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

            // Handle status update only
            $taskId = trim($_POST['id']);
            $status = isset($_POST['status']) ? trim($_POST['status']) : null;

            if ($status !== null) {
                // Handle status update
                if ($this->tasksModel->updateTaskStatus($taskId, $status)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Task status updated successfully'
                    ]);
                    return;
                }
            }

            // If we get here, something went wrong
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update task'
            ]);
            return;
        }
    }

    public function details($taskId = null)
    {
        if (!$taskId) {
            redirect('clerk/tasks');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $taskDetails = $this->tasksModel->getProjectTasksById($taskId);

            // Debug
            error_log("Task Details: " . print_r($taskDetails, true));

            $data = [
                'task' => $taskDetails
            ];

            $this->view('clerk/v_clerkTaskDetails', $data);
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Handle comment update (adding or editing)
            if (isset($_POST['comment'])) {
                $comment = trim($_POST['comment']);

                if (empty($comment)) {
                    echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
                    return;
                }

                if ($this->tasksModel->updateTaskComment($taskId, $comment)) {
                    echo json_encode(['success' => true, 'message' => 'Comment added/updated successfully']);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update comment']);
                    return;
                }
            }

            // Handle comment deletion
            if (isset($_POST['action']) && $_POST['action'] === 'delete_comment') {
                $result = $this->tasksModel->deleteTaskComment($taskId);

                header('Content-Type: application/json');
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
                exit;
            }
        }
    }
}   



?>