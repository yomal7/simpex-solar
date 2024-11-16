<?php
    class Users extends Controller{
        public function __construct()
        {
            $this->userModel = $this->model("M_Users");
        }

        public function register() {
            // Check for POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Process form
                
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
                // Init data
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];
    
                // Validate Email
                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter email';
                } else {
                    // Check email
                    if ($this->userModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'Email is already taken';
                    }
                }
    
                // Validate Name
                if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter name';
                }
    
                // Validate Password
                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter password';
                } elseif (strlen($data['password']) < 6) {
                    $data['password_err'] = 'Password must be at least 6 characters';
                }
    
                // Validate Confirm Password
                if (empty($data['confirm_password'])) {
                    $data['confirm_password_err'] = 'Please confirm password';
                } else {
                    if ($data['password'] != $data['confirm_password']) {
                        $data['confirm_password_err'] = 'Passwords do not match';
                    }
                }
    
                // Make sure errors are empty
                if (empty($data['email_err']) && empty($data['name_err']) && 
                    empty($data['password_err']) && empty($data['confirm_password_err'])) {
                    
                    // SUCCESS - Validated
                    
                    // Hash Password - This is where we ensure the password is hashed
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    
                    // Register User
                    if ($this->userModel->register($data)) {
                        //create a flash message
                        flash('reg_flash', 'You are registered and can log in');
                        // Redirect to login
                        redirect('users/login');
                    } else {
                        die('Something went wrong');
                    }
    
                } else {
                    // Load view with errors
                    $this->view('users/v_register', $data);
                }
    
            } else {
                // Init data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];
    
                // Load view
                $this->view('users/v_register', $data);
            }
        }

        public function login(){
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => '',
                ];

                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter email';
                }

                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter password';
                }

                if (empty($data['email_err']) && empty($data['password_err'])) {
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                    if ($loggedInUser) {
                        // Create user session
                        // $_SESSION['user_id'] = $loggedInUser->id;
                        // $_SESSION['user_email'] = $loggedInUser->email;
                        $this->createUserSessions($loggedInUser);
                        
                    } else {
                        $data['password_err'] = 'Password is incorrect';
                        $this->view('users/v_login', $data);
                    }
                } else {
                    $this->view('users/v_login', $data);
                }
            } else {
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => ''
                ];

                $this->view('users/v_login', $data);
            }
        }

        public function index() {
            echo "This is the index method of the Users controller.";
        }

        public function createUserSessions($user){
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email; 
            $_SESSION['user_name'] = $user->name;

            redirect('pages/index');
        }

        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            session_destroy();
            redirect('users/login');
        }

        public function isLoggedIn(){
            if (isset($_SESSION['user_id'])) {
                return true;
            } else {
                return false;
            }
        }
        
        public function userIndex(){
            // get all users

            $data = [
            
            ];

            $this->view('users/v_user', $data);
        }

    }

?>