<?php

class DeliveryPerson extends Controller
{
    private $employeeModel;
    private $leavesModel;
    private $tasksModel;
    private $shopModel;
    private $paymentModel;
    private $deliveryPersonModel;
    private $settingsModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'deliveryPerson') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');
        $this->leavesModel = $this->model('M_Leaves');
        $this->tasksModel = $this->model('M_Tasks');
        $this->shopModel = $this->model('M_Shop');
        $this->paymentModel = $this->model('M_Payment');
        $this->deliveryPersonModel = $this->model('M_DeliveryPerson');
        $this->settingsModel = $this->model('M_Settings');
    }

    public function index()
    {
                // Get the delivery person's employee ID from the user ID in session
                $userId = $_SESSION['user_id'];

                // Add a method to fetch employee_id from user_id
                $employee = $this->deliveryPersonModel->getEmployeeByUserId($userId);
                if (!$employee) {
                    flash('order_message', 'Employee profile not found', 'alert alert-danger');
                    redirect('deliveryPerson/dashboard');
                }
        
                $employeeId = $employee->employee_id;
        
                // Get pending and completed orders for this delivery person
                $pendingOrders = $this->shopModel->getDeliveryPersonPendingOrders($employeeId);
                $completedOrders = $this->shopModel->getDeliveryPersonCompletedOrders($employeeId);
        
                $data = [
                    'pendingOrders' => $pendingOrders,
                    'completedOrders' => $completedOrders
                ];
        
                $this->view('deliveryPerson/v_deliveryPersonOrders', $data);
    }

    public function dashboard()
    {

        $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

        if (!$employee) {
            flash('error_msg', 'Employee not found');
            redirect('users/login');
        }

        
        // Fetch tasks for the delivery person
        $tasks = $this->tasksModel->getTotalProjectTasksById($employee->employee_id);

        // Get task counts by status
        $inProgressCount = $this->tasksModel->getTaskCountByStatusForEmployee('in_progress', $employee->employee_id);
        $notStartedCount = $this->tasksModel->getTaskCountByStatusForEmployee('not_started', $employee->employee_id);
        $completedCount = $this->tasksModel->getTaskCountByStatusForEmployee('completed', $employee->employee_id);

        // Get order counts by status
        $pendingCount = $this->deliveryPersonModel->getOrderCountByStatusForEmployee('pending', $employee->employee_id);
        $deliveredCount = $this->deliveryPersonModel->getOrderCountByStatusForEmployee('delivered', $employee->employee_id);

        // Prepare data for view
        $data = [
            'employee' => $employee,
            'tasks' => $tasks,
            'not_started' => 'not_started',
            'in_progress' => 'in_progress',
            'completed' => 'completed',
            'pending' => 'pending',
            'delivered' => 'delivered',
            'inProgressCount' => $inProgressCount,
            'notStartedCount' => $notStartedCount,
            'completedCount' => $completedCount,
            'pendingCount' => $pendingCount,
            'deliveredCount' => $deliveredCount
        ];

        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);

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

            $this->view('deliveryPerson/v_deliveryPersonRequestHoliday', $data);
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
                $this->view('deliveryPerson/v_deliveryPersonRequestHoliday', $data);
            }
        }
    }

    public function holidayDetails($recordId = null)
    {
        if (!$recordId) {
            redirect('deliveryPerson/requestHoliday');
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
                redirect('deliveryPerson/requestHoliday');
            }

            $data = [
                'record' => $holidayDetails
            ];

            $this->view('deliveryPerson/v_requestDetails', $data);
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
            $this->view('deliveryPerson/v_deliveryPersonTasks', $data);
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
            redirect('deliveryPerson/tasks');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $taskDetails = $this->tasksModel->getProjectTasksById($taskId);

            // Debug
            error_log("Task Details: " . print_r($taskDetails, true));

            $data = [
                'task' => $taskDetails
            ];

            $this->view('deliveryPerson/v_deliveryPersonTaskDetails', $data);
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
                        redirect('deliveryPerson/settings');
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
                        redirect('deliveryPerson                                                                    /settings');
                    } else {
                        die('Something went wrong');
                    }
                }
            }
        }
        
        $this->view('deliveryPerson/v_settings', $data);
    }



    public function orders()
    {
        // Get the delivery person's employee ID from the user ID in session
        $userId = $_SESSION['user_id'];

        // Add a method to fetch employee_id from user_id
        $employee = $this->deliveryPersonModel->getEmployeeByUserId($userId);
        if (!$employee) {
            flash('order_message', 'Employee profile not found', 'alert alert-danger');
            redirect('deliveryPerson/dashboard');
        }

        $employeeId = $employee->employee_id;

        // Get pending and completed orders for this delivery person
        $pendingOrders = $this->shopModel->getDeliveryPersonPendingOrders($employeeId);
        $completedOrders = $this->shopModel->getDeliveryPersonCompletedOrders($employeeId);

        $data = [
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders
        ];

        $this->view('deliveryPerson/v_deliveryPersonOrders', $data);
    }

    public function viewOrder($orderId)
    {
        // Get the employee ID correctly from the user ID
        $userId = $_SESSION['user_id'];
        $employee = $this->deliveryPersonModel->getEmployeeByUserId($userId);

        if (!$employee) {
            flash('order_message', 'Employee profile not found', 'alert alert-danger');
            redirect('deliveryPerson/orders');
        }

        $employeeId = $employee->employee_id;
        $order = $this->shopModel->getOrderById($orderId);

        $payment = $this->shopModel->getOrderPayment($orderId);

        if (!$order || $order->deliver_id != $employeeId) {
            flash('order_message', 'Unauthorized access to order', 'alert alert-danger');
            redirect('deliveryPerson/orders');
        }

        $orderItems = $this->shopModel->getOrderItems($orderId);

        $data = [
            'order' => $order,
            'orderItems' => $orderItems,
            'payment' => $payment
        ];

        $this->view('deliveryPerson/v_deliveryPersonViewOrder', $data);
    }

    // Generate delivery Report
    public function generateDeliveryReport($orderId)
    {
        // Verify that this order belongs to the logged-in delivery person
        $userId = $_SESSION['user_id'];
        $employee = $this->deliveryPersonModel->getEmployeeByUserId($userId);

        if (!$employee) {
            flash('delivery_message', 'Employee profile not found', 'alert alert-danger');
            redirect('deliveryPerson/orders');
            return;
        }

        $employeeId = $employee->employee_id;
        $order = $this->shopModel->getOrderById($orderId);

        if (!$order || $order->deliver_id != $employeeId) {
            flash('delivery_message', 'Unauthorized access to order', 'alert alert-danger');
            redirect('deliveryPerson/orders');
            return;
        }

        $orderItems = $this->shopModel->getOrderItems($orderId);

        // Generate the PDF
        $pdfGenerator = new PdfGenerator();
        $pdfData = [
            'order' => $order,
            'orderItems' => $orderItems,
            'deliveryPerson' => $employee->name ?? 'Delivery Person'
        ];

        $pdfContent = $pdfGenerator->generateDeliveryReport($pdfData);

        // Output the PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="delivery_report_' . $order->order_number . '.pdf"');
        echo $pdfContent;
    }
    public function confirmDelivery()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('deliveryPerson/orders');
            return;
        }

        // Verify that this order belongs to the logged-in delivery person
        $userId = $_SESSION['user_id'];
        $employee = $this->deliveryPersonModel->getEmployeeByUserId($userId);

        if (!$employee) {
            flash('delivery_message', 'Employee profile not found', 'alert alert-danger');
            redirect('deliveryPerson/orders');
            return;
        }

        $employeeId = $employee->employee_id;
        $orderId = $_POST['order_id'];
        $order = $this->shopModel->getOrderById($orderId);

        if (!$order || $order->deliver_id != $employeeId) {
            flash('delivery_message', 'Unauthorized access to order', 'alert alert-danger');
            redirect('deliveryPerson/orders');
            return;
        }

        // For cash payments, check if payment received checkbox is ticked
        if ($order->payment_method == 'cash' && !isset($_POST['payment_received'])) {
            flash('delivery_message', 'You must confirm receiving the cash payment', 'alert alert-danger');
            redirect('deliveryPerson/viewOrder/' . $orderId);
            return;
        }

        // Handle delivery report file upload
        $deliveryReport = null;
        if (isset($_FILES['delivery_report']) && $_FILES['delivery_report']['error'] == 0) {
            $fileInfo = pathinfo($_FILES['delivery_report']['name']);
            $fileExt = strtolower($fileInfo['extension']);

            // Validate file type
            // $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            // if (!in_array($fileExt, $allowedExtensions)) {
            //     flash('delivery_message', 'Invalid file format. Only PDF files are allowed', 'alert alert-danger');
            //     redirect('deliveryPerson/viewOrder/' . $orderId);
            //     return;
            // }
            $allowedExtensions = ['pdf'];
            if (!in_array($fileExt, $allowedExtensions)) {
                flash('delivery_message', 'Invalid file format. Only PDF files are allowed', 'alert alert-danger');
                redirect('deliveryPerson/viewOrder/' . $orderId);
                return;
            }

            // Only allow uploads less that 5MB
            if ($_FILES['delivery_report']['size'] > 5 * 1024 * 1024) { // 5MB in bytes
                flash('delivery_message', 'File size exceeds the 5MB limit', 'alert alert-danger');
                redirect('deliveryPerson/viewOrder/' . $orderId);
                return;
            }


            // Generate a unique filename
            $deliveryReport = 'delivery_report_' . $order->order_number . '_' . time() . '.' . $fileExt;

            // Create uploads directory if it doesn't exist
            $uploadDir = APPROOT . '/../public/uploads/delivery_reports/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move the uploaded file
            if (!move_uploaded_file($_FILES['delivery_report']['tmp_name'], $uploadDir . $deliveryReport)) {
                flash('delivery_message', 'Failed to upload delivery report', 'alert alert-danger');
                redirect('deliveryPerson/viewOrder/' . $orderId);
                return;
            }
        } else {
            flash('delivery_message', 'Please upload the signed delivery report', 'alert alert-danger');
            redirect('deliveryPerson/viewOrder/' . $orderId);
            return;
        }

        // Mark order as delivered and update payment status if cash payment
        if ($this->shopModel->markOrderAsDelivered($orderId, $deliveryReport)) {
            if ($order->payment_method == 'cash') {
                // Update payment status to approved for cash payments
                $payment = $this->shopModel->getOrderPayment($orderId);
                if ($payment) {
                    $this->paymentModel->approvePayment($payment->id);
                }
            }

            flash('delivery_message', 'Order marked as delivered successfully', 'alert alert-success');
        } else {
            flash('delivery_message', 'Failed to mark order as delivered', 'alert alert-danger');
        }

        redirect('deliveryPerson/orders');
    }
}
