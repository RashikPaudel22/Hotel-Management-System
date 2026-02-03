<?php
/**
 * Customer Review Page
 */
require_once "../../system/htmlload.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../../system/login.php");
    exit();
}

$username = $_SESSION['user']['username'] ?? 'Guest';
loadHeader('Leave a Review');
renderTopBar($username);
?>

<style>
/* Container Styles */
.bookings-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 32px 80px;
}

/* Page Header */
.page-header {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid var(--border);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.page-header h1 {
    font-size: 32px;
    color: var(--text-dark);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-actions {
    display: flex;
    gap: 12px;
}

/* Modern Container */
.modern-container {
    align-items: flex-start;
    padding-top: 40px;
}

/* Form Styles */
.customer-form,
.form-card {
    background: white;
    border-radius: 12px;
    padding: 32px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
}

.styled-form .form-group {
    margin-bottom: 24px;
}

.styled-form .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-dark);
    font-size: 15px;
}

.form-control {
    width: 100%;
    border: 1px solid var(--border);
    padding: 12px;
    border-radius: var(--radius-sm);
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

select.form-control {
    cursor: pointer;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 32px;
}

.form-actions button {
    flex: 1;
    padding: 14px 28px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.form-actions button:hover {
    background: #3651d4;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

/* Alerts */
.alert {
    padding: 14px 18px;
    margin-bottom: 24px;
    border-radius: 8px;
    font-size: 14px;
    line-height: 1.5;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-warning {
    background: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s;
    text-align: center;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

/* Tablet Responsive (768px - 1024px) */
@media (max-width: 1024px) {
    .bookings-container {
        padding: 32px 24px 60px;
    }

    .customer-form,
    .form-card {
        padding: 28px;
    }

    .page-header h1 {
        font-size: 28px;
    }
}

/* Mobile Responsive (max-width: 768px) */
@media (max-width: 768px) {
    .bookings-container {
        padding: 24px 20px 60px;
    }

    .page-header {
        padding: 24px;
    }

    .page-header h1 {
        font-size: 24px;
    }

    .header-content {
        flex-direction: column;
        align-items: stretch;
    }

    .header-actions {
        flex-direction: column;
    }

    .header-actions .btn {
        width: 100%;
    }

    .modern-container {
        padding-top: 24px;
    }

    .customer-form,
    .form-card {
        padding: 24px;
        border-radius: 8px;
    }

    .styled-form .form-group {
        margin-bottom: 20px;
    }

    .form-actions {
        flex-direction: column;
        margin-top: 24px;
    }

    .form-actions button {
        width: 100%;
    }
}

/* Small Mobile (max-width: 480px) */
@media (max-width: 480px) {
    .bookings-container {
        padding: 20px 16px 50px;
    }

    .page-header {
        padding: 20px;
        margin-bottom: 20px;
    }

    .page-header h1 {
        font-size: 20px;
        gap: 8px;
    }

    .customer-form,
    .form-card {
        padding: 20px;
    }

    .styled-form .form-group label {
        font-size: 14px;
    }

    .form-control {
        padding: 10px;
        font-size: 14px;
    }

    .form-actions button {
        padding: 12px 20px;
        font-size: 15px;
    }

    .alert {
        padding: 12px 16px;
        font-size: 13px;
    }

    .btn {
        padding: 10px 20px;
        font-size: 14px;
    }
}
</style>

<div class="bookings-container">
    <div class="page-header">
        <div class="header-content">
            <h1>📋 Leave a Review</h1>
            <div class="header-actions">
                <a href="../customer/dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="modern-container">
        <section class="customer-form">

        <?php
            require_once "../../system/database.php";

            if (isset($_POST['submit_review'])) {
                $userId = $_SESSION['user']['id'];
                $rating = $_POST['rating'];
                $comment = $_POST['comment'];

                if (!empty($rating)) {
                    $stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $userId, $rating, $comment);

                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success">Thank you for your review!</div>';
                    } else {
                        echo '<div class="alert alert-danger">Error submitting review: ' . $conn->error . '</div>';
                    }
                    $stmt->close();
                } else {
                     echo '<div class="alert alert-warning">Please select a rating.</div>';
                }
            }
        ?>

            <form method="post" class="styled-form">
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" required class="form-control">
                        <option value="">Select a rating</option>
                        <option value="5">★★★★★ - Excellent</option>
                        <option value="4">★★★★☆ - Good</option>
                        <option value="3">★★★☆☆ - Average</option>
                        <option value="2">★★☆☆☆ - Bad</option>
                        <option value="1">★☆☆☆☆ - Very Bad</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Comment</label>
                    <textarea name="comment" rows="4" class="form-control" placeholder="Share your experience with us..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit_review">Submit Review</button>
                </div>
            </form>
        </section>
    </div>
</div>

<?php loadFooter(); ?>