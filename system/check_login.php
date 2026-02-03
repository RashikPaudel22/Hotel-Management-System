<?php
/**
 * Check Login Status (AJAX Endpoint)
 * Returns JSON response with user's login status and role
 */

require_once __DIR__ . '/auth.php';

header('Content-Type: application/json');

$response = [
    'loggedIn' => isLoggedIn(),
    'role' => getUserRole(),
    'userId' => getUserId(),
    'username' => isLoggedIn() ? $_SESSION['user']['username'] : null
];

echo json_encode($response);
?>