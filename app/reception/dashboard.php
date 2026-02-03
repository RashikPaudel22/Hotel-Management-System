<?php
/**
 * Admin Dashboard
 * Main overview page for administrators
 */

// Authentication
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('receptionist');

// Dependencies
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

// Fetch dashboard statistics
$totalRooms = $conn->query("SELECT COUNT(*) AS total FROM rooms")->fetch_assoc()['total'];
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='receptionist'")->fetch_assoc()['total'];
$totalAvailable = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability='0'")->fetch_assoc()['total'];
$totalBooked = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability='1'")->fetch_assoc()['total'];
$totalCheckedIn = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE status='1'")->fetch_assoc()['total'];

// Build statistics array
$stats = [
    'rooms' => $totalRooms,
    'bookings' => $totalBookings,
    'available' => $totalAvailable,
    'booked' => $totalBooked,
    'checked_in' => $totalCheckedIn
];

// Load page
loadHeader('Reception Dashboard');

// Include sidebar navigation
include __DIR__ . '/includes/sidebar.php';

// Include dashboard content
include __DIR__ . '/components/dashboard_stats.php';

// Load footer
loadFooter();
?>