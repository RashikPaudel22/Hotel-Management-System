<?php
session_start();
header('Content-Type: application/json');

$response = [
    'loggedIn' => isset($_SESSION['user']),
    'role' => isset($_SESSION['user']) ? $_SESSION['user']['role'] : null
];

echo json_encode($response);
?>