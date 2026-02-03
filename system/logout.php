<?php
/**
 * Logout Handler
 * Logs out the user and redirects to homepage
 */

require_once __DIR__ . '/auth.php';

// Log out the user
logoutUser();

// Redirect to homepage
header("Location: /hms/index.php");
exit();
?>