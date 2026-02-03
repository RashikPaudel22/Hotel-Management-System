/**
 * Hotel Management System - Main JavaScript File
 * Handles AJAX requests and UI interactions
 */

$(document).ready(function () {

    /**
     * Room Type Change Handler
     * Fetches available rooms based on selected room type
     */
    $(document).on('change', '#room_type', function () {
        let roomTypeId = $(this).val();
        console.log('Selected room type:', roomTypeId);

        $.ajax({
            url: '/hms/app/admin/book_rooms/fetch_rooms.php',
            type: 'POST',
            data: { room_type_id: roomTypeId },
            success: function (response) {
                console.log('AJAX response:', response);
                $('#room').html(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                alert('Failed to fetch rooms. Please try again.');
            }
        });
    });

    /**
     * Form validation helper
     */
    function validateForm(formId) {
        let isValid = true;
        $(`#${formId} input[required]`).each(function () {
            if ($(this).val() === '') {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        return isValid;
    }

    /**
     * Smooth scroll for navigation links
     */
    $('a[href^="#"]').on('click', function (e) {
        let target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 1000);
        }
    });

});

/**
 * Open login modal/popup
 * This function should trigger your login modal
 */
function openLogin() {
    // Redirect to login page
    window.location.href = '/hms/system/login.php';

    // OR if you want to use a modal:
    // $('#loginModal').modal('show');
}

/**
 * Close login modal
 */
function closeLogin() {
    $('#loginModal').modal('hide');
}