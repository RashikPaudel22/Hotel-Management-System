<?php
require_once "../../system/auth.php";
requireLogin();
requireRole("admin");

include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");

// Fetch counts
$totalRooms = $conn->query("SELECT COUNT(*) AS total FROM rooms")->fetch_assoc()['total'];
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='receptionist'")->fetch_assoc()['total'];
//$totalReports = $conn->query("SELECT COUNT(*) AS total FROM reports")->fetch_assoc()['total'];
$totalAvailable = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability='0'")->fetch_assoc()['total'];
$totalBooked = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability='1'")->fetch_assoc()['total'];
$totalCheckedIn = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE status='1'")->fetch_assoc()['total'];
//$totalCheckedIN = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
//$totalpendingpayment = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

$loadData = [
    "rooms" => $totalRooms,
    "bookings" => $totalBookings,
    "users" => $totalUsers,
    "available" => $totalAvailable,
    "booked" => $totalBooked,
    "checked_in"=> $totalCheckedIn,
];
$username = $_SESSION['user']['username'];
loadHeader("Admin Dashboard");

// SHOW SIDEBAR HERE
sidebar($username);

adminDashboard($loadData,);

loadFooter();
?>
