<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Rashik's Hotel</h1>
        <p>Experience comfort and elegance like never before</p>
        <?php if(isLoggedIn()): ?>
            <a href="#rooms" class="btn-primary">Book Your Stay</a>
        <?php else: ?>
            <a href="#" onclick="openLoginModal(); return false;" class="btn-primary">Book Your Stay</a>
        <?php endif; ?>
    </div>
</section>