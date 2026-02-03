<?php
require_once("../../system/database.php");
require_once("../../system/htmlload.php");
$id = $_GET['id'];
$sql = "DELETE FROM rooms WHERE id='$id'";

if ($conn->query($sql)) {
  header("location: room.php");
} else {
  echo "Error deleting record: ";
}
?>