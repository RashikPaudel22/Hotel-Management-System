function openBooking() {
    document.getElementById("bookingOverlay").style.display = "block";
}

function closeBooking() {
    document.getElementById("bookingOverlay").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
    openBooking();
});
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
document.getElementById('room_type').addEventListener('change', function () {
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
document.getElementById('check_in_date').addEventListener('change', function () {
    const checkOutDate = document.getElementById('check_out_date');
    checkOutDate.setAttribute('min', this.value);
    calculateTotal();
});

document.getElementById('check_out_date').addEventListener('change', calculateTotal);