<?php
include("../../system/auth.php"); 
include("../../system/database.php");
require_once __DIR__ . "/../../system/htmlload.php";
require_once("functions.php");
$query = "SELECT rooms.*, room_types.name 
          FROM rooms 
          INNER JOIN room_types ON rooms.type_id = room_types.id
          WHERE rooms.status = 'booked'";

$result = $conn->query($query);

loadHeader("Rooms List");

rooms_table_reception_booked($result);

loadFooter();
?>
