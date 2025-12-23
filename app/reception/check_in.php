<?php
include("../../system/auth.php");
include("../../system/database.php");
require_once("../../system/htmlload.php");

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM rooms WHERE id='$id'");
$room = $result->fetch_assoc();


    $sql = "UPDATE rooms 
            SET status='1'
            WHERE id='$id'";

    if ($conn->query($sql)) {
        header("location:rooms.php");
    } else {
        echo "Error updating record";
    }

// SEND variables to HTML loader

?>