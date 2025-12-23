<?php
include("../../system/auth.php");
include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");

requireLogin();
requireRole("admin");

/* Fetch only staff (receptionists) */
$query = "
    SELECT id, username, role 
    FROM users 
    WHERE role='receptionist' 
    ORDER BY id DESC
";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
$username = $_SESSION['user']['username'];

/* ---------- LAYOUT ---------- */
loadHeader("Staff Section");
sidebar($username);

echo "<div class='main'>";

/* ---------- PAGE CONTENT ---------- */
users_table($data);

echo "</div>";

loadFooter();
?>
