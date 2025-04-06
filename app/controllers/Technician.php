<?php

class technician extends Controller
{
    private $technicianModel;
    private $employeeModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');
        $this->technicianModel = $this->model('M_Technician');
    }

    public function index()
    {
        //$technician = $this->technicianModel->getTechnicianByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('technician/v_technicianDashboard', $data);
    }

    public function dashboard()
    {
        $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

    if (!$employee) {
        flash('error_msg', 'Employee not found');
        redirect('users/login');
    }

    // Fetch tasks for the technician
    $tasks = $this->technicianModel->getTotalProjectTasksById($employee->employee_id);

    // Prepare data for view
    $data = [
        'employee' => $employee,
        'tasks' => $tasks
    ];

    $this->view('technician/v_technicianDashboard', $data);
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
                'holidayRecords' => $this->technicianModel->getHolidayRecords($employee->employee_id, $limit, $offset),
                'totalRecords' => $this->technicianModel->getTotalHolidayRecords($employee->employee_id),
                'currentPage' => $page,
                'totalPages' => ceil($this->technicianModel->getTotalHolidayRecords($employee->employee_id) / $limit),
                'start_date' => '',
                'end_date' => '',
                'number_of_days' => '',
                'reason' => '',
                'comment' => '',
                'leave_type' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'days_err' => '',
                'reason_err' => '',
                'leave_type_err' => '',
                'comment_err' => ''
            ];

            $this->view('technician/v_technicianRequestHoliday', $data);
        } else {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

            $data = [
                'employee' => $employee,
                'holidayRecords' => $this->technicianModel->getHolidayRecords($employee->employee_id),
                'employee_id' => $employee->employee_id,
                'start_date' => trim($_POST['startDate']),
                'end_date' => trim($_POST['endDate']),
                'number_of_days' => trim($_POST['numberOfDays']),
                'reason' => trim($_POST['reason']),
                'leave_type' => trim($_POST['leaveType']),
                'status' => 'pending',
                'comment' => '',
                // Initialize error fields
                'start_date_err' => '',
                'end_date_err' => '',
                'days_err' => '',
                'reason_err' => '',
                'leave_type_err' => '',
                'comment_err' => ''
            ];

            // Validation
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please select start date';
            }

            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please select end date';
            }

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

                if ($this->technicianModel->addHolidayRecords($holidayData)) {
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
                $this->view('technician/v_technicianRequestHoliday', $data);
            }
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
            'projectTasks' => $this->technicianModel->getProjectTasks($employee->employee_id, $limit, $offset),
            'totalTasks' => $this->technicianModel->getTotalProjectTasks($employee->employee_id),
            'currentPage' => $page,
            'totalPages' => ceil($this->technicianModel->getTotalProjectTasks($employee->employee_id) / $limit),
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
        $this->view('technician/v_technicianTasks', $data);
    } else {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);

        // Handle status update only
        $taskId = trim($_POST['id']);
        $status = isset($_POST['status']) ? trim($_POST['status']) : null;

        if ($status !== null) {
            // Handle status update
            if ($this->technicianModel->updateTaskStatus($taskId, $status)) {
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

public function details($taskId = null) {
    if (!$taskId) {
        redirect('technician/tasks');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $taskDetails = $this->technicianModel->getProjectTasksById($taskId);

        // Debug
        error_log("Task Details: " . print_r($taskDetails, true));

        $data = [
            'task' => $taskDetails
        ];

        $this->view('technician/v_technicianTaskDetails', $data);

    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Handle comment update (adding or editing)
        if (isset($_POST['comment'])) {
            $comment = trim($_POST['comment']);

            if (empty($comment)) {
                echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
                return;
            }

            if ($this->technicianModel->updateTaskComment($taskId, $comment)) {
                echo json_encode(['success' => true, 'message' => 'Comment added/updated successfully']);
                return;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update comment']);
                return;
            }
        }

        // Handle comment deletion
        if (isset($_POST['action']) && $_POST['action'] === 'delete_comment') {
            $result = $this->technicianModel->deleteTaskComment($taskId);

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
        $data = [];
        $this->view('technician/v_technicianSettings', $data);
    }
}
