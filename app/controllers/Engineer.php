<?php

class engineer extends Controller {
    private $engineerModel;
    private $employeeModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'engineer') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');
        $this->engineerModel = $this->model('M_Engineer');
    }


    public function index() {
        //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('engineer/v_engineerDashboard', $data);
    }

    public function dashboard() {
        //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
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
                'holidayRecords' => $this->engineerModel->getHolidayRecords($employee->employee_id, $limit, $offset),
                'totalRecords' => $this->engineerModel->getTotalHolidayRecords($employee->employee_id),
                'currentPage' => $page,
                'totalPages' => ceil($this->engineerModel->getTotalHolidayRecords($employee->employee_id) / $limit),
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
                'holidayRecords' => $this->engineerModel->getHolidayRecords($employee->employee_id),
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

                if ($this->engineerModel->addHolidayRecords($holidayData)) {
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

    // public function tasks() {
    //     //$engineer = $this->engineerModel->getEngineerByUserId($_SESSION['employee_id']);
    //     $data = [];
    //     $this->view('engineer/v_engineerTasks', $data);
    // }

    public function settings() {
        $data = [];
        $this->view('engineer/v_engineerSettings', $data);  
    }
}   



?>