<?php
/**
 * Add User/Staff Form
 * Form to add new staff member
 */

// Load page

?>

<style>
.section-box {
    background: #ffffff;
    padding: 28px;
    border-radius: 14px;
    margin: 40px auto;
    max-width: 500px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.35s ease-in-out;
}

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

.form-label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
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
    margin-bottom: 18px;
}

.btn {
    padding: 11px 18px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    color: #ffffff;
    width: 100%;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
}

.d-flex {
    display: flex;
}

.gap-2 {
    gap: 12px;
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

<div class='section-box'>
    <div class='section-title'>
        <h2>Add New Staff</h2>
    </div>

    <form method='POST'>
        <div class='mb-3'>
            <label class='form-label'>Username</label>
            <input 
                type='text' 
                name='username' 
                class='form-control' 
                placeholder='Enter username'
                required
            >
        </div>

        <div class='mb-3'>
            <label class='form-label'>Password</label>
            <input 
                type='password' 
                name='password' 
                class='form-control' 
                placeholder='Enter password'
                required
            >
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
            <button type='submit' name='add_user' class='btn btn-primary'>
                Add Staff
            </button>
            <a href='users.php' class='btn btn-secondary'>Cancel</a>
        </div>
    </form>
</div>