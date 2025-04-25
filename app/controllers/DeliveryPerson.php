<?php

class DeliveryPerson extends Controller
{
    private $employeeModel;
    private $leavesModel;
    private $tasksModel;
    private $shopModel;
    private $paymentModel;
    private $deliveryPersonModel;

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
    }

    public function index()
    {
        //$deliveryPerson = $this->leavesModel->getDeliveryPersonByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);
    }

    public function dashboard()
    {
        //$deliveryPerson = $this->leavesModel->getDeliveryPersonByUserId($_SESSION['user_id']);
        $data = [];
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
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonTasks', $data);
    }

    public function settings()
    {
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonSettings', $data);
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
