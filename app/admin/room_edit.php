<?php
include("../../system/auth.php"); 
include("../../system/database.php");
require_once __DIR__ . "/../../system/htmlload.php";
require_once("functions.php");

requireLogin();
requireRole("admin");

/* ---------- VALIDATE ID ---------- */
if (!isset($_GET['id'])) {
    die("Room not found!");
}

$id = intval($_GET['id']);

/* ---------- FETCH ROOM ---------- */
$result = $conn->query("SELECT * FROM rooms WHERE id='$id'");
if ($result->num_rows === 0) {
    die("Room not found!");
}
$room = $result->fetch_assoc();

/* ---------- FETCH ROOM TYPES ---------- */
$typesResult = $conn->query("SELECT * FROM room_types");

/* ---------- FORM SUBMIT ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $room_number = $_POST["room_number"];
    $room_type   = $_POST["room_type"];
    $status      = $_POST["status"];

    $sql = "
        UPDATE rooms 
        SET room_number='$room_number',
            type_id='$room_type',
            status='$status'
        WHERE id='$id'
    ";

    if ($conn->query($sql)) {
        header("Location: room.php?updated=1");
        exit;
    } else {
        $error = "Error updating room!";
    }
}
$username=$_SESSION['user']['username'];

/* ---------- LAYOUT ---------- */
loadHeader("Edit Room");
sidebar($username);

echo "<div class='main'>";

if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}

/* ---------- PAGE CONTENT ---------- */
edit_room_form($room, $typesResult);

echo "</div>";

loadFooter();
?>
