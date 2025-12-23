<?php
include("../../system/auth.php");
include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");
requireLogin();   // optional but recommended
requireRole("customer"); // or reception if needed

$user_id = $_SESSION['user']['id'];

if (isset($_POST['submit_review'])) {

    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO reviews (user_id, rating, comment)
            VALUES ('$user_id', '$rating', '$comment')";

    if ($conn->query($sql)) {
        echo "<p class='success'>Review submitted successfully</p>";
    } else {
        echo "<p class='error'>Failed to submit review</p>";
    }
}
$username=$_SESSION['user']['username'];

/* ---------- LAYOUT ---------- */
loadHeader("Customer Review");
sidebar_customer($username);

echo "<div class='main'>";

if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}

/* ---------- PAGE CONTENT ---------- */
customer_review_form();

echo "</div>";

loadFooter();
?>
