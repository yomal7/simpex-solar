<?php
class OperationsCoordinator extends Controller
{
    private $clientModel;
    private $tasksModel;
    private $packageModel;
    private $inventoryModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'operationsCoordinator') {
            flash('error_msg', 'Unauthorized access');
            redirect('users/login');
        }
        $this->tasksModel = $this->model('M_Tasks');
        $this->packageModel = $this->model('M_Packages');
        $this->inventoryModel = $this->model('M_Inventory');
    }

    public function index()
    {
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function dashboard()
    {
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function projects()
    {
        $data = [];
        $this->view('operationsCoordinator/v_projects', $data);
    }

    public function manageAproject()
    {
        $data = [];
        $this->view('operationsCoordinator/v_manageAproject', $data);
    }

    public function tasks()
    {
        $data = [];
        $this->view('operationsCoordinator/v_tasks', $data);
    }

    // View Task
    public function viewTask($taskId)
    {
        // Fetch the task from the model
        $task = $this->tasksModel->getTaskById($taskId);

        // Check if task exists
        if ($task) {
            $data = [
                'task' => $task
            ];
            // Load the view with task data
            $this->view('operationsCoordinator/v_viewTask', $data);
        } else {
            // Task not found, redirect to tasks list
            flash('task_msg', 'Task not found', 'alert alert-danger');
            redirect('operationsCoordinator/tasks');
        }
    }

    public function addTask()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => trim($_POST['title']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'description' => trim($_POST['description']),
                'title_err' => '',
                'start_time_err' => '',
                'end_time_err' => '',
                'description_err' => ''
            ];

            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
            if (empty($data['start_time'])) {
                $data['start_time_err'] = 'Please enter start time';
            }
            if (empty($data['end_time'])) {
                $data['end_time_err'] = 'Please enter end time';
            }
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter description';
            }

            if (
                empty($data['title_err']) && empty($data['start_time_err']) &&
                empty($data['end_time_err']) && empty($data['description_err'])
            ) {
                if ($this->tasksModel->create($data)) {
                    flash('task_msg', 'Task added successfully');
                    redirect('operationsCoordinator/tasks');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('operationsCoordinator/v_addTask', $data);
            }
        } else {
            $data = [
                'title' => '',
                'start_time' => '',
                'end_time' => '',
                'description' => '',
                'title_err' => '',
                'start_time_err' => '',
                'end_time_err' => '',
                'description_err' => ''
            ];
            $this->view('operationsCoordinator/v_addTask', $data);
        }
    }

    public function packages()
    {
        // Get inventory items through packageModel
        $inventoryItems = $this->packageModel->getInventoryItems();

        $data = [
            'title' => '',
            'description' => '',
            'warranty_years' => '',
            'type' => 'on-grid',
            'service_charge' => '',
            'inventory_items' => $inventoryItems,
            'errors' => []
        ];

        $this->view('operationsCoordinator/v_createPackage', $data);
    }

    public function createPackage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Handle file upload
            $image = null;
            if (isset($_FILES['package_image']) && $_FILES['package_image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                $file = $_FILES['package_image'];
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (in_array($file_ext, $allowed)) {
                    $file_name = uniqid('package_') . '.' . $file_ext;
                    $file_destination = APPROOT . '/../public/uploads/packages/' . $file_name;

                    if (move_uploaded_file($file['tmp_name'], $file_destination)) {
                        $image = 'uploads/packages/' . $file_name;
                    }
                }
            }

            // Process equipment items
            $equipment = [];
            if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
                foreach ($_POST['item_id'] as $key => $item_id) {
                    if (!empty($item_id) && isset($_POST['quantity'][$key])) {
                        $equipment[] = [
                            'item_id' => intval($item_id),
                            'quantity' => intval($_POST['quantity'][$key])
                        ];
                    }
                }
            }

            // Process features
            $features = [];
            if (isset($_POST['feature_name']) && is_array($_POST['feature_name'])) {
                foreach ($_POST['feature_name'] as $key => $name) {
                    if (!empty($name) && isset($_POST['feature_description'][$key])) {
                        $features[] = [
                            'feature_name' => trim($name),
                            'description' => trim($_POST['feature_description'][$key])
                        ];
                    }
                }
            }

            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'warranty_years' => intval($_POST['warranty_years']),
                'type' => trim($_POST['type']),
                'service_charge' => floatval($_POST['service_charge']) ?: 0.00,
                'equipment' => $equipment,
                'features' => $features,
                'image' => $image,
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];

            $totalEquipmentPrice = $this->packageModel->calculateFinalPrice($equipment, $data['service_charge']);
            $data['price'] = $totalEquipmentPrice;
            $data['final_price'] = $totalEquipmentPrice + floatval($data['service_charge']);

            // Validation
            if (empty($data['title'])) {
                $data['errors']['title'] = 'Please enter package title';
            }
            if (!in_array($data['type'], ['on-grid', 'off-grid', 'hybrid'])) {
                $data['type'] = str_replace('onGrid', 'on-grid', $data['type']);
                $data['type'] = str_replace('offGrid', 'off-grid', $data['type']);
            }

            if (empty($equipment)) {
                $data['errors']['equipment'] = 'Please add at least one equipment item';
            }

            if (empty($data['errors'])) {
                $result = $this->packageModel->createPackage($data);

                if ($result) {
                    flash('package_message', 'Package created successfully');
                    redirect('operationsCoordinator/managePackages');
                } else {
                    flash('package_message', 'Failed to create package', 'alert alert-danger');
                    $this->view('operationsCoordinator/v_createPackage', $data);
                }
            } else {
                $this->view('operationsCoordinator/v_createPackage', $data);
            }
        } else {
            $data = [
                'title' => '',
                'description' => '',
                'warranty_years' => '',
                'type' => 'on-grid',
                'service_charge' => '',
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];

            $this->view('operationsCoordinator/v_createPackage', $data);
        }
    }

    public function managePackages()
    {
        $data = [
            'packages' => $this->packageModel->getAllPackagesWithDetails(),
            'title' => 'Manage Packages'
        ];

        $this->view('operationsCoordinator/v_managePackages', $data);
    }

    public function editPackage($id)
    {
        // First verify if package exists and user has permission
        $package = $this->packageModel->getPackageById($id);
        if (!$package) {
            flash('package_message', 'Package not found', 'alert alert-danger');
            redirect('operationsCoordinator/managePackages');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Process equipment items
            $equipment = [];
            if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
                foreach ($_POST['item_id'] as $key => $item_id) {
                    if (!empty($item_id) && isset($_POST['quantity'][$key])) {
                        // Verify if item exists in inventory and has sufficient quantity
                        $inventoryItem = $this->inventoryModel->getItemById($item_id);
                        if ($inventoryItem && $inventoryItem->quantity >= $_POST['quantity'][$key]) {
                            $equipment[] = [
                                'item_id' => intval($item_id),
                                'quantity' => intval($_POST['quantity'][$key])
                            ];
                        }
                    }
                }
            }

            // Process features
            $features = [];
            if (isset($_POST['feature_name']) && is_array($_POST['feature_name'])) {
                foreach ($_POST['feature_name'] as $key => $name) {
                    if (!empty($name) && isset($_POST['feature_description'][$key])) {
                        $features[] = [
                            'name' => trim($name),
                            'description' => trim($_POST['feature_description'][$key])
                        ];
                    }
                }
            }

            $data = [
                'package_id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'warranty_years' => intval($_POST['warranty_years']),
                'type' => trim($_POST['type']),
                'service_charge' => floatval($_POST['service_charge']),
                'equipment' => $equipment,
                'features' => $features,
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];

            // Validation
            if (empty($data['title'])) {
                $data['errors']['title'] = 'Please enter package title';
            }
            if (strlen($data['title']) > 100) {
                $data['errors']['title'] = 'Title cannot exceed 100 characters';
            }
            if (!in_array($data['type'], ['on-grid', 'off-grid', 'hybrid'])) {
                $data['errors']['type'] = 'Invalid package type selected';
            }
            if (empty($equipment)) {
                $data['errors']['equipment'] = 'Please add at least one equipment item';
            }
            if ($data['warranty_years'] < 0) {
                $data['errors']['warranty_years'] = 'Warranty years cannot be negative';
            }
            if ($data['service_charge'] < 0) {
                $data['errors']['service_charge'] = 'Service charge cannot be negative';
            }

            if (empty($data['errors'])) {
                try {
                    if ($this->packageModel->updatePackage($id, $data)) {
                        flash('package_message', 'Package updated successfully');
                        redirect('operationsCoordinator/managePackages');
                    } else {
                        flash('package_message', 'Failed to update package', 'alert alert-danger');
                        $this->view('operationsCoordinator/v_editPackage', $data);
                    }
                } catch (Exception $e) {
                    error_log("Error updating package: " . $e->getMessage());
                    flash('package_message', 'An error occurred while updating the package', 'alert alert-danger');
                    $this->view('operationsCoordinator/v_editPackage', $data);
                }
            } else {
                $this->view('operationsCoordinator/v_editPackage', $data);
            }
        } else {
            // GET request - display edit form
            $data = [
                'package_id' => $id,
                'title' => $package->title,
                'description' => $package->description,
                'warranty_years' => $package->warranty_years,
                'type' => $package->type,
                'service_charge' => $package->service_charge,
                'final_price' => $package->final_price,
                'equipment' => $this->packageModel->getPackageEquipment($id),
                'features' => $this->packageModel->getPackageFeatures($id),
                'inventory_items' => $this->inventoryModel->getAllItems(),
                'errors' => []
            ];

            $this->view('operationsCoordinator/v_editPackage', $data);
        }
    }

    public function deletePackage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->packageModel->deletePackage($id)) {
                flash('package_message', 'Package deleted successfully');
            } else {
                flash('package_message', 'Failed to delete package', 'alert alert-danger');
            }
        }
        redirect('operationsCoordinator/managePackages');
    }
}
