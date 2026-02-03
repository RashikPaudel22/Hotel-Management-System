<?php
require_once "../../system/auth.php";
require_once "../../system/database.php";
require_once "../../system/htmlload.php";

requireLogin();
requireRole("customer");

// Get user data
$username = $_SESSION['user']['username'];
?>

<?php loadHeader("Customer Dashboard"); ?>

<?php renderTopBar($username); ?>

<?php
renderHeroSection();
renderAboutSection();
renderRoomsSection_logged_in();
renderServicesSection();
renderTestimonialsSection();
?>

<?php loadFooter(); ?>