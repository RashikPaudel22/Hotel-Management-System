<?php
session_start();
require_once '../../system/database.php';
require_once '../../system/htmlload.php';

// Check if user is admin or receptionist
$is_logged_in = false;
$user_role = '';

if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $is_logged_in = true;
    $user_role = $_SESSION['user']['role'];
}

if (!$is_logged_in || ($user_role != 'admin' && $user_role != 'receptionist')) {
    header("Location: ../../login.php");
    exit();
}

// Fetch all bookings
$query = "SELECT 
            b.id as booking_id,
            b.checkin_date,
            b.checkout_date,
            b.total_price,
            b.status,
            b.created_at,
            r.room_number,
            r.id as room_id,
            rt.name as room_type,
            rt.price as room_price,
            c.fname,
            c.lname,
            c.email,
            c.phone,
            c.address,
            DATEDIFF(b.checkout_date, b.checkin_date) as nights,
            DATEDIFF(CURDATE(), b.checkin_date) as days_until_checkin
          FROM bookings b
          JOIN customers c ON b.customer_id = c.id
          JOIN rooms r ON b.room_id = r.id
          JOIN room_types rt ON r.type_id = rt.id
          ORDER BY 
            CASE b.status
                WHEN 'pending' THEN 1
                WHEN 'confirmed' THEN 2
                WHEN 'checked_in' THEN 3
                WHEN 'checked_out' THEN 4
                WHEN 'cancelled' THEN 5
            END,
            b.checkin_date ASC";

$result = mysqli_query($conn, $query);
$bookings = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}

// Get statistics
$stats_query = "SELECT 
                  SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                  SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                  SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                  SUM(CASE WHEN status = 'checked_out' THEN 1 ELSE 0 END) as checked_out,
                  COUNT(*) as total
                FROM bookings";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// LOAD TEMPLATE
loadHeader('Manage Bookings');
include __DIR__ . '/includes/sidebar.php';
?>

<style>
    /* Main Content */
    .main-content {
        margin-left: 260px;
        padding: 40px;
        min-height: 100vh;
    }

    /* Header */
    .header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: start;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header h1 {
        color: var(--primary);
        font-size: 28px;
        margin-bottom: 10px;
    }

    .header p {
        color: var(--text-gray);
        margin: 0;
    }

    .header-actions .btn {
        padding: 10px 20px;
    }

    /* Statistics */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        text-align: center;
        border: 1px solid #f0f0f0;
    }

    .stat-card h3 {
        font-size: 32px;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .stat-card p {
        color: var(--text-gray);
        font-size: 14px;
        margin: 0;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
        border: 1px solid #eee;
    }

    .filter-section label {
        font-weight: 600;
    }

    .filter-section select {
        padding: 8px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
    }

    /* Table Container */
    .bookings-table-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    table {
        width: 100%;
        min-width: 1000px;
        border-collapse: collapse;
    }

    th {
        background: var(--primary);
        color: white;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: top;
        color: var(--text-dark);
    }

    tr:hover {
        background-color: #f8f9fa;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        white-space: nowrap;
    }

    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-confirmed { background-color: #d1ecf1; color: #0c5460; }
    .status-checked_in { background-color: #d4edda; color: #155724; }
    .status-checked_out { background-color: #e2e3e5; color: #383d41; }
    .status-cancelled { background-color: #f8d7da; color: #721c24; }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .btn-sm-custom {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        display: inline-block;
        white-space: nowrap;
    }

    /* Alerts */
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px;
    }

    .customer-info {
        line-height: 1.5;
    }

    /* Tablet Responsive (768px - 1024px) */
    @media (max-width: 1024px) {
        .main-content {
            margin-left: 220px;
            padding: 30px;
        }

        .stats-container {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-card h3 {
            font-size: 28px;
        }
    }

    /* Mobile Responsive (max-width: 768px) */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 80px 20px 20px;
        }

        .header {
            flex-direction: column;
            align-items: stretch;
        }

        .header h1 {
            font-size: 24px;
        }

        .header-actions .btn {
            width: 100%;
            text-align: center;
        }

        .stats-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            padding: 18px 15px;
        }

        .stat-card h3 {
            font-size: 24px;
        }

        .stat-card p {
            font-size: 13px;
        }

        .filter-section {
            padding: 15px;
        }

        .filter-section label,
        .filter-section select {
            width: 100%;
        }

        .bookings-table-container {
            padding: 20px 15px;
        }

        table {
            min-width: 900px;
            font-size: 13px;
        }

        th, td {
            padding: 10px 8px;
        }

        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .btn-sm-custom {
            width: 100%;
            text-align: center;
        }
    }

    /* Small Mobile (max-width: 480px) */
    @media (max-width: 480px) {
        .main-content {
            padding: 70px 15px 15px;
        }

        .header h1 {
            font-size: 22px;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 15px;
        }

        .stat-card h3 {
            font-size: 22px;
        }

        .stat-card p {
            font-size: 12px;
        }

        .filter-section {
            padding: 12px;
        }

        .bookings-table-container {
            padding: 15px 10px;
        }

        table {
            min-width: 800px;
            font-size: 12px;
        }

        th, td {
            padding: 8px 6px;
        }

        .status-badge {
            padding: 4px 8px;
            font-size: 10px;
        }

        .btn-sm-custom {
            padding: 4px 8px;
            font-size: 11px;
        }
    }
</style>

<!-- MAIN CONTENT WRAPPER -->
<div class="main-content">
    
    <div class="header">
        <div>
            <h1>📋 Manage Bookings</h1>
            <p>View and manage all reservation requests</p>
        </div>
        <div class="header-actions">
            <a href="reservation.php" class="btn btn-primary">+ New Booking</a>
        </div>
    </div>

    <?php if (isset($_GET['checkin']) && $_GET['checkin'] == 'success'): ?>
        <div class="alert alert-success">
            ✓ Guest checked in successfully!
        </div>
    <?php elseif (isset($_GET['checkin']) && $_GET['checkin'] == 'error'): ?>
        <div class="alert alert-error">
            ✗ Error checking in guest. Please try again.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['checkout']) && $_GET['checkout'] == 'success'): ?>
        <div class="alert alert-success">
            ✓ Guest checked out successfully! Room is now available.
        </div>
    <?php elseif (isset($_GET['checkout']) && $_GET['checkout'] == 'error'): ?>
        <div class="alert alert-error">
            ✗ Error checking out guest. Please try again.
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <h3><?php echo $stats['total']; ?></h3>
            <p>Total Bookings</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $stats['pending']; ?></h3>
            <p>Pending</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $stats['confirmed']; ?></h3>
            <p>Confirmed</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $stats['checked_in']; ?></h3>
            <p>Checked In</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $stats['checked_out']; ?></h3>
            <p>Checked Out</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <label for="statusFilter">Filter by Status:</label>
        <select id="statusFilter" onchange="filterBookings()">
            <option value="all">All Bookings</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="checked_in">Checked In</option>
            <option value="checked_out">Checked Out</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <!-- Bookings Table -->
    <div class="bookings-table-container">
        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <div style="font-size: 60px; margin-bottom: 20px; opacity: 0.3;">📋</div>
                <h2 style="color: var(--text-dark);">No Bookings Found</h2>
                <p style="color: var(--text-gray);">There are no bookings in the system yet.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Nights</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr data-status="<?php echo $booking['status']; ?>">
                            <td><strong>#<?php echo $booking['booking_id']; ?></strong></td>
                            <td>
                                <div class="customer-info">
                                    <strong style="color: var(--primary); display:block;"><?php echo $booking['fname'] . ' ' . $booking['lname']; ?></strong>
                                    <small style="display:block; color:#666;">📧 <?php echo $booking['email']; ?></small>
                                    <small style="display:block; color:#666;">📞 <?php echo $booking['phone']; ?></small>
                                </div>
                            </td>
                            <td>
                                <strong>Room <?php echo $booking['room_number']; ?></strong><br>
                                <small style="color:#666;"><?php echo $booking['room_type']; ?></small>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($booking['checkin_date'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['checkout_date'])); ?></td>
                            <td><?php echo $booking['nights']; ?></td>
                            <td><strong>NPR <?php echo number_format($booking['total_price'], 2); ?></strong></td>
                            <td>
                                <span class="status-badge status-<?php echo $booking['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $booking['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($booking['status'] == 'pending' || $booking['status'] == 'confirmed'): ?>
                                        <a href="book_rooms/check_in.php?booking_id=<?php echo $booking['booking_id']; ?>" 
                                           class="btn-sm-custom" style="background-color: #28a745;"
                                           onclick="return confirm('Check in this guest?')">
                                            ✓ Check In
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($booking['status'] == 'checked_in'): ?>
                                        <a href="book_rooms/check_out.php?booking_id=<?php echo $booking['booking_id']; ?>" 
                                           class="btn-sm-custom" style="background-color: #ffc107; color: black;"
                                           onclick="return confirm('Check out this guest?')">
                                            ✓ Check Out
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($booking['status'] == 'pending'): ?>
                                        <a href="confirm_booking.php?booking_id=<?php echo $booking['booking_id']; ?>" 
                                           class="btn-sm-custom" style="background-color: #17a2b8;"
                                           onclick="return confirm('Confirm this booking?')">
                                            Confirm
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
    function filterBookings() {
        const filter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (filter === 'all' || status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

<?php loadFooter(); ?>