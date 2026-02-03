<?php
/**
 * Admin Dashboard Main Content
 * Displays statistics and overview
 * 
 * Required: $stats array with keys: rooms, bookings, users, available, booked, checked_in
 */
$currentUser = getCurrentUser();
$username = $currentUser['username'] ?? 'Admin';
if (!isset($stats) || !is_array($stats)) {
    $stats = [
        'rooms' => 0,
        'bookings' => 0,
        'users' => 0,
        'available' => 0,
        'booked' => 0,
        'checked_in' => 0
    ];
}
?>

<!-- Mobile Menu Toggle -->
<div class="mobile-menu-toggle" id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-content">
    <div class="dashboard-header">
        <div class="header-text">
            <h1>Admin Dashboard</h1>
            <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($username); ?></p>
        </div>
        <div class="header-actions">
            <a href="/hms/system/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class='stats-grid'>
        <div class='stat-card'>
            <div class="stat-number"><?php echo $stats['rooms']; ?></div>
            <h3 class="stat-title">Total Rooms</h3>
        </div>

        <div class='stat-card'>
            <div class="stat-number"><?php echo $stats['bookings']; ?></div>
            <h3 class="stat-title">Bookings</h3>
        </div>
        <div class='stat-card'>
            <div class="stat-number"><?php echo $stats['users']; ?></div>
            <h3 class="stat-title">Staff</h3>
        </div>

        <div class='stat-card'>
            <div class="stat-number"><?php echo $stats['available']; ?></div>
            <h3 class="stat-title">Available Rooms</h3>
        </div>

        <div class='stat-card'>
            <div class="stat-number"><?php echo $stats['booked']; ?></div>
            <h3 class="stat-title">Booked Rooms</h3>
        </div>

        <div class='stat-card'>
            <div class="stat-number"><?php echo $stats['checked_in']; ?></div>
            <h3 class="stat-title">Checked In</h3>
        </div>
    </div>
</div>

<style>
/* Mobile Menu Toggle (Hamburger) */
.mobile-menu-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1001;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    background: white;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.mobile-menu-toggle span {
    display: block;
    width: 25px;
    height: 3px;
    background: #333;
    border-radius: 2px;
    transition: 0.3s;
}

/* Sidebar Overlay for Mobile */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 998;
    opacity: 0;
    transition: opacity 0.3s;
}

.sidebar-overlay.active {
    opacity: 1;
}

/* Main Content */
.main-content {
    margin-left: 260px;
    padding: 40px;
    min-height: 100vh;
}

/* Dashboard Header */
.dashboard-header {
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    flex-wrap: wrap;
}

.header-text h1 {
    color: var(--primary, #007bff);
    font-size: 28px;
    margin-bottom: 10px;
    font-weight: 600;
}

.welcome-text {
    color: var(--text-gray, #666);
    font-size: 16px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: background 0.3s;
    cursor: pointer;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-number {
    font-size: 36px;
    font-weight: bold;
    color: var(--primary, #007bff);
    margin-bottom: 10px;
}

.stat-title {
    font-size: 16px;
    color: var(--text-gray, #666);
    font-weight: 600;
    margin: 0;
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */

/* Tablet */
@media(max-width: 900px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

/* Mobile */
@media(max-width: 768px) {
    /* Show mobile menu toggle */
    .mobile-menu-toggle {
        display: flex;
    }

    /* Show overlay */
    .sidebar-overlay {
        display: block;
    }

    /* Adjust main content for mobile */
    .main-content {
        margin-left: 0;
        padding: 70px 15px 15px 15px;
    }

    /* Dashboard header */
    .dashboard-header {
        flex-direction: column;
        align-items: stretch;
    }

    .header-text h1 {
        font-size: 24px;
    }

    .header-actions {
        width: 100%;
    }

    .btn-danger {
        width: 100%;
        text-align: center;
    }

    /* Stats grid - single column on mobile */
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .stat-card {
        padding: 20px;
    }

    .stat-number {
        font-size: 30px;
    }

    .stat-title {
        font-size: 14px;
    }
}

/* Small Mobile */
@media(max-width: 480px) {
    .main-content {
        padding: 60px 10px 10px 10px;
    }

    .header-text h1 {
        font-size: 20px;
    }

    .stat-number {
        font-size: 28px;
    }
}
</style>

<script>
// Only toggle sidebar when clicking the hamburger menu
document.getElementById('menuToggle')?.addEventListener('click', function(e) {
    e.stopPropagation();
    const sidebar = document.querySelector('.sidebar') || document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
    if (overlay) {
        overlay.classList.toggle('active');
    }
});

// Close sidebar when clicking overlay
document.getElementById('sidebarOverlay')?.addEventListener('click', function(e) {
    e.stopPropagation();
    const sidebar = document.querySelector('.sidebar') || document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar) {
        sidebar.classList.remove('active');
    }
    if (overlay) {
        overlay.classList.remove('active');
    }
});

// Close sidebar when clicking on a sidebar link (mobile only)
document.querySelectorAll('.sidebar a, .nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar') || document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar) {
                sidebar.classList.remove('active');
            }
            if (overlay) {
                overlay.classList.remove('active');
            }
        }
    });
});
</script>