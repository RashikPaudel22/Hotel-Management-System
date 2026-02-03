<?php
/**
 * Admin Statistics Page
 * Comprehensive analytics dashboard with charts and graphs
 */

// Authentication
require_once __DIR__ . '/../../system/auth.php';
requireLogin();
requireRole('admin');

// Dependencies
require_once __DIR__ . '/../../system/database.php';
require_once __DIR__ . '/../../system/htmlload.php';

// Fetch comprehensive statistics
// 1. Booking Statistics
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$pendingBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='pending'")->fetch_assoc()['total'];
$confirmedBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='confirmed'")->fetch_assoc()['total'];
$checkedInBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='checked_in'")->fetch_assoc()['total'];
$checkedOutBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='checked_out'")->fetch_assoc()['total'];
$cancelledBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='cancelled'")->fetch_assoc()['total'];

// 2. Revenue Statistics
$totalRevenue = $conn->query("SELECT COALESCE(SUM(total_price), 0) AS total FROM bookings WHERE status != 'cancelled'")->fetch_assoc()['total'];
$monthlyRevenue = $conn->query("SELECT COALESCE(SUM(total_price), 0) AS total FROM bookings WHERE status != 'cancelled' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'];
$todayRevenue = $conn->query("SELECT COALESCE(SUM(total_price), 0) AS total FROM bookings WHERE status != 'cancelled' AND DATE(created_at) = CURDATE()")->fetch_assoc()['total'];

// 3. Room Statistics
$totalRooms = $conn->query("SELECT COUNT(*) AS total FROM rooms")->fetch_assoc()['total'];
$availableRooms = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability=1")->fetch_assoc()['total'];
$occupiedRooms = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE availability=0")->fetch_assoc()['total'];
$occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

// 4. Review Statistics
$totalReviews = $conn->query("SELECT COUNT(*) AS total FROM reviews")->fetch_assoc()['total'];
$avgRating = $conn->query("SELECT COALESCE(AVG(rating), 0) AS avg FROM reviews")->fetch_assoc()['avg'];
$fiveStarReviews = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE rating=5")->fetch_assoc()['total'];
$fourStarReviews = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE rating=4")->fetch_assoc()['total'];
$threeStarReviews = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE rating=3")->fetch_assoc()['total'];
$twoStarReviews = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE rating=2")->fetch_assoc()['total'];
$oneStarReviews = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE rating=1")->fetch_assoc()['total'];

// 5. Monthly Booking Trend (Last 6 months)
$monthlyBookings = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $count = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month'")->fetch_assoc()['total'];
    $monthlyBookings[] = [
        'month' => date('M Y', strtotime("-$i months")),
        'count' => $count
    ];
}

// 6. Revenue by Month (Last 6 months)
$monthlyRevenueData = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $revenue = $conn->query("SELECT COALESCE(SUM(total_price), 0) AS total FROM bookings WHERE status != 'cancelled' AND DATE_FORMAT(created_at, '%Y-%m') = '$month'")->fetch_assoc()['total'];
    $monthlyRevenueData[] = [
        'month' => date('M Y', strtotime("-$i months")),
        'revenue' => $revenue
    ];
}

// 7. Room Type Statistics
$roomTypeStats = $conn->query("
    SELECT rt.name, COUNT(r.id) AS total, 
           SUM(CASE WHEN r.availability = 1 THEN 1 ELSE 0 END) AS available,
           SUM(CASE WHEN r.availability = 0 THEN 1 ELSE 0 END) AS occupied
    FROM room_types rt
    LEFT JOIN rooms r ON rt.id = r.type_id
    GROUP BY rt.id, rt.name
")->fetch_all(MYSQLI_ASSOC);

// 8. Customer Statistics
$totalCustomers = $conn->query("SELECT COUNT(DISTINCT customer_id) AS total FROM bookings")->fetch_assoc()['total'];
$newCustomersThisMonth = $conn->query("SELECT COUNT(DISTINCT b.customer_id) AS total FROM bookings b WHERE MONTH(b.created_at) = MONTH(CURRENT_DATE()) AND YEAR(b.created_at) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'];

// Load page
loadHeader('Statistics - Admin Dashboard');

// Include sidebar navigation
include __DIR__ . '/includes/sidebar.php';
?>

<style>
body {
    background: var(--bg-gray);
}

.stats-container {
    margin-left: 260px;
    padding: 32px;
    min-height: 100vh;
}

.page-header {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid var(--border);
}

.page-header h1 {
    font-size: 32px;
    color: var(--text-dark);
    font-weight: 700;
    margin-bottom: 8px;
}

.page-header p {
    color: var(--text-gray);
    font-size: 16px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--bg-white);
    padding: 28px;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    transition: var(--transition);
}

.stat-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.stat-card .stat-icon {
    font-size: 32px;
    margin-bottom: 12px;
}

.stat-card .stat-label {
    font-size: 14px;
    color: var(--text-gray);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
}

.stat-card .stat-value {
    font-size: 36px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 4px;
}

.stat-card .stat-change {
    font-size: 13px;
    color: var(--text-light);
}

.chart-section {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid var(--border);
}

.chart-section h2 {
    font-size: 24px;
    color: var(--text-dark);
    font-weight: 700;
    margin-bottom: 24px;
}

.chart-container {
    position: relative;
    height: 350px;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.table-section {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid var(--border);
}

.table-section h2 {
    font-size: 24px;
    color: var(--text-dark);
    font-weight: 700;
    margin-bottom: 24px;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
}

.stats-table th {
    background: var(--bg-light);
    padding: 12px 16px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid var(--border);
}

.stats-table td {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-gray);
}

.stats-table tr:hover {
    background: var(--bg-light);
}

.progress-bar {
    height: 8px;
    background: var(--bg-light);
    border-radius: 4px;
    overflow: hidden;
    margin-top: 8px;
}

.progress-fill {
    height: 100%;
    background: var(--primary);
    transition: width 0.3s ease;
}

@media (max-width: 768px) {
    .stats-container {
        margin-left: 0;
        padding: 20px;
    }
    
    .charts-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="stats-container">
    <div class="page-header">
        <h1>📊 Statistics & Analytics</h1>
        <p>Comprehensive overview of hotel performance and metrics</p>
    </div>

    <!-- Key Metrics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">NPR <?php echo number_format($totalRevenue, 2); ?></div>
            <div class="stat-change">All time earnings</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value">NPR <?php echo number_format($monthlyRevenue, 2); ?></div>
            <div class="stat-change">This month</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📈</div>
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value"><?php echo $totalBookings; ?></div>
            <div class="stat-change"><?php echo $confirmedBookings; ?> confirmed</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🏨</div>
            <div class="stat-label">Occupancy Rate</div>
            <div class="stat-value"><?php echo $occupancyRate; ?>%</div>
            <div class="stat-change"><?php echo $occupiedRooms; ?> of <?php echo $totalRooms; ?> rooms</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-label">Average Rating</div>
            <div class="stat-value"><?php echo number_format($avgRating, 1); ?></div>
            <div class="stat-change">From <?php echo $totalReviews; ?> reviews</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-value"><?php echo $totalCustomers; ?></div>
            <div class="stat-change"><?php echo $newCustomersThisMonth; ?> new this month</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Booking Trend Chart -->
        <div class="chart-section">
            <h2>📊 Booking Trends (6 Months)</h2>
            <div class="chart-container">
                <canvas id="bookingTrendChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="chart-section">
            <h2>💵 Revenue Trends (6 Months)</h2>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Booking Status Chart -->
    <div class="chart-section">
        <h2>📋 Booking Status Distribution</h2>
        <div class="chart-container">
            <canvas id="bookingStatusChart"></canvas>
        </div>
    </div>

    <!-- Review Distribution Chart -->
    <div class="chart-section">
        <h2>⭐ Review Ratings Distribution</h2>
        <div class="chart-container">
            <canvas id="reviewChart"></canvas>
        </div>
    </div>

    <!-- Room Type Statistics Table -->
    <div class="table-section">
        <h2>🏠 Room Type Statistics</h2>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Total Rooms</th>
                    <th>Available</th>
                    <th>Occupied</th>
                    <th>Occupancy Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roomTypeStats as $roomType): ?>
                    <?php 
                        $occupancy = $roomType['total'] > 0 ? round(($roomType['occupied'] / $roomType['total']) * 100, 1) : 0;
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($roomType['name']); ?></strong></td>
                        <td><?php echo $roomType['total']; ?></td>
                        <td><?php echo $roomType['available']; ?></td>
                        <td><?php echo $roomType['occupied']; ?></td>
                        <td>
                            <?php echo $occupancy; ?>%
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $occupancy; ?>%;"></div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Chart.js Configuration
Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
Chart.defaults.color = '#4a5568';

// 1. Booking Trend Chart
const bookingTrendCtx = document.getElementById('bookingTrendChart').getContext('2d');
new Chart(bookingTrendCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($monthlyBookings, 'month')); ?>,
        datasets: [{
            label: 'Bookings',
            data: <?php echo json_encode(array_column($monthlyBookings, 'count')); ?>,
            borderColor: '#1e3a8a',
            backgroundColor: 'rgba(30, 58, 138, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// 2. Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($monthlyRevenueData, 'month')); ?>,
        datasets: [{
            label: 'Revenue (NPR)',
            data: <?php echo json_encode(array_column($monthlyRevenueData, 'revenue')); ?>,
            backgroundColor: '#d4af37',
            borderColor: '#b5952f',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'NPR ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// 3. Booking Status Chart
const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
new Chart(bookingStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled'],
        datasets: [{
            data: [
                <?php echo $pendingBookings; ?>,
                <?php echo $confirmedBookings; ?>,
                <?php echo $checkedInBookings; ?>,
                <?php echo $checkedOutBookings; ?>,
                <?php echo $cancelledBookings; ?>
            ],
            backgroundColor: [
                '#fbbf24',
                '#3b82f6',
                '#10b981',
                '#6b7280',
                '#ef4444'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: {
                        size: 13
                    }
                }
            }
        }
    }
});

// 4. Review Distribution Chart
const reviewCtx = document.getElementById('reviewChart').getContext('2d');
new Chart(reviewCtx, {
    type: 'bar',
    data: {
        labels: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
        datasets: [{
            label: 'Number of Reviews',
            data: [
                <?php echo $fiveStarReviews; ?>,
                <?php echo $fourStarReviews; ?>,
                <?php echo $threeStarReviews; ?>,
                <?php echo $twoStarReviews; ?>,
                <?php echo $oneStarReviews; ?>
            ],
            backgroundColor: [
                '#10b981',
                '#3b82f6',
                '#fbbf24',
                '#f97316',
                '#ef4444'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<?php loadFooter(); ?>
