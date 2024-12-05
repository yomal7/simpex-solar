<?php
    class Packages extends Controller {
        private $packagesModel;
        private $userModel;

        public function __construct() {
            
            $this->packagesModel = $this->model("M_Packages");
            $this->userModel = $this->model("M_Users");
        }

        public function index() {
            $packages = $this->packagesModel->getAllPackagesWithFeatures();
            $data = [
                'packages' => $packages
            ];
            $this->view('packages/v_packageShowcase', $data);
        }

        public function packageConformation($slug) {
            if (!isLoggedIn()) {
                redirect('users/index');
                return;
            }
            $package = $this->packagesModel->getPackageBySlug($slug);
            if (!$package) {
                redirect('packages/index');
            }
            $data['package'] = $package;
            $this->view('packages/v_packageConformation', $data);
        }
        
        public function submitQuotation() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Create quotation data array from POST data
                $quotationData = [
                    'user_id' => $_SESSION['user_id'],
                    'package_id' => trim($_POST['package_id']),
                    'address' => trim($_POST['address']),
                    'monthly_consumption' => trim($_POST['consumption']),
                    'nearest_city' => trim($_POST['city']),
                    'customizations' => trim($_POST['customization'])
                ];
                
                // Validate required fields
                if (empty($quotationData['address']) || 
                    empty($quotationData['monthly_consumption']) || 
                    empty($quotationData['nearest_city'])) {
                    flash('quotation_error', 'Please fill all required fields');
                    redirect('packages/index');
                    return;
                }
                
                // Submit quotation
                if ($this->packagesModel->submitQuotation($quotationData)) {
                    // Get current user data
                    $user = $this->userModel->getUserById($_SESSION['user_id']);
                    
                    // Update phone number if provided in form and different from current
                    if (!empty($_POST['phone']) && $_POST['phone'] !== $user->phone) {
                        $this->userModel->updatePhone($_SESSION['user_id'], $_POST['phone']);
                    }
                    
                    flash('quotation_success', 'Your quotation has been submitted successfully');
                    redirect('client/project');
                } else {
                    flash('quotation_error', 'Failed to submit quotation. Please try again.');
                    redirect('packages/index');
                }
            } else {
                redirect('packages/index');
            }
        }

        public function packageDetails($slug = null) {
            if ($slug) {
                $package = $this->packagesModel->getPackageBySlug($slug);
                if ($package) {
                    $equipment = $this->packagesModel->getPackageEquipmentDetails($package->package_id);
                    $data = [
                        'package' => $package,
                        'equipment' => $equipment
                    ];
                    $this->view('packages/v_packageDetails', $data);
                } else {
                    redirect('pages/error');
                }
            } else {
                redirect('pages/error');
            }
        }

    
        public function filterByType() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Set proper headers
                header('Content-Type: application/json');
                header('X-Requested-With: XMLHttpRequest');
        
                try {
                    $type = isset($_POST['type']) ? trim($_POST['type']) : 'all';
                    
                    if ($type === 'all') {
                        $packages = $this->packagesModel->getAllPackagesWithFeatures();
                    } else {
                        $packages = $this->packagesModel->getPackagesByType($type);
                    }
                    
                    // Convert to array and ensure all required fields are present
                    $response = array_map(function($package) {
                        return [
                            'package_id' => $package->package_id,
                            'title' => $package->title,
                            'type' => $package->type,
                            'price' => $package->price,
                            'final_price' => $package->final_price,
                            'image' => $package->image,
                            'features' => $package->features ? explode('|', $package->features) : []
                        ];
                    }, $packages);
                    
                    echo json_encode($response);
                } catch (Exception $e) {
                    echo json_encode(['error' => 'Failed to fetch packages']);
                }
                exit;
            }
            redirect('packages/index');

        }



    }
?>