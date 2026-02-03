<nav class="topbar">
    <div class="topbar-container">
        <!-- Hamburger Menu Button -->
        <button class="hamburger-menu" onclick="toggleMobileMenu()" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="auth-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="/hms/system/logout.php">Logout</a>
                <a href="/hms/app/customer/dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="#" onclick="openLoginModal(); return false;">Login</a>
                <a href="#" onclick="openRegisterModal(); return false;">Sign Up</a>
            <?php endif; ?>
        </div>

        <ul class="nav-menu" id="navMenu">
            <li><a href="/hms/index.php#about">About Hotel</a></li>
            <li><a href="/hms/index.php#rooms">Our Rooms</a></li>
            <li><a href="/hms/index.php#services">Best Services</a></li>
            <li><a href="/hms/index.php#testimonials">Best Reviews</a></li>
        </ul>
    </div>
</nav>

<style>
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

.auth-links {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.auth-links a {
    color: #4361ee;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid rgba(67, 97, 238, 0.3);
    transition: all 0.3s;
    font-size: 14px;
}

.auth-links a:hover {
    background: rgba(67, 97, 238, 0.1);
    border-color: #4361ee;
}

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

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .topbar-container {
        padding: 1rem;
    }

    .hamburger-menu {
        display: flex;
        order: -1;
    }

    .auth-links {
        gap: 0.5rem;
    }

    .auth-links a {
        padding: 6px 12px;
        font-size: 12px;
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
}

@media (max-width: 480px) {
    .topbar-container {
        padding: 0.75rem;
    }

    .auth-links {
        gap: 0.3rem;
    }

    .auth-links a {
        padding: 5px 10px;
        font-size: 11px;
    }

    .nav-menu {
        width: 250px;
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

// Close menu when clicking on a link
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', () => {
        const navMenu = document.getElementById('navMenu');
        const hamburger = document.querySelector('.hamburger-menu');
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    });
});

// Close menu when clicking outside
document.addEventListener('click', (e) => {
    const navMenu = document.getElementById('navMenu');
    const hamburger = document.querySelector('.hamburger-menu');
    
    if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    }
});
</script>