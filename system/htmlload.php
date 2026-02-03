<?php
/**
 * HTML Load Helper Functions
 * Simplified and clean functions for loading page components
 * 
 * Usage:
 * - Include this file in your pages: require_once 'system/htmlload.php';
 * - Use helper functions to load sections dynamically
 */

// Include authentication functions
require_once __DIR__ . '/auth.php';

/**
 * Load the HTML header with page title
 * @param string $title - Page title (default: 'Destr0yer Hotel')
 */
function loadHeader($title = 'Destr0yer Hotel') {
    $pageTitle = $title;
    include(__DIR__ . '/../app/includes/header.php');
}

/**
 * Load the HTML footer
 */
function loadFooter() {
    include(__DIR__ . '/../app/includes/footer.php');
    include(__DIR__ . '/../app/popups/auth_modals.php');
}

/**
 * Render the top navigation bar
 * @param string|null $username - Username for logged-in users
 */
function renderTopBar($username = null) {
    // If username is not passed, check if user is logged in
    if ($username === null && isLoggedIn()) {
        $username = $_SESSION['user']['username'] ?? 'User';
    }

    if ($username) {
        // User is logged in - load customer topbar
        include(__DIR__ . '/../app/includes/topbar_customer.php');
    } else {
        // Guest user - load regular topbar
        include(__DIR__ . '/../app/includes/topbar.php');
    }
}

/**
 * Render the hero section
 */
function renderHeroSection() {
    include(__DIR__ . '/../app/sections/hero.php');
}

/**
 * Render the about section
 */
function renderAboutSection() {
    include(__DIR__ . '/../app/sections/about.php');
}

/**
 * Render rooms section for logged-in users
 */
function renderRoomsSection_logged_in() {
    include(__DIR__ . '/../app/sections/rooms_logged_in.php');
}

/**
 * Render rooms section for guest users (not logged in)
 */
function renderRoomsSection() {
    include(__DIR__ . '/../app/sections/rooms.php');
}

/**
 * Render services section
 */
function renderServicesSection() {
    include(__DIR__ . '/../app/sections/services.php');
}

/**
 * Render testimonials section
 */
function renderTestimonialsSection() {
    include(__DIR__ . '/../app/sections/testimonials.php');
}

/**
 * Render footer
 */
function renderFooter() {
    loadFooter();
}

/**
 * Display login form
 */
function login_form() {
    echo '
    <div class="modern-container">
        <div class="modern-card">
            <h2>User Login</h2>
            <form action="" method="POST">
                <label>Enter Username</label>
                <input type="text" name="username" required>

                <label>Enter Password</label>
                <input type="password" name="password" required>

                <input type="submit" name="login" value="Log In">
            </form>
        </div>
    </div>
    ';
}

/**
 * Load complete homepage
 * @param bool $loggedIn - Whether user is logged in
 */
function loadHomePage($loggedIn = false) {
    loadHeader('Destr0yer Hotel - Home');
    renderTopBar();
    renderHeroSection();
    renderAboutSection();
    
    if ($loggedIn) {
        renderRoomsSection_logged_in();
    } else {
        renderRoomsSection();
    }
    
    renderServicesSection();
    renderTestimonialsSection();
    renderPopup();
    renderFooter();
}

/**
 * Render the main popup
 */
function renderPopup() {
    include(__DIR__ . '/../app/popups/main_popup.php');
}
?>