<?php
include("../../system/auth.php");
include("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");
requireLogin();
requireRole("receptionist"); // or admin if needed

/* ---------- FETCH ROOM TYPES ---------- */
$room_type = $conn->query("SELECT * FROM room_types");

/* ---------- FETCH AVAILABLE ROOMS ---------- */
$availableRooms = $conn->query("SELECT * FROM rooms WHERE availability = 0");

/* ---------- GET ROOM ID ---------- */
$roomId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* ---------- FETCH SELECTED ROOM ---------- */
$room = null;
if ($roomId > 0) {
    $sql = "
        SELECT 
            r.id,
            r.room_number,
            r.type_id,
            rt.name AS room_type,
            rt.price
        FROM rooms r
        JOIN room_types rt ON r.type_id = rt.id
        WHERE r.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
}

/* ---------- FORM SUBMIT ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname          = $_POST["fname"];
    $lname          = $_POST["lname"];
    $cus_number     = $_POST["cus_number"];
    $cus_verify     = $_POST["cus_verification"];
    $email          = $_POST["email"];
    $address        = $_POST["address"];
    $room_id        = (int)$_POST["room_id"];
    $check_in_date  = $_POST["check_in_date"];
    $check_out_date = $_POST["check_out_date"];

    /* Insert customer */
    $conn->query("
        INSERT INTO customers (fname,lname,phone,email,`address`,id_number)
        VALUES ('$fname','$lname','$cus_number','$email','$address','$cus_verify')
    ");

    $customer_id = $conn->insert_id;

    /* Insert booking */
    $stmt = $conn->prepare("
        INSERT INTO bookings (customer_id, room_id, checkin_date, checkout_date)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiss", $customer_id, $room_id, $check_in_date, $check_out_date);

    if ($stmt->execute()) {

        /* Update room availability */
        $update = $conn->prepare("UPDATE rooms SET availability = 1 WHERE id = ?");
        $update->bind_param("i", $room_id);
        $update->execute();

        $success = "Room successfully booked!";
    } else {
        $error = "Booking failed: " . $stmt->error;
    }
}
$username = $_SESSION['user']['username'];

/* ---------- PAGE LAYOUT ---------- */
loadHeader("Book Room");
sidebar_recep($username);

echo "<div class='main'>";

if (!empty($success)) {
    echo "<div class='alert alert-success'>$success</div>";
}
if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}

/* ---------- FORM UI ---------- */
booking_form_tr($availableRooms, $room_type, $room);

echo "</div>";

loadFooter();
?>
