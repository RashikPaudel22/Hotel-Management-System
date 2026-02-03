<?php

function getCustomerUsername(): string {
    return $_SESSION['user']['username'] ?? 'Customer';
}
function renderTopBar() {
    include __DIR__ . "/../app/includes/topbar_customer.php";
}
