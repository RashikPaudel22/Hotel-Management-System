<?php
/**
 * Room Edit Form
 * Form to edit existing room details
 * 
 * Required: $room array, $roomTypes (mysqli_result)
 */

if (!isset($room) || !isset($roomTypes)) {
    die("Room data and room types are required");
}
?>

<style>
.section-box {
    background: #ffffff;
    padding: 30px;
    border-radius: 14px;
    margin: 40px auto;
    max-width: 600px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.section-title {
    font-size: 24px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 25px;
    text-align: center;
}

.form-label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

.form-control,
.form-select {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #dcdcdc;
    font-size: 14px;
    background: #fafafa;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.form-control:focus,
.form-select:focus {
    outline: none;
    background: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
}

.mb-3 {
    margin-bottom: 20px;
}

.d-flex {
    display: flex;
}

.gap-2 {
    gap: 12px;
}

.btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #ffffff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
}
</style>

<div class='section-box'>
    <div class='section-title'>Edit Room</div>

    <form method='POST'>
        <div class='mb-3'>
            <label class='form-label'>Room Number</label>
            <input 
                type='text' 
                name='room_number'
                value='<?php echo htmlspecialchars($room['room_number']); ?>'
                class='form-control' 
                required
            >
        </div>

        <div class='mb-3'>
            <label class='form-label'>Room Type</label>
            <select name='room_type' class='form-select' required>
                <?php while ($type = $roomTypes->fetch_assoc()): ?>
                    <option 
                        value='<?php echo $type['id']; ?>' 
                        <?php echo ($type['id'] == $room['type_id']) ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($type['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class='mb-3'>
            <label class='form-label'>Status</label>
            <select name='status' class='form-select' required>
                <option value='available' <?php echo ($room['status'] == 'available') ? 'selected' : ''; ?>>
                    Available
                </option>
                <option value='maintenance' <?php echo ($room['status'] == 'maintenance') ? 'selected' : ''; ?>>
                    Maintenance
                </option>
            </select>
        </div>

        <div class='d-flex gap-2'>
            <button type='submit' name='update_room' class='btn btn-primary'>
                Update Room
            </button>
            <a href='room.php' class='btn btn-secondary'>Cancel</a>
        </div>
    </form>
</div>