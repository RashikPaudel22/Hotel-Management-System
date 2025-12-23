<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location:../../system/login.php");
        exit; // stop execution after redirect
    }
}

function requireRole($role) {
    if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== $role) {
        die("Access denied");
    }
}
?>
