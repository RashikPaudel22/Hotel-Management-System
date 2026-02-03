<?php
/**
 * API Authentication Handler
 * Handles AJAX login and registration requests
 * Returns JSON response
 */

// Prevent direct output of errors to keep JSON clean

error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/database.php';

// Helper to send JSON response
function sendResponse($success, $message, $redirect = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirect' => $redirect
    ]);
    exit();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Get JSON input if sent as raw body, otherwise use $_POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$action = $input['action'] ?? '';

if ($action === 'login') {
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';

    if (empty($username) || empty($password)) {
        sendResponse(false, 'Please fill in all fields');
    }

    $stmt = $conn->prepare("SELECT id, username, password, role, full_name, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check password
        $passwordMatch = false;
        if (password_get_info($user['password'])['algo'] !== null) {
            $passwordMatch = password_verify($password, $user['password']);
        } else {
            $passwordMatch = ($password === $user['password']);
        }

        if ($passwordMatch) {
            loginUser($user);
            
            // Determine redirect URL based on role
            $role = strtolower(trim($user['role']));
            $redirect = '/hms/app/customer/dashboard.php';
            
            if ($role === 'admin') {
                $redirect = '/hms/app/admin/dashboard.php';
            } elseif ($role === 'receptionist') {
                $redirect = '/hms/app/reception/dashboard.php';
            }
            
            sendResponse(true, 'Login successful', $redirect);
        } else {
            sendResponse(false, 'Invalid username or password');
        }
    } else {
        sendResponse(false, 'Invalid username or password');
    }
    $stmt->close();

} elseif ($action === 'register') {
    $username = trim($input['username'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    $confirm_password = $input['confirm_password'] ?? '';
    $full_name = trim($input['full_name'] ?? '');
    $phone = trim($input['phone'] ?? '');

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        sendResponse(false, 'All required fields must be filled');
    }
    if (strlen($username) < 3) {
        sendResponse(false, 'Username must be at least 3 characters');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, 'Invalid email format');
    }
    if (strlen($password) < 6) {
        sendResponse(false, 'Password must be at least 6 characters');
    }
    if ($password !== $confirm_password) {
        sendResponse(false, 'Passwords do not match');
    }

    // Check availability
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        sendResponse(false, 'Username or Email already exists');
    }

    // Insert
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'customer';

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $full_name, $phone, $role);

    if ($stmt->execute()) {
        $userId = $conn->insert_id;
        
        $userData = [
            'id' => $userId,
            'username' => $username,
            'role' => $role,
            'full_name' => $full_name,
            'email' => $email
        ];
        
        loginUser($userData);
        sendResponse(true, 'Registration successful', '/hms/app/customer/dashboard.php');
    } else {
        sendResponse(false, 'Registration failed. Please try again.');
    }

} else {
    sendResponse(false, 'Invalid action');
}
?>