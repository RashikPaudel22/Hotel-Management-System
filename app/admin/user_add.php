<?php
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    
    if ($stmt->execute()) {
        header('Location: users.php?success=1');
        exit();
    } else {
        $error = 'Failed to add staff.';
    }
}

loadHeader('Add Staff');
include __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content" style="margin-left: 260px; padding: 40px; min-height: 100vh;">
    <div class="header" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary); font-size: 28px; margin-bottom: 10px;">Add New Staff</h1>
        <p style="color: var(--text-gray);">Create a new staff account</p>
    </div>
    
    <div style="margin-bottom: 20px;">
        <a href="users.php" class="btn btn-secondary">← Back to Staff List</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px;"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php include __DIR__ . '/components/user_add_form.php'; ?>
</div>

<?php loadFooter(); ?>