<?php
include("../../system/auth.php"); 
include("../../system/database.php");
require_once __DIR__ . "/../../system/htmlload.php";
require_once("functions.php");
requireLogin();
requireRole("receptionist");

$query = "
    SELECT rooms.*, room_types.name 
    FROM rooms 
    INNER JOIN room_types ON rooms.type_id = room_types.id
";

$result = $conn->query($query);
$username = $_SESSION['user']['username'];

/* ---------- LAYOUT ---------- */
loadHeader("Manage Rooms");
sidebar_recep($username);

echo "<div class='main'>";

/* ---------- PAGE CONTENT ---------- */
rooms_table_recep($result);

echo "</div>";

loadFooter();
?>
