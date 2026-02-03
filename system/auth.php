<?php
/**
 * Authentication Helper Functions
 * Handles user authentication and authorization
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
}

/**
 * Require user to be logged in, redirect to login if not
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location: /hms/system/login.php");
        exit();
    }
}

/**
 * Require specific role, deny access if user doesn't have it
 * @param string $role - Required role (admin, customer, receptionist)
 */
function requireRole($role) {
    requireLogin(); // First ensure they're logged in
    
    if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== $role) {
        http_response_code(403);
        die("Access denied. You don't have permission to access this page.");
    }
}

/**
 * Get current user data
 * @return array|null
 */
function getCurrentUser() {
    return isLoggedIn() ? $_SESSION['user'] : null;
}

/**
 * Get current user's role
 * @return string|null
 */
function getUserRole() {
    return isLoggedIn() ? $_SESSION['user']['role'] : null;
}

/**
 * Get current user's ID
 * @return int|null
 */
function getUserId() {
    return isLoggedIn() ? $_SESSION['user']['id'] : null;
}

/**
 * Log in a user
 * @param array $userData - User data array with id, username, role
 */
function loginUser($userData) {
    $_SESSION['user'] = [
        'id' => $userData['id'],
        'username' => $userData['username'],
        'role' => $userData['role'],
        'full_name' => $userData['full_name'] ?? null,
        'email' => $userData['email'] ?? null
    ];
    
    // Regenerate session ID for security
    session_regenerate_id(true);
}

/**
 * Log out current user
 */
function logoutUser() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

/**
 * Redirect based on user role
 */
function redirectByRole() {
    if (!isLoggedIn()) {
        header("Location: /hms/system/login.php");
        exit();
    }
    
    $role = getUserRole();
    
    switch ($role) {
        case 'admin':
            header("Location: /hms/app/admin/dashboard.php");
            break;
        case 'receptionist':
            header("Location: /hms/app/reception/dashboard.php");
            break;
        case 'customer':
        default:
            header("Location: /hms/app/customer/dashboard.php");
            break;
    }
    exit();
}

/**
 * Check if user has specific role
 * @param string $role
 * @return bool
 */
function hasRole($role) {
    return isLoggedIn() && getUserRole() === $role;
}
?>