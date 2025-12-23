<?php
function customer_report_form() {
    echo"
    <style>
    .form-card {
    max-width: 600px;
    margin: 40px auto;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.form-card h2 {
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}

.styled-form .form-group {
    margin-bottom: 15px;
}

.styled-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
}

.styled-form input,
.styled-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

.styled-form input:focus,
.styled-form textarea:focus {
    border-color: #007bff;
    outline: none;
}

.form-actions {
    text-align: center;
    margin-top: 20px;
}

.form-actions button {
    padding: 10px 25px;
    border: none;
    background: #007bff;
    color: white;
    font-size: 15px;
    border-radius: 5px;
    cursor: pointer;
}

.form-actions button:hover {
    background: #0056b3;
}

    </style>
    
    ";
    echo "
    <div class='form-card'>
        <h2>Send a Report / Complaint</h2>

        <form method='post' class='styled-form'>

            <div class='form-group'>
                <label>Subject</label>
                <input type='text' name='subject' placeholder='Enter subject' required>
            </div>

            <div class='form-group'>
                <label>Message</label>
                <textarea name='message' rows='5' placeholder='Describe your issue...' required></textarea>
            </div>

            <div class='form-actions'>
                <button type='submit' name='submit_report'>Send Report</button>
            </div>

        </form>
    </div>
    ";
}

function customer_review_form() {
    echo"
    <style>
    .customer-form {
    max-width: 500px;
    margin: 30px auto;
    padding: 25px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.customer-form h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Labels */
.customer-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #444;
}

/* Inputs, select, textarea */
.customer-form input[type='text'],
.customer-form select,
.customer-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: border 0.2s ease;
}

.customer-form input:focus,
.customer-form select:focus,
.customer-form textarea:focus {
    border-color: #007bff;
    outline: none;
}

/* Button */
.customer-form button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    border: none;
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.customer-form button:hover {
    background: #0056b3;
}
    </style>
    
    ";
    echo "
    <div class='customer-form'>
        <h2>Leave a Review</h2>

        <form method='post'>
            <label>Rating (1–5)</label>
            <select name='rating' required>
                <option value=''>Select</option>
                <option value='1'>1 - Very Bad</option>
                <option value='2'>2 - Bad</option>
                <option value='3'>3 - Average</option>
                <option value='4'>4 - Good</option>
                <option value='5'>5 - Excellent</option>
            </select>

            <label>Comment</label>
            <textarea name='comment' rows='4' placeholder='Write your review...'></textarea>

            <button type='submit' name='submit_review'>Submit Review</button>
        </form>
    </div>
    ";
}
function render_cus_TopBar($username)
{
    echo"
        <nav class='topbar'>
        <div class='topbar-container'>
            <div class='auth-links'>
               <strong>Hello {$username}</strong>
            </div>
            <ul class='nav-menu'>
                <li><a href='#about'>About Hotel</a></li>
                <li><a href='#rooms'>Our Rooms</a></li>
                <li><a href='#services'>Best Services</a></li>
                <li><a href='#testimonials'>Best Reviews</a></li>
            </ul>
            <div class='auth-links'>
               <a href='/hms/system/logout.php'>Log Out</a>
            </div>
        </div>
    </nav>
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

?>