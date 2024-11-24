<?php

class Client extends Controller {
    private $clientModel;

        public function __construct() {
            $this->clientModel = $this->model('M_Client');

            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
                redirect('users/login'); 
            }
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
            // $userData = $this->clientModel->getUserById($_SESSION['user_id']);
            
            // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //     // Handle profile update
            //     if (isset($_POST['update_profile'])) {
            //         $phone = trim($_POST['phone']);
                    
            //         $data = [
            //             'phone' => $phone,
            //             'phone_err' => '',
            //         ];
    
            //         // Validate phone
            //         if (empty($phone)) {
            //             $data['phone_err'] = 'Please enter phone number';
            //         }
    
            //         // If no errors, update profile
            //         if (empty($data['phone_err'])) {
            //             if ($this->clientModel->updateProfile($_SESSION['user_id'], $data)) {
            //                 flash('profile_message', 'Profile Updated Successfully');
            //             } else {
            //                 flash('profile_message', 'Something went wrong', 'alert alert-danger');
            //             }
            //         }
            //     }
    
            //     // Handle password change
            //     if (isset($_POST['change_password'])) {
            //         $currentPassword = trim($_POST['current_password']);
            //         $newPassword = trim($_POST['new_password']);
    
            //         if ($this->clientModel->verifyPassword($_SESSION['user_id'], $currentPassword)) {
            //             if ($this->clientModel->updatePassword($_SESSION['user_id'], $newPassword)) {
            //                 flash('password_message', 'Password Updated Successfully');
            //             } else {
            //                 flash('password_message', 'Password Update Failed', 'alert alert-danger');
            //             }
            //         } else {
            //             flash('password_message', 'Current Password is Incorrect', 'alert alert-danger');
            //         }
            //     }
    
            //     // Handle profile picture upload
            //     if (isset($_FILES['profile_picture'])) {
            //         $file = $_FILES['profile_picture'];
            //         $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            //         $maxSize = 5 * 1024 * 1024; // 5MB
        
            //         if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
            //             // Create unique filename
            //             $fileName = uniqid() . '_' . basename($file['name']);
                        
            //             // Define upload directory - use physical path
            //             $uploadDir = APPROOT . '/../public/uploads/profile_pictures/';
                        
            //             // Create directory if it doesn't exist
            //             if (!file_exists($uploadDir)) {
            //                 mkdir($uploadDir, 0777, true);
            //             }
                        
            //             // Complete file path
            //             $uploadPath = $uploadDir . $fileName;
        
            //             if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            //                 // Store only the filename in database
            //                 if ($this->clientModel->updateProfilePicture($_SESSION['user_id'], $fileName)) {
            //                     flash('profile_picture_message', 'Profile Picture Updated Successfully');
            //                     // Update user data for view
            //                     $userData->profile_picture = $fileName;
            //                 } else {
            //                     flash('profile_picture_message', 'Database update failed', 'alert alert-danger');
            //                 }
            //             } else {
            //                 flash('profile_picture_message', 'Failed to upload file', 'alert alert-danger');
            //             }
            //         } else {
            //             flash('profile_picture_message', 'Invalid file type or size', 'alert alert-danger');
            //         }
            // }
    
            // $this->view('client/v_clientSettings', $userData);
            $this->view('client/v_clientSettings');
        }

    }

?>