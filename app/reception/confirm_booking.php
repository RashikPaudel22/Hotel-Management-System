<?php
include("../../system/auth.php");
include("../../system/database.php");

// Get booking_id from URL
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id > 0) {
    // Update booking status to confirmed
    $update_query = "UPDATE bookings SET status = 'confirmed' WHERE id = $booking_id AND status = 'pending'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?confirm=success");
        exit();
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?confirm=error");
        exit();
    }
} else {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?confirm=error");
    exit();
}
?>