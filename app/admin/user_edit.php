<?php
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

$userId = $_GET['id'] ?? 0;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, role = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $role, $hashedPassword, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $role, $userId);
    }
    
    if ($stmt->execute()) {
        header('Location: users.php?success=1');
        exit();
    } else {
        $error = 'Failed to update staff.';
    }
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

loadHeader('Edit Staff');
include __DIR__ . '/includes/sidebar.php';
?>

<div class="main">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php include __DIR__ . '/components/user_edit_form.php'; ?>
</div>

<?php loadFooter(); ?>