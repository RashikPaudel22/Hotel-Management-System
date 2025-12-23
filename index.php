<!-- Overlay -->
<style>
.overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 9999;
}

.modal-box {
  width: 420px;
  max-width: 90%;
  background: #fff;
  margin: 5% auto;
  border-radius: 12px;
  overflow: hidden;
  position: relative;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 22px;
  background: none;
  border: none;
  cursor: pointer;
  z-index: 1;
  color: #666;
}

.close-btn:hover {
  color: #000;
}
</style>

<div id="loginOverlay" class="overlay">
  <div class="modal-box">
    <button class="close-btn" onclick="closeLogin()">×</button>
    <div id="loginContainer"></div>
  </div>
</div>

<script>
// Global function to open login - can be called from anywhere
window.openLogin = function() {
  document.getElementById("loginOverlay").style.display = "block";
  loadLoginForm();
}

function closeLogin() {
  document.getElementById("loginOverlay").style.display = "none";
}

// Check if user is logged in
function checkLoginStatus() {
  fetch('/hms/system/check_login.php')
    .then(response => response.json())
    .then(data => {
      if (data.loggedIn && data.role === 'customer') {
        // Redirect to customer dashboard
        window.location.href = '/hms/app/customer/dashboard.php';
      } else if (!data.loggedIn) {
        // Show login popup only on initial page load
        openLogin();
      }
    });
}

function loadLoginForm() {
  fetch('/hms/system/login_form_ajax.php')
    .then(response => response.text())
    .then(html => {
      document.getElementById('loginContainer').innerHTML = html;
      attachLoginHandler();
    });
}

function attachLoginHandler() {
  const loginForm = document.querySelector('#loginContainer form');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      formData.append('ajax_login', '1');
      
      fetch('/hms/system/login.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Full page redirect based on role
          window.location.href = data.redirect;
        } else {
          // Show error message
          const errorDiv = document.getElementById('loginError');
          if (errorDiv) {
            errorDiv.innerHTML = '<p style="color:red;text-align:center;background:#fee;padding:12px;border-radius:6px;margin:0 0 15px 0;border-left:4px solid #c33;">' + data.message + '</p>';
          } else {
            alert(data.message);
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during login');
      });
    });
  }
}

// Close overlay when clicking outside the modal
document.addEventListener('click', function(e) {
  const overlay = document.getElementById('loginOverlay');
  if (e.target === overlay) {
    closeLogin();
  }
});

document.addEventListener("DOMContentLoaded", function () {
  checkLoginStatus();
});
</script>

<?php
require_once __DIR__ . "/system/htmlload.php";

session_start();

// If already logged in as customer, redirect
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'customer') {
    header('Location: /hms/app/customer/dashboard.php');
    exit();
}

renderHeader();
renderTopBar();
renderHeroSection();
renderAboutSection();
renderRoomsSection();
renderServicesSection();
renderTestimonialsSection();
renderFooter();
?>