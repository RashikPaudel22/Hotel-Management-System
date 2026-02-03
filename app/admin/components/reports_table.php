<?php
/**
 * Admin Reports Table
 * Displays customer complaints/reports
 * 
 * Required: $reports array
 */

if (!isset($reports) || !is_array($reports)) {
    $reports = [];
}
?>

<style>
.reports-container h2 {
    font-size: 24px;
    color: var(--text-dark);
    margin-bottom: 20px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.reports-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.reports-table th,
.reports-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.reports-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.reports-table tr:hover {
    background: #f5f5f5;
}

.reports-table .message {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.reports-table .status {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.reports-table .status.pending {
    background: #fff3cd;
    color: #856404;
}

.reports-table .status.resolved {
    background: #d4edda;
    color: #155724;
}

.reports-table .status.in-progress {
    background: #cce5ff;
    color: #004085;
}

/* Tablet Responsive */
@media (max-width: 1024px) {
    .reports-container h2 {
        font-size: 22px;
    }

    .reports-table {
        min-width: 800px;
    }

    .reports-table th,
    .reports-table td {
        padding: 10px 12px;
        font-size: 14px;
    }

    .reports-table .message {
        max-width: 250px;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .reports-container h2 {
        font-size: 20px;
    }

    .reports-table {
        min-width: 700px;
        font-size: 13px;
    }

    .reports-table th,
    .reports-table td {
        padding: 8px 10px;
    }

    .reports-table .message {
        max-width: 200px;
    }

    .reports-table .status {
        padding: 4px 8px;
        font-size: 11px;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .reports-container h2 {
        font-size: 18px;
    }

    .reports-table {
        min-width: 600px;
        font-size: 12px;
    }

    .reports-table th,
    .reports-table td {
        padding: 6px 8px;
    }

    .reports-table .message {
        max-width: 150px;
    }
}
</style>

<div class="reports-container">
    <h2>Customer Reports</h2>

    <div class="table-responsive">
        <table class='reports-table'>
            <thead>
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
            </thead>
            <tbody>
                <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan='8' style='text-align:center; padding: 30px; color: #999;'>
                            No reports found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['id']); ?></td>
                            <td>
                                <?php 
                                echo htmlspecialchars($report['fname'] ?? '');
                                echo ' ';
                                echo htmlspecialchars($report['lname'] ?? '');
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($report['email'] ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars($report['booking_id'] ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars($report['subject'] ?? ''); ?></td>
                            <td class='message' title='<?php echo htmlspecialchars($report['message'] ?? ''); ?>'>
                                <?php echo htmlspecialchars($report['message'] ?? ''); ?>
                            </td>
                            <td>
                                <span class='status <?php echo htmlspecialchars($report['status'] ?? 'pending'); ?>'>
                                    <?php echo htmlspecialchars($report['status'] ?? 'Pending'); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($report['created_at'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>