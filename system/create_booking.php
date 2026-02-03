<?php
session_start();

// Debug: Check what's in the session (remove this after fixing)
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; exit;

require_once 'database.php';

// More flexible session check - check multiple possible session variable names
$is_logged_in = false;
$user_role = 'customer'; // default role
$currentUser = getCurrentUser();
$username = $currentUser['username'] ?? 'Customer';

// Check for nested session array structure (your system)
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $is_logged_in = true;
    $user_role = $_SESSION['user']['role'];
}
// Fallback: Check for flat session structure
elseif (isset($_SESSION['user_id']) || isset($_SESSION['id']) || isset($_SESSION['username'])) {
    $is_logged_in = true;
    
    // Check for role in different possible session keys
    if (isset($_SESSION['role'])) {
        $user_role = $_SESSION['role'];
    } elseif (isset($_SESSION['user_role'])) {
        $user_role = $_SESSION['user_role'];
    } elseif (isset($_SESSION['user_id'])) {
        // Fetch role from database if not in session
        $user_id = $_SESSION['user_id'];
        $role_query = "SELECT role FROM users WHERE id = $user_id";
        $role_result = mysqli_query($conn, $role_query);
        if ($role_result && mysqli_num_rows($role_result) > 0) {
            $role_data = mysqli_fetch_assoc($role_result);
            $user_role = $role_data['role'];
        }
    }
}

// For now, allow access even if not logged in (for customer bookings)
// You can enable this redirect later once session is working
/*
if (!$is_logged_in) {
    header("Location: ../login.php");
    exit();
}
*/

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
    
    // Validate dates
    if (strtotime($checkin_date) >= strtotime($checkout_date)) {
        $error_message = "Check-out date must be after check-in date!";
    } else {
        // Check if room is available for selected dates
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
            // Start transaction
            mysqli_begin_transaction($conn);
            
            try {
                // Insert customer
                $customer_query = "INSERT INTO customers (fname, lname, phone, email, address, id_number) 
                                 VALUES ('$fname', '$lname', '$phone', '$email', '$address', '$id_number')";
                
                if (mysqli_query($conn, $customer_query)) {
                    $customer_id = mysqli_insert_id($conn);
                    
                    // Calculate total price
                    $room_query = "SELECT rt.price, DATEDIFF('$checkout_date', '$checkin_date') as days 
                                  FROM rooms r 
                                  JOIN room_types rt ON r.type_id = rt.id 
                                  WHERE r.id = $room_id";
                    $room_result = mysqli_query($conn, $room_query);
                    $room_data = mysqli_fetch_assoc($room_result);
                    $total_price = $room_data['price'] * $room_data['days'];
                    
                    // Insert booking
                    $booking_query = "INSERT INTO bookings (customer_id, room_id, checkin_date, checkout_date, total_price, status) 
                                    VALUES ($customer_id, $room_id, '$checkin_date', '$checkout_date', $total_price, 'pending')";
                    
                    if (mysqli_query($conn, $booking_query)) {
                        // Don't update room availability permanently - it's based on date ranges now
                        // The room will show as unavailable only for the booked dates
                        
                        mysqli_commit($conn);
                        $booking_id = mysqli_insert_id($conn);
                        
                        // Different redirects based on user role
                        if ($user_role == 'customer') {
                            // Customers go to their bookings page
                            header("Location: ../app/customer/my_bookings.php?booking_success=" . $booking_id . "&amount=" . $total_price);
                            exit();
                        } else {
                            // Admin/Reception see success message and can continue booking
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking - HMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 13px;
        }

        .form-container {
            padding: 40px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-grid.single-column {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-group label span {
            color: #dc3545;
        }

        .form-group input,
        .form-group select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .container {
                margin: 10px;
            }

            .form-container {
                padding: 20px;
            }

            .btn-container {
                flex-direction: column;
            }
        }

        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box p {
            margin: 0;
            color: #1976D2;
            font-size: 14px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏨 Create New Booking</h1>
            <p>Fill in the details below to create a new reservation</p>
            <?php if ($is_logged_in): ?>
                <div class="user-info">
                    Logged in as: <?php echo ucfirst($username); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-container">
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
                <p><strong>📅 Important:</strong> First select your check-in and check-out dates, then choose the room type to see available rooms for your dates.</p>
            </div>

            <form method="POST" action="" id="bookingForm">
                <!-- Booking Details - MOVED TO TOP -->
                <div class="form-section">
                    <h2 class="section-title">Booking Dates</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="checkin_date">Check-in Date <span>*</span></label>
                            <input type="date" id="checkin_date" name="checkin_date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="checkout_date">Check-out Date <span>*</span></label>
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
                            <label for="room_type">Room Type <span>*</span></label>
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
                            <label for="room_id">Available Room <span>*</span></label>
                            <select id="room_id" name="room_id" required disabled>
                                <option value="">-- Select dates and room type first --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid single-column" style="margin-top: 20px;">
                        <div class="form-group">
                            <label for="estimated_total">Estimated Total</label>
                            <input type="text" id="estimated_total" readonly value="NPR 0.00">
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="form-section">
                    <h2 class="section-title">Customer Information</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fname">First Name <span>*</span></label>
                            <input type="text" id="fname" name="fname" required>
                        </div>

                        <div class="form-group">
                            <label for="lname">Last Name <span>*</span></label>
                            <input type="text" id="lname" name="lname" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number <span>*</span></label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span>*</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="address">Address <span>*</span></label>
                            <input type="text" id="address" name="address" required>
                        </div>

                        <div class="form-group">
                            <label for="id_number">ID Number <span>*</span></label>
                            <input type="text" id="id_number" name="id_number" required>
                        </div>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">Create Booking</button>
                    <?php if ($user_role == 'customer'): ?>
                        <a href="../app/customer/my_bookings.php" class="btn btn-secondary">My Bookings</a>
                    <?php endif; ?>
                    <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preselected room type from URL
        const preselectedRoomType = <?php echo $preselected_room_type; ?>;
        
        // On page load, if room type is preselected, load rooms
        window.addEventListener('DOMContentLoaded', function() {
            if (preselectedRoomType > 0) {
                const checkinDate = document.getElementById('checkin_date').value;
                const checkoutDate = document.getElementById('checkout_date').value;
                if (checkinDate && checkoutDate) {
                    loadRoomsForType(preselectedRoomType);
                }
            }
        });

        // Dynamic room loading based on room type AND dates
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
            
            // Enable room select
            roomSelect.disabled = false;
            roomSelect.innerHTML = '<option value="">Loading available rooms...</option>';
            
            // Fetch available rooms for this type and date range
            fetch(`get_available_rooms.php?type_id=${typeId}&checkin=${checkinDate}&checkout=${checkoutDate}`)
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
                        
                        // Show info message
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

        // Calculate total price
        function calculateTotal() {
            const roomType = document.getElementById('room_type');
            const checkinDate = document.getElementById('checkin_date').value;
            const checkoutDate = document.getElementById('checkout_date').value;
            const totalInput = document.getElementById('estimated_total');
            
            if (roomType.value && checkinDate && checkoutDate) {
                const selectedOption = roomType.options[roomType.selectedIndex];
                const pricePerNight = parseFloat(selectedOption.dataset.price);
                
                const checkin = new Date(checkinDate);
                const checkout = new Date(checkoutDate);
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

        // Add event listeners for date changes
        document.getElementById('checkin_date').addEventListener('change', function() {
            const checkoutDate = document.getElementById('checkout_date');
            const minCheckout = new Date(this.value);
            minCheckout.setDate(minCheckout.getDate() + 1);
            checkoutDate.min = minCheckout.toISOString().split('T')[0];
            
            // Reload rooms if room type is already selected
            const roomType = document.getElementById('room_type').value;
            if (roomType && checkoutDate.value) {
                loadRoomsForType(roomType);
            }
            
            calculateTotal();
        });

        document.getElementById('checkout_date').addEventListener('change', function() {
            // Reload rooms if room type is already selected
            const roomType = document.getElementById('room_type').value;
            const checkinDate = document.getElementById('checkin_date').value;
            if (roomType && checkinDate) {
                loadRoomsForType(roomType);
            }
            
            calculateTotal();
        });

        // Form validation
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
</body>
</html>