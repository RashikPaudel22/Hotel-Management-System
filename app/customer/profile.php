<?php
/**
 * Customer Profile Page
 * Displays user details and allows updating of basic information.
 */

// Load system functions
require_once __DIR__ . '/../../system/htmlload.php';
require_once __DIR__ . '/../../system/database.php';

// Ensure user is logged in as customer
requireRole('customer');

$userId = getUserId();
$success = "";
$error = "";

// Handle form submission
if (isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    
    if (empty($full_name)) {
        $error = "Full Name cannot be empty";
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssi", $full_name, $phone, $userId);
        
        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            // Update session data
            $_SESSION['user']['full_name'] = $full_name;
        } else {
            $error = "Error updating profile. Please try again.";
        }
    }
}

// Fetch latest user data
$stmt = $conn->prepare("SELECT username, email, full_name, phone, role FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Load Header
loadHeader('My Profile - Destr0yer Hotel');

// Load Topbar
renderTopBar($user['username']);
?>

<div class="profile-hero">
    <div class="profile-overlay"></div>
    <div class="profile-content">
        <h1>My Profile</h1>
        <p>Manage your personal information</p>
    </div>
</div>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="/hms/assets/images/default_avatar.png" alt="Profile Photo">
                <button class="change-photo-btn" onclick="alert('Photo upload feature coming soon!')">
                    📷
                </button>
            </div>
            <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
            <span class="role-badge"><?php echo ucfirst($user['role']); ?></span>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="profile-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled class="input-disabled">
                    <small>Username cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="input-disabled">
                    <small>Contact support to change email</small>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="update_profile" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Page Layout */
body {
    background-color: #f7f9fc;
}

.profile-hero {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    height: 250px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.profile-content h1 {
    font-size: 32px;
    margin-bottom: 5px;
}

.profile-container {
    max-width: 800px;
    margin: -60px auto 50px; /* Overlap hero */
    padding: 0 20px;
    position: relative;
    z-index: 10;
}

.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 40px;
}

/* Header & Avatar */
.profile-header {
    text-align: center;
    margin-bottom: 30px;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    margin: 0 auto 15px;
    position: relative;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.change-photo-btn {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: #4361ee;
    color: white;
    border: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.change-photo-btn:hover {
    transform: scale(1.1);
}

.role-badge {
    background: #e0e7ff;
    color: #4361ee;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* Form Styles */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 15px;
    box-sizing: border-box;
    transition: border-color 0.2s;
}

.form-group input:focus {
    border-color: #4361ee;
    outline: none;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.input-disabled {
    background-color: #f7fafc;
    color: #718096;
    cursor: not-allowed;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #a0aec0;
    font-size: 12px;
}

.form-actions {
    text-align: right;
    border-top: 1px solid #e2e8f0;
    padding-top: 20px;
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}
.alert-success { background: #def7ec; color: #046c4e; }
.alert-error { background: #fde8e8; color: #c81e1e; }

@media (max-width: 600px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php loadFooter(); ?>
