<?php
include("../../system/auth.php");
include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");

requireLogin();
requireRole("admin");

/* ---------- VALIDATE ID ---------- */
if (!isset($_GET['id'])) {
    die("User not found!");
}

$id = intval($_GET['id']);

/* ---------- FETCH USER ---------- */
$result = $conn->query("SELECT id, username, role FROM users WHERE id='$id'");

if ($result->num_rows === 0) {
    die("User not found!");
}

$user = $result->fetch_assoc();

/* ---------- FORM SUBMIT ---------- */
if (isset($_POST['update_user'])) {

    $username = $_POST['username'];
    $role     = $_POST['role'];

    // Password update only if filled
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "
            UPDATE users 
            SET username='$username', role='$role', password='$password'
            WHERE id='$id'
        ";
    } else {
        $sql = "
            UPDATE users 
            SET username='$username', role='$role'
            WHERE id='$id'
        ";
    }

    if ($conn->query($sql)) {
        header("Location: users.php?updated=1");
        exit;
    } else {
        $error = "Update failed!";
    }
}
$username=$_SESSION['user']['username'];
/* ---------- LAYOUT ---------- */
loadHeader("Edit Staff");
sidebar($username);

echo "<div class='main'>";

if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}

/* ---------- PAGE CONTENT ---------- */
user_edit_form($user);

echo "</div>";

loadFooter();
?>
