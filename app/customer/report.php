<?php
include("../../system/auth.php");
include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");
requireLogin();   // optional but recommended
requireRole("customer"); // or reception if needed

$user_id = $_SESSION['user']['id'];

if (isset($_POST['submit_report'])) {

    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $sql = "INSERT INTO reports (user_id, `subject`, `message`)
            VALUES ('$user_id', '$subject', '$message')";

    if ($conn->query($sql)) {
        echo "<p class='success'>Report sent successfully</p>";
    } else {
        echo "<p class='error'>Failed to send report</p>";
    }
}
$username=$_SESSION['user']['username'];

/* ---------- LAYOUT ---------- */
loadHeader("Customer Report");
sidebar_customer($username);

echo "<div class='main'>";

if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}

/* ---------- PAGE CONTENT ---------- */
customer_report_form();

echo "</div>";

loadFooter();
?>
