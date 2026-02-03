<?php
require_once 'database.php';

header('Content-Type: application/json');

if (isset($_GET['type_id'])) {
    $type_id = mysqli_real_escape_string($conn, $_GET['type_id']);
    
    // Get check-in and check-out dates if provided
    $checkin_date = isset($_GET['checkin']) ? mysqli_real_escape_string($conn, $_GET['checkin']) : '';
    $checkout_date = isset($_GET['checkout']) ? mysqli_real_escape_string($conn, $_GET['checkout']) : '';
    
    if ($checkin_date && $checkout_date) {
        // Fetch rooms that are NOT booked during the selected date range
        $query = "SELECT r.id, r.room_number, rt.name as type_name, rt.price 
                  FROM rooms r 
                  JOIN room_types rt ON r.type_id = rt.id 
                  WHERE r.type_id = $type_id 
                  AND r.id NOT IN (
                      SELECT room_id 
                      FROM bookings 
                      WHERE status IN ('pending', 'confirmed', 'checked_in')
                      AND (
                          (checkin_date <= '$checkin_date' AND checkout_date > '$checkin_date')
                          OR (checkin_date < '$checkout_date' AND checkout_date >= '$checkout_date')
                          OR (checkin_date >= '$checkin_date' AND checkout_date <= '$checkout_date')
                      )
                  )
                  ORDER BY r.room_number";
    } else {
        // If no dates provided, show all rooms of this type
        // (You might want to show which ones have upcoming bookings)
        $query = "SELECT r.id, r.room_number, rt.name as type_name, rt.price 
                  FROM rooms r 
                  JOIN room_types rt ON r.type_id = rt.id 
                  WHERE r.type_id = $type_id 
                  ORDER BY r.room_number";
    }
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $rooms = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'rooms' => $rooms,
            'total_rooms' => count($rooms)
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching rooms: ' . mysqli_error($conn)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Room type ID is required'
    ]);
}
?>