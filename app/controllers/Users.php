<?php
class Users extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model("M_Users");
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
            } else {
                redirect('users/index');
            }
        } else {
            redirect('users/index');
        }
    }

    private function handleRegistration($postData)
    {
        $data = [
            'name' => trim($postData['name']),
            'email' => trim($postData['email']),
            'password' => trim($postData['password']),
            'confirm_password' => trim($postData['confirm_password']),
            'role' => trim($postData['role']),
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

        if (
            empty($data['email_err']) && empty($data['name_err']) &&
            empty($data['password_err']) && empty($data['confirm_password_err'])
        ) {

            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if ($this->userModel->register($data)) {
                flash('reg_flash', 'You are registered and can log in');
                redirect('users/index');
            } else {
                die('Something went wrong');
            }
        } else {
            $this->view('users/v_auth', $data);
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
            'supplierCoordinator' => 'supplierCoordinator/index'
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
