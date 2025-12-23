<?php
function sidebar_recep($username) {
echo "
<div class='sidebar'>
<h5 class='mb-0'><strong>{$username}</strong></h5>
<p>Receptionist</p>

<a href='/hms/app/reception/dashboard.php'>Dashboard</a>
<a href='/hms/app/reception/create_booking.php'>Reservations</a>
<a href='/hms/app/reception/rooms.php'>Rooms</a>


</div>
";
}


function receptionDashboard($data) {
echo "
<div class='main'>

    <div class='topbar d-flex justify-content-between align-items-center'>
    <h4>Receptionist Dashboard</h4>
        

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
function rooms_table_recep($result) {

    echo '
    <div class="page-header">
        <h2>View Rooms</h2>
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
            : "<a href='../reception/create_booking_tr.php?id={$row['id']}' class='badge badge-green'>Book Room</a>";

            if($row['availability'] == 1)
            {
        $checkInBtn = ($row['status'] == 1 )
            ? "<span class='badge badge-yellow'>Checked In</span>"
            : "<a href='../reception/check_in.php?id={$row['id']}' class='badge badge-yellow'>Check In</a>";
            }
            else{
               $checkInBtn = "-";
            }
        $checkOutBtn = ($row['status'] == 1)
            ? "<a href='../reception/check_out.php?id={$row['id']}' class='badge badge-blue'>Check Out</a>"
            : "-";

        echo "
        <tr>
            <td>{$row['room_number']}</td>
            <td>{$row['name']}</td>
            <td>$bookingBadge</td>
            <td>$checkInBtn</td>
            <td>$checkOutBtn</td>

            <td class='action-buttons'>
                <a class='btn-view' href='room_view.php?id={$row['id']}'><i class='fa fa-eye'></i></a>
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

function rooms_table_reception_available($result) {
    echo "<table class='rooms_table'>
        <tr>
            <th>ID</th>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>".$row['id']."</td>
            <td>".$row['room_number']."</td>
            <td>".$row['name']."</td>
            <td>".$row['status']."</td>
            <td class='ops'>
                <a class='check_in' href='check_in.php?room_id={$row['id']}'
                onclick='return confirm(\"Are you sure?\")'>
                Check In</a>
            </td>
        </tr>";
    }

    echo "</table>";
}
function rooms_table_reception_booked($result) {
    echo "<table class='rooms_table'>
        <tr>
            <th>ID</th>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>".$row['id']."</td>
            <td>".$row['room_number']."</td>
            <td>".$row['name']."</td>
            <td>".$row['status']."</td>
            <td class='ops'>
                <a class='check_in' href='check_out.php?room_id={$row['id']}'
                onclick='return confirm(\"Are you sure?\")'>
                Check Out</a>
            </td>
        </tr>";
    }

    echo "</table>";
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

?>