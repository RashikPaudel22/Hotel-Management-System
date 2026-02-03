<?php
/**
 * Edit Room Page
 * Form to edit existing room details
 */

// Authentication
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

// Dependencies
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

$error = '';
$roomId = $_GET['id'] ?? 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_room'])) {
    $roomNumber = trim($_POST['room_number']);
    $roomType = $_POST['room_type'];
    $status = $_POST['status'];
    
    // Validation
    if (empty($roomNumber) || empty($roomType)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check if room number exists for other rooms
        $checkStmt = $conn->prepare("SELECT id FROM rooms WHERE room_number = ? AND id != ?");
        $checkStmt->bind_param("si", $roomNumber, $roomId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Room number already exists!';
        } else {
            // Update room
            $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, type_id = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sisi", $roomNumber, $roomType, $status, $roomId);
            
            if ($stmt->execute()) {
                header('Location: room.php?success=1');
                exit();
            } else {
                $error = 'Failed to update room. Please try again.';
            }
        }
    }
}

// Fetch room data
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    header('Location: room.php');
    exit();
}

// Fetch room types for dropdown
$roomTypes = $conn->query("SELECT * FROM room_types ORDER BY name");

// Load page
loadHeader('Edit Room');

// Include sidebar
include __DIR__ . '/includes/sidebar.php';
?>

<!-- Main content area -->
<div class="main-content" style="margin-left: 260px; padding: 40px; min-height: 100vh;">
    <div class="header" style="margin-bottom: 30px;">
        <h1 style="color: var(--primary); font-size: 28px; margin-bottom: 10px;">Edit Room</h1>
        <p style="color: var(--text-gray);">Update room details</p>
    </div>
    
    <div style="margin-bottom: 20px;">
        <a href="room.php" class="btn btn-secondary">← Back to Rooms</a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger" style="max-width: 600px; margin: 20px auto;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php
    // Include edit room form component
    include __DIR__ . '/components/room_edit_form.php';
    ?>
</div>

<?php loadFooter(); ?>