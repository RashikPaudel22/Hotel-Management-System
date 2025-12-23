<?php
require_once "../../system/auth.php";
requireLogin();
requireRole("customer");

require_once("../../system/database.php");
require_once("../../system/htmlload.php");
require_once("functions.php");
$username = $_SESSION['user']['username'];
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
.overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 9999;
}

.modal-box {
  width: 70%;
  max-height: 90vh;
  background: #fff;
  margin: 3% auto;
  border-radius: 8px;
  overflow: hidden;
  position: relative;
}

.booking-frame {
  width: 100%;
  height: 80vh;
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 22px;
  background: none;
  border: none;
  cursor: pointer;
}
</style>

<div id="bookingOverlay" class="overlay">
  <div class="modal-box">
    <button class="close-btn" onclick="closeBooking()">×</button>

    <iframe 
      src="/hms/app/customer/create_booking_popup.php"
      frameborder="0"
      class="booking-frame">
    </iframe>
  </div>
</div>

<script>
function openBooking() {
  document.getElementById("bookingOverlay").style.display = "block";
}

function closeBooking() {
  document.getElementById("bookingOverlay").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  openBooking();
});
</script>

<body>

<?php
renderHeader();
render_cus_TopBar($username);
renderHeroSection();
renderAboutSection();
renderRoomsSection_logged_in();
renderServicesSection();
renderTestimonialsSection();
renderFooter();
?>
</body>
</html>