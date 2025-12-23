<?php
session_start();
include("database.php");
require_once("htmlload.php");

$error = "";
$success = "";

// If already logged in, redirect based on role
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role === 'customer') {
        header('Location: ../app/customer/dashboard.php');
    } elseif ($role === 'admin') {
        header('Location: ../app/admin/dashboard.php');
    } elseif ($role === 'receptionist') {
        header('Location: ../app/reception/dashboard.php');
    }
    exit();
}

if (isset($_POST['register'])) {
    // Get form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone"]);

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = "All fields are required!";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if username already exists
        $check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $result = $check_username->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            // Check if email already exists
            $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $result_email = $check_email->get_result();
            
            if ($result_email->num_rows > 0) {
                $error = "Email already registered!";
            } else {
                // Hash password for security (recommended)
                // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user with 'customer' role automatically
                $role = 'customer'; // Automatically set role to customer
                
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $username, $email, $password, $full_name, $phone, $role);
                
                if ($stmt->execute()) {
                    // Auto-login after registration
                    $_SESSION['user'] = [
                        "id" => $conn->insert_id,
                        "username" => $username,
                        "role" => $role
                    ];
                    
                    // Redirect to customer dashboard
                    header("Location: ../app/customer/dashboard.php");
                    exit();
                } else {
                    $error = "Registration failed! Please try again.";
                }
            }
        }
    }
}

loadHeader("Register - Create Account");
?>

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.register-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    width: 100%;
    max-width: 500px;
    padding: 40px;
    animation: slideUp 0.5s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.register-header {
    text-align: center;
    margin-bottom: 35px;
}

.register-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 8px;
}

.register-header p {
    color: #6c757d;
    font-size: 15px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.password-requirements {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 5px;
}

.register-btn {
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

.register-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.register-btn:active {
    transform: translateY(0);
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-error {
    background: #fee;
    color: #c33;
    border-left: 4px solid #c33;
}

.alert-success {
    background: #efe;
    color: #3c3;
    border-left: 4px solid #3c3;
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

.login-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #6c757d;
}

.login-link a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.login-link a:hover {
    text-decoration: underline;
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

@media (max-width: 576px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .register-container {
        padding: 30px 20px;
    }
}
</style>

<div class="register-container">
    <div class="register-header">
        <h1>Create Account</h1>
        <p>Join us today and start booking your perfect stay</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input 
                type="text" 
                name="full_name" 
                id="full_name"
                class="form-input"
                placeholder="Enter your full name"
                value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
                required
            >
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="username">Username *</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username"
                    class="form-input"
                    placeholder="Choose a username"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    required
                    minlength="3"
                >
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input 
                    type="tel" 
                    name="phone" 
                    id="phone"
                    class="form-input"
                    placeholder="Your phone number"
                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                >
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input 
                type="email" 
                name="email" 
                id="email"
                class="form-input"
                placeholder="your.email@example.com"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <div class="password-toggle">
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="form-input"
                    placeholder="Create a strong password"
                    required
                    minlength="6"
                >
                <span class="password-toggle-icon" onclick="togglePassword('password')">👁️</span>
            </div>
            <div class="password-requirements">
                Minimum 6 characters
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password *</label>
            <div class="password-toggle">
                <input 
                    type="password" 
                    name="confirm_password" 
                    id="confirm_password"
                    class="form-input"
                    placeholder="Re-enter your password"
                    required
                    minlength="6"
                >
                <span class="password-toggle-icon" onclick="togglePassword('confirm_password')">👁️</span>
            </div>
        </div>

        <button type="submit" name="register" class="register-btn">
            Create Account
        </button>
    </form>

    <div class="divider">
        <span>OR</span>
    </div>

    <div class="login-link">
        Already have an account? <a href="/hms/system/login.php">Login here</a>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const icon = passwordInput.nextElementSibling;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        icon.textContent = '👁️';
    }
}

// Password match validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.style.borderColor = '#ef4444';
    } else {
        this.style.borderColor = '#e2e8f0';
    }
});
</script>

<?php loadFooter(); ?>