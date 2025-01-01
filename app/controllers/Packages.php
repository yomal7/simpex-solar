<?php
    class Packages extends Controller {
        private $packagesModel;
        private $userModel;
        private $preProjectModel;

        public function __construct() {
            
            $this->packagesModel = $this->model("M_Packages");
            $this->userModel = $this->model("M_Users");
            $this->preProjectModel = $this->model('M_CustomerPreProject');
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
        
        // public function submitQuotation() {
        //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //         // Create quotation data array from POST data
        //         $quotationData = [
        //             'user_id' => $_SESSION['user_id'],
        //             'package_id' => trim($_POST['package_id']),
        //             'address' => trim($_POST['address']),
        //             'monthly_consumption' => trim($_POST['consumption']),
        //             'nearest_city' => trim($_POST['city']),
        //             'customizations' => trim($_POST['customization'])
        //         ];
                
        //         // Validate required fields
        //         if (empty($quotationData['address']) || 
        //             empty($quotationData['monthly_consumption']) || 
        //             empty($quotationData['nearest_city'])) {
        //             flash('quotation_error', 'Please fill all required fields');
        //             redirect('packages/index');
        //             return;
        //         }
                
        //         // Submit quotation
        //         if ($this->packagesModel->submitQuotation($quotationData)) {
        //             // Get current user data
        //             $user = $this->userModel->getUserById($_SESSION['user_id']);
                    
        //             // Update phone number if provided in form and different from current
        //             if (!empty($_POST['phone']) && $_POST['phone'] !== $user->phone) {
        //                 $this->userModel->updatePhone($_SESSION['user_id'], $_POST['phone']);
        //             }
                    
        //             flash('quotation_success', 'Your quotation has been submitted successfully');
        //             redirect('client/project');
        //         } else {
        //             flash('quotation_error', 'Failed to submit quotation. Please try again.');
        //             redirect('packages/index');
        //         }
        //     } else {
        //         redirect('packages/index');
        //     }
        // }

        // public function submitQuotation() {
        //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //         // Collect form data matching model field names
        //         $data = [
        //             'user_id' => $_SESSION['user_id'],
        //             'package_id' => trim($_POST['package_id']),
        //             'package_type' => trim($_POST['package_type']),
        //             'address' => trim($_POST['address']),
        //             'monthly_consumption' => trim($_POST['monthly_consumption']),
        //             'nearest_city' => trim($_POST['nearest_city']),
        //             'customizations' => trim($_POST['customizations']),
        //             'phone' => trim($_POST['phone']),
        //             'errors' => []
        //         ];
        
        //         // Validate inputs
        //         if (empty($data['address'])) {
        //             $data['errors']['address'] = 'Address is required';
        //         }
        
        //         if (empty($data['monthly_consumption'])) {
        //             $data['errors']['monthly_consumption'] = 'Monthly consumption is required';
        //         } elseif (!is_numeric($data['monthly_consumption']) || $data['monthly_consumption'] <= 0) {
        //             $data['errors']['monthly_consumption'] = 'Please enter a valid consumption amount';
        //         }
        
        //         if (empty($data['nearest_city'])) {
        //             $data['errors']['nearest_city'] = 'City is required';
        //         }
        
        //         if (empty($data['package_id'])) {
        //             $data['errors']['package_id'] = 'Please select a package';
        //         }
        
        //         if (!empty($data['phone']) && !preg_match('/^[0-9]{10}$/', $data['phone'])) {
        //             $data['errors']['phone'] = 'Please enter a valid 10-digit phone number';
        //         }
        
        //         // If no errors, submit
        //         if (empty($data['errors'])) {
        //             if ($this->preProjectModel->submitQuotation($data)) {
        //                 // Update phone if needed
        //                 if (!empty($data['phone'])) {
        //                     $user = $this->userModel->getUserById($_SESSION['user_id']);
        //                     if ($data['phone'] !== $user->phone) {
        //                         $this->userModel->updatePhone($_SESSION['user_id'], $data['phone']);
        //                     }
        //                 }
        
        //                 flash('success_msg', 'Your quotation has been submitted successfully');
        //                 redirect('client/myQuotations');
        //             } else {
        //                 flash('error_msg', 'Failed to submit quotation');
        //                 redirect('packages/submitQuotation');
        //             }
        //         } else {
        //             $_SESSION['quotation_errors'] = $data['errors'];
        //             $_SESSION['quotation_data'] = $data;
        //             redirect('packages/submitQuotation');
        //         }
        //     } else {
        //         redirect('packages/index');
        //     }
        // }

        public function submitQuotation() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                error_log("Processing POST request in submitQuotation");
                
                $data = [
                    'user_id' => $_SESSION['user_id'], // Make sure this exists!
                    'package_id' => trim($_POST['package_id']),
                    'package_type' => trim($_POST['package_type']),
                    'address' => trim($_POST['address']),
                    'monthly_consumption' => trim($_POST['monthly_consumption']),
                    'nearest_city' => trim($_POST['nearest_city']),
                    'customizations' => trim($_POST['customizations']),
                    'phone' => trim($_POST['phone'])
                ];
        
                error_log("Data prepared: " . print_r($data, true));
        
                // Check if user is logged in
                if(!isset($_SESSION['user_id'])) {
                    error_log("No user_id in session");
                    flash('error_msg', 'Please login to submit a quotation');
                    redirect('users/login');
                    return;
                }
        
                // Try to submit
                if($this->preProjectModel->submitQuotation($data)) {
                    error_log("Quotation submitted successfully");
                    flash('success_msg', 'Your quotation has been submitted successfully');
                    redirect('client/operationDashboard');
                } else {
                    error_log("Quotation submission failed");
                    flash('error_msg', 'Something went wrong. Please try again.');
                    redirect('packages/submitQuotation');
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