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
// Fetch all reviews with customer information
$reviewsQuery = $conn->query("
    SELECT 
        r.*,
        u.full_name as fname,
        '' as lname,
        u.email
    FROM reviews r
    LEFT JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
");

$reviews = [];
while ($row = $reviewsQuery->fetch_assoc()) {
    // Split full_name into fname and lname if needed
    $nameParts = explode(' ', $row['fname'], 2);
    $row['fname'] = $nameParts[0];
    $row['lname'] = $nameParts[1] ?? '';
    
    $reviews[] = $row;
}

// Load page
loadHeader('Customer Reviews');

// Include sidebar
include __DIR__ . '/includes/sidebar.php';
?>

<!-- Main content area -->
<div class="main-content" style="margin-left: 260px; padding: 40px; min-height: 100vh;">
    <div class="header" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary); font-size: 28px; margin-bottom: 10px;">Customer Reviews</h1>
        <p style="color: var(--text-gray);">View and manage customer reviews</p>
    </div>
    
    <?php
    // Include reports table component
    include __DIR__ . '/components/reviews_table.php';
    ?>
</div>

<?php loadFooter(); ?>