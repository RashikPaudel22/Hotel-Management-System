<?php
/**
 * Public Index Page
 * Main landing page for the hotel website
 */

// Include the helper functions (which includes auth.php)
require_once __DIR__ . '/system/htmlload.php';

// Check if user is logged in using auth function
$isLoggedIn = isLoggedIn();

// Load the header
loadHeader('Destr0yer Hotel - Welcome');

// Render navigation
renderTopBar();

// Render hero section
renderHeroSection();

// Render about section
renderAboutSection();

// Render rooms section (different based on login status)
if ($isLoggedIn) {
    renderRoomsSection_logged_in();
} else {
    renderRoomsSection();
}

// Render services section
renderServicesSection();

// Render testimonials section
renderTestimonialsSection();

// Render popup
renderPopup();

// Render footer
renderFooter();
?>