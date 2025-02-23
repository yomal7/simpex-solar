<?php

class engineer extends Controller {
    private $engineerModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'engineer') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
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

    public function requestHoliday() {
        //$holidayRecords = $this->engineerModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
//            'holidayRecords' => $holidayRecords
        ];
        $this->view('engineer/v_engineerRequestHoliday', $data);
    }

    public function addRequests() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'employee_id' => $_SESSION['employee_id'],
                'start_date' => $_POST['startDate'],
                'end_date' => $_POST['endDate'],
                'number_of_days' => $_POST['numberOfDays'],
                'reason' => $_POST['reason'],
                'leave_type' => $_POST['leaveType']
            ];

            if ($this->engineerModel->addHolidayRecords($data)) {
                header('Location: ' . URLROOT . '/engineer');
            } else {
                die('Something went wrong');
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