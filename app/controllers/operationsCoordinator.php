<?php

class operationsCoordinator extends Controller {

    private $packageModel;
    private $inventoryModel;

    public function __construct() {
        $this->packageModel = $this->model('M_Packages');
        $this->inventoryModel = $this->model('M_Inventory');
    }

    public function index() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function dashboard() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_dashboard', $data);
    }

    public function projects() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_projects', $data);
    }

    public function manageAproject() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_manageAproject', $data);
    }

    public function managePackages() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [
            'packages' => $this->packageModel->getAllPackages(),
            'inventoryItems' => $this->inventoryModel->getAllItems()
        ];
        $this->view('operationsCoordinator/v_managePackages', $data);
    }

    public function tasks() {
        // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
        $data = [];
        $this->view('operationsCoordinator/v_tasks', $data);
    }

    public function getInventoryItems() {
        $items = $this->inventoryModel->getAllItems();
        echo json_encode($items);
    }

    public function createPackage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $packageData = [
                'title' => $_POST['title'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'service_charge' => $_POST['service_charge'],
                'warranty_years' => $_POST['warranty_years']
            ];

            // Handle package image upload
            $imagePath = $this->uploadPackageImage();
            if ($imagePath) {
                $packageData['image_path'] = $imagePath;
            }

            $packageId = $this->packageModel->createPackage($packageData);

            // Add features
            if (isset($_POST['features'])) {
                foreach ($_POST['features'] as $feature) {
                    $this->packageModel->addFeature($packageId, $feature);
                }
            }

            // Add equipment
            if (isset($_POST['equipment'])) {
                foreach ($_POST['equipment'] as $equipment) {
                    $this->packageModel->addEquipment($packageId, $equipment['item_id'], $equipment['quantity']);
                }
            }

            echo json_encode(['success' => true, 'package_id' => $packageId]);
        }
    }

    public function updatePackage($packageId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Similar logic to createPackage, but for updating
            $packageData = [
                'title' => $_POST['title'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'service_charge' => $_POST['service_charge'],
                'warranty_years' => $_POST['warranty_years']
            ];

            $this->packageModel->updatePackage($packageId, $packageData);

            // Update logic for features and equipment similar to createPackage
        }
    }

    private function uploadPackageImage() {
        if (isset($_FILES['package_image']) && $_FILES['package_image']['error'] == 0) {
            $uploadDir = 'uploads/packages/';
            $fileName = uniqid() . '_' . basename($_FILES['package_image']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['package_image']['tmp_name'], $uploadPath)) {
                return $uploadPath;
            }
        }
        return null;
    }

}
?>