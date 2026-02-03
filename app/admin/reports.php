<?php
/**
 * Customer Reports Page
 * Displays all customer complaints and reports
 */

// Authentication
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

// Dependencies
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

// Fetch all reports with customer information
$reportsQuery = $conn->query("
    SELECT 
        r.*,
        u.full_name as fname,
        '' as lname,
        u.email
    FROM reports r
    LEFT JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
");

$reports = [];
while ($row = $reportsQuery->fetch_assoc()) {
    // Split full_name into fname and lname if needed
    $nameParts = explode(' ', $row['fname'], 2);
    $row['fname'] = $nameParts[0];
    $row['lname'] = $nameParts[1] ?? '';
    
    $reports[] = $row;
}

// Load page
loadHeader('Customer Reports');

// Include sidebar
include __DIR__ . '/includes/sidebar.php';
?>

<!-- Main content area -->
<div class="main-content" style="margin-left: 260px; padding: 40px; min-height: 100vh;">
    <div class="header" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary); font-size: 28px; margin-bottom: 10px;">Customer Complaints</h1>
        <p style="color: var(--text-gray);">View and manage customer reports</p>
    </div>
    
    <?php
    // Include reports table component
    include __DIR__ . '/components/reports_table.php';
    ?>
</div>

<?php loadFooter(); ?>