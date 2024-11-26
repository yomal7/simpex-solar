<?php

class SupplierCoordinator extends Controller
{

    private $supplierModel;
    private $inventoryModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplierCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
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
            $this->validateProductData($data);

            // Check if there are no errors
            if (
                empty($data['name_err']) && empty($data['supplier_err']) && empty($data['description_err']) &&
                empty($data['price_err']) && empty($data['quantity_err']) &&
                empty($data['blog_link_err']) && empty($data['image_path_err'])
            ) {
                // Handle image upload
                $targetDir = "uploads/images/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
                }

                // Sanitize and create a unique filename to prevent overwriting
                $uniqueFileName = uniqid() . '_' . basename($data['image_path']);
                $targetFile = $targetDir . $uniqueFileName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Perform image upload validation
                if (!$this->validateImageUpload($targetFile, $imageFileType, $_FILES['image_path']['tmp_name'], $_FILES['image_path']['size'], $data)) {
                    $this->view('supplierCoordinator/v_addProducts', $data);
                    return;
                }

                // Move uploaded file
                if (move_uploaded_file($_FILES['image_path']['tmp_name'], $targetFile)) {
                    $data['image_path'] = $uniqueFileName;

                    // Add product to the database
                    if ($this->inventoryModel->createItem($data)) {
                        flash('product_message', 'Product Added Successfully');
                        redirect('supplierCoordinator/inventory');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    $data['image_path_err'] = 'Failed to move the uploaded file.';
                    $this->view('supplierCoordinator/v_addProducts', $data);
                }
            } else {
                // Load view with errors
                $this->view('supplierCoordinator/v_addProducts', $data);
            }
        } else {
            // Init data for GET request
            $data = [
                'name' => '',
                'supplier_id' => '',
                'description' => '',
                'price' => '',
                'quantity' => '',
                'blog_link' => '',
                'image_path' => '',
                'suppliers' => $this->supplierModel->getSuppliers(),
                'name_err' => '',
                'supplier_err' => '',
                'description_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'blog_link_err' => '',
                'image_path_err' => ''
            ];

            // Load view
            $this->view('supplierCoordinator/v_addProducts', $data);
        }
    }

    /**
     * Validate product data.
     */
    private function validateProductData(&$data)
    {
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
    }

    /**
     * Validate image upload.
     */
    private function validateImageUpload($targetFile, $imageFileType, $tmpFile, $fileSize, &$data)
    {
        // Check if the file is an image
        if (getimagesize($tmpFile) === false) {
            $data['image_path_err'] = 'File is not an image.';
            return false;
        }

        // Check file size (5MB max)
        if ($fileSize > 5000000) {
            $data['image_path_err'] = 'File is too large.';
            return false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $data['image_path_err'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
            return false;
        }

        return true;
    }

    public function editInventory($id = null)
    {
        if ($id === null) {
            flash('error_msg', 'No product specified');
            redirect('inventory');
        }

        // Handle GET request to show edit form
        $product = $this->inventoryModel->getItemById($id);

        if (!$product) {
            flash('inventory_message', 'Product not found', 'alert alert-danger');
            redirect('Inventory');
        }

        $data = [
            'id' => $id,
            'name' => $product->item_name, // Using item_name from the query
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'supplier_id' => $product->supplier_id,
            'blog_link' => $product->blog_link,
            'image_path' => $product->image,
            'supplier_name' => $product->supplier_name,
            'suppliers' => $this->supplierModel->getSuppliers(),
            'price_err' => '',
            'quantity_err' => '',
            'description_err' => '',
            'blog_link_err' => '',
            'supplier_err' => ''
        ];

        $this->view('supplierCoordinator/v_inventoryEdit', $data);
    }

    public function updateInventory()
    {
        // Handle POST request for updating inventory
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $_POST['id'],
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'description' => trim($_POST['description']),
                'supplier_id' => trim($_POST['supplier']),
                'blog_link' => trim($_POST['blog_link']),
                'price_err' => '',
                'quantity_err' => '',
                'description_err' => '',
                'blog_link_err' => '',
                'supplier_err' => ''
            ];

            // Validation
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter the price';
            } elseif (!is_numeric($data['price'])) {
                $data['price_err'] = 'Price must be a number';
            }

            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter the quantity';
            } elseif (!is_numeric($data['quantity'])) {
                $data['quantity_err'] = 'Quantity must be a number';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description';
            }

            if (empty($data['supplier_id'])) {
                $data['supplier_err'] = 'Please select a supplier';
            }

            if (empty($data['blog_link'])) {
                $data['blog_link_err'] = 'Please enter the blog link';
            } elseif (!filter_var($data['blog_link'], FILTER_VALIDATE_URL)) {
                $data['blog_link_err'] = 'Please enter a valid URL';
            }

            // Check for any validation errors
            if (
                empty($data['price_err']) && empty($data['quantity_err']) &&
                empty($data['description_err']) && empty($data['supplier_err']) &&
                empty($data['blog_link_err'])
            ) {
                // Attempt to update inventory item
                if ($this->inventoryModel->updateItem($data['id'], $data)) {
                    flash('product_message', 'Product updated successfully');
                    redirect('supplierCoordinator/inventory');
                } else {
                    flash('product_message', 'Error updating product', 'alert alert-danger');
                    $data['suppliers'] = $this->supplierModel->getSuppliers();
                    $this->view('supplierCoordinator/v_inventoryEdit', $data);
                }
            } else {
                // If validation fails, re-render the edit form with error messages
                $data['suppliers'] = $this->supplierModel->getSuppliers();
                $this->view('supplierCoordinator/v_inventoryEdit', $data);
            }
        } else {
            // If not a POST request, redirect to inventory list
            redirect('supplierCoordinator/inventory');
        }
    }
}
