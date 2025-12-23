<?php
include("../../system/auth.php");
include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");

requireLogin();
requireRole("admin");

/* ---------- FORM SUBMIT ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if ($conn->query("
        INSERT INTO users (username, password, role)
        VALUES ('$username', '$password', '$role')
    ")) {
        header("Location: users.php?success=1");
        exit;
    } else {
        $error = "Failed to add staff!";
    }
}
$username=$_SESSION['user']['username'];
/* ---------- LAYOUT ---------- */
loadHeader("Add Staff");
sidebar($username);

echo "<div class='main'>";

if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}

/* ---------- PAGE CONTENT ---------- */
user_add();

echo "</div>";

loadFooter();
?>