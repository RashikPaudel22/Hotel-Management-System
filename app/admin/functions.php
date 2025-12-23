<?php
function sidebar($username) {
echo "
<div class='sidebar'>
<h5 class='mb-0'><strong>{$username}</strong></h5>
<p>Admin</p>

<a href='/hms/app/admin/dashboard.php'>Dashboard</a>
<a href='/hms/app/admin/book_rooms/create_booking.php'>Reservations</a>
<a href='/hms/app/admin/room.php'>Manage Rooms</a>
<a href='/hms/app/admin/users.php'>Staff</a>
<a href='/hms/app/admin/reports.php'>Complaints</a>
<a href='/hms/stats.php'>Statistics</a>


</div>
";
}
function adminDashboard($data,) {
echo "
<div class='main'>

    <div class='topbar d-flex justify-content-between align-items-center'>
    <h4>Admin Dashboard</h4>
        

        <a href='../../system/logout.php' 
           class='btn btn-sm btn-danger'>
           Logout
        </a>
    </div>

<div class='row g-4'>
<div class='col-md-3'>
<div class='stat-card'>

<p>{$data['rooms']}</p>
<h3>Total Rooms</h3>

</div>
</div>


<div class='col-md-3'>
<div class='stat-card'>

<p>{$data['bookings']}</p>
<h3>Bookings</h3>
</div>
</div>


<div class='col-md-3'>
<div class='stat-card'>

<p>{$data['users']}</p>
<h3>Staff</h3>
</div>
</div>


<div class='col-md-3'>
<div class='stat-card'>

<p>{$data['available']}</p>
<h3>Available Rooms</h3>
</div>
</div>


<div class='col-md-3'>
<div class='stat-card'>

<p>{$data['booked']}</p>
<h3>Booked Rooms</h3>
</div>
</div>
<div class='col-md-3'>
<div class='stat-card'>

<p>{$data['checked_in']}</p>
<h3>Checked In</h3>
</div>
</div>
</div>
</div>
";
}
function admin_reports_page($reports)
{
    echo "
    <h2>Customer Reports</h2>

    <table class='reports-table'>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Booking ID</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    ";

    if (empty($reports)) {
        echo "
        <tr>
            <td colspan='8' style='text-align:center;'>No reports found</td>
        </tr>
        ";
    } else {
        foreach ($reports as $r) {

            $booking = $r['booking_id'] ?? '—';

            echo "
            <tr>
                <td>{$r['id']}</td>
                <td>{$r['fname']} {$r['lname']}</td>
                <td>{$r['email']}</td>
                <td>{$booking}</td>
                <td>{$r['subject']}</td>
                <td class='message'>{$r['message']}</td>
                <td>
                    <span class='status {$r['status']}'>
                        {$r['status']}
                    </span>
                </td>
                <td>{$r['created_at']}</td>
            </tr>
            ";
        }
    }

    echo "</table>";
}
function edit_room_form($room, $typesResult) {

    echo "
    <div class='section-box' style='max-width:500px;'>

        <div class='section-title'>Edit Room</div>

        <form method='POST'>

            <div class='mb-3'>
                <label class='form-label'>Room Number</label>
                <input type='number' name='room_number'
                       value='{$room['room_number']}'
                       class='form-control' required>
            </div>

            <div class='mb-3'>
                <label class='form-label'>Room Type</label>
                <select name='room_type' class='form-select' required>";
                    
                    while ($type = mysqli_fetch_assoc($typesResult)) {
                        $selected = ($type['id'] == $room['type_id']) ? "selected" : "";
                        echo "<option value='{$type['id']}' $selected>{$type['name']}</option>";
                    }

    echo "
                </select>
            </div>

            <div class='mb-3'>
                <label class='form-label'>Status</label>
                <select name='status' class='form-select' required>
                    <option value='available' ".($room['status']=='available'?'selected':'').">Available</option>
                    <option value='maintenance' ".($room['status']=='maintenance'?'selected':'').">Maintenance</option>
                </select>
            </div>

            <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-primary'>Update Room</button>
                <a href='room.php' class='btn btn-secondary'>Cancel</a>
            </div>

        </form>
    </div>
    ";
}
function rooms_table($result) {

    echo '
    <div class="page-header">
        <h2>Manage Rooms</h2>
        <a href="room_add.php" class="add-room-btn">Add Rooms</a>
    </div>

    <div class="table-responsive">
    <table class="modern-table">
        <thead>
            <tr>
                <th>Room No</th>
                <th>Room Type</th>
                <th>Booking Status</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody">
    ';

    while ($row = $result->fetch_assoc()) {

        $bookingBadge = ($row['availability'] == 1)
            ? "<span class='badge badge-red'>Booked</span>"
            : "<a href='../admin/book_rooms/create_booking_tr.php?id={$row['id']}' class='badge badge-green'>Book Room</a>";

            if($row['availability'] == 1)
            {
        $checkInBtn = ($row['status'] == 1 )
            ? "<span class='badge badge-yellow'>Checked In</span>"
            : "<a href='../admin/book_rooms/check_in.php?id={$row['id']}' class='badge badge-yellow'>Check In</a>";
            }
            else{
               $checkInBtn = "-";
            }
        $checkOutBtn = ($row['status'] == 1)
            ? "<a href='../admin/book_rooms/check_out.php?id={$row['id']}' class='badge badge-blue'>Check Out</a>"
            : "-";

        echo "
        <tr>
            <td>{$row['room_number']}</td>
            <td>{$row['name']}</td>
            <td>$bookingBadge</td>
            <td>$checkInBtn</td>
            <td>$checkOutBtn</td>

            <td class='action-buttons'>
                <a class='btn-edit' href='room_edit.php?id={$row['id']}'><i class='fa fa-edit'></i></a>
                <a class='btn-view' href='room_view.php?id={$row['id']}'><i class='fa fa-eye'></i></a>
                <a class='btn-delete' onclick='return confirm(\"Are you sure?\")'
                    href='room_delete.php?id={$row['id']}'><i class='fa fa-trash'></i></a>
            </td>
        </tr>";
    }

    echo "</tbody></table></div>";

    // -------- CSS BELOW --------
    echo "
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .add-room-btn {
            background: #555;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .modern-table th, .modern-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .modern-table th {
            background: #f7f7f7;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 5px;
            color: #fff;
            font-size: 12px;
        }
        .badge-red { background: #e74c3c; }
        .badge-green { background: #2ecc71; text-decoration:none; }
        .badge-yellow { background: #f1c40f; color:black; }
        .badge-blue { background: #3498db; }

        .action-buttons a {
            margin: 0 5px;
            padding: 8px 10px;
            border-radius: 50%;
            color: white;
            text-align: center;
        }

        .btn-edit { background: #3498db; }
        .btn-view { background: #f1c40f; color:black; }
        .btn-delete { background: #e74c3c; }
    </style>
    ";
}

function user_edit_form($user) {
    echo "
    <div class='section-box' style='max-width:500px;'>

        <div class='section-title'>Edit Staff</div>

        <form method='POST'>

            <div class='mb-3'>
                <label class='form-label'>Username</label>
                <input type='text' name='username' class='form-control'
                       value='{$user['username']}' required>
            </div>

            <div class='mb-3'>
                <label class='form-label'>New Password 
                    <small class='text-muted'>(leave blank to keep current)</small>
                </label>
                <input type='password' name='password' class='form-control'>
            </div>

            <div class='mb-3'>
                <label class='form-label'>Role</label>
                <select name='role' class='form-select' required>
                    <option value='admin' ".($user['role']=='admin'?'selected':'').">Admin</option>
                    <option value='receptionist' ".($user['role']=='receptionist'?'selected':'').">Receptionist</option>
                </select>
            </div>

            <div class='d-flex gap-2'>
                <button type='submit' name='update_user' class='btn btn-primary'>
                    Update Staff
                </button>
                <a href='users.php' class='btn btn-secondary'>Cancel</a>
            </div>

        </form>
    </div>
    ";
}
function users_table($data) {
    echo "
    <div class='section-box'>
        <div class='section-title d-flex justify-content-between align-items-center'>
            <span><h2>Staff Members</h2></span>
            <a href='user_add.php' class='btn btn-sm btn-primary'>+ Add Staff</a>
        </div>

        <table class='table table-hover align-middle'>
            <thead class='table-light'>
                <tr>
                    <th>SN</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th width='180'>Actions</th>
                </tr>
            </thead>
            <tbody>
    ";

    if (empty($data)) {
        echo "
        <tr>
            <td colspan='5' class='text-center text-muted'>No staff found</td>
        </tr>
        ";
    }

    $sn = 1;
    foreach ($data as $row) {
        echo "
        <tr>
            <td>{$sn}</td>
            <td>{$row['id']}</td>
            <td>{$row['username']}</td>
            <td>
                <span class='badge bg-info text-dark'>{$row['role']}</span>
            </td>
            <td>
                <a href='user_edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                <a href='user_delete.php?id={$row['id']}' 
                   class='btn btn-sm btn-danger'
                   onclick='return confirm(\"Delete this staff member?\")'>
                   Delete
                </a>
            </td>
        </tr>
        ";
        $sn++;
    }

    echo "
            </tbody>
        </table>
    </div>
    ";
}

function user_add()
{
    echo"
    <style>
    /* ---------- SECTION BOX ---------- */
.section-box {
    background: #ffffff;
    padding: 28px;
    border-radius: 14px;
    margin: 40px auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.35s ease-in-out;
}

/* ---------- TITLE ---------- */
.section-title {
    text-align: center;
    margin-bottom: 24px;
}

.section-title h2 {
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

/* ---------- FORM LABEL ---------- */
.form-label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
    display: block;
}

/* ---------- INPUTS ---------- */
.form-control,
.form-select {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #dcdcdc;
    font-size: 14px;
    background: #fafafa;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    outline: none;
    background: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
}

/* ---------- SPACING ---------- */
.mb-3 {
    margin-bottom: 18px;
}

/* ---------- BUTTONS ---------- */
.btn {
    padding: 11px 18px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
}

/* Primary */
.btn-primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #ffffff;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
}

/* Secondary */
.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
}

/* ---------- FLEX ---------- */
.d-flex {
    display: flex;
}

.gap-2 {
    gap: 12px;
}

/* ---------- ANIMATION ---------- */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
    
    ";
    echo "
    <div class='section-box' style='max-width:500px;'>

        <div class='section-title'><h2>Add New Staff</h2></div>

        <form method='POST'>

            <div class='mb-3'>
                <label class='form-label'>Username</label>
                <input type='text' name='username' class='form-control' required>
            </div>

            <div class='mb-3'>
                <label class='form-label'>Password</label>
                <input type='password' name='password' class='form-control' required>
            </div>

            <div class='mb-3'>
                <label class='form-label'>Role</label>
                <select name='role' class='form-select' required>
                    <option value=''>Select Role</option>
                    <option value='receptionist'>Receptionist</option>
                    <option value='admin'>Admin</option>
                </select>
            </div>

            <div class='d-flex gap-2'>
                <button type='submit' class='btn btn-primary'>Add Staff</button>
                <a href='users.php' class='btn btn-secondary'>Cancel</a>
            </div>

        </form>
    </div>
    ";
}
function booking_form($availableRooms,$room_type)
{
    echo "
    <style>
        .section-box{
            background:#fff;
            padding:25px;
            border-radius:8px;
            margin-bottom:20px;
            box-shadow:0 2px 6px rgba(0,0,0,0.1);
        }
        .section-title{
            font-size:20px;
            font-weight:600;
            margin-bottom:15px;
            color:#333;
        }
        .grid-2{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
        }
        label{
            font-weight:500;
            margin-bottom:5px;
            display:block;
            color:#555;
        }
        input, select{
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:6px;
            margin-bottom:15px;
        }
        .submit-btn{
            background:#4CAF50;
            padding:12px 22px;
            border:none;
            color:white;
            border-radius:6px;
            cursor:pointer;
            font-size:16px;
            margin-top:10px;
        }
        .submit-btn:hover{
            background:#43a047;
        }
        .info-box{
            font-size:16px;
            margin-top:10px;
            color:#444;
            padding:15px;
            background:#f5f5f5;
            border-radius:6px;
        }
    </style>

    <form action='' method='POST'>

        <!-- ROOM INFORMATION -->
        <div class='section-box'>
            <div class='section-title'>Room Information:</div>

            <div class='grid-2'>
                <div>
                    <label>Room Type</label>
                    <select name='room_type' id='room_type' required>
                        <option value='' >Select Room Type</option>";
                        while($row = mysqli_fetch_assoc($room_type)) {
                        echo "<option value='".$row['id']."' data-price='".$row['price']."'>".$row['name']." - $".$row['price']."/night</option>";
                        }
                 echo"   </select>
                </div>

                <div>
                    <label>Room No</label>
                    <select name='room' id='room' required>
                        <option value=''>Select Room</option>";

                        while($row = mysqli_fetch_assoc($availableRooms)) {
                            echo "<option value='".$row['id']."' data-room-type='".$row['room_type_id']."'>".$row['room_number']."</option>";
                        }

    echo "      </select>
                </div>

                <div>
                    <label>Check In Date</label>
                    <input type='date' name='check_in_date' id='check_in_date' required>
                </div>

                <div>
                    <label>Check Out Date</label>
                    <input type='date' name='check_out_date' id='check_out_date' required>
                </div>
            </div>

            <div class='info-box'>
                <strong>Total Days:</strong> <span id='total_days'>0</span> Days<br>
                <strong>Price per Night:</strong> $<span id='price_per_night'>0</span><br>
                <strong>Total Amount:</strong> $<span id='total_amount'>0</span>
            </div>
        </div>

        <!-- CUSTOMER DETAILS -->
        <div class='section-box'>
            <div class='section-title'>Customer Detail:</div>

            <div class='grid-2'>
                <div>
                    <label>First Name</label>
                    <input type='text' name='fname' required>
                </div>

                <div>
                    <label>Last Name</label>
                    <input type='text' name='lname' required>
                </div>

                <div>
                    <label>Contact Number</label>
                    <input type='text' name='cus_number' required>
                </div>

                <div>
                    <label>ID Card Type</label>
                    <select name='cus_id_type' required>
                        <option value=''>Select ID Card Type</option>
                        <option value='NID'>NID</option>
                        <option value='Passport'>Passport</option>
                        <option value='Driving License'>Driving License</option>
                    </select>
                </div>

                <div>
                    <label>Email Address</label>
                    <input type='email' name='email'>
                </div>

                <div>
                    <label>ID Card Number</label>
                    <input type='text' name='cus_verification' required>
                </div>

                <div>
                    <label>Residential Address</label>
                    <input type='text' name='address'>
                </div>
            </div>
        </div>

        <button type='submit' class='submit-btn'>Create Booking</button>
    </form>

    <script>
    // Set minimum date to today
// Set min & max booking window (today → 10 days ahead)
const today = new Date();
const maxDate = new Date();
maxDate.setDate(today.getDate() + 10);

const minDateStr = today.toISOString().split('T')[0];
const maxDateStr = maxDate.toISOString().split('T')[0];

document.getElementById('check_in_date').setAttribute('min', minDateStr);
document.getElementById('check_in_date').setAttribute('max', maxDateStr);

document.getElementById('check_out_date').setAttribute('min', minDateStr);


    // Store room prices
    let currentPrice = 0;

    // Function to calculate total
    function calculateTotal() {
        const checkIn = document.getElementById('check_in_date').value;
        const checkOut = document.getElementById('check_out_date').value;

        if (checkIn && checkOut && currentPrice > 0) {
            const date1 = new Date(checkIn);
            const date2 = new Date(checkOut);
            
            // Calculate difference in days
            const diffTime = date2 - date1;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays > 0) {
                const totalAmount = diffDays * currentPrice;
                
                document.getElementById('total_days').textContent = diffDays;
                document.getElementById('price_per_night').textContent = currentPrice.toFixed(2);
                document.getElementById('total_amount').textContent = totalAmount.toFixed(2);
            } else {
                resetDisplay();
                if (diffDays < 0) {
                    alert('Check-out date must be after check-in date!');
                }
            }
        } else {
            resetDisplay();
        }
    }

    // Function to reset display
    function resetDisplay() {
        document.getElementById('total_days').textContent = '0';
        document.getElementById('price_per_night').textContent = '0';
        document.getElementById('total_amount').textContent = '0';
    }

    // Event listener for room type selection
    document.getElementById('room_type').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        currentPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        
        // Filter rooms by room type
        const roomTypeId = this.value;
        const roomSelect = document.getElementById('room');
        const allOptions = roomSelect.querySelectorAll('option');
        
        allOptions.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
            } else if (option.getAttribute('data-room-type') === roomTypeId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset room selection
        roomSelect.value = '';
        
        calculateTotal();
    });

    // Event listeners for date changes
    document.getElementById('check_in_date').addEventListener('change', function() {
        const checkOutDate = document.getElementById('check_out_date');
        checkOutDate.setAttribute('min', this.value);
        calculateTotal();
    });

    document.getElementById('check_out_date').addEventListener('change', calculateTotal);
    </script>
    ";
}



function booking_form_tr($availableRooms,$room_type,$room)
{
    echo "
    <style>
        .section-box{
            background:#fff;
            padding:25px;
            border-radius:8px;
            margin-bottom:20px;
            box-shadow:0 2px 6px rgba(0,0,0,0.1);
        }
        .section-title{
            font-size:20px;
            font-weight:600;
            margin-bottom:15px;
            color:#333;
        }
        .grid-2{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
        }
        label{
            font-weight:500;
            margin-bottom:5px;
            display:block;
            color:#555;
        }
        input, select{
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:6px;
            margin-bottom:15px;
        }
        .submit-btn{
            background:#4CAF50;
            padding:12px 22px;
            border:none;
            color:white;
            border-radius:6px;
            cursor:pointer;
            font-size:16px;
            margin-top:10px;
        }
        .submit-btn:hover{
            background:#43a047;
        }
        .info-box{
            font-size:16px;
            margin-top:10px;
            color:#444;
        }
    </style>";
echo "
<form action='' method='POST'>

    <div class='section-box'>
        <div class='section-title'>Room Information:</div>

        <div class='grid-2'>
            <div>
                <label>Room Type</label>
                <input type='text' value='" . htmlspecialchars($room['room_type']) . "' readonly>
            </div>

            <div>
                <label>Room No</label>

                <input type='hidden' name='room_id' value='" . $room['id'] . "'>

                <input type='text' value='" . $room['room_number'] . "' readonly>
            </div>
        </div>
    </div>
        <div class='grid-2'>
            <div>
                <label>Check In Date</label>
                <input type='date' name='check_in_date' required>
            </div>

            <div>
                <label>Check Out Date</label>
                <input type='date' name='check_out_date' required>
            </div>
        </div>

        <div class='section-box'>
            <div class='section-title'>Customer Detail:</div>

            <div class='grid-2'>
                <div>
                    <label>First Name</label>
                    <input type='text' name='fname' required>
                </div>

                <div>
                    <label>Last Name</label>
                    <input type='text' name='lname' required>
                </div>

                <div>
                    <label>Contact Number</label>
                    <input type='text' name='cus_number' required>
                </div>
                <div>
                <label>ID Card Type</label>
                <select name='cus_id_type' required>
                        <option value=''>Select ID Card Type</option>
                        <option value='NID'>NID</option>
                        <option value='Passport'>Passport</option>
                        <option value='Driving License'>Driving License</option>
                </select>
                </div>
                <div>
                    <label>Email Address</label>
                    <input type='email' name='email'>
                </div>

                <div>
                    <label>ID Card Number</label>
                    <input type='text' name='cus_verification' required>
                </div>

                <div>
                    <label>Residential Address</label>
                    <input type='text' name='address'>
                </div>
            </div>
        </div>

        <button type='submit' class='submit-btn'>Create Booking</button>
    </form>
    ";
}
function add_room_form($typesResult,$room_type)
{
    echo"
    <style>
    /* ---------- CARD ---------- */
.card {
    background: #ffffff;
    border-radius: 14px;
    padding: 30px;
    max-width: 520px;
    margin: 40px auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.4s ease-in-out;
}

.card h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
}

/* ---------- FORM GROUP ---------- */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    font-weight: 500;
    color: #555;
}

/* ---------- INPUTS & SELECT ---------- */
.form-control {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #dcdcdc;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #fafafa;
}

.form-control:focus {
    outline: none;
    border-color: #4f46e5;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
}

/* ---------- BUTTON ---------- */
.btn {
    width: 100%;
    padding: 13px;
    border-radius: 12px;
    border: none;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #ffffff;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
}

/* ---------- ALERTS ---------- */
.alert {
    max-width: 520px;
    margin: 20px auto;
    padding: 14px 18px;
    border-radius: 10px;
    font-size: 14px;
}

.alert-success {
    background: #e8f7ee;
    color: #1e7f4f;
    border: 1px solid #bce5cf;
}

.alert-danger {
    background: #fdeaea;
    color: #a61b1b;
    border: 1px solid #f5bcbc;
}

/* ---------- ANIMATION ---------- */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
    ";
    echo"
    <div class='card'>
        <h2>Add New Room</h2>

        <form method='POST'>

            <!-- Room Number -->
            <div class='form-group'>
                <label for='room_number'>Room Number</label>
                <input 
                    type='text' 
                    name='room_number' 
                    id='room_number' 
                    class='form-control' 
                    required
                >
            </div>

            <!-- Room Type -->
            <div class='form-group'>
                <label for='room_type'>Room Type</label>
                                    <select name='room_type' id='room_type' required>
                        <option value='' >Select Room Type</option>";
                        while($row = mysqli_fetch_assoc($room_type)) {
                        echo "<option value='".$row['id']."'>".$row['name']."</option>";
                        }
                        echo"</select>
            </div>

            <!-- Status -->
            <div class='form-group'>
                <label for='status'>Room Status</label>
                <select 
                    name='status' 
                    id='status' 
                    class='form-control' 
                    required
                >
                    <option value='0'>Available</option>
                    <option value='1'>Booked</option>
                </select>
            </div>

            <!-- Submit -->
            <div class='form-group'>
                <button type='submit' class='btn btn-primary'>
                    Add Room
                </button>
            </div>

        </form>
    </div>

    ";
}


?>