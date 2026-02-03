<?php
/**
 * Admin Reviews Table
 * Displays customer reviews
 * 
 * Required: $reviews array
 */

if (!isset($reviews) || !is_array($reviews)) {
    $reviews = [];
}
?>

<style>
.reviews-container h2 {
    font-size: 24px;
    color: var(--text-dark);
    margin-bottom: 20px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.reviews-table {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.reviews-table th,
.reviews-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.reviews-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.reviews-table tr:hover {
    background: #f5f5f5;
}

.reviews-table .comment {
    max-width: 400px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.reviews-table .rating {
    color: #ffc107;
    font-weight: bold;
    white-space: nowrap;
}

/* Tablet Responsive */
@media (max-width: 1024px) {
    .reviews-container h2 {
        font-size: 22px;
    }

    .reviews-table {
        min-width: 700px;
    }

    .reviews-table th,
    .reviews-table td {
        padding: 10px 12px;
        font-size: 14px;
    }

    .reviews-table .comment {
        max-width: 300px;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .reviews-container h2 {
        font-size: 20px;
    }

    .reviews-table {
        min-width: 600px;
        font-size: 13px;
    }

    .reviews-table th,
    .reviews-table td {
        padding: 8px 10px;
    }

    .reviews-table .comment {
        max-width: 250px;
    }

    .reviews-table .rating {
        font-size: 14px;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .reviews-container h2 {
        font-size: 18px;
    }

    .reviews-table {
        min-width: 550px;
        font-size: 12px;
    }

    .reviews-table th,
    .reviews-table td {
        padding: 6px 8px;
    }

    .reviews-table .comment {
        max-width: 200px;
    }

    .reviews-table .rating {
        font-size: 13px;
    }
}
</style>

<div class="reviews-container">
    <h2>Customer Reviews</h2>

    <div class="table-responsive">
        <table class='reviews-table'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan='6' style='text-align:center; padding: 30px; color: #999;'>
                            No reviews found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review['id']); ?></td>
                            <td>
                                <?php 
                                echo htmlspecialchars($review['fname'] ?? '');
                                echo ' ';
                                echo htmlspecialchars($review['lname'] ?? '');
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($review['email'] ?? '—'); ?></td>
                            <td>
                                <span class="rating">
                                    <?php 
                                    $stars = intval($review['rating']);
                                    echo str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
                                    ?>
                                </span>
                                <span style="color: #666; font-size: 12px;">(<?php echo $stars; ?>/5)</span>
                            </td>
                            <td class='comment' title='<?php echo htmlspecialchars($review['comment'] ?? ''); ?>'>
                                <?php echo htmlspecialchars($review['comment'] ?? ''); ?>
                            </td>
                            <td><?php echo htmlspecialchars($review['created_at'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>