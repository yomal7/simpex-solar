<?php
class Clerk extends Controller {
    private $attendanceModel;
    private $employeeModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'clerk') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->attendanceModel = $this->model('M_Attendance');
        $this->employeeModel = $this->model('M_Employee');
    }

    public function index() {
        $data = [];
        $this->view('clerk/v_dashboard', $data);
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

    public function tasks() {
        $data = [];
        $this->view('clerk/v_tasks', $data);
    }

    public function requestHoliday() {
        $data = [
//            'holidayRecords' => $holidayRecords
        ];
        $this->view('clerk/v_requestHoliday', $data);
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

            if ($this->technicianModel->addHolidayRecords($data)) {
                header('Location: ' . URLROOT . '/Technician');
            } else {
                die('Something went wrong');
            }
        }
    }

    public function settings() {
        $data = [];
        $this->view('clerk/v_technicianSettings', $data);  
    }
}   



?>