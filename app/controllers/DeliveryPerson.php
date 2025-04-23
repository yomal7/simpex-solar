<?php

class DeliveryPerson extends Controller
{
    private $deliveryPersonModel;
    private $shopModel;
    private $paymentModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'deliveryPerson') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->deliveryPersonModel = $this->model('M_DeliveryPerson');
        $this->shopModel = $this->model('M_Shop');
        $this->paymentModel = $this->model('M_Payment');
    }

    public function index()
    {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);
    }

    public function dashboard()
    {
        //$deliveryPerson = $this->deliveryPersonModel->getDeliveryPersonByUserId($_SESSION['employee_id']);
        $data = [];
        $this->view('deliveryPerson/v_deliveryPersonDashboard', $data);
    }

    public function requestHoliday()
    {
        //$holidayRecords = $this->deliveryPersonModel->getHolidayRecords($_SESSION['employee_id']);
        $data = [
            //            'holidayRecords' => $holidayRecords
        ];
        $this->view('deliveryPerson/v_deliveryPersonRequestHoliday', $data);
    }

    public function addRequests()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'employee_id' => $_SESSION['employee_id'],
                'start_date' => $_POST['startDate'],
                'end_date' => $_POST['endDate'],
                'number_of_days' => $_POST['numberOfDays'],
                'reason' => $_POST['reason'],
                'leave_type' => $_POST['leaveType']
            ];

            if ($this->deliveryPersonModel->addHolidayRecords($data)) {
                header('Location: ' . URLROOT . '/DeliveryPerson');
            } else {
                die('Something went wrong');
            }
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

        if (!$order || $order->deliver_id != $employeeId) {
            flash('order_message', 'Unauthorized access to order', 'alert alert-danger');
            redirect('deliveryPerson/orders');
        }

        $orderItems = $this->shopModel->getOrderItems($orderId);

        $data = [
            'order' => $order,
            'orderItems' => $orderItems
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
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            if (!in_array($fileExt, $allowedExtensions)) {
                flash('delivery_message', 'Invalid file format. Only PDF, JPG, and PNG files are allowed', 'alert alert-danger');
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
