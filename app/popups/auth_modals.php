<!-- Auth Modals -->
<div id="auth-modal-overlay" class="auth-overlay">
    
    <!-- Login Modal -->
    <div id="login-modal" class="auth-modal">
        <button class="auth-close-btn" onclick="closeAuthModals()">&times;</button>
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Please login to your account</p>
        </div>
        
        <div id="login-error" class="auth-alert error" style="display:none;"></div>
        
        <form id="login-form" onsubmit="handleLogin(event)">
            <div class="auth-form-group">
                <label>Username</label>
                <input type="text" name="username" class="auth-input" required placeholder="Enter username">
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
            <button type="submit" class="auth-submit-btn">Log In</button>
        </form>
        
        <div class="auth-divider"><span>OR</span></div>
        <button type="button" class="auth-switch-btn" onclick="switchModal('register')">Create New Account</button>
    </div>

    <!-- Register Modal -->
    <div id="register-modal" class="auth-modal" style="display:none;">
        <button class="auth-close-btn" onclick="closeAuthModals()">&times;</button>
        <div class="auth-header">
            <h2>Create Account</h2>
            <p>Join us today</p>
        </div>
        
        <div id="register-error" class="auth-alert error" style="display:none;"></div>
        
        <form id="register-form" onsubmit="handleRegister(event)">
            <div class="auth-form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="auth-input" required placeholder="Full Name">
            </div>
            
            <div class="auth-form-row">
                <div class="auth-form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="auth-input" required placeholder="Username">
                </div>
                <div class="auth-form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" class="auth-input" placeholder="Phone">
                </div>
            </div>

            <div class="auth-form-group">
                <label>Email</label>
                <input type="email" name="email" class="auth-input" required placeholder="Email Address">
            </div>

            <div class="auth-form-row">
                <div class="auth-form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="auth-input" required placeholder="Password">
                </div>
                <div class="auth-form-group">
                    <label>Confirm</label>
                    <input type="password" name="confirm_password" class="auth-input" required placeholder="Confirm">
                </div>
            </div>

            <button type="submit" class="auth-submit-btn">Create Account</button>
        </form>
        
        <div class="auth-divider"><span>OR</span></div>
        <p class="auth-link-text">Already have an account? <a href="#" onclick="switchModal('login')">Login here</a></p>
    </div>

</div>

<style>
.auth-overlay {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    justify-content: center;
    align-items: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.auth-overlay.active {
    opacity: 1;
    visibility: visible;
}

.auth-modal {
    background: white;
    width: 90%;
    max-width: 450px;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    position: relative;
    transform: translateY(20px);
    transition: transform 0.3s ease;
    max-height: 90vh;
    overflow-y: auto;
}

.auth-overlay.active .auth-modal {
    transform: translateY(0);
}

.auth-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.auth-header {
    text-align: center;
    margin-bottom: 20px;
}

.auth-header h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 5px;
}

.auth-header p {
    color: #666;
    font-size: 14px;
}

.auth-form-group {
    margin-bottom: 15px;
}

.auth-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.auth-form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.auth-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}

.auth-input:focus {
    border-color: #667eea;
    outline: none;
}

.auth-submit-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
}

.auth-submit-btn:hover {
    opacity: 0.9;
}

.auth-switch-btn {
    width: 100%;
    padding: 10px;
    background: white;
    border: 1px solid #667eea;
    color: #667eea;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
}

.auth-switch-btn:hover {
    background: #f8f9fa;
}

.auth-divider {
    text-align: center;
    margin: 20px 0;
    position: relative;
}

.auth-divider:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 1px;
    background: #eee;
}

.auth-divider span {
    background: white;
    padding: 0 10px;
    position: relative;
    color: #999;
    font-size: 12px;
}

.auth-alert {
    padding: 10px;
    background-color: #fee;
    color: #c33;
    border-radius: 4px;
    margin-bottom: 15px;
    font-size: 14px;
    text-align: center;
}

.auth-link-text {
    text-align: center;
    font-size: 14px;
    color: #666;
}

.auth-link-text a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
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
    font-size: 18px;
}
.login-form-group {
    margin-bottom: 15px;
}

.login-form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.login-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}

.login-input:focus {
    border-color: #667eea;
    outline: none;
}
</style>

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
function openAuthModal(type = 'login') {
    document.getElementById('auth-modal-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
    switchModal(type);
}

function closeAuthModals() {
    document.getElementById('auth-modal-overlay').classList.remove('active');
    document.body.style.overflow = '';
}

function switchModal(type) {
    const loginModal = document.getElementById('login-modal');
    const registerModal = document.getElementById('register-modal');
    
    // Clear errors
    document.getElementById('login-error').style.display = 'none';
    document.getElementById('register-error').style.display = 'none';
    
    if (type === 'login') {
        loginModal.style.display = 'block';
        registerModal.style.display = 'none';
    } else {
        loginModal.style.display = 'none';
        registerModal.style.display = 'block';
    }
}

// Close when clicking overlay
document.getElementById('auth-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAuthModals();
    }
});

// Handle Login
async function handleLogin(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    data.action = 'login';
    
    const errorEl = document.getElementById('login-error');
    errorEl.style.display = 'none';
    
    try {
        const response = await fetch('/hms/system/api_auth.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            window.location.href = result.redirect || '/hms/app/customer/dashboard.php';
        } else {
            errorEl.textContent = result.message;
            errorEl.style.display = 'block';
        }
    } catch (err) {
        errorEl.textContent = 'An error occurred. Please try again.';
        errorEl.style.display = 'block';
    }
}

// Handle Register
async function handleRegister(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    data.action = 'register';
    
    const errorEl = document.getElementById('register-error');
    errorEl.style.display = 'none';
    
    // Simple frontend validation
    if (data.password !== data.confirm_password) {
        errorEl.textContent = "Passwords do not match";
        errorEl.style.display = 'block';
        return;
    }
    
    try {
        const response = await fetch('/hms/system/api_auth.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            window.location.href = result.redirect || '/hms/app/customer/dashboard.php';
        } else {
            errorEl.textContent = result.message;
            errorEl.style.display = 'block';
        }
    } catch (err) {
        errorEl.textContent = 'An error occurred. Please try again.';
        errorEl.style.display = 'block';
    }
}

// Expose global functions
window.openLoginModal = () => openAuthModal('login');
window.openRegisterModal = () => openAuthModal('register');
</script>
