<?php
$servername="localhost";
$username="root";
$password="";
$database="hms";

$conn=new mysqli($servername,$username,$password,$database);
if($conn->connect_error)
    {
        die ("Connection Failed");
    } 
?>