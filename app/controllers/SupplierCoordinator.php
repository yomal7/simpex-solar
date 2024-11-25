<?php

class SupplierCoordinator extends Controller {

    private $supplierModel;

    public function __construct() {
        $this->supplierModel = $this->model('M_Suppliers');
    }

    public function index() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function dashboard() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function addSupplier() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'address' => trim($_POST['address']),
                'email' => trim($_POST['email']),
                'contact_number' => trim($_POST['contact_number']),
                'other_details' => trim($_POST['other_details']),
                'name_err' => '',
                'address_err' => '',
                'email_err' => '',
                'contact_number_err' => ''
            ];

            // Validate name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }

            // Validate address
            if(empty($data['address'])) {
                $data['address_err'] = 'Please enter address';
            }

            // Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            } elseif($this->supplierModel->findSupplierByEmail($data['email'])) {
                $data['email_err'] = 'Email is already registered';
            }

            // Validate contact number
            if(empty($data['contact_number'])) {
                $data['contact_number_err'] = 'Please enter contact number';
            } elseif(!preg_match('/^[0-9]{10,15}$/', $data['contact_number'])) {
                $data['contact_number_err'] = 'Please enter a valid contact number';
            }

            // Make sure errors are empty
            if(empty($data['name_err']) && empty($data['address_err']) && 
               empty($data['email_err']) && empty($data['contact_number_err'])) {
                // Validated
                if($this->supplierModel->addSupplier($data)) {
                    flash('supplier_message', 'Supplier Added Successfully');
                    redirect('supplierCoordinator/suppliers');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('supplierCoordinator/v_addSupplier', $data);
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'address' => '',
                'email' => '',
                'contact_number' => '',
                'other_details' => '',
                'name_err' => '',
                'address_err' => '',
                'email_err' => '',
                'contact_number_err' => ''
            ];

            // Load view
            $this->view('supplierCoordinator/v_addSupplier', $data);
        }
    }


    public function manageAproject() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsManager/v_manageAproject', $data);
    }

}
?>