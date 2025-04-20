<?php

class SupplierCoordinator extends Controller
{

    private $supplierModel;
    private $inventoryModel;
    private $shopModel;
    private $chatModel;
    private $supplierCoordinatorModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supplierCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->supplierModel = $this->model('M_Suppliers');
        $this->inventoryModel = $this->model('M_Inventory');
        $this->shopModel = $this->model('M_Shop');
        $this->chatModel = $this->model('M_Chat');
        $this->supplierCoordinatorModel = $this->model('M_SupplierCoordinator');
    }

    public function index()
    {
        // Get all required order data
        $pendingOrders = $this->shopModel->getPendingOrders();
        $processingOrders = $this->shopModel->getProcessingOrders();
        $activeOrders = $this->shopModel->getActiveOrders();

        $data = [
            'pending_orders' => $pendingOrders,
            'processing_orders' => $processingOrders,
            'active_orders' => $activeOrders,
            'orders' => $pendingOrders, // Default view
            'show_status' => false
        ];

        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function dashboard()
    {
        $pendingOrders = $this->shopModel->getPendingOrders();
        $processingOrders = $this->shopModel->getProcessingOrders();
        $activeOrders = $this->shopModel->getActiveOrders();

        $data = [
            'pending_orders' => $pendingOrders,
            'processing_orders' => $processingOrders,
            'active_orders' => $activeOrders,
            'orders' => $pendingOrders, // Default view
            'show_status' => false
        ];

        $this->view('supplierCoordinator/v_dashboard', $data);
    }

    public function getOrders($type)
    {
        switch ($type) {
            case 'pending':
                $orders = $this->shopModel->getPendingOrders();
                $show_status = false;
                break;
            case 'processing':
                $orders = $this->shopModel->getProcessingOrders();
                $show_status = false;
                break;
            case 'active':
                $orders = $this->shopModel->getActiveOrders();
                $show_status = true;
                break;
        }
        echo json_encode(['orders' => $orders, 'show_status' => $show_status]);
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
        $this->view('supplierCoordinator/v_manageAproject', $data);
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

    // public function deleteItem()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    //         // Validate the ID
    //         $itemId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    //         if ($itemId === false || $itemId === null) {
    //             echo json_encode(['success' => false, 'message' => 'Invalid item ID']);
    //             return;
    //         }

    //         // Attempt soft delete
    //         if ($this->inventoryModel->softDeleteItem($itemId)) {
    //             echo json_encode(['success' => true]);
    //         } else {
    //             echo json_encode(['success' => false, 'message' => 'Failed to delete item']);
    //         }
    //     } else {
    //         // Invalid request method or missing ID
    //         echo json_encode(['success' => false, 'message' => 'Invalid request']);
    //     }
    // }

    public function deleteItem($itemId = null)
    {
        header('Content-Type: application/json'); // Set JSON response

        try {
            // Get ID from POST or raw input
            $itemId = isset($_POST['id']) ?
                filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) :
                json_decode(file_get_contents('php://input'), true)['id'] ?? null;

            if ($itemId === null || $itemId === false) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid item ID']);
                return;
            }

            // Attempt soft delete
            $deleteResult = $this->inventoryModel->softDeleteItem($itemId);

            if ($deleteResult) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete item']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server Error', 'details' => $e->getMessage()]);
        }
    }

    public function viewProductDetails($id)
    {

        $inventory = $this->inventoryModel->getInventoryById($id);

        if ($inventory) {
            $data = [
                'inventory' => $inventory
            ];
            $this->view('supplierCoordinator/v_productDetails', $data);
        } else {
            redirect('supplierCoordinator/inventory');
        }
    }

    public function settings()
    {
        $data = [];
        $this->view('supplierCoordinator/v_setting', $data);
    }

    public function shop()
    {
        $products = $this->shopModel->getProducts();

        $data = [
            'title' => 'Product Management',
            'products' => $products
        ];

        $this->view('supplierCoordinator/v_shop', $data);
    }

    public function viewProduct($id)
    {
        $product = $this->shopModel->getProductById($id);
        $features = $this->shopModel->getProductFeatures($id);

        if (!$product) {
            flash('product_message', 'Product not found', 'alert alert-danger');
            redirect('supplierCoordinator/shop');
        }

        $data = [
            'product' => $product,
            'features' => $features
        ];

        $this->view('supplierCoordinator/v_productDetails', $data);
    }

    public function addShopProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = [
                'suppliers' => $this->supplierModel->getSuppliers(),
                'name' => '',
                'price' => '',
                'description' => '',
                'category' => '',
                'supplier_id' => '',
                'blog_link' => '',
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'category_err' => '',
                'supplier_err' => ''
            ];

            $this->view('supplierCoordinator/v_addshop', $data);
        } else {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'suppliers' => $this->supplierModel->getSuppliers(),
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'supplier_id' => trim($_POST['supplier_id']),
                'blog_link' => trim($_POST['blog_link']),
                'features' => $_POST['features'] ?? [],
                // Initialize all error fields
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'category_err' => '',
                'supplier_err' => '',
                'blog_link_err' => '',
                'features_err' => ''
            ];

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter product name';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter description';
            }
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select category';
            }
            if (empty($data['supplier_id'])) {
                $data['supplier_err'] = 'Please select supplier';
            }
            if (empty($_POST['features'][0]) || empty($_POST['features'][1])) {
                $data['features_err'] = 'First two features are required';
            }

            // Make sure no errors
            if (
                empty($data['name_err']) && empty($data['price_err']) &&
                empty($data['description_err']) && empty($data['category_err']) &&
                empty($data['supplier_err']) && empty($data['features_err'])
            ) {

                // Handle file uploads
                $image1 = $_FILES['image1']['name'] ? $this->handleUpload($_FILES['image1']) : null;
                $image2 = $_FILES['image2']['name'] ? $this->handleUpload($_FILES['image2']) : null;
                $image3 = $_FILES['image3']['name'] ? $this->handleUpload($_FILES['image3']) : null;

                // Prepare product data
                $productData = [
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'supplier_id' => $data['supplier_id'],
                    'blog_link' => $data['blog_link'],
                    'image1' => $image1,
                    'image2' => $image2,
                    'image3' => $image3,
                    'features' => $_POST['features']
                ];

                if ($this->shopModel->addProduct($productData)) {
                    flash('product_message', 'Product Added Successfully');
                    redirect('supplierCoordinator/shop');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('supplierCoordinator/v_addshop', $data);
            }
        }
    }

    private function handleUpload($file)
    {
        // Create target directory if it doesn't exist
        $targetDir = APPROOT . "/../public/uploads/store/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Generate unique filename
        $fileName = time() . '_' . basename($file['name']);
        $targetFilePath = $targetDir . $fileName;

        // Check file type
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($imageFileType, $allowTypes)) {
            // Upload file
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                return $fileName; // Return only filename for database storage
            } else {
                error_log("Failed to upload file: " . $file['name']);
                return null;
            }
        } else {
            error_log("Invalid file type: " . $imageFileType);
            return null;
        }
    }

    public function editShopProduct($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $product = $this->shopModel->getProductById($id);
            $features = $this->shopModel->getProductFeatures($id);

            if (!$product) {
                flash('product_message', 'Product not found', 'alert alert-danger');
                redirect('supplierCoordinator/shop');
            }

            $data = [
                'id' => $id,
                'name' => $product->name,
                'price' => $product->price,
                'description' => $product->description,
                'category' => $product->category,
                'supplier_id' => $product->supplier_id,
                'blog_link' => $product->blog_link,
                'features' => array_column($features, 'feature'),
                'suppliers' => $this->supplierModel->getSuppliers(),
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'category_err' => '',
                'supplier_err' => '',
                'blog_link_err' => '',
                'features_err' => ''
            ];

            $this->view('supplierCoordinator/v_editshop', $data);
        }
    }

    public function updateShopProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'supplier_id' => trim($_POST['supplier_id']),
                'blog_link' => trim($_POST['blog_link']),
                'features' => $_POST['features'],
                // Keep current images
                'image1' => $_POST['current_image1'],
                'image2' => $_POST['current_image2'],
                'image3' => $_POST['current_image3'],
                'suppliers' => $this->supplierModel->getSuppliers(),
                // Error fields
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'category_err' => '',
                'supplier_err' => '',
                'features_err' => ''
            ];

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter product name';
            }

            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
                $data['price_err'] = 'Please enter a valid price';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter description';
            }

            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            if (empty($data['supplier_id'])) {
                $data['supplier_err'] = 'Please select a supplier';
            }

            // Features validation - At least 2 required
            if (empty($data['features'][0]) || empty($data['features'][1])) {
                $data['features_err'] = 'First two features are required';
            }

            // Handle new image uploads if provided
            for ($i = 1; $i <= 3; $i++) {
                if (!empty($_FILES['image' . $i]['name'])) {
                    $newImage = $this->handleUpload($_FILES['image' . $i]);
                    if ($newImage) {
                        $data['image' . $i] = $newImage;
                    }
                }
            }

            // Check for validation errors
            if (
                empty($data['name_err']) && empty($data['price_err']) &&
                empty($data['description_err']) && empty($data['category_err']) &&
                empty($data['supplier_err']) && empty($data['features_err'])
            ) {

                // Update product
                if ($this->shopModel->updateProduct($data['id'], $data)) {
                    // Update features
                    $this->shopModel->deleteProductFeatures($data['id']);
                    foreach ($data['features'] as $feature) {
                        if (!empty($feature)) {
                            $this->shopModel->addProductFeature($data['id'], $feature);
                        }
                    }
                    flash('product_message', 'Product Updated Successfully');
                    redirect('supplierCoordinator/shop');
                } else {
                    flash('product_message', 'Error updating product', 'alert alert-danger');
                    $this->view('supplierCoordinator/v_editshop', $data);
                }
            } else {
                // Load view with errors
                $this->view('supplierCoordinator/v_editshop', $data);
            }
        } else {
            redirect('supplierCoordinator/shop');
        }
    }

    public function deleteShopProduct($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->shopModel->deleteProduct($id)) {
                flash('product_message', 'Product Removed');
            } else {
                flash('product_message', 'Something went wrong', 'alert alert-danger');
            }
        }
        redirect('supplierCoordinator/shop');
    }

    public function viewOrder($id)
    {
        $order = $this->shopModel->getOrderDetails($id);
        if ($order) {
            $data = [
                'order' => $order
            ];
            $this->view('supplierCoordinator/v_requestOrders', $data);
        } else {
            redirect('supplierCoordinator/dashboard');
        }
    }

    public function approveOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"));

            if ($this->shopModel->approveOrder($data)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Order approved successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to approve order'
                ]);
            }
        }
    }

    public function rejectOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"));

            if ($this->shopModel->rejectOrder($data->orderId)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Order rejected successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to reject order'
                ]);
            }
        }
    }

    public function chat()
    {
        // Get clients who have chat history with this coordinator
        $data = [
            'title' => 'Client Messages',
            'clients' => $this->supplierCoordinatorModel->getClientsWithChats()
        ];

        $this->view('supplierCoordinator/v_chat', $data);
    }

    public function getClientChats()
    {
        // Get client ID from query string
        $clientId = isset($_GET['client_id']) ? $_GET['client_id'] : null;
    
        if (!$clientId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Client ID required']);
            return;
        }
    
        // Get chat history between this coordinator and the specified client
        $messages = $this->chatModel->getClientChats($_SESSION['user_id'], $clientId);
    
        // Mark messages as read after retrieving them
        $this->chatModel->markMessagesAsRead($clientId, $_SESSION['user_id']);
    
        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    public function getAllClientChats()
    {
        // Get all clients with chat history and return as JSON
        $clients = $this->supplierModel->getClientsWithChats();
        header('Content-Type: application/json');
        echo json_encode($clients);
    }

    public function saveMessage()
    {
        // Handle AJAX request to save a new message
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['to_user_id']) || empty($data['message'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $messageData = [
            'sender_id' => $_SESSION['user_id'],
            'sender_role' => 'supplierCoordinator',
            'receiver_id' => $data['to_user_id'],
            'receiver_role' => 'customer',
            'message' => $data['message']
        ];

        if ($this->chatModel->saveMessage($messageData)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
        }
    }

    public function markMessagesAsRead()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['client_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Client ID required']);
            return;
        }

        $success = $this->chatModel->markMessagesAsRead($data['client_id'], $_SESSION['user_id']);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }
}
