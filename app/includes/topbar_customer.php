<nav class="topbar">
    <div class="topbar-container">
        <!-- Hamburger Menu Button -->
        <button class="hamburger-menu" onclick="toggleMobileMenu()" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="user-welcome">
            <strong>Hello <?= htmlspecialchars($username) ?></strong>
        </div>

        <ul class="nav-menu" id="navMenu">
            <li><a href="/hms/app/customer/dashboard.php#about">About Hotel</a></li>
            <li><a href="/hms/app/customer/dashboard.php#rooms">Our Rooms</a></li>
            <li><a href="/hms/app/customer/dashboard.php#services">Best Services</a></li>
            <li><a href="/hms/app/customer/dashboard.php#testimonials">Best Reviews</a></li>
            <li><a href="/hms/app/customer/my_bookings.php">My Rooms</a></li>
        </ul>

        <div class="auth-links">
            <div class="settings-dropdown">
                <button class="settings-btn" onclick="toggleSettingsDropdown(event)">Settings ▾</button>
                <div class="settings-dropdown-content" id="settingsDropdown">
                    <a href="/hms/app/customer/profile.php">My Profile</a>
                    <a href="/hms/app/sections/customer_report.php">Report A Problem</a>
                    <a href="/hms/app/sections/customer_review.php">Leave A Review</a>
                    <hr class="dropdown-divider">
                    <a href="/hms/system/logout.php" class="logout-link">Log Out</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
/* Base Topbar Styles */
.topbar {
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 999;
    width: 100%;
}

.topbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem 2rem;
    position: relative;
}

/* Hamburger Menu */
.hamburger-menu {
    display: none;
    flex-direction: column;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    z-index: 1001;
}

.hamburger-menu span {
    width: 25px;
    height: 3px;
    background: #333;
    margin: 3px 0;
    transition: all 0.3s ease;
    border-radius: 3px;
}

.hamburger-menu.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.hamburger-menu.active span:nth-child(2) {
    opacity: 0;
}

.hamburger-menu.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* User Welcome */
.user-welcome {
    color: blue;
    font-size: 14px;
}

/* Navigation Menu */
.nav-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
    margin: 0;
    padding: 0;
}

.nav-menu li a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}

.nav-menu li a:hover {
    color: #4361ee;
}

/* Auth Links */
.auth-links {
    display: flex;
    gap: 1rem;
    align-items: center;
}

/* Dropdown Styles */
.settings-dropdown {
    position: relative;
    display: inline-block;
}

.settings-btn {
    background: transparent;
    color: blue;
    border: 1px solid rgba(25, 0, 255, 0.5);
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.settings-btn:hover {
    background: rgba(17, 0, 255, 0.1);
}

.settings-dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background-color: white;
    min-width: 180px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    border-radius: 8px;
    z-index: 1000;
    overflow: hidden;
    margin-top: 5px;
}

.settings-dropdown-content.show {
    display: block;
    animation: fadeIn 0.3s ease;
}

.settings-dropdown-content a {
    color: #333 !important;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    font-size: 14px;
    transition: background 0.2s;
    text-align: left;
}

.settings-dropdown-content a:hover {
    background-color: #f1f1f1;
    color: #4361ee !important;
}

.dropdown-divider {
    height: 1px;
    background-color: #eee;
    margin: 0;
    border: none;
}

.logout-link {
    background-color: #dc3545 !important;
    color: white !important;
    font-weight: 600;
    margin: 5px;
    border-radius: 4px;
    text-align: center;
}

.logout-link:hover {
    background-color: #bb2d3b !important;
    color: white !important;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Mobile Responsive Styles */
@media (max-width: 992px) {
    .nav-menu {
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .topbar-container {
        padding: 1rem;
        flex-wrap: nowrap;
    }

    .hamburger-menu {
        display: flex;
        order: -1;
    }

    .user-welcome {
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }

    .nav-menu {
        position: fixed;
        top: 0;
        left: -100%;
        width: 280px;
        height: 100vh;
        background: white;
        flex-direction: column;
        gap: 0;
        padding: 80px 0 20px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        transition: left 0.3s ease;
        overflow-y: auto;
    }

    .nav-menu.active {
        left: 0;
    }

    .nav-menu li {
        width: 100%;
        border-bottom: 1px solid #f0f0f0;
    }

    .nav-menu li a {
        display: block;
        padding: 1rem 2rem;
        font-size: 16px;
    }

    .nav-menu li a:hover {
        background: #f8f9fa;
    }

    .settings-btn {
        padding: 6px 12px;
        font-size: 12px;
    }

    .settings-dropdown-content {
        right: 0;
        min-width: 160px;
    }
}

@media (max-width: 480px) {
    .topbar-container {
        padding: 0.75rem;
    }

    .user-welcome {
        font-size: 11px;
        max-width: 100px;
    }

    .user-welcome strong {
        font-size: 11px;
    }

    .nav-menu {
        width: 250px;
    }

    .settings-btn {
        padding: 5px 10px;
        font-size: 11px;
    }

    .settings-dropdown-content {
        min-width: 140px;
    }

    .settings-dropdown-content a {
        padding: 10px 12px;
        font-size: 13px;
    }
}

/* Overlay for mobile menu */
@media (max-width: 768px) {
    .nav-menu.active::before {
        content: '';
        position: fixed;
        top: 0;
        left: 280px;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: -1;
    }
}
</style>

<script>
function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    const hamburger = document.querySelector('.hamburger-menu');
    
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
}

function toggleSettingsDropdown(event) {
    event.stopPropagation();
    document.getElementById("settingsDropdown").classList.toggle("show");
}

// Close menu when clicking on a nav link
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            const navMenu = document.getElementById('navMenu');
            const hamburger = document.querySelector('.hamburger-menu');
            if (navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
    });
});

// Close mobile menu and dropdown when clicking outside
window.onclick = function(event) {
    // Close settings dropdown
    if (!event.target.matches('.settings-btn')) {
        var dropdowns = document.getElementsByClassName("settings-dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
    
    // Close mobile menu
    const navMenu = document.getElementById('navMenu');
    const hamburger = document.querySelector('.hamburger-menu');
    
    if (navMenu && hamburger && 
        !navMenu.contains(event.target) && 
        !hamburger.contains(event.target)) {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    }
}
</script>