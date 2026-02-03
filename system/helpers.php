<?php

function loadHeader($title = "HMS") {
    include __DIR__ . "/../app/includes/header.php";
}

function loadFooter() {
    include __DIR__ . "/../app/includes/footer.php";
}

function renderTopBar() {
    include __DIR__ . "/../app/includes/topbar.php";
}

function section($name) {
    include __DIR__ . "/../app/sections/{$name}.php";
}
