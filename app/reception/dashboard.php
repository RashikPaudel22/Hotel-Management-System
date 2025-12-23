<?php
require_once "../../system/auth.php";
requireLogin();
requireRole("receptionist");

include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");

// Fetch counts
$totalRooms = $conn->query("SELECT COUNT(*) AS total FROM rooms")->fetch_assoc()['total'];
//$totalReports = $conn->query("SELECT COUNT(*) AS total FROM reports")->fetch_assoc()['total'];
$totalAvailable = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability='0'")->fetch_assoc()['total'];
$totalBooked = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability='1'")->fetch_assoc()['total'];
$totalCheckedIn = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE status='1'")->fetch_assoc()['total'];
//$totalCheckedIN = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
//$totalpendingpayment = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

$loadData = [
    "rooms" => $totalRooms,
    "available" => $totalAvailable,
    "booked" => $totalBooked,
    "checked_in"=> $totalCheckedIn,
];
$username = $_SESSION['user']['username'];
loadHeader("Reception Dashboard");

// SHOW SIDEBAR HERE
sidebar_recep($username);

receptionDashboard($loadData);

loadFooter();
?>
