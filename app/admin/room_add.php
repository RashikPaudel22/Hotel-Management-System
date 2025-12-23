<?php
include("../../system/auth.php"); 
include("../../system/database.php");
require_once __DIR__ . "/../../system/htmlload.php";
require_once("functions.php");
requireLogin();
requireRole("admin");

/* ---------- FETCH ROOM TYPES ---------- */
$typesResult = $conn->query("SELECT * FROM room_types");
$room_type = $conn->query("SELECT * FROM room_types");
/* ---------- FORM SUBMIT ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $room_number = $_POST["room_number"];
    $room_type   = $_POST["room_type"];
    $status      = $_POST["status"];

    $sql = "
        INSERT INTO rooms (room_number, type_id, status)
        VALUES ('$room_number', '$room_type', '$status')
    ";

    if ($conn->query($sql)) {
        $success = "Room successfully added!";
    } else {
        $error = "Error adding room!";
    }
}
$username=$_SESSION['user']['username'];

/* ---------- LAYOUT ---------- */
loadHeader("Add Room");
sidebar($username);

echo "<div class='main'>";

if (!empty($success)) {
    echo "<div class='alert alert-success'>$success</div>";
}
if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}

/* ---------- PAGE CONTENT ---------- */
add_room_form($typesResult,$room_type);

echo "</div>";

loadFooter();
?>