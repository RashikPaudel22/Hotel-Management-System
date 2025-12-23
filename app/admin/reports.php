<?php
include("../../system/auth.php"); 
include("../../system/database.php");
require_once __DIR__ . "/../../system/htmlload.php";
require_once("functions.php");
requireLogin();
requireRole("admin");

// fetch all reports with customer info
$sql = "
    SELECT 
        r.id,
        r.user_id,
        r.subject,
        r.message,
        r.status,
        r.created_at,
        c.fname,
        c.lname,
        c.email
    FROM reports r
    JOIN customers c ON r.user_id = c.id
    ORDER BY r.created_at DESC
";
$result = mysqli_query($conn, $sql);

$reports = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reports[] = $row;
}
$username = $_SESSION['user']['username'];

/* ---------- LAYOUT ---------- */
loadHeader("Reports");
sidebar($username);
// call HTML function

echo "<div class='main'>";

if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}
admin_reports_page($reports);
echo "</div>";

loadFooter();
?>
