<?php
/**
 * Add Room Form
 * Form to add new room
 * 
 * Required: $roomTypes (mysqli_result)
 */

if (!isset($roomTypes)) {
    die("Room types data is required");
}
?>

<style>
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

.form-control {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #dcdcdc;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #fafafa;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #4f46e5;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
}

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
                placeholder='e.g., 101'
                required
            >
        </div>

        <!-- Room Type -->
        <div class='form-group'>
            <label for='room_type'>Room Type</label>
            <select name='room_type' id='room_type' class='form-control' required>
                <option value=''>Select Room Type</option>
                <?php while ($type = $roomTypes->fetch_assoc()): ?>
                    <option value='<?php echo $type['id']; ?>'>
                        <?php echo htmlspecialchars($type['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Status -->
        <div class='form-group'>
            <label for='status'>Room Status</label>
            <select name='status' id='status' class='form-control' required>
                <option value='0'>Available</option>
                <option value='1'>Booked</option>
            </select>
        </div>

        <!-- Submit -->
        <div class='form-group'>
            <button type='submit' name='add_room' class='btn btn-primary'>
                Add Room
            </button>
        </div>
    </form>
</div>