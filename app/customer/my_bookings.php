<?php
session_start();
require_once '../../system/database.php';

// DEBUG MODE: Uncomment the lines below to see your session data
// echo "<pre>SESSION DATA:<br>"; print_r($_SESSION); echo "</pre>";
// echo "<pre>SERVER DATA:<br>"; print_r($_SERVER); echo "</pre>";
// exit(); // Comment this out after debugging

// Check if user is logged in - check multiple possible session variables
$is_logged_in = false;
$user_id = null;
$user_role = 'customer';
$user_email = null;
$username = null;

// Check for nested session array structure (your system)
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $is_logged_in = true;
    $user_id = $_SESSION['user']['id'];
    $user_role = $_SESSION['user']['role'];
    $username = $_SESSION['user']['username'];
    
    // Get email from database since it's empty in session
    if (empty($_SESSION['user']['email']) && $user_id) {
        $query = "SELECT email FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $user_email = $user_data['email'];
        }
    } else {
        $user_email = $_SESSION['user']['email'];
    }
}
// Fallback: Check for flat session structure
elseif (isset($_SESSION['user_id'])) {
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
} elseif (isset($_SESSION['id'])) {
    $is_logged_in = true;
    $user_id = $_SESSION['id'];
} elseif (isset($_SESSION['username'])) {
    $is_logged_in = true;
    // Get user ID from username
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    $query = "SELECT id, role, email FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        $user_id = $user_data['id'];
        $user_role = $user_data['role'];
        $user_email = $user_data['email'];
    }
}

// If we have user_id but missing role/email, get from database
if ($is_logged_in && $user_id && (!$user_role || !$user_email)) {
    $query = "SELECT role, email FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        if (!$user_role) $user_role = $user_data['role'];
        if (!$user_email) $user_email = $user_data['email'];
    }
}

// Redirect non-customers to appropriate dashboard
if ($is_logged_in && $user_role != 'customer') {
    if ($user_role == 'admin') {
        header("Location: ../admin/");
    } else {
        header("Location: ../reception/");
    }
    exit();
}

// Fetch user's bookings with all related information
$bookings = [];
if ($is_logged_in && $user_email) {
    $email = mysqli_real_escape_string($conn, $user_email);
    $query = "SELECT 
                b.id as booking_id,
                b.checkin_date,
                b.checkout_date,
                b.total_price,
                b.status,
                b.created_at,
                r.room_number,
                rt.name as room_type,
                rt.price as room_price,
                c.fname,
                c.lname,
                c.email,
                c.phone,
                DATEDIFF(b.checkout_date, b.checkin_date) as nights
              FROM bookings b
              JOIN customers c ON b.customer_id = c.id
              JOIN rooms r ON b.room_id = r.id
              JOIN room_types rt ON r.type_id = rt.id
              WHERE c.email = '$email'
              ORDER BY b.created_at DESC";
    
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    }
}

// Handle booking cancellation
if (isset($_POST['cancel_booking']) && $is_logged_in) {
    $booking_id = intval($_POST['booking_id']);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Get room_id before cancelling
        $get_room = "SELECT room_id FROM bookings WHERE id = $booking_id";
        $room_result = mysqli_query($conn, $get_room);
        $room_data = mysqli_fetch_assoc($room_result);
        $room_id = $room_data['room_id'];
        
        // Update booking status to cancelled
        $cancel_query = "UPDATE bookings SET status = 'cancelled' WHERE id = $booking_id";
        mysqli_query($conn, $cancel_query);
        
        // Make room available again
        $update_room = "UPDATE rooms SET availability = 1 WHERE id = $room_id";
        mysqli_query($conn, $update_room);
        
        mysqli_commit($conn);
        
        // Refresh page
        header("Location: my_bookings.php?cancelled=success");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: my_bookings.php?cancelled=error");
        exit();
    }
}

$pageTitle = "My Bookings - Destr0yer Hotel";
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/topbar_customer.php'; ?>

<style>
    body {
        background: var(--bg-gray);
    }

    .bookings-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 32px 80px;
    }

    .page-header {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 32px;
        margin-bottom: 32px;
        border: 1px solid var(--border);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .page-header h1 {
        font-size: 32px;
        color: var(--text-dark);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: var(--accent);
        color: var(--navy);
        box-shadow: 0 4px 12px rgba(0, 217, 255, 0.3);
    }

    .btn-primary:hover {
        background: var(--electric);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 217, 255, 0.4);
    }

    .btn-secondary {
        background: var(--bg-white);
        color: var(--text-dark);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background: var(--bg-light);
        border-color: var(--primary);
    }

    .btn-danger {
        background: #dc3545;
        color: white;
        padding: 10px 20px;
        font-size: 13px;
    }

    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .alert {
        padding: 16px 20px;
        border-radius: var(--radius);
        margin-bottom: 24px;
        font-weight: 500;
        background: var(--bg-white);
        border: 1px solid;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        color: #155724;
        border-color: #28a745;
        background: #d4edda;
    }

    .alert-error {
        color: #721c24;
        border-color: #dc3545;
        background: #f8d7da;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--navy) 0%, var(--primary) 100%);
        border-radius: var(--radius-lg);
        padding: 28px;
        border: 2px solid var(--electric);
        text-align: center;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(0, 217, 255, 0.15);
    }

    .stat-card:hover {
        box-shadow: 0 8px 24px rgba(0, 217, 255, 0.3);
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    .stat-card h3 {
        font-size: 36px;
        color: var(--electric);
        margin-bottom: 8px;
        font-weight: 700;
        text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
    }

    .stat-card p {
        color: #a0d8f1;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .bookings-grid {
        display: grid;
        gap: 24px;
    }

    .booking-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 28px;
        border: 1px solid var(--border);
        transition: var(--transition);
    }

    .booking-card:hover {
        box-shadow: 0 8px 20px rgba(0, 71, 171, 0.15);
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--border);
    }

    .booking-id {
        font-size: 14px;
        color: var(--text-gray);
    }

    .booking-id strong {
        color: var(--primary);
        font-size: 20px;
        font-weight: 700;
    }

    .booking-id small {
        color: var(--text-light);
        font-size: 13px;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-checked_in {
        background: #d4edda;
        color: #155724;
    }

    .status-checked_out {
        background: #e2e3e5;
        color: #383d41;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .booking-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .detail-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .detail-label {
        font-size: 12px;
        color: var(--text-light);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .detail-value {
        font-size: 15px;
        color: var(--text-dark);
        font-weight: 500;
    }

    .room-info {
        background: linear-gradient(135deg, var(--navy) 0%, var(--primary) 100%);
        color: white;
        padding: 20px;
        border-radius: var(--radius);
        margin-bottom: 24px;
        border: 1px solid var(--electric);
        box-shadow: 0 4px 12px rgba(0, 217, 255, 0.2);
    }

    .room-info h3 {
        font-size: 20px;
        margin-bottom: 6px;
        font-weight: 600;
        color: var(--electric);
    }

    .room-info p {
        font-size: 14px;
        opacity: 0.95;
        color: #a0d8f1;
    }

    .price-summary {
        background: var(--bg-light);
        padding: 20px;
        border-radius: var(--radius);
        margin-bottom: 20px;
        border: 1px solid var(--border);
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        color: var(--text-gray);
    }

    .price-row.total {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        padding-top: 12px;
        border-top: 2px solid var(--border);
        margin-top: 4px;
    }

    .booking-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .empty-state {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 80px 32px;
        text-align: center;
        border: 1px solid var(--border);
    }

    .empty-state-icon {
        font-size: 80px;
        margin-bottom: 24px;
        opacity: 0.3;
    }

    .empty-state h2 {
        font-size: 28px;
        color: var(--text-dark);
        margin-bottom: 12px;
        font-weight: 700;
    }

    .empty-state p {
        color: var(--text-gray);
        margin-bottom: 28px;
        font-size: 16px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 31, 63, 0.7);
        animation: fadeIn 0.3s;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: var(--bg-white);
        margin: 10% auto;
        padding: 36px;
        border-radius: var(--radius-lg);
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0, 71, 171, 0.3);
        animation: slideDown 0.3s;
        border: 2px solid var(--electric);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal h2 {
        margin-bottom: 16px;
        color: #dc3545;
        font-size: 24px;
        font-weight: 700;
    }

    .modal p {
        color: var(--text-gray);
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
    }

    @media (max-width: 768px) {
        .bookings-container {
            padding: 24px 20px 60px;
        }

        .header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            flex-direction: column;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .booking-details {
            grid-template-columns: 1fr;
        }

        .booking-actions {
            flex-direction: column;
        }

        .modal-content {
            margin: 20% auto;
            padding: 28px 24px;
        }

        .modal-actions {
            flex-direction: column;
        }
    }
</style>

<div class="bookings-container">
    <div class="page-header">
        <div class="header-content">
            <h1>📋 My Bookings</h1>
            <div class="header-actions">
                <a href="../../system/create_booking.php" class="btn btn-primary">+ New Booking</a>
                <a href="../customer/dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['booking_success'])): ?>
        <div class="alert alert-success">
            ✓ Booking created successfully! Booking ID: #<?php echo $_GET['booking_success']; ?> | Total Amount: NPR <?php echo number_format($_GET['amount'], 2); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['cancelled'])): ?>
        <?php if ($_GET['cancelled'] == 'success'): ?>
            <div class="alert alert-success">
                ✓ Booking cancelled successfully! The room is now available for other guests.
            </div>
        <?php else: ?>
            <div class="alert alert-error">
                ✗ Error cancelling booking. Please try again.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!$is_logged_in): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🔒</div>
            <h2>Please Log In</h2>
            <p>You need to be logged in to view your bookings.</p>
            <a href="../../system/login.php" class="btn btn-primary">Log In</a>
        </div>
    <?php elseif (empty($bookings)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🏨</div>
            <h2>No Bookings Yet</h2>
            <p>You haven't made any bookings. Start by booking your first room!</p>
            <a href="../../system/create_booking.php" class="btn btn-primary">Book a Room</a>
        </div>
    <?php else: ?>
        <!-- Stats -->
        <div class="stats-container">
            <div class="stat-card">
                <h3><?php echo count($bookings); ?></h3>
                <p>Total Bookings</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($bookings, function($b) { return $b['status'] == 'pending' || $b['status'] == 'confirmed'; })); ?></h3>
                <p>Active Bookings</p>
            </div>
            <div class="stat-card">
                <h3>NPR <?php echo number_format(array_sum(array_column($bookings, 'total_price')), 2); ?></h3>
                <p>Total Spent</p>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="bookings-grid">
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="booking-id">
                            Booking ID: <strong>#<?php echo $booking['booking_id']; ?></strong>
                            <br>
                            <small>Booked on: <?php echo date('M d, Y', strtotime($booking['created_at'])); ?></small>
                        </div>
                        <span class="status-badge status-<?php echo $booking['status']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $booking['status'])); ?>
                        </span>
                    </div>

                    <div class="room-info">
                        <h3>🛏️ Room <?php echo $booking['room_number']; ?> - <?php echo $booking['room_type']; ?></h3>
                        <p>NPR <?php echo number_format($booking['room_price'], 2); ?> per night</p>
                    </div>

                    <div class="booking-details">
                        <div class="detail-group">
                            <span class="detail-label">Check-in</span>
                            <span class="detail-value">📅 <?php echo date('M d, Y', strtotime($booking['checkin_date'])); ?></span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Check-out</span>
                            <span class="detail-value">📅 <?php echo date('M d, Y', strtotime($booking['checkout_date'])); ?></span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Guest Name</span>
                            <span class="detail-value">👤 <?php echo $booking['fname'] . ' ' . $booking['lname']; ?></span>
                        </div>
                        <div class="detail-group">
                            <span class="detail-label">Contact</span>
                            <span class="detail-value">📞 <?php echo $booking['phone']; ?></span>
                        </div>
                    </div>

                    <div class="price-summary">
                        <div class="price-row">
                            <span>Room Rate:</span>
                            <span>NPR <?php echo number_format($booking['room_price'], 2); ?> × <?php echo $booking['nights']; ?> night<?php echo $booking['nights'] > 1 ? 's' : ''; ?></span>
                        </div>
                        <div class="price-row total">
                            <span>Total Amount:</span>
                            <span>NPR <?php echo number_format($booking['total_price'], 2); ?></span>
                        </div>
                    </div>

                    <?php if ($booking['status'] == 'pending' || $booking['status'] == 'confirmed'): ?>
                        <div class="booking-actions">
                            <button onclick="confirmCancel(<?php echo $booking['booking_id']; ?>)" class="btn btn-danger">
                                Cancel Booking
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <h2>⚠️ Cancel Booking?</h2>
        <p>Are you sure you want to cancel this booking? This action cannot be undone.</p>
        <form method="POST" action="">
            <input type="hidden" name="booking_id" id="cancelBookingId">
            <div class="modal-actions">
                <button type="submit" name="cancel_booking" class="btn btn-danger" style="flex: 1;">Yes, Cancel Booking</button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">No, Keep It</button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmCancel(bookingId) {
        document.getElementById('cancelBookingId').value = bookingId;
        document.getElementById('cancelModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('cancelModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

<?php include '../includes/footer.php'; ?>