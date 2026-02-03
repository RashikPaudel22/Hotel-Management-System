<?php
/**
 * Admin Rooms Table
 * Displays all rooms with management options
 * 
 * Required: $rooms array (result from database query)
 */

if (!isset($rooms)) {
    die("Rooms data is required");
}
?>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-header h2 {
    margin: 0;
    font-size: 24px;
    color: var(--text-dark);
}

.add-room-btn {
    background: #555;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s;
    white-space: nowrap;
}

.add-room-btn:hover {
    background: #333;
    transform: translateY(-2px);
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.modern-table {
    width: 100%;
    min-width: 600px;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.modern-table th,
.modern-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

.modern-table th {
    background: #f7f7f7;
    font-weight: 600;
}

.modern-table tbody tr:hover {
    background: #f9f9f9;
}

.badge {
    padding: 6px 12px;
    border-radius: 5px;
    color: #fff;
    font-size: 12px;
    text-decoration: none;
    display: inline-block;
    white-space: nowrap;
}

.badge-red { background: #e74c3c; }
.badge-green { background: #2ecc71; }
.badge-yellow { background: #f1c40f; color: black; }
.badge-blue { background: #3498db; }

.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.action-buttons a {
    padding: 8px 10px;
    border-radius: 5px;
    color: white;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 13px;
    white-space: nowrap;
}

.btn-edit { background: #3498db; }
.btn-edit:hover { background: #2980b9; }

.btn-view { background: #f1c40f; color: black; }
.btn-view:hover { background: #f39c12; }

.btn-delete { background: #e74c3c; }
.btn-delete:hover { background: #c0392b; }

/* Tablet Responsive */
@media (max-width: 1024px) {
    .modern-table th,
    .modern-table td {
        padding: 10px 12px;
    }

    .action-buttons a {
        padding: 6px 8px;
        font-size: 12px;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .page-header h2 {
        font-size: 20px;
    }

    .add-room-btn {
        width: 100%;
        text-align: center;
        padding: 10px 15px;
    }

    .modern-table {
        min-width: 500px;
        font-size: 14px;
    }

    .modern-table th,
    .modern-table td {
        padding: 8px 10px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 3px;
    }

    .action-buttons a {
        width: 100%;
        text-align: center;
        padding: 6px 8px;
        font-size: 12px;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .page-header h2 {
        font-size: 18px;
    }

    .modern-table {
        min-width: 450px;
        font-size: 13px;
    }

    .modern-table th,
    .modern-table td {
        padding: 6px 8px;
    }

    .badge {
        padding: 4px 8px;
        font-size: 11px;
    }
}
</style>

<div class="page-header">
    <h2>Manage Rooms</h2>
    <a href="room_add.php" class="add-room-btn">+ Add Room</a>
</div>

<div class="table-responsive">
    <table class="modern-table">
        <thead>
            <tr>
                <th>Room No</th>
                <th>Room Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($room = $rooms->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                    <td><?php echo htmlspecialchars($room['name']); ?></td>
                    
                    <td>
                        <?php if ($room['availability'] == 1): ?>
                            <span class='badge badge-red'>Booked</span>
                        <?php else: ?>
                            <span class='badge badge-green'>Available</span>
                        <?php endif; ?>
                    </td>
                    
                    <td class='action-buttons'>
                        <a class='btn-edit' href='room_edit.php?id=<?php echo $room['id']; ?>' 
                           title='Edit'>
                            <i class='fa fa-edit'></i> Edit
                        </a>
                        <a class='btn-delete' 
                           href='room_delete.php?id=<?php echo $room['id']; ?>'
                           onclick='return confirm("Are you sure you want to delete this room?")' 
                           title='Delete'>
                            <i class='fa fa-trash'></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>