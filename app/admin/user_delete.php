<?php
require_once("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");
$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id='$id'";

if ($conn->query($sql)) {
  header("location: users.php");
} else {
  echo "Error deleting record: ";
}
?>