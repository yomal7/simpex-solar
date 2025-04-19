<?php

require_once APPROOT . '/libraries/Mailer.php';
class Users extends Controller {
    private $userModel;
    private $otpModel;
    private $mailer;

    public function __construct() {

        $this->userModel = $this->model("M_Users");
        $this->otpModel = $this->model("M_OTP");
        $this->mailer = new Mailer();
    }

    public function index()
    {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'role' => '',
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'action' => 'login',
            'mode' => 'signin'
        ];

        $this->view('users/v_auth', $data);
    }

    public function login() {
        // Simply redirect to index which has the login form
        redirect('users/index');
    }

    public function auth()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Check which form was submitted based on the mode field
            $mode = isset($_POST['mode']) ? $_POST['mode'] : '';

            if ($mode === 'signup') {
                $this->handleRegistration($_POST);
            } elseif ($mode === 'signin') {
                $this->handleLogin($_POST);
            } elseif ($mode === 'verify_otp') {
                $this->verifyOTP($_POST);
            } else {
                redirect('users/index');
            }
        } else {
            redirect('users/index');
        }
    }

    // private function handleRegistration($postData) {
    //     $data = [
    //         'name' => trim($postData['name']),
    //         'email' => trim($postData['email']),
    //         'password' => trim($postData['password']),
    //         'confirm_password' => trim($postData['confirm_password']),
    //         'role' => trim($postData['role']),
    //         'name_err' => '',
    //         'email_err' => '',
    //         'password_err' => '',
    //         'confirm_password_err' => '',
    //         'action' => 'register',
    //         'mode' => 'signup' 
    //     ];

    //     // Validation
    //     if (empty($data['email'])) {
    //         $data['email_err'] = 'Please enter email';
    //     } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    //         $data['email_err'] = 'Please enter a valid email';
    //     } elseif ($this->userModel->findUserByEmail($data['email'])) {
    //         $data['email_err'] = 'Email is already taken';
    //     }

    //     if (empty($data['name'])) {
    //         $data['name_err'] = 'Please enter name';
    //     }

    //     if (empty($data['password'])) {
    //         $data['password_err'] = 'Please enter password';
    //     } elseif (strlen($data['password']) < 6) {
    //         $data['password_err'] = 'Password must be at least 6 characters';
    //     }

    //     if (empty($data['confirm_password'])) {
    //         $data['confirm_password_err'] = 'Please confirm password';
    //     } elseif ($data['password'] != $data['confirm_password']) {
    //         $data['confirm_password_err'] = 'Passwords do not match';
    //     }

    //     if (empty($data['email_err']) && empty($data['name_err']) && 
    //         empty($data['password_err']) && empty($data['confirm_password_err'])) {
            
    //         $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    //         if ($this->userModel->register($data)) {
    //             flash('reg_flash', 'You are registered and can log in');
    //             redirect('users/index');
    //         } else {
    //             die('Something went wrong');
    //         }
    //     } else {
    //         $this->view('users/v_auth', $data);
    //     }
    // }
    // private function handleRegistration($postData) {
    //     $data = [
    //         'name' => trim($postData['name']),
    //         'email' => trim($postData['email']),
    //         'password' => trim($postData['password']),
    //         'confirm_password' => trim($postData['confirm_password']),
    //         'role' => isset($postData['role']) ? trim($postData['role']) : 'user',
    //         'name_err' => '',
    //         'email_err' => '',
    //         'password_err' => '',
    //         'confirm_password_err' => '',
    //         'mode' => 'signup'
    //     ];
    
    //     // Validation
    //     if (empty($data['email'])) {
    //         $data['email_err'] = 'Please enter email';
    //     } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    //         $data['email_err'] = 'Please enter a valid email';
    //     } elseif ($this->userModel->findUserByEmail($data['email'])) {
    //         $data['email_err'] = 'Email is already taken';
    //     }
    
    //     if (empty($data['name'])) {
    //         $data['name_err'] = 'Please enter name';
    //     }
    
    //     if (empty($data['password'])) {
    //         $data['password_err'] = 'Please enter password';
    //     } elseif (strlen($data['password']) < 6) {
    //         $data['password_err'] = 'Password must be at least 6 characters';
    //     }
    
    //     if (empty($data['confirm_password'])) {
    //         $data['confirm_password_err'] = 'Please confirm password';
    //     } elseif ($data['password'] != $data['confirm_password']) {
    //         $data['confirm_password_err'] = 'Passwords do not match';
    //     }
    
    //     if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
    //         // Generate OTP
    //         $otp = sprintf("%06d", mt_rand(100000, 999999));
    //         $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            
    //         // Store user data in session for later use after OTP verification
    //         $_SESSION['temp_user'] = [
    //             'name' => $data['name'],
    //             'email' => $data['email'],
    //             'password' => password_hash($data['password'], PASSWORD_DEFAULT),
    //             'role' => $data['role']
    //         ];
            
    //         // Save OTP in the database
    //         if ($this->otpModel->saveOTP($data['email'], $otp, $expiry)) {
    //             // Send OTP via email
    //             if ($this->mailer->sendOTP($data['email'], $data['name'], $otp)) {
    //                 // Switch to OTP verification mode
    //                 $verifyData = [
    //                     'email' => $data['email'],
    //                     'otp' => '',
    //                     'otp_err' => '',
    //                     'mode' => 'verify_otp'
    //                 ];
                    
    //                 // Instead of redirecting, just show the same page with OTP section
    //                 $this->view('users/v_register', $verifyData);
    //                 return;
    //             } else {
    //                 $data['email_err'] = 'Failed to send verification email. Please try again.';
    //             }
    //         } else {
    //             $data['email_err'] = 'Something went wrong. Please try again.';
    //         }
    //     }
        
    //     // If we got here, there was an error, show the registration form again
    //     $this->view('users/v_register', $data);
    // }
    
    // private function verifyOTP($postData) {
    //     $data = [
    //         'email' => trim($postData['email']),
    //         'otp' => trim($postData['otp']),
    //         'otp_err' => '',
    //         'mode' => 'verify_otp'
    //     ];
    
    //     if (empty($data['otp'])) {
    //         $data['otp_err'] = 'Please enter OTP';
    //         $this->view('users/v_register', $data);
    //         return;
    //     }
    
    //     // Verify OTP
    //     $otpVerified = $this->otpModel->verifyOTP($data['email'], $data['otp']);
    
    //     if ($otpVerified) {
    //         // OTP verified, register the user
    //         if (isset($_SESSION['temp_user'])) {
    //             $userData = $_SESSION['temp_user'];
                
    //             if ($this->userModel->register($userData)) {
    //                 // Clean up
    //                 $this->otpModel->deleteOTP($data['email']);
    //                 unset($_SESSION['temp_user']);
                    
    //                 flash('reg_flash', 'Your email has been verified. You are now registered and can log in.');
    //                 redirect('users/v_login');
    //             } else {
    //                 $data['otp_err'] = 'Something went wrong during registration';
    //                 $this->view('users/v_register', $data);
    //             }
    //         } else {
    //             $data = [
    //                 'name' => '',
    //                 'email' => '',
    //                 'password' => '',
    //                 'confirm_password' => '',
    //                 'role' => '',
    //                 'name_err' => '',
    //                 'email_err' => 'Session expired. Please register again.',
    //                 'password_err' => '',
    //                 'confirm_password_err' => '',
    //                 'mode' => 'signup'
    //             ];
    //             $this->view('users/v_register', $data);
    //         }
    //     } else {
    //         $data['otp_err'] = 'Invalid or expired OTP. Please try again.';
    //         $this->view('users/v_register', $data);
    //     }
    // }
    
    // // Resend OTP if needed
    // public function resendOTP() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    //         $email = trim($_POST['email']);
            
    //         if (isset($_SESSION['temp_user']) && $_SESSION['temp_user']['email'] == $email) {
    //             $name = $_SESSION['temp_user']['name'];
                
    //             // Generate new OTP
    //             $otp = sprintf("%06d", mt_rand(100000, 999999));
    //             $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
    //             // Update OTP in the database
    //             if ($this->otpModel->updateOTP($email, $otp, $expiry)) {
    //                 // Send new OTP via email
    //                 if ($this->mailer->sendOTP($email, $name, $otp)) {
    //                     flash('otp_flash', 'A new OTP has been sent to your email', 'alert alert-success');
    //                 } else {
    //                     flash('otp_flash', 'Failed to send new OTP. Please try again.', 'alert alert-danger');
    //                 }
    //             } else {
    //                 flash('otp_flash', 'Something went wrong. Please try again.', 'alert alert-danger');
    //             }
    //         } else {
    //             flash('otp_flash', 'Session expired. Please register again.', 'alert alert-danger');
    //         }
            
    //         // Show OTP verification mode again
    //         $this->view('users/v_register', [
    //             'email' => $email,
    //             'otp' => '',
    //             'otp_err' => '',
    //             'mode' => 'verify_otp'
    //         ]);
    //     } else {
    //         redirect('users/v_register');
    //     }
    // }

    private function handleRegistration($postData) {

        $data = [
            'name' => trim($postData['name']),
            'email' => trim($postData['email']),
            'password' => trim($postData['password']),
            'confirm_password' => trim($postData['confirm_password']),
            'role' => isset($postData['role']) ? trim($postData['role']) : 'user',
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'confirm_password_err' => '',
            'action' => 'register',
            'mode' => 'signup'
        ];

        // Validation
        if (empty($data['email'])) {
            $data['email_err'] = 'Please enter email';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_err'] = 'Please enter a valid email';
        } elseif ($this->userModel->findUserByEmail($data['email'])) {
            $data['email_err'] = 'Email is already taken';
        }

        if (empty($data['name'])) {
            $data['name_err'] = 'Please enter name';
        }

        if (empty($data['password'])) {
            $data['password_err'] = 'Please enter password';
        } elseif (strlen($data['password']) < 6) {
            $data['password_err'] = 'Password must be at least 6 characters';
        }

        if (empty($data['confirm_password'])) {
            $data['confirm_password_err'] = 'Please confirm password';
        } elseif ($data['password'] != $data['confirm_password']) {
            $data['confirm_password_err'] = 'Passwords do not match';
        }


        if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
            // Generate OTP
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            
            // Store user data in session for later use after OTP verification
            $_SESSION['temp_user'] = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => $data['role']
            ];
            
            // Save OTP in the database
            if ($this->otpModel->saveOTP($data['email'], $otp, $expiry)) {
                // Send OTP via email
                if ($this->mailer->sendOTP($data['email'], $data['name'], $otp)) {
                    // Prepare data for OTP verification view
                    $verifyData = [
                        'email' => $data['email'],
                        'otp' => '',
                        'otp_err' => '',
                        'mode' => 'verify_otp'
                    ];
                    
                    $this->view('users/v_verify_otp', $verifyData);
                    return;
                } else {
                    $data['email_err'] = 'Failed to send verification email. Please try again.';
                }
            } else {
                $data['email_err'] = 'Something went wrong. Please try again.';
            }
        }
        
        // If we got here, there was an error
        $this->view('users/v_auth', $data);
    }


    private function verifyOTP($postData) {
        $data = [
            'email' => trim($postData['email']),
            'otp' => trim($postData['otp']),
            'otp_err' => '',
            'mode' => 'verify_otp'
        ];

        if (empty($data['otp'])) {
            $data['otp_err'] = 'Please enter OTP';
            $this->view('users/v_verify_otp', $data);
            return;
        }

        // Verify OTP
        $otpVerified = $this->otpModel->verifyOTP($data['email'], $data['otp']);

        if ($otpVerified) {
            // OTP verified, register the user
            if (isset($_SESSION['temp_user'])) {
                $userData = $_SESSION['temp_user'];
                
                if ($this->userModel->register($userData)) {
                    // Clean up
                    $this->otpModel->deleteOTP($data['email']);
                    unset($_SESSION['temp_user']);
                    
                    flash('reg_flash', 'Your email has been verified. You are now registered and can log in.');
                    redirect('users/index');
                } else {
                    die('Something went wrong during registration');
                }
            } else {
                $data['otp_err'] = 'Session expired. Please register again.';
                $this->view('users/v_auth', [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'role' => '',
                    'name_err' => '',
                    'email_err' => $data['otp_err'],
                    'password_err' => '',
                    'confirm_password_err' => '',
                    'action' => 'register',
                    'mode' => 'signup'
                ]);
            }
        } else {
            $data['otp_err'] = 'Invalid or expired OTP. Please try again.';
            $this->view('users/v_verify_otp', $data);
        }
    }

    // Resend OTP if needed
    public function resendOTP() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $email = trim($_POST['email']);
            
            if (isset($_SESSION['temp_user']) && $_SESSION['temp_user']['email'] == $email) {
                $name = $_SESSION['temp_user']['name'];
                
                // Generate new OTP
                $otp = sprintf("%06d", mt_rand(100000, 999999));
                $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
                // Update OTP in the database
                if ($this->otpModel->updateOTP($email, $otp, $expiry)) {
                    // Send new OTP via email
                    if ($this->mailer->sendOTP($email, $name, $otp)) {
                        flash('otp_flash', 'A new OTP has been sent to your email', 'alert alert-success');
                    } else {
                        flash('otp_flash', 'Failed to send new OTP. Please try again.', 'alert alert-danger');
                    }
                } else {
                    flash('otp_flash', 'Something went wrong. Please try again.', 'alert alert-danger');
                }
            } else {
                flash('otp_flash', 'Session expired. Please register again.', 'alert alert-danger');
            }
            
            // Show OTP verification view again
            $this->view('users/v_verify_otp', [
                'email' => $email,
                'otp' => '',
                'otp_err' => '',
                'mode' => 'verify_otp'
            ]);
        } else {
            redirect('users/index');
        }
    }


    public function createUserSession($user)
    {
        error_log("Creating session with user data: " . print_r($user, true));

        // Use user_id instead of id
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['role'] = $user->role;

        error_log("Session created with: " . print_r($_SESSION, true));
    }

    public function checkLogin()
    {
        header('Content-Type: application/json');
        if (isset($_SESSION['user_id'])) {
            echo json_encode(['isLoggedIn' => true]);
        } else {
            echo json_encode(['isLoggedIn' => false]);
        }
        exit;
    }


    private function handleLogin($postData)
    {
        $data = [
            'email' => trim($postData['email']),
            'password' => trim($postData['password']),
            'email_err' => '',
            'password_err' => '',
            'action' => 'login',
            'mode' => 'signin'
        ];

        if (empty($data['email'])) {
            $data['email_err'] = 'Please enter email';
        }

        if (empty($data['password'])) {
            $data['password_err'] = 'Please enter password';
        }

        if (!empty($data['email'])) {
            $user = $this->userModel->getUserByEmail($data['email']);
            if (!$user) {
                $data['email_err'] = 'No user found';
            }
        }

        if (empty($data['email_err']) && empty($data['password_err'])) {
            $user = $this->userModel->login($data['email'], $data['password']);

            if ($user) {
                $this->createUserSession($user);

                // Handle redirect after login
                echo "<script>
                    if (sessionStorage.getItem('redirectAfterLogin')) {
                        let redirect = sessionStorage.getItem('redirectAfterLogin');
                        sessionStorage.removeItem('redirectAfterLogin');
                        window.location.href = '" . URLROOT . "' + redirect;
                    } else {
                        window.location.href = '" . URLROOT . "/" . $this->getRoleRedirect($user->role) . "';
                    }
                </script>";
                exit;
            } else {
                $data['password_err'] = 'Invalid password';
                $this->view('users/v_auth', $data);
            }
        } else {
            $this->view('users/v_auth', $data);
        }
    }

    private function getRoleRedirect($role)
    {
        $redirects = [
            'admin' => 'admin/index',
            'customer' => 'client/index',
            'operationsCoordinator' => 'operationsCoordinator/index',
            'hRAdministrator' => 'hRAdministrator/index',
            'supplierCoordinator' => 'supplierCoordinator/index',
            'employee' => 'employee/index'
        ];

        return $redirects[$role] ?? '';
    }


    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['role']);
        session_destroy();
        redirect('users/index');
    }

    public function forgotPassword() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process forgot password request
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Get email
            $email = trim($_POST['email']);
            
            // Validate Email
            if (empty($email)) {
                $data = [
                    'email' => '',
                    'email_err' => 'Please enter your email address'
                ];
                $this->view('users/v_forgot_password', $data);
                return;
            }
            
            // Check email exists
            $user = $this->userModel->getUserByEmail($email);
            if (!$user) {
                $data = [
                    'email' => $email,
                    'email_err' => 'No account found with that email'
                ];
                $this->view('users/v_forgot_password', $data);
                return;
            }
            
            // Generate OTP
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            
            // Save OTP to database
            if ($this->otpModel->saveOTP($email, $otp, $expiry)) {
                // Store email in session for reset process
                $_SESSION['reset_email'] = $email;
                
                // Send OTP email
                if ($this->mailer->sendPasswordResetOTP($email, $user->name, $otp)) {
                    // Redirect to OTP verification page
                    redirect('users/verifyResetOTP');
                } else {
                    flash('forgot_password_message', 'Failed to send OTP email. Please try again.', 'alert alert-danger');
                    redirect('users/forgotPassword');
                }
            } else {
                flash('forgot_password_message', 'Something went wrong. Please try again.', 'alert alert-danger');
                redirect('users/forgotPassword');
            }
        } else {
            // Display forgot password form
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            
            $this->view('users/v_forgot_password', $data);
        }
    }
    
    public function verifyResetOTP() {
        // Check if email is in session
        if (!isset($_SESSION['reset_email'])) {
            redirect('users/forgotPassword');
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process OTP verification
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'email' => $_SESSION['reset_email'],
                'otp' => trim($_POST['otp']),
                'otp_err' => ''
            ];
            
            // Validate OTP
            if (empty($data['otp'])) {
                $data['otp_err'] = 'Please enter the OTP';
                $this->view('users/v_verify_reset_otp', $data);
                return;
            }
            
            // Verify OTP
            if ($this->otpModel->verifyOTP($data['email'], $data['otp'])) {
                // OTP is valid, redirect to reset password page
                redirect('users/resetPassword');
            } else {
                $data['otp_err'] = 'Invalid or expired OTP. Please try again.';
                $this->view('users/v_verify_reset_otp', $data);
            }
        } else {
            // Display OTP verification form
            $data = [
                'email' => $_SESSION['reset_email'],
                'otp' => '',
                'otp_err' => ''
            ];
            
            $this->view('users/v_verify_reset_otp', $data);
        }
    }
    
    public function resetPassword() {
        // Check if email is in session
        if (!isset($_SESSION['reset_email'])) {
            redirect('users/forgotPassword');
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process password reset
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'email' => $_SESSION['reset_email'],
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            
            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } elseif ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }
            
            // Make sure there are no errors
            if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Update password in the database
                if ($this->userModel->resetPassword($data['email'], $data['password'])) {
                    // Clean up OTP and session
                    $this->otpModel->deleteOTP($data['email']);
                    unset($_SESSION['reset_email']);
                    
                    flash('password_reset_success', 'Your password has been reset successfully', 'alert alert-success');
                    redirect('users/index');
                } else {
                    flash('password_reset_error', 'Failed to reset password', 'alert alert-danger');
                    redirect('users/resetPassword');
                }
            } else {
                // Load view with errors
                $this->view('users/v_reset_password', $data);
            }
        } else {
            // Display reset password form
            $data = [
                'email' => $_SESSION['reset_email'],
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            
            $this->view('users/v_reset_password', $data);
        }
    }

    public function resendResetOTP() {
        // Check if email is in session
        if (!isset($_SESSION['reset_email'])) {
            redirect('users/forgotPassword');
            return;
        }
        
        $email = $_SESSION['reset_email'];
        $user = $this->userModel->getUserByEmail($email);
        
        if (!$user) {
            flash('forgot_password_message', 'Something went wrong. Please try again.', 'alert alert-danger');
            redirect('users/forgotPassword');
            return;
        }
        
        // Generate new OTP
        $otp = sprintf("%06d", mt_rand(100000, 999999));
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        
        // Update OTP in database
        if ($this->otpModel->updateOTP($email, $otp, $expiry)) {
            // Send OTP email
            if ($this->mailer->sendPasswordResetOTP($email, $user->name, $otp)) {
                flash('otp_message', 'A new verification code has been sent to your email', 'alert alert-success');
            } else {
                flash('otp_message', 'Failed to send verification code. Please try again.', 'alert alert-danger');
            }
        } else {
            flash('otp_message', 'Something went wrong. Please try again.', 'alert alert-danger');
        }
        
        redirect('users/verifyResetOTP');
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function updatePhone()
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/index');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $phone = $_POST['phone'];

            if ($this->userModel->updatePhone($_SESSION['user_id'], $phone)) {
                flash('phone_update', 'Phone number updated successfully');
                // Don't redirect here since it's part of the quotation submission
                return true;
            } else {
                flash('phone_update', 'Failed to update phone number', 'alert alert-danger');
                return false;
            }
        }
    }
}
