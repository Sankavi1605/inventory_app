<?php

class Auth extends Controller
{
    private $userModel;
    private $activityLogModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // Show login page
    public function login()
    {
        if (isLoggedIn()) {
            redirect('pages/index');
        }
        $this->view('users/login');
    }

    // Process login
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $data = [
                'username/email' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username/email_err' => '',
                'password_err' => ''
            ];

            // Validate username/email
            if (empty($data['username/email'])) {
                $data['username/email_err'] = 'Please enter username or email';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for errors
            if (empty($data['username/email_err']) && empty($data['password_err'])) {
                // Attempt to login
                $loggedInUser = $this->userModel->login($data['username/email'], $data['password']);

                if ($loggedInUser) {
                    // Create session
                    $this->createUserSession($loggedInUser);

                    // Log activity
                    $logData = [
                        'user_id' => $loggedInUser->id,
                        'action' => 'user_login',
                        'table_name' => 'users',
                        'record_id' => $loggedInUser->id,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    // Update last login
                    $this->userModel->updateLastLogin($loggedInUser->id);

                    flash('login_message', 'Welcome back, ' . $loggedInUser->first_name . '!', 'alert alert-success');
                    redirect('pages/index');
                } else {
                    $data['password_err'] = 'Invalid username/email or password';
                    $this->view('users/login', $data);
                }
            } else {
                $this->view('users/login', $data);
            }
        } else {
            $data = [
                'username/email' => '',
                'password' => '',
                'username/email_err' => '',
                'password_err' => ''
            ];
            $this->view('users/login', $data);
        }
    }

    // Create session variables
    public function createUserSession($user)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->username;
        $_SESSION['user_first_name'] = $user->first_name;
        $_SESSION['user_last_name'] = $user->last_name;
        $_SESSION['user_role'] = 'admin';
        $_SESSION['user_role_id'] = 2;
        $_SESSION['logged_in'] = true;
    }

    // Logout and destroy session
    public function logout()
    {
        $userId = $_SESSION['user_id'] ?? 0;

        // Log activity before destroying session
        if ($userId) {
            $logData = [
                'user_id' => $userId,
                'action' => 'user_logout',
                'table_name' => 'users',
                'record_id' => $userId,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ];
            $this->activityLogModel->logActivity($logData);
        }

        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_first_name']);
        unset($_SESSION['user_last_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_role_id']);
        unset($_SESSION['logged_in']);

        session_destroy();
        redirect('auth/login');
    }

    // Show signup page
    public function signup()
    {
        if (isLoggedIn()) {
            redirect('pages/index');
        }
        $this->view('users/signup');
    }

    // Process signup
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone' => trim($_POST['phone'] ?? ''),
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'first_name_err' => '',
                'last_name_err' => ''
            ];

            // Validate inputs
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } elseif (strlen($data['username']) < 3) {
                $data['username_err'] = 'Username must be at least 3 characters';
            } elseif ($this->userModel->findUserByEmailOrUsername($data['email'], $data['username'])) {
                $data['username_err'] = 'Username or email already exists';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter valid email';
            }

            if (empty($data['first_name'])) {
                $data['first_name_err'] = 'Please enter first name';
            }

            if (empty($data['last_name'])) {
                $data['last_name_err'] = 'Please enter last name';
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

            // Check for errors
            if (empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) &&
                empty($data['confirm_password_err']) && empty($data['first_name_err']) && empty($data['last_name_err'])) {

                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register user
                if ($this->userModel->register($data)) {
                    flash('register_message', 'Registration received. Please wait for an administrator to activate your account.', 'alert alert-success');
                    redirect('auth/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/signup', $data);
            }
        } else {
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name' => '',
                'last_name' => '',
                'phone' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'first_name_err' => '',
                'last_name_err' => ''
            ];
            $this->view('users/signup', $data);
        }
    }

    // Show forgot password page
    public function forgotPassword()
    {
        $this->view('users/forgotpassword');
    }

    // Process forgot password
    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $email = trim($_POST['email']);
            $user = $this->userModel->findUserByEmailOrUsername($email, '');

            if ($user) {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $this->userModel->setPasswordResetToken($email, $token, $expires);

                // In a real application, you would send an email here
                // For now, we'll just show a success message
                flash('reset_message', 'Password reset link has been sent to your email. Token: ' . $token, 'alert alert-info');
                redirect('auth/login');
            } else {
                flash('reset_message', 'If an account with that email exists, a reset link has been sent.', 'alert alert-info');
                redirect('auth/login');
            }
        }
        redirect('auth/forgotpassword');
    }

    // Show reset password page
    public function resetPassword($token)
    {
        $user = $this->userModel->getUserByResetToken($token);

        if ($user) {
            $data = ['token' => $token];
            $this->view('users/resetpassword', $data);
        } else {
            flash('reset_message', 'Invalid or expired token', 'alert alert-danger');
            redirect('auth/login');
        }
    }

    // Process password reset
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $data = [
                'token' => trim($_POST['token']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate
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

            // Check for errors
            if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
                $user = $this->userModel->getUserByResetToken($data['token']);

                if ($user) {
                    // Hash password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    if ($this->userModel->updatePassword(['password' => $data['password'], 'id' => $user->id])) {
                        $this->userModel->clearPasswordResetToken($user->email);
                        flash('login_message', 'Password has been reset. Please login with your new password.', 'alert alert-success');
                        redirect('auth/login');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    flash('reset_message', 'Invalid or expired token', 'alert alert-danger');
                    redirect('auth/login');
                }
            } else {
                $this->view('users/resetpassword', $data);
            }
        }
    }
}
