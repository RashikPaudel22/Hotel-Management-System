<?php
include("../../../system/auth.php");
include("../../../system/database.php");
require_once("../../../system/htmlload.php");
require_once("../functions.php");
requireLogin();   // optional but recommended
requireRole("admin"); // or reception if needed

$room_type = $conn->query("SELECT * FROM room_types");
$availableRooms = $conn->query("SELECT * FROM rooms WHERE status='0'");
$username = $_SESSION['user']['username'];
/* ---------- LAYOUT START ---------- */
loadHeader("Create Reservation");
sidebar($username);

echo "<div class='main'>";

/* ---------- PAGE CONTENT ---------- */
booking_form($availableRooms, $room_type);

/* ---------- FORM HANDLER ---------- */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $cus_number = $_POST["cus_number"];
    $cus_verify = $_POST["cus_verification"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $room = $_POST["room"];
    $check_in_date = $_POST["check_in_date"];
    $check_out_date = $_POST["check_out_date"];

    $conn->query("
        INSERT INTO customers (fname,lname,phone,email,`address`,id_number)
        VALUES ('$fname','$lname','$cus_number','$email','$address','$cus_verify')
    ");

    $customer_id = $conn->insert_id;

    if ($conn->query("
        INSERT INTO bookings (customer_id, room_id, checkin_date, checkout_date)
        VALUES ('$customer_id', '$room', '$check_in_date', '$check_out_date')
    ")) {

        $conn->query("UPDATE rooms SET availability='1' WHERE id='$room'");

        echo "<div class='alert alert-success mt-3'>Room Successfully Booked!</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error Booking Room!</div>";
    }
}

echo "</div>"; // END .main
loadFooter();
?>
