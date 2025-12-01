<?php

class UserController extends Controller
{
    private $userModel;
    private $activityLogModel;

    public function __construct()
    {
        requireLogin();
        $this->userModel = $this->model('User');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    public function index()
    {
        $data = [
            'users' => $this->userModel->getAllUsers()
        ];
        $this->view('users/manageUsers', $data);
    }

    // Create new user
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone' => trim($_POST['phone']),
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
                if ($userId = $this->userModel->register($data)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'create_user',
                        'table_name' => 'users',
                        'record_id' => $userId,
                        'new_values' => [
                            'username' => $data['username'],
                            'email' => $data['email'],
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name']
                        ],
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('user_message', 'User created successfully', 'alert alert-success');
                    redirect('usercontroller/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/createUser', $data);
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
            $this->view('users/createUser', $data);
        }
    }

    // Edit user
    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            flash('user_message', 'User not found', 'alert alert-danger');
            redirect('usercontroller/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'email' => trim($_POST['email']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone' => trim($_POST['phone']),
                'email_err' => '',
                'first_name_err' => '',
                'last_name_err' => ''
            ];

            // Store old values for logging
            $oldValues = [
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name
            ];

            // Validate
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

            // Check for errors
            if (empty($data['email_err']) && empty($data['first_name_err']) && empty($data['last_name_err'])) {
                if ($this->userModel->updateUser($data)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'update_user',
                        'table_name' => 'users',
                        'record_id' => $id,
                        'old_values' => $oldValues,
                        'new_values' => $data,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('user_message', 'User updated successfully', 'alert alert-success');
                    redirect('usercontroller/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/editUser', $data);
            }
        } else {
            $data = [
                'id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'email_err' => '',
                'first_name_err' => '',
                'last_name_err' => ''
            ];

            $this->view('users/editUser', $data);
        }
    }

    // Delete user
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->userModel->getUserById($id);

            if ($user) {
                // Prevent deletion of self
                if ($user->id == getCurrentUserId()) {
                    flash('user_message', 'You cannot delete your own account', 'alert alert-danger');
                    redirect('usercontroller/index');
                    return;
                }

                // Log activity before deletion
                $logData = [
                    'user_id' => getCurrentUserId(),
                    'action' => 'delete_user',
                    'table_name' => 'users',
                    'record_id' => $id,
                    'old_values' => $user,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                ];
                $this->activityLogModel->logActivity($logData);

                if ($this->userModel->deleteUser($id)) {
                    flash('user_message', 'User deleted successfully', 'alert alert-success');
                } else {
                    flash('user_message', 'Failed to delete user', 'alert alert-danger');
                }
            } else {
                flash('user_message', 'User not found', 'alert alert-danger');
            }
        }

        redirect('usercontroller/index');
    }


    // Change user password
    public function changePassword($id)
    {
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            flash('user_message', 'User not found', 'alert alert-danger');
            redirect('usercontroller/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
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
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->userModel->updatePassword($data)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'change_user_password',
                        'table_name' => 'users',
                        'record_id' => $id,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('user_message', 'Password changed successfully', 'alert alert-success');
                    redirect('usercontroller/index');
                } else {
                    die('Something went wrong');
                }
            } else {
                $data['username'] = $user->username;
                $this->view('users/createUser', $data);
            }
        } else {
            redirect('usercontroller/index');
        }
    }

    // View user profile
    public function profile()
    {
        $userId = getCurrentUserId();
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            flash('login_message', 'User not found', 'alert alert-danger');
            redirect('auth/login');
        }

        $data = [
            'user' => $user
        ];

        $this->view('users/profile', $data);
    }

    // Update own profile
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $userId = getCurrentUserId();
            $user = $this->userModel->getUserById($userId);

            if (!$user) {
                flash('login_message', 'User not found', 'alert alert-danger');
                redirect('auth/login');
                return;
            }

            $data = [
                'id' => $userId,
                'email' => trim($_POST['email']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'phone' => trim($_POST['phone']),
                'email_err' => '',
                'first_name_err' => '',
                'last_name_err' => ''
            ];

            // Validate
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

            // Check for errors
            if (empty($data['email_err']) && empty($data['first_name_err']) && empty($data['last_name_err'])) {
                if ($this->userModel->updateUser($data)) {
                    // Update session variables
                    $_SESSION['user_email'] = $data['email'];
                    $_SESSION['user_first_name'] = $data['first_name'];
                    $_SESSION['user_last_name'] = $data['last_name'];

                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'update_profile',
                        'table_name' => 'users',
                        'record_id' => $userId,
                        'new_values' => $data,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('profile_message', 'Profile updated successfully', 'alert alert-success');
                    redirect('usercontroller/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                $data['user'] = $user;
                $this->view('users/profile', $data);
            }
        } else {
            redirect('usercontroller/profile');
        }
    }

    // Change own password
    public function changeOwnPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $userId = getCurrentUserId();

            $data = [
                'id' => $userId,
                'current_password' => trim($_POST['current_password']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'current_password_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            $user = $this->userModel->getUserById($userId);

            // Validate current password
            if (empty($data['current_password'])) {
                $data['current_password_err'] = 'Please enter current password';
            } elseif (!password_verify($data['current_password'], $user->password)) {
                $data['current_password_err'] = 'Current password is incorrect';
            }

            // Validate new password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter new password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm new password';
            } elseif ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }

            // Check for errors
            if (empty($data['current_password_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->userModel->updatePassword($data)) {
                    // Log activity
                    $logData = [
                        'user_id' => getCurrentUserId(),
                        'action' => 'change_own_password',
                        'table_name' => 'users',
                        'record_id' => $userId,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT']
                    ];
                    $this->activityLogModel->logActivity($logData);

                    flash('profile_message', 'Password changed successfully', 'alert alert-success');
                    redirect('usercontroller/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                $data['user'] = $user;
                $this->view('users/profile', $data);
            }
        } else {
            redirect('usercontroller/profile');
        }
    }

}
