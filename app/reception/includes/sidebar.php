<?php
/**
 * Receptionist Sidebar Navigation
 * Shows navigation menu for receptionist panel
 */

// Get current user info
$currentUser = getCurrentUser();
$username = $currentUser['username'] ?? 'Receptionist';
?>

<!-- Mobile Hamburger Toggle -->
<button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
    <span></span>
    <span></span>
    <span></span>
</button>

<div class='sidebar' id="receptionSidebar">
    <div class="sidebar-header">
        <h5 class='mb-0'><strong><?php echo htmlspecialchars($username); ?></strong></h5>
        <p>Receptionist</p>
    </div>

    <nav class="sidebar-nav">
        <a href='/hms/app/reception/dashboard.php'>
            <span class="icon">📊</span> Dashboard
        </a>
        <a href='/hms/app/reception/reservation.php'>
            <span class="icon">📅</span> Reservations
        </a>
        <a href='/hms/app/reception/manage_bookings.php'>
            <span class="icon">📖</span> Manage Bookings
        </a>
    </nav>
</div>

<style>
/* Sidebar Base Styles */
.sidebar {
    width: 260px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 1000;
    transition: transform 0.3s ease;
}

.sidebar-header {
    padding: 30px 20px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.sidebar-header h5 {
    margin-bottom: 5px;
    font-size: 18px;
}

.sidebar-header p {
    margin: 0;
    opacity: 0.8;
    font-size: 14px;
}

.sidebar-nav {
    padding: 20px 0;
}

.sidebar-nav a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 15px;
}

.sidebar-nav a:hover,
.sidebar-nav a.active {
    background: rgba(255, 255, 255, 0.1);
    padding-left: 25px;
}

.sidebar-nav a .icon {
    margin-right: 10px;
    font-size: 18px;
}

/* Mobile Hamburger Toggle */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1001;
    background: #667eea;
    border: none;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    flex-direction: column;
    gap: 4px;
}

.sidebar-toggle span {
    width: 25px;
    height: 3px;
    background: white;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.sidebar-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.sidebar-toggle.active span:nth-child(2) {
    opacity: 0;
}

.sidebar-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* Tablet Responsive (768px - 1024px) */
@media (max-width: 1024px) {
    .sidebar {
        width: 220px;
    }

    .sidebar-header {
        padding: 25px 15px 15px;
    }

    .sidebar-nav a {
        font-size: 14px;
        padding: 10px 15px;
    }

    .sidebar-nav a:hover,
    .sidebar-nav a.active {
        padding-left: 20px;
    }
}

/* Mobile Responsive (max-width: 768px) */
@media (max-width: 768px) {
    .sidebar-toggle {
        display: flex;
    }

    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.active {
        transform: translateX(0);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
    }

    .sidebar-header {
        padding: 70px 20px 20px;
    }

    /* Overlay when sidebar is open */
    .sidebar.active::before {
        content: '';
        position: fixed;
        top: 0;
        left: 260px;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: -1;
    }
}

/* Small Mobile (max-width: 480px) */
@media (max-width: 480px) {
    .sidebar {
        width: 240px;
    }

    .sidebar.active::before {
        left: 240px;
    }

    .sidebar-header h5 {
        font-size: 16px;
    }

    .sidebar-header p {
        font-size: 13px;
    }

    .sidebar-nav a {
        font-size: 13px;
        padding: 10px 15px;
    }

    .sidebar-nav a .icon {
        font-size: 16px;
    }
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    
    sidebar.classList.toggle('active');
    toggle.classList.toggle('active');
}

// Close sidebar when clicking on a link (mobile)
document.querySelectorAll('.sidebar-nav a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            sidebar.classList.remove('active');
            toggle.classList.remove('active');
        }
    });
});

// Close sidebar when clicking outside (mobile)
document.addEventListener('click', (e) => {
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    
    if (window.innerWidth <= 768 && 
        sidebar.classList.contains('active') &&
        !sidebar.contains(e.target) && 
        !toggle.contains(e.target)) {
        sidebar.classList.remove('active');
        toggle.classList.remove('active');
    }
});

// Highlight active page
const currentPage = window.location.pathname;
document.querySelectorAll('.sidebar-nav a').forEach(link => {
    if (link.getAttribute('href') === currentPage || 
        currentPage.includes(link.getAttribute('href').split('/').pop())) {
        link.classList.add('active');
    }
});
</script>