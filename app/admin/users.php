<?php
/**
 * Staff Management Page
 * Displays all staff members (admin and receptionist)
 */

// Authentication
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

// Dependencies
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

// Fetch all staff members (exclude customers)
$usersQuery = $conn->query("
    SELECT * FROM users 
    WHERE role IN ('admin', 'receptionist') 
    ORDER BY id DESC
");

$users = [];
while ($row = $usersQuery->fetch_assoc()) {
    $users[] = $row;
}

// Load page
loadHeader('Staff Management');

// Include sidebar
include __DIR__ . '/includes/sidebar.php';
?>

<!-- Main content area -->
<div class="main-content" style="margin-left: 260px; padding: 40px; min-height: 100vh;">
    <div class="header" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary); font-size: 28px; margin-bottom: 10px;">Staff Management</h1>
        <p style="color: var(--text-gray);">Manage admin and receptionist accounts</p>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Operation completed successfully!
        </div>
    <?php endif; ?>
    
    <?php
    // Include users table component
    include __DIR__ . '/components/users_table.php';
    ?>
</div>

<?php loadFooter(); ?>