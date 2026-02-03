<?php
/**
 * USER TABLE COMPONENT
 * Staff/Users Table
 * Displays all staff members with management options
 * 
 * Required: $users array
 */

if (!isset($users) || !is_array($users)) {
    $users = [];
}
?>

<style>
.section-box {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-title h2 {
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background: #f8f9fa;
}

.table th,
.table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.table th {
    font-weight: 600;
    color: #2c3e50;
}

.table tbody tr:hover {
    background: #f9f9f9;
}

.table td[data-label] {
    position: relative;
}

.badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.bg-info {
    background: #cce5ff;
    color: #004085;
}

.text-dark {
    color: #212529;
}

.btn {
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-block;
    white-space: nowrap;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

.btn-primary {
    background: #4f46e5;
    color: white;
}

.btn-primary:hover {
    background: #4338ca;
}

.btn-warning {
    background: #f1c40f;
    color: #000;
}

.btn-warning:hover {
    background: #f39c12;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}

.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #6c757d;
}

.empty-state {
    padding: 40px 20px;
    text-align: center;
}

/* Tablet Responsive */
@media (max-width: 1024px) {
    .section-box {
        padding: 20px;
    }

    .section-title h2 {
        font-size: 20px;
    }

    .table th,
    .table td {
        padding: 10px 12px;
    }
}

/* Mobile Responsive - Card Layout */
@media (max-width: 768px) {
    .section-box {
        padding: 18px;
    }

    .section-title {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .section-title h2 {
        font-size: 18px;
    }

    .btn-primary {
        width: 100%;
        text-align: center;
    }

    /* Hide table headers */
    .table thead {
        display: none;
    }

    /* Make table rows into cards */
    .table,
    .table tbody,
    .table tr {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table tr:hover {
        background: white;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    .table td {
        display: block;
        text-align: right;
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
        padding-left: 50%;
        min-height: 40px;
    }

    .table td:last-child {
        border-bottom: none;
        padding-bottom: 15px;
    }

    .table td:before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        top: 12px;
        font-weight: 600;
        text-align: left;
        color: #2c3e50;
    }

    .table td.action-cell {
        text-align: center;
        padding-left: 15px;
        padding-top: 15px;
    }

    .table td.action-cell:before {
        display: none;
    }

    .action-buttons {
        justify-content: center;
        width: 100%;
    }

    .action-buttons .btn-sm {
        flex: 1;
        min-width: 80px;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .section-box {
        padding: 15px;
    }

    .section-title h2 {
        font-size: 16px;
    }

    .table td {
        padding: 10px 12px;
        padding-left: 45%;
        font-size: 14px;
    }

    .table td:before {
        left: 12px;
        top: 10px;
        font-size: 13px;
    }

    .badge {
        padding: 4px 8px;
        font-size: 11px;
    }

    .btn-sm {
        padding: 6px 10px;
        font-size: 12px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 8px;
    }

    .action-buttons .btn-sm {
        width: 100%;
    }
}
</style>

<div class='section-box'>
    <div class='section-title'>
        <h2>Staff Members</h2>
        <a href='/hms/app/admin/user_add.php' class='btn btn-sm btn-primary'>+ Add Staff</a>
    </div>

    <div class="table-responsive">
        <table class='table'>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan='5' class='empty-state'>
                            <p class='text-muted' style='margin: 0;'>No staff members found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $sn = 1;
                    foreach ($users as $user): 
                    ?>
                        <tr>
                            <td data-label="SN"><?php echo $sn++; ?></td>
                            <td data-label="ID"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td data-label="Username"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td data-label="Role">
                                <span class='badge bg-info text-dark'>
                                    <?php echo htmlspecialchars($user['role']); ?>
                                </span>
                            </td>
                            <td class="action-cell">
                                <div class="action-buttons">
                                    <a href='/hms/app/admin/user_edit.php?id=<?php echo $user['id']; ?>' 
                                       class='btn btn-sm btn-warning'>
                                        Edit
                                    </a>
                                    <a href='user_delete.php?id=<?php echo $user['id']; ?>' 
                                       class='btn btn-sm btn-danger'
                                       onclick='return confirm("Delete this staff member?")'>
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>