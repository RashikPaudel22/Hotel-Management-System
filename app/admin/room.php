<?php
/**
 * Room Management Page
 * Displays all rooms with management options
 */

// Authentication
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

// Dependencies
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

// Fetch rooms with their type information
$rooms = $conn->query("
    SELECT r.*, rt.name 
    FROM rooms r
    JOIN room_types rt ON r.type_id = rt.id
    ORDER BY r.room_number ASC
");

// Load page
loadHeader('Manage Rooms');

// Include sidebar
include __DIR__ . '/includes/sidebar.php';
?>

<!-- Main content area -->
<div class="main-content" style="margin-left: 260px; padding: 40px; min-height: 100vh;">
    <div class="header" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary); font-size: 28px; margin-bottom: 10px;">Room Management</h1>
        <p style="color: var(--text-gray);">Manage rooms and their status</p>
    </div>
    
    <?php 
    // Display success message if redirected from add/edit/delete
    if (isset($_GET['success'])):
    ?>
        <div class="alert alert-success">
            Operation completed successfully!
        </div>
    <?php endif; ?>
    
    <?php
    // Include rooms table component
    include __DIR__ . '/components/rooms_table.php';
    ?>
</div>

<?php loadFooter(); ?>