<?php
/**
 * Customer Report Page
 */
require_once "../../system/htmlload.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../../system/login.php");
    exit();
}

$username = $_SESSION['user']['username'] ?? 'Guest';
loadHeader('Report a Problem');
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

input.form-control {
    height: 48px;
}

textarea.form-control {
    resize: vertical;
    min-height: 140px;
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

/* Help Text */
.help-text {
    background: #e7f3ff;
    border-left: 4px solid #2196F3;
    padding: 16px;
    margin-bottom: 24px;
    border-radius: 6px;
    font-size: 14px;
    color: #1976D2;
}

/* Tablet Responsive (768px - 1024px) */
@media (max-width: 1024px) {
    .bookings-container {
        padding: 32px 24px 60px;
    }

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

    .help-text {
        padding: 14px;
        font-size: 13px;
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

    input.form-control {
        height: 44px;
    }

    textarea.form-control {
        min-height: 120px;
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

    .help-text {
        padding: 12px;
        font-size: 12px;
    }
}
</style>

<div class="bookings-container">
    <div class="page-header">
        <div class="header-content">
            <h1>📋 Send A Report / Complaint</h1>
            <div class="header-actions">
                <a href="../customer/dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <div class="modern-container">
        <section class="form-card">

            <div class="help-text">
                <strong>💡 Need Help?</strong> Use this form to report any issues or complaints. Our team will review and respond as soon as possible.
            </div>

            <?php
            require_once "../../system/database.php";

            if (isset($_POST['submit_report'])) {
                $userId = $_SESSION['user']['id'];
                $subject = $_POST['subject'];
                $message = $_POST['message'];

                if (!empty($subject) && !empty($message)) {
                    $stmt = $conn->prepare("INSERT INTO reports (user_id, subject, message) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $userId, $subject, $message);

                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success">Report sent successfully! We will contact you shortly.</div>';
                    } else {
                        echo '<div class="alert alert-danger">Error sending report: ' . $conn->error . '</div>';
                    }
                    $stmt->close();
                } else {
                     echo '<div class="alert alert-warning">Please fill in all fields.</div>';
                }
            }
            ?>

            <form method="post" class="styled-form">
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" required class="form-control" placeholder="Brief description of the issue">
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="5" required class="form-control" placeholder="Please provide detailed information about your issue or complaint..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit_report">Send Report</button>
                </div>
            </form>
        </section>
    </div>
</div>

<?php loadFooter(); ?>