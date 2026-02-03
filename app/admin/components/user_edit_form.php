<?php
/**
 * User/Staff Edit Form
 * Form to edit existing staff member
 * 
 * Required: $user array
 */

if (!isset($user) || !is_array($user)) {
    die("User data is required");
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

.text-muted {
    color: #6c757d;
    font-size: 12px;
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
    <div class='section-title'>Edit Staff</div>

    <form method='POST'>
        <div class='mb-3'>
            <label class='form-label'>Username</label>
            <input 
                type='text' 
                name='username' 
                class='form-control'
                value='<?php echo htmlspecialchars($user['username']); ?>' 
                required
            >
        </div>

        <div class='mb-3'>
            <label class='form-label'>
                New Password 
                <small class='text-muted'>(leave blank to keep current)</small>
            </label>
            <input 
                type='password' 
                name='password' 
                class='form-control'
                placeholder='Enter new password or leave blank'
            >
        </div>

        <div class='mb-3'>
            <label class='form-label'>Role</label>
            <select name='role' class='form-select' required>
                <option value='admin' <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>
                    Admin
                </option>
                <option value='receptionist' <?php echo ($user['role'] == 'receptionist') ? 'selected' : ''; ?>>
                    Receptionist
                </option>
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
