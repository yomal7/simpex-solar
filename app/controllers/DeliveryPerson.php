<?php

class DeliveryPerson extends Controller {
    private $deliveryPersonModel;
    private $employeeModel;

    public function __construct() {
        
        $_SESSION['user_id'] = 1; // Replace 1 with a valid user_id for testing
        $_SESSION['role'] = 'deliveryPerson';

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'deliveryPerson') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->employeeModel = $this->model('M_Employee');
        $this->deliveryPersonModel = $this->model('M_DeliveryPerson');
    }

    public function index() {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);
    }

    public function dashboard() {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);
    }

    public function requestHoliday() {

        //$_SESSION['user_id'] = 1; // Replace 1 with a valid user_id for testing
        //$_SESSION['role'] = 'deliveryPerson';

        // $holidayRecords = $this->deliveryPersonModel->getHolidayRecords($_SESSION['user_id']);
        // $data = [
        //     'holidayRecords' => $holidayRecords
        // ];
        // $this->view('deliveryPerson/v_deliveryPersonRequestHoliday', $data);

        $employee = $this->employeeModel->getEmployeeIdByUserId($_SESSION['user_id']);
        
        if ($employee) {
            // Fetch the holiday records for the employee
            $holidayRecords = $this->deliveryPersonModel->getHolidayRecords($employee->employee_id);

            $data = [
                'holidayRecords' => $holidayRecords
            ];

            $this->view('deliveryPerson/v_deliveryPersonRequestHoliday', $data);
        } else {
            flash('error_msg', 'Employee not found');
            redirect('users/login');
        }


    }

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

    //         if ($this->deliveryPersonModel->addHolidayRecords($data)) {
    //             header('Location: ' . URLROOT . '/DeliveryPerson');
    //         } else {
    //             die('Something went wrong');
    //         }
    //     }
    // }

    public function addRequests() {
        

        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Fetch the employee ID
        $employee = $this->employeeModel->getEmployeeByUserId($_SESSION['user_id']);



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'start_date' => trim($_POST['startDate']),
                'end_date' => trim($_POST['endDate']),
                'number_of_days' => trim($_POST['numberOfDays']),
                'reason' => trim($_POST['reason']),
                'leave_type' => trim($_POST['leaveType']),
                'employee_id' => $_SESSION['employee_id'],
                'start_date_err' => '',
                'end_date_err' => '',
                'number_of_days_err' => '',
                'reason_err' => '',
                'leave_type_err' => ''
                
            ];

            // Validate inputs
            $this->validateRequestData($data);

            // Check if there are no errors
            if (
                empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['number_of_days_err']) && empty($data['reason_err']) && empty($data['leave_type_err'])
            ) {
                // Add holiday request
                if ($this->deliveryPersonModel->addHolidayRecords($data)) {
                    // Redirect to the list of holiday requests with a success message
                    flash('request_success', 'Holiday request added successfully');
                    redirect('holidayRequests/index');
                } else {
                    die('Something went wrong while adding the request');
                }
            } else {
                // Load view with errors
                $this->view('delveryPerson/v_deliveryPersonRequestHoliday', $data);
            }
        } else {
            // Init data for GET request
            $data = [
                'start_date' => '',
                'end_date' => '',
                'numuber_of_days' => '',
                'reason' => '',
                'leave_type' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'number_of_days_err' => '',
                'reason_err' => '',
                'leave_type_err' => ''
            ];

            // Load view
            $this->view('deliveryPerson/v_deliveryPersonRequestHoliday', $data);
        }
    }

    //private function validateRequestData(&$data)
    //{
        // if (empty($data['name'])) {
        //     $data['name_err'] = 'Please enter the product name.';
        // }

        // if (empty($data['supplier_id'])) {
        //     $data['supplier_err'] = 'Please select a supplier.';
        // }

        // if (empty($data['description'])) {
        //     $data['description_err'] = 'Please enter a description.';
        // }

        // if (empty($data['price'])) {
        //     $data['price_err'] = 'Please enter the price.';
        // } elseif (!is_numeric($data['price'])) {
        //     $data['price_err'] = 'Price must be a number.';
        // }

        // if (empty($data['quantity'])) {
        //     $data['quantity_err'] = 'Please enter the quantity.';
        // } elseif (!is_numeric($data['quantity'])) {
        //     $data['quantity_err'] = 'Quantity must be a number.';
        // }

        // if (empty($data['blog_link'])) {
        //     $data['blog_link_err'] = 'Please enter the blog link.';
        // } elseif (!filter_var($data['blog_link'], FILTER_VALIDATE_URL)) {
        //     $data['blog_link_err'] = 'Please enter a valid URL.';
        // }

    //}

    private function validateRequestData(&$data) {
        // Validate start date
        if (empty($data['start_date'])) {
            $data['start_date_err'] = 'Start date is required';
        }
        // Validate end date
        if (empty($data['end_date'])) {
            $data['end_date_err'] = 'End date is required';
        }
        // Validate number of days
        if (empty($data['number_of_days']) || !is_numeric($data['number_of_days'])) {
            $data['number_of_days_err'] = 'Number of days must be a valid number';
        }
        // Validate reason
        if (empty($data['reason'])) {
            $data['reason_err'] = 'Reason is required';
        }
        // Validate leave type
        if (empty($data['leave_type'])) {
            $data['leave_type_err'] = 'Leave type is required';
        }
    }
    

    public function tasks() {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonTasks', $data);
    }

    public function settings() {
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonSettings', $data);  
    }
}   



?>