<?php

class SupplierCoordinator extends Controller
{

    private $supplierModel;
    private $inventoryModel;


    public function __construct()
    {
        $this->supplierModel = $this->model('M_Suppliers');
        $this->inventoryModel = $this->model('M_Inventory');
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
        $this->view('operationsManager/v_manageAproject', $data);
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

    public function updateSupplier()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'address' => trim($_POST['address']),
                'email' => trim($_POST['email']),
                'contact_number' => trim($_POST['contact_number']),
                'other_details' => trim($_POST['other_details'])
            ];

            if ($this->supplierModel->updateSupplier($data)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
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

    public function inventory()
    {
        $products = $this->inventoryModel->getAllItems();
        $data = [
            'title' => 'Manage Inventory',
            'products' => $products
        ];
        $this->view('supplierCoordinator/v_inventory', $data);
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'supplier_id' => trim($_POST['supplier']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'blog_link' => trim($_POST['blog_link']),
                'image_path' => $_FILES['image_path']['name'],
                'name_err' => '',
                'supplier_err' => '',
                'description_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'blog_link_err' => '',
                'image_path_err' => '',
                'suppliers' => $this->supplierModel->getSuppliers()
            ];

            // Validate inputs
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter the product name.';
            }

            if (empty($data['supplier_id'])) {
                $data['supplier_err'] = 'Please select a supplier.';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description.';
            }

            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter the price.';
            } elseif (!is_numeric($data['price'])) {
                $data['price_err'] = 'Price must be a number.';
            }

            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter the quantity.';
            } elseif (!is_numeric($data['quantity'])) {
                $data['quantity_err'] = 'Quantity must be a number.';
            }

            if (empty($data['blog_link'])) {
                $data['blog_link_err'] = 'Please enter the blog link.';
            } elseif (!filter_var($data['blog_link'], FILTER_VALIDATE_URL)) {
                $data['blog_link_err'] = 'Please enter a valid URL.';
            }

            if (empty($data['image_path'])) {
                $data['image_path_err'] = 'Please upload an image.';
            }

            // Check if there are no errors
            if (
                empty($data['name_err']) && empty($data['supplier_err']) && empty($data['description_err']) &&
                empty($data['price_err']) && empty($data['quantity_err']) &&
                empty($data['blog_link_err']) && empty($data['image_path_err'])
            ) {

                // Handle image upload
                $targetDir = "uploads/images/"; // specify the directory for storing images
                $targetFile = $targetDir . basename($data['image_path']);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if the file is an image
                if (getimagesize($_FILES['image_path']['tmp_name']) === false) {
                    $data['image_path_err'] = "File is not an image.";
                }

                // Check file size (5MB max)
                if ($_FILES['image_path']['size'] > 5000000) {
                    $data['image_path_err'] = "File is too large.";
                }

                // Allow certain file formats
                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $data['image_path_err'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
                }

                // If no errors in image upload, move file to the target directory
                if (empty($data['image_path_err']) && move_uploaded_file($_FILES['image_path']['tmp_name'], $targetFile)) {
                    // Add product to the database
                    if ($this->inventoryModel->createItem($data)) {
                        flash('product_message', 'Product Added Successfully');
                        redirect('supplierCoordinator/inventory');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // Display image upload error
                    $this->view('supplierCoordinator/v_addProducts', $data);
                }
            } else {
                // Load view with errors
                $this->view('supplierCoordinator/v_addProducts', $data);
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'supplier_id' => '',
                'description' => '',
                'price' => '',
                'quantity' => '',
                'status' => '',
                'blog_link' => '',
                'image_path' => '',
                'suppliers' => $this->supplierModel->getSuppliers(),
                'name_err' => '',
                'supplier_err' => '',
                'description_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'status_err' => '',
                'blog_link_err' => '',
                'image_path_err' => ''
            ];

            // Load view
            $this->view('supplierCoordinator/v_addProducts', $data);
        }
    }
}
