<?php
include '../../../system/database.php';
require_once("../functions.php");
if (isset($_POST['room_type_id'])) {

    $room_type_id = (int) $_POST['room_type_id']; // sanitize input

    $stmt = $conn->prepare("
        SELECT id, room_number 
        FROM rooms 
        WHERE type_id = ?
        AND availability = 0
    ");

    $stmt->bind_param("i", $room_type_id);
    $stmt->execute();

    $result = $stmt->get_result();

    echo '<option value="">Select Room</option>';

    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['room_number'].'</option>';
    }
}
?>
