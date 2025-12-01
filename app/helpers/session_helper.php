<?php

session_start();

function flash($name = '', $message = '', $class = 'alert alert-info', $dismissible = false)
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
            $_SESSION[$name . '_dismissible'] = $dismissible;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $dismissible = isset($_SESSION[$name . '_dismissible']) ? $_SESSION[$name . '_dismissible'] : false;

            $dismissBtn = $dismissible ?
                '<button class="dismiss-btn" onclick="this.parentElement.style.display=\'none\'">&times;</button>' :
                '';

            echo '<div class="flash-message ' . $_SESSION[$name . '_class'] . ' ' .
                ($dismissible ? 'dismissible' : '') . '">' .
                $_SESSION[$name] . $dismissBtn .
                '</div>';

            $_SESSION[$name] = '';
            $_SESSION[$name . '_class'] = '';
            $_SESSION[$name . '_dismissible'] = '';
        }
    }
}

// Check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && $_SESSION['logged_in'] === true;
}

// Get current user ID
function getCurrentUserId()
{
    return $_SESSION['user_id'] ?? 0;
}

// Get current user role
function getCurrentUserRole()
{
    return $_SESSION['user_role'] ?? 'admin';
}

// Get current user name
function getCurrentUserName()
{
    return ($_SESSION['user_first_name'] ?? '') . ' ' . ($_SESSION['user_last_name'] ?? '');
}

function getRoleHierarchyMap()
{
    return [
        'guest' => 0,
        'external' => 1,
        'resident' => 2,
        'maintenance' => 3,
        'security' => 4,
        'admin' => 5,
        'superadmin' => 6
    ];
}

// Check if user has specific role or higher
function hasRole($requiredRole)
{
    return isLoggedIn();
}

// Require user to be logged in
function requireLogin()
{
    if (!isLoggedIn()) {
        flash('login_message', 'Please login to access this page', 'alert alert-warning');
        redirect('auth/login');
    }
}

// Require specific role or higher
function requireRole($requiredRole)
{
    requireLogin();
}
