<?php
include("../../../system/auth.php");
include("../../../system/database.php");

// Get booking_id from URL
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id > 0) {
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Get booking details
        $booking_query = "SELECT room_id, status FROM bookings WHERE id = $booking_id";
        $booking_result = mysqli_query($conn, $booking_query);
        
        if ($booking_result && mysqli_num_rows($booking_result) > 0) {
            $booking = mysqli_fetch_assoc($booking_result);
            $room_id = $booking['room_id'];
            
            // Update booking status to checked_in
            $update_booking = "UPDATE bookings SET status = 'checked_in' WHERE id = $booking_id";
            mysqli_query($conn, $update_booking);
            
            // Update room status to occupied (status = 1)
            $update_room = "UPDATE rooms SET status = 1 WHERE id = $room_id";
            mysqli_query($conn, $update_room);
            
            mysqli_commit($conn);
            
            // Redirect back with success message
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?checkin=success");
            exit();
        } else {
            throw new Exception("Booking not found");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?checkin=error");
        exit();
    }
} else {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?checkin=error");
    exit();
}
?>