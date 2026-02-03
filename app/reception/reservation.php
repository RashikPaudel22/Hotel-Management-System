<?php
session_start();

require_once '../../system/database.php';
require_once '../../system/htmlload.php';

// Authentication Layer
$is_logged_in = false;
$user_role = 'customer'; // default
$currentUser = getCurrentUser();
$username = $currentUser['username'] ?? 'Receptionist';

if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $is_logged_in = true;
    $user_role = $_SESSION['user']['role'];
}
elseif (isset($_SESSION['user_id']) || isset($_SESSION['id']) || isset($_SESSION['username'])) {
    $is_logged_in = true;
    
    if (isset($_SESSION['role'])) {
        $user_role = $_SESSION['role'];
    } elseif (isset($_SESSION['user_role'])) {
        $user_role = $_SESSION['user_role'];
    } elseif (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $role_query = "SELECT role FROM users WHERE id = $user_id";
        $role_result = mysqli_query($conn, $role_query);
        if ($role_result && mysqli_num_rows($role_result) > 0) {
            $role_data = mysqli_fetch_assoc($role_result);
            $user_role = $role_data['role'];
        }
    }
}

if ($is_logged_in && $user_role !== 'receptionist') {
     // Optional: Redirect them out if you want strict access control
}

$success_message = '';
$error_message = '';

// Get room_type from URL if present
$preselected_room_type = isset($_GET['room_type']) ? intval($_GET['room_type']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $id_number = mysqli_real_escape_string($conn, $_POST['id_number']);
    $room_id = mysqli_real_escape_string($conn, $_POST['room_id']);
    $checkin_date = mysqli_real_escape_string($conn, $_POST['checkin_date']);
    $checkout_date = mysqli_real_escape_string($conn, $_POST['checkout_date']);
    
    if (strtotime($checkin_date) >= strtotime($checkout_date)) {
        $error_message = "Check-out date must be after check-in date!";
    } else {
        $conflict_check = "SELECT id FROM bookings 
                          WHERE room_id = $room_id 
                          AND status IN ('pending', 'confirmed', 'checked_in')
                          AND (
                              (checkin_date <= '$checkin_date' AND checkout_date > '$checkin_date')
                              OR (checkin_date < '$checkout_date' AND checkout_date >= '$checkout_date')
                              OR (checkin_date >= '$checkin_date' AND checkout_date <= '$checkout_date')
                          )";
        
        $conflict_result = mysqli_query($conn, $conflict_check);
        
        if (mysqli_num_rows($conflict_result) > 0) {
            $error_message = "Sorry, this room is already booked for the selected dates. Please choose different dates or another room.";
        } else {
            mysqli_begin_transaction($conn);
            
            try {
                $customer_query = "INSERT INTO customers (fname, lname, phone, email, address, id_number) 
                                 VALUES ('$fname', '$lname', '$phone', '$email', '$address', '$id_number')";
                
                if (mysqli_query($conn, $customer_query)) {
                    $customer_id = mysqli_insert_id($conn);
                    
                    $room_query = "SELECT rt.price, DATEDIFF('$checkout_date', '$checkin_date') as days 
                                  FROM rooms r 
                                  JOIN room_types rt ON r.type_id = rt.id 
                                  WHERE r.id = $room_id";
                    $room_result = mysqli_query($conn, $room_query);
                    $room_data = mysqli_fetch_assoc($room_result);
                    $total_price = $room_data['price'] * $room_data['days'];
                    
                    $booking_query = "INSERT INTO bookings (customer_id, room_id, checkin_date, checkout_date, total_price, status) 
                                    VALUES ($customer_id, $room_id, '$checkin_date', '$checkout_date', $total_price, 'pending')";
                    
                    if (mysqli_query($conn, $booking_query)) {
                        
                        mysqli_commit($conn);
                        $booking_id = mysqli_insert_id($conn);
                        
                        if ($user_role == 'customer') {
                            header("Location: ../customer/my_bookings.php?booking_success=" . $booking_id . "&amount=" . $total_price);
                            exit();
                        } else {
                            $success_message = "Booking created successfully! Booking ID: #" . $booking_id . " | Total Amount: NPR " . number_format($total_price, 2);
                        }
                    } else {
                        throw new Exception("Error creating booking");
                    }
                } else {
                    throw new Exception("Error creating customer");
                }
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error_message = "Booking failed: " . $e->getMessage();
            }
        }
    }
}

// Fetch room types for dropdown
$room_types_query = "SELECT * FROM room_types ORDER BY name";
$room_types_result = mysqli_query($conn, $room_types_query);

loadHeader('Admin - New Reservation');
include __DIR__ . '/includes/sidebar.php';
?>

<style>
/* Main Content Responsive */
.main-content {
    margin-left: 260px;
    padding: 40px;
    min-height: 100vh;
}

/* Header */
.header {
    margin-bottom: 30px;
}

.header h1 {
    color: var(--primary);
    font-size: 28px;
    margin-bottom: 10px;
}

.header p {
    color: var(--text-gray);
}

.user-info {
    margin-top: 10px;
    font-size: 13px;
    color: var(--text-light);
}

/* Modern Card */
.modern-card {
    max-width: 100%;
    margin: 0;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-grid.single-column {
    grid-template-columns: 1fr;
}

/* Form Sections */
.form-section {
    margin-bottom: 30px;
}

.section-title {
    font-size: 18px;
    color: var(--primary);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--border);
    font-weight: 600;
}

/* Info Boxes */
.info-box {
    background-color: #e7f3ff;
    border-left: 4px solid #2196F3;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.availability-note {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 10px 15px;
    margin-top: 10px;
    border-radius: 4px;
    font-size: 13px;
    color: #856404;
}

/* Alerts */
.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Form Groups */
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
}

/* Button Container */
.btn-container {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    padding: 12px 24px;
    border-radius: 6px;
    text-align: center;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* Tablet Responsive (768px - 1024px) */
@media (max-width: 1024px) {
    .main-content {
        margin-left: 220px;
        padding: 30px;
    }
}

/* Mobile Responsive (max-width: 768px) */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 80px 20px 20px;
    }

    .modern-card {
        padding: 24px;
    }

    .header h1 {
        font-size: 22px;
    }

    .section-title {
        font-size: 16px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .btn-container {
        flex-direction: column;
        gap: 10px;
    }

    .btn {
        width: 100%;
    }
}

/* Small Mobile (max-width: 480px) */
@media (max-width: 480px) {
    .main-content {
        padding: 70px 15px 15px;
    }

    .modern-card {
        padding: 20px;
    }

    .header h1 {
        font-size: 20px;
    }

    .section-title {
        font-size: 15px;
    }

    .form-group label {
        font-size: 14px;
    }

    .form-group input,
    .form-group select {
        padding: 8px;
        font-size: 14px;
    }

    .info-box,
    .availability-note {
        padding: 10px;
        font-size: 12px;
    }

    .alert {
        padding: 12px;
        font-size: 13px;
    }

    .btn {
        padding: 10px 18px;
        font-size: 14px;
    }
}
</style>

<div class="main-content">
    
    <div class="header">
        <h1>🏨 Create New Booking</h1>
        <p>Fill in the details below to create a new reservation</p>
        <?php if ($is_logged_in): ?>
            <div class="user-info">
                Logged in as: <?php echo ucfirst($username); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="modern-card">
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                ✓ <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-error">
                ✗ <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <p style="margin: 0; color: #1976D2; font-size: 14px;"><strong>📅 Important:</strong> First select your check-in and check-out dates, then choose the room type to see available rooms for your dates.</p>
        </div>

        <form method="POST" action="" id="bookingForm" class="styled-form">
            <!-- Booking Dates -->
            <div class="form-section">
                <h2 class="section-title">Booking Dates</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="checkin_date">Check-in Date <span style="color:red">*</span></label>
                        <input type="date" id="checkin_date" name="checkin_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="checkout_date">Check-out Date <span style="color:red">*</span></label>
                        <input type="date" id="checkout_date" name="checkout_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                </div>
                <div class="availability-note">
                    💡 Select your dates first to see which rooms are available for your stay period
                </div>
            </div>

            <!-- Room Selection -->
            <div class="form-section">
                <h2 class="section-title">Room Selection</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="room_type">Room Type <span style="color:red">*</span></label>
                        <select id="room_type" name="room_type" required>
                            <option value="">-- Select Room Type --</option>
                            <?php while ($type = mysqli_fetch_assoc($room_types_result)): ?>
                                <option value="<?php echo $type['id']; ?>" 
                                        data-price="<?php echo $type['price']; ?>"
                                        data-max-persons="<?php echo $type['max_persons']; ?>"
                                        <?php echo ($preselected_room_type == $type['id']) ? 'selected' : ''; ?>>
                                    <?php echo $type['name']; ?> - NPR <?php echo number_format($type['price'], 2); ?>/night
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="room_id">Available Room <span style="color:red">*</span></label>
                        <select id="room_id" name="room_id" required disabled>
                            <option value="">-- Select dates and room type first --</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid single-column" style="margin-top: 20px;">
                    <div class="form-group">
                        <label for="estimated_total">Estimated Total</label>
                        <input type="text" id="estimated_total" readonly value="NPR 0.00" style="background-color: #f8f9fa;">
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="form-section">
                <h2 class="section-title">Customer Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="fname">First Name <span style="color:red">*</span></label>
                        <input type="text" id="fname" name="fname" required>
                    </div>

                    <div class="form-group">
                        <label for="lname">Last Name <span style="color:red">*</span></label>
                        <input type="text" id="lname" name="lname" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number <span style="color:red">*</span></label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span style="color:red">*</span></label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address <span style="color:red">*</span></label>
                        <input type="text" id="address" name="address" required>
                    </div>

                    <div class="form-group">
                        <label for="id_number">ID Number <span style="color:red">*</span></label>
                        <input type="text" id="id_number" name="id_number" required>
                    </div>
                </div>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Create Booking</button>
                <?php if ($user_role == 'customer'): ?>
                    <a href="../customer/my_bookings.php" class="btn btn-secondary">My Bookings</a>
                <?php endif; ?>
                <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const preselectedRoomType = <?php echo $preselected_room_type; ?>;
    
    window.addEventListener('DOMContentLoaded', function() {
        if (preselectedRoomType > 0) {
            const checkinDate = document.getElementById('checkin_date').value;
            const checkoutDate = document.getElementById('checkout_date').value;
            if (checkinDate && checkoutDate) {
                loadRoomsForType(preselectedRoomType);
            }
        }
    });

    document.getElementById('room_type').addEventListener('change', function() {
        const typeId = this.value;
        const checkinDate = document.getElementById('checkin_date').value;
        const checkoutDate = document.getElementById('checkout_date').value;
        
        if (typeId && checkinDate && checkoutDate) {
            loadRoomsForType(typeId);
        } else if (typeId && (!checkinDate || !checkoutDate)) {
            alert('Please select check-in and check-out dates first to see available rooms.');
            this.value = '';
        } else {
            const roomSelect = document.getElementById('room_id');
            roomSelect.disabled = true;
            roomSelect.innerHTML = '<option value="">-- Select dates and room type first --</option>';
        }
        calculateTotal();
    });

    function loadRoomsForType(typeId) {
        const roomSelect = document.getElementById('room_id');
        const checkinDate = document.getElementById('checkin_date').value;
        const checkoutDate = document.getElementById('checkout_date').value;
        
        if (!checkinDate || !checkoutDate) {
            roomSelect.disabled = true;
            roomSelect.innerHTML = '<option value="">-- Select dates first --</option>';
            return;
        }
        
        roomSelect.disabled = false;
        roomSelect.innerHTML = '<option value="">Loading available rooms...</option>';
        
        fetch(`../../system/get_available_rooms.php?type_id=${typeId}&checkin=${checkinDate}&checkout=${checkoutDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.rooms.length > 0) {
                    roomSelect.innerHTML = '<option value="">-- Select Room Number --</option>';
                    data.rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `Room ${room.room_number}`;
                        roomSelect.appendChild(option);
                    });
                    
                    if (data.total_rooms === 1) {
                        console.log(`${data.total_rooms} room available for selected dates`);
                    } else {
                        console.log(`${data.total_rooms} rooms available for selected dates`);
                    }
                } else {
                    roomSelect.innerHTML = '<option value="">No rooms available for selected dates</option>';
                    roomSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                roomSelect.innerHTML = '<option value="">Error loading rooms</option>';
                roomSelect.disabled = true;
            });
    }

    function calculateTotal() {
        const roomType = document.getElementById('room_type');
        const checkinDate = document.getElementById('checkin_date').value;
        const checkoutDate = document.getElementById('checkout_date').value;
        const totalInput = document.getElementById('estimated_total');
        
        if (roomType.value && checkinDate && checkoutDate) {
            const selectedOption = roomType.options[roomType.selectedIndex];
            const pricePerNight = parseFloat(selectedOption.dataset.price);
            
            checkin = new Date(checkinDate);
            checkout = new Date(checkoutDate);
            const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                const total = pricePerNight * nights;
                totalInput.value = `NPR ${total.toFixed(2)} (${nights} night${nights > 1 ? 's' : ''})`;
            } else {
                totalInput.value = 'NPR 0.00';
            }
        } else {
            totalInput.value = 'NPR 0.00';
        }
    }

    document.getElementById('checkin_date').addEventListener('change', function() {
        const checkoutDate = document.getElementById('checkout_date');
        const minCheckout = new Date(this.value);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkoutDate.min = minCheckout.toISOString().split('T')[0];
        
        const roomType = document.getElementById('room_type').value;
        if (roomType && checkoutDate.value) {
            loadRoomsForType(roomType);
        }
        
        calculateTotal();
    });

    document.getElementById('checkout_date').addEventListener('change', function() {
        const roomType = document.getElementById('room_type').value;
        const checkinDate = document.getElementById('checkin_date').value;
        if (roomType && checkinDate) {
            loadRoomsForType(roomType);
        }
        
        calculateTotal();
    });

    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const checkinDate = new Date(document.getElementById('checkin_date').value);
        const checkoutDate = new Date(document.getElementById('checkout_date').value);
        
        if (checkoutDate <= checkinDate) {
            e.preventDefault();
            alert('Check-out date must be after check-in date!');
            return false;
        }
    });
</script>

<?php loadFooter(); ?>