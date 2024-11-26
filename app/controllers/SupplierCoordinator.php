<?php

class SupplierCoordinator extends Controller
{

    private $supplierModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplierCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->supplierModel = $this->model('M_Suppliers');
    }

    public function index()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function dashboard()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function addSupplier()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }

            // Validate address
            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter address';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            } elseif ($this->supplierModel->findSupplierByEmail($data['email'])) {
                $data['email_err'] = 'Email is already registered';
            }

            // Validate contact number
            if (empty($data['contact_number'])) {
                $data['contact_number_err'] = 'Please enter contact number';
            } elseif (!preg_match('/^[0-9]{10,15}$/', $data['contact_number'])) {
                $data['contact_number_err'] = 'Please enter a valid contact number';
            }

            // Make sure errors are empty
            if (
                empty($data['name_err']) && empty($data['address_err']) &&
                empty($data['email_err']) && empty($data['contact_number_err'])
            ) {
                // Validated
                if ($this->supplierModel->addSupplier($data)) {
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

    public function manageAproject()
    {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_manageAproject', $data);
    }

    public function suppliers()
    {
        $suppliers = $this->supplierModel->getSuppliers();
        $data = [
            'title' => 'Manage Suppliers',
            'suppliers' => $suppliers
        ];
        $this->view('supplierCoordinator/v_suppliers', $data);
    }


    public function getSupplierDetails()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $supplier = $this->supplierModel->getSupplierById($_POST['id']);
            if ($supplier) {
                echo json_encode($supplier);
            } else {
                echo json_encode(['error' => 'Supplier not found']);
            }
        }
    }

    public function edit($id = null)
    {
        if ($id === null) {
            flash('error_msg', 'No supplier specified');
            redirect('suppliers');
        }

        // Handle GET request to show edit form
        $supplier = $this->supplierModel->getSupplierById($id);

        if (!$supplier) {
            flash('supplier_message', 'Supplier not found', 'alert alert-danger');
            redirect('suppliers');
        }

        $data = [
            'id' => $id,
            'name' => $supplier->name,
            'address' => $supplier->address,
            'email' => $supplier->email,
            'contact_number' => $supplier->contact_number,
            'other_details' => $supplier->other_details,
            'name_err' => '',
            'address_err' => '',
            'email_err' => '',
            'contact_number_err' => '',
            'other_details_err' => ''
        ];

        $this->view('supplierCoordinator/v_supplierEdit', $data);
    }

    public function update()
    {
        // Handle POST request for updating supplier
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'address' => trim($_POST['address']),
                'email' => trim($_POST['email']),
                'contact_number' => trim($_POST['contact_number']),
                'other_details' => trim($_POST['other_details']),
                'name_err' => '',
                'address_err' => '',
                'email_err' => '',
                'contact_number_err' => '',
                'other_details_err' => ''
            ];

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }

            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter address';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }

            if (empty($data['contact_number'])) {
                $data['contact_number_err'] = 'Please enter contact number';
            }

            // Check for any validation errors
            if (
                empty($data['name_err']) && empty($data['address_err']) &&
                empty($data['email_err']) && empty($data['contact_number_err'])
            ) {
                // Attempt to update supplier
                if ($this->supplierModel->updateSupplier($data)) {
                    flash('supplier_message', 'Supplier updated successfully');
                    redirect('suppliercoordinator/suppliers');
                } else {
                    flash('supplier_message', 'Error updating supplier', 'alert alert-danger');
                    // If update fails, re-render the edit form with current data
                    $this->view('supplierCoordinator/v_supplierEdit', $data);
                }
            } else {
                // If validation fails, re-render the edit form with error messages
                $this->view('supplierCoordinator/v_supplierEdit', $data);
            }
        } else {
            // If not a POST request, redirect to suppliers list
            redirect('suppliers');
        }
    }
    public function deleteSupplier()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            if ($this->supplierModel->deleteSupplier($_POST['id'])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }
}
