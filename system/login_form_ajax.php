<?php
require_once("htmlload.php");
?>
<style>
.login-wrapper {
  padding: 40px 30px;
  background: #fff;
}

.login-header {
  text-align: center;
  margin-bottom: 30px;
}

.login-header h2 {
  font-size: 28px;
  font-weight: 600;
  color: #1a1a2e;
  margin-bottom: 8px;
}

.login-header p {
  color: #6c757d;
  font-size: 14px;
}

.login-form-group {
  margin-bottom: 20px;
}

.login-form-group label {
  display: block;
  font-weight: 500;
  color: #2d3748;
  margin-bottom: 8px;
  font-size: 14px;
}

.login-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.login-input:focus {
  outline: none;
  border-color: #4361ee;
  box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.login-btn {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 10px;
}

.login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.login-btn:active {
  transform: translateY(0);
}

.divider {
  display: flex;
  align-items: center;
  text-align: center;
  margin: 25px 0;
  color: #94a3b8;
  font-size: 13px;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #e2e8f0;
}

.divider span {
  padding: 0 10px;
}

.create-account-btn {
  width: 100%;
  padding: 14px;
  background: #fff;
  color: #4361ee;
  border: 2px solid #4361ee;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.create-account-btn:hover {
  background: #4361ee;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.create-account-btn:active {
  transform: translateY(0);
}

#loginError {
  margin-bottom: 15px;
}

#loginError p {
  background: #fee;
  color: #c33;
  padding: 12px;
  border-radius: 6px;
  margin: 0;
  font-size: 14px;
  border-left: 4px solid #c33;
}

.password-toggle {
  position: relative;
}

.password-toggle-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #94a3b8;
  user-select: none;
}
</style>

<div id="loginError"></div>

<div class="login-wrapper">
  <div class="login-header">
    <h2>Welcome Back!</h2>
    <p>Please login to your account</p>
  </div>

  <form method="POST">
    <div class="login-form-group">
      <label for="username">Username</label>
      <input 
        type="text" 
        name="username" 
        id="username"
        class="login-input"
        placeholder="Enter your username"
        required
        autocomplete="username"
      >
    </div>

    <div class="login-form-group">
      <label for="password">Password</label>
      <div class="password-toggle">
        <input 
          type="password" 
          name="password" 
          id="password"
          class="login-input"
          placeholder="Enter your password"
          required
          autocomplete="current-password"
        >
        <span class="password-toggle-icon" onclick="togglePassword()">👁️</span>
      </div>
    </div>

    <button type="submit" name="login" class="login-btn">
      Log In
    </button>
  </form>

  <div class="divider">
    <span>OR</span>
  </div>

  <button type="button" class="create-account-btn" onclick="goToRegister()">
    Create New Account
  </button>
</div>

<script>
function togglePassword() {
  const passwordInput = document.getElementById('password');
  const icon = document.querySelector('.password-toggle-icon');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    icon.textContent = '🙈';
  } else {
    passwordInput.type = 'password';
    icon.textContent = '👁️';
  }
}

function goToRegister() {
  window.location.href = '/hms/system/register.php';
}
</script>