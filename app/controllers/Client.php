<?php

class Client extends Controller {
    private $clientModel;

        public function __construct() {
            // Check if user is logged in and is a customer
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
                redirect('users/index');
            }
            
            $this->clientModel = $this->model('M_Client');
        }

        public function index() {
            $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
            $data = [
                'customer' => $client
            ];
            $this->view('client/v_clientDashboard', $data);
        }

        public function dashboard() {
            // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
            $data = [];
            $this->view('client/v_clientDashboard', $data);
        }

        public function project() {
            // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
            $data = [];
            $this->view('client/v_clientProject', $data);
        }

        public function agreement() {
            // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
            $data = [];
            $this->view('client/v_clientAgreement', $data);
        }

        public function sitevisit() {
            // $client = $this->clientModel->getClientByUserId($_SESSION['user_id']);
            $data = [];
            $this->view('client/v_clientsitevisit', $data);
        }

        public function firstPayment() {
            $data = [];
            $this->view('client/v_clientFirstPayment', $data);  
        }
        
        public function finalPayment() { 
            $data = [];
            $this->view('client/v_clientFinalPayment', $data); 
        }
        
        public function installation() {
            $data = [];
            $this->view('client/v_clientInstallation', $data);  
        }

        public function settings() {
            // Get user data using session user_id
            $userId = $_SESSION['user_id'];
            $userData = $this->clientModel->getUserById($userId);
    
            if (!$userData) {
                flash('profile_message', 'User data not found', 'alert alert-danger');
                redirect('client/dashboard');
            }
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Handle profile update
                if (isset($_POST['update_profile'])) {
                    $phone = trim($_POST['phone']);
                    
                    $updateData = [
                        'phone' => $phone,
                        'phone_err' => ''
                    ];
    
                    // Validate phone
                    if (empty($phone)) {
                        $updateData['phone_err'] = 'Please enter phone number';
                    }
    
                    // If no errors, update profile
                    if (empty($updateData['phone_err'])) {
                        if ($this->clientModel->updateProfile($userId, $updateData)) {
                            flash('profile_message', 'Profile Updated Successfully', 'alert alert-success');
                            redirect('client/settings');
                        } else {
                            flash('profile_message', 'Something went wrong', 'alert alert-danger');
                        }
                    }
                }
    
                // Handle password change
                if (isset($_POST['change_password'])) {
                    $currentPassword = trim($_POST['current_password']);
                    $newPassword = trim($_POST['new_password']);
    
                    if (empty($currentPassword) || empty($newPassword)) {
                        flash('password_message', 'Both password fields are required', 'alert alert-danger');
                    } else {
                        if ($this->clientModel->verifyPassword($userId, $currentPassword)) {
                            if ($this->clientModel->updatePassword($userId, $newPassword)) {
                                flash('password_message', 'Password Updated Successfully', 'alert alert-success');
                                redirect('client/settings');
                            } else {
                                flash('password_message', 'Failed to update password', 'alert alert-danger');
                            }
                        } else {
                            flash('password_message', 'Current password is incorrect', 'alert alert-danger');
                        }
                    }
                }
    
                // Handle profile picture upload
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['profile_picture'];
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $maxSize = 5 * 1024 * 1024; // 5MB
    
                    if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
                        $fileName = uniqid() . '_' . basename($file['name']);
                        $uploadDir = APPROOT . '/../public/uploads/profile_pictures/';
                        
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $uploadPath = $uploadDir . $fileName;
    
                        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                            if ($this->clientModel->updateProfilePicture($userId, $fileName)) {
                                flash('profile_picture_message', 'Profile Picture Updated Successfully', 'alert alert-success');
                                redirect('client/settings');
                            } else {
                                flash('profile_picture_message', 'Failed to update database', 'alert alert-danger');
                            }
                        } else {
                            flash('profile_picture_message', 'Failed to upload file', 'alert alert-danger');
                        }
                    } else {
                        flash('profile_picture_message', 'Invalid file type or size', 'alert alert-danger');
                    }
                }
            }
    
            // Prepare view data
            $viewData = [
                'name' => $userData->name,
                'email' => $userData->email,
                'phone' => $userData->phone ?? '',
                'profile_picture' => $userData->profile_picture ?? '',
                'title' => 'Settings'
            ];
    
            $this->view('client/v_clientSettings', $viewData);
        }
    }


?>