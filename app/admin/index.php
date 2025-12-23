<?php
require_once "../../system/auth.php";
requireLogin();
requireRole("admin");

header("Location: dashboard.php");
exit;
?>
