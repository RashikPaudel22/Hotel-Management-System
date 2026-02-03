<?php
/**
 * Add Room Page
 * Form to add a new room to the system
 */

// Authentication
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

// Dependencies
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $roomNumber = trim($_POST['room_number']);
    $roomType = $_POST['room_type'];
    $status = $_POST['status'];
    
    // Validation
    if (empty($roomNumber) || empty($roomType)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check if room number already exists
        $checkStmt = $conn->prepare("SELECT id FROM rooms WHERE room_number = ?");
        $checkStmt->bind_param("s", $roomNumber);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Room number already exists!';
        } else {
            // Insert new room
            $stmt = $conn->prepare("INSERT INTO rooms (room_number, type_id, availability, status) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sii", $roomNumber, $roomType, $status);
            
            if ($stmt->execute()) {
                header('Location: room.php?success=1');
                exit();
            } else {
                $error = 'Failed to add room. Please try again.';
            }
        }
    }
}

// Fetch room types for dropdown
$roomTypes = $conn->query("SELECT * FROM room_types ORDER BY name");

// Load page
loadHeader('Add Room');

// Include sidebar
include __DIR__ . '/includes/sidebar.php';
?>

<!-- Main content area -->
<div class="main-content" style="margin-left: 260px; padding: 40px; min-height: 100vh;">
    <div class="header" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary); font-size: 28px; margin-bottom: 10px;">Add New Room</h1>
        <p style="color: var(--text-gray);">Create a new room listing</p>
    </div>
    <div style="margin-bottom: 20px;">
        <a href="room.php" class="btn btn-secondary">← Back to Rooms</a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger" style="max-width: 520px; margin: 20px auto;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php
    // Include add room form component
    include __DIR__ . '/components/room_add_form.php';
    ?>
</div>

<?php loadFooter(); ?>