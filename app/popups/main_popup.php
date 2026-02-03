<?php
/**
 * Main Popup Component (Sequential Mode)
 * 
 * This file handles the display of promotional popups.
 * It is configured to show popups ONE BY ONE.
 * Closing one popup triggers the opening of the next one after a short delay.
 */

// CONFIGURATION: Add as many popups as you want here
$popups = [
    [
        'image' => 'assets/images/promo_popup.png',
        'link' => 'app/customer/reservation.php',
        'alt' => 'Special Room Offer'
    ],
    [
        'image' => 'assets/images/promo_dining.png',
        'link' => 'index.php#services',
        'alt' => 'Exquisite Dining'
    ]
];

// PHP to JS data transfer
$popupsJson = json_encode($popups);
$popupId = 'main-site-popup';
?>

<div id="<?php echo $popupId; ?>" class="popup-overlay">
    <div class="popup-content">
        <button class="popup-close-btn" onclick="nextPopup()">&times;</button>
        
        <a id="popup-link-target" href="#">
            <img id="popup-img-target" src="" alt="Popup" class="popup-image">
        </a>
    </div>
</div>

<style>
.popup-overlay {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.popup-overlay.active {
    opacity: 1;
    visibility: visible;
}

.popup-content {
    position: relative;
    max-width: 800px;
    max-height: 90vh;
    width: 90%;
    background: transparent;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    overflow: hidden;
    transform: scale(0.8);
    transition: transform 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
}

.popup-overlay.active .popup-content {
    transform: scale(1);
}

.popup-image {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s;
}

.popup-image:hover {
    transform: scale(1.02);
}

.popup-close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    font-size: 28px;
    line-height: 28px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10001;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.popup-close-btn:hover {
    background: rgba(200, 0, 0, 0.8);
}

@media (max-width: 600px) {
    .popup-close-btn {
        top: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        font-size: 20px;
    }
}
</style>

<script>
// Data from PHP
const popupData = <?php echo $popupsJson; ?>;
let currentPopupIndex = 0;
const popupOverlay = document.getElementById('<?php echo $popupId; ?>');
const popupImg = document.getElementById('popup-img-target');
const popupLink = document.getElementById('popup-link-target');

document.addEventListener('DOMContentLoaded', function() {
    // Start the sequence shortly after load
    if (popupData.length > 0) {
        setTimeout(showCurrentPopup, 500);
    }
});

function showCurrentPopup() {
    if (currentPopupIndex >= popupData.length) {
        // No more popups
        return;
    }

    const data = popupData[currentPopupIndex];
    
    // Set content
    popupImg.src = data.image;
    popupImg.alt = data.alt;
    popupLink.href = data.link;

    // Show overlay
    popupOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function nextPopup() {
    // 1. Close current
    popupOverlay.classList.remove('active');
    document.body.style.overflow = '';

    // 2. Increment index
    currentPopupIndex++;

    // 3. Check if there are more
    if (currentPopupIndex < popupData.length) {
        // Wait a bit before showing the next one
        setTimeout(function() {
            showCurrentPopup();
        }, 200); // 800ms delay between popups
    }
}

// Close/Next if clicking outside
popupOverlay.addEventListener('click', function(e) {
    if (e.target === this) {
        nextPopup();
    }
});
</script>
