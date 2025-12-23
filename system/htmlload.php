

<?php

function loadHeader($title) {
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>$title</title>


<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'></script>
<link rel='stylesheet' href='style.css'>
<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>


<script>
$(document).on('change', '#room_type', function() {
    let roomTypeId = $(this).val();
    console.log('Selected room type:', roomTypeId);

    $.ajax({
        url: '/hms/app/admin/book_rooms/fetch_rooms.php',
        type: 'POST',
        data: { room_type_id: roomTypeId },
        success: function(response) {
            console.log('AJAX response:', response);
            $('#room').html(response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
        }
    });
});

</script>



<style>
body { background:#f4f6f9; }


/* Sidebar */
.sidebar {
width:250px;
height:100vh;
position:fixed;
left:0;
top:0;
background:#1e293b;
color:#fff;
padding:20px;
}
.sidebar h4 { color:#fff; margin-bottom:30px; }
.sidebar a {
display:block;
color:#cbd5e1;
padding:10px 15px;
text-decoration:none;
border-radius:6px;
margin-bottom:5px;
}
.sidebar a:hover { background:#334155; color:#fff; }


/* Main area */
.main {
margin-left:250px;
padding:20px;
}


/* Top navbar */
.topbar {
background:#fff;
padding:15px 20px;
border-radius:10px;
box-shadow:0 2px 10px rgba(0,0,0,0.05);
margin-bottom:20px;
}


/* Dashboard cards */
.stat-card {
background:#fff;
padding:20px;
border-radius:15px;
box-shadow:0 10px 25px rgba(0,0,0,0.05);
text-align:center;
}
.stat-card h3 { font-size:16px; color:#64748b; }
.stat-card p { font-size:28px; font-weight:bold; margin:10px 0; }
.stat-card a {
text-decoration:none;
font-size:14px;
color:#2563eb;
}
</style>
</head>
<body>

";
}
function loadFooter() {
echo "</body></html>";
}
function login_form() {
    echo '
    <div class="modern-container">
    <div class="modern-card">
    <h2>User Login</h2>

        <form action="" method="POST">

            <label>Enter Username</label>
            <input type="text" name="username">

            <label>Enter Password</label>
            <input type="password" name="password">

            <input type="submit" name="login" value="Log In">
        </form>

    
            </div>
</div>
    ';
}
function renderHeader() {
   
    
        echo"
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                line-height: 1.6;
                color: #333;
            }

            .topbar {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 0;
                position: sticky;
                top: 0;
                z-index: 1000;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .topbar-container {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 20px;
            }

            .auth-links {
                display: flex;
                gap: 15px;
            }

            .auth-links a {
                color: white;
                text-decoration: none;
                padding: 8px 20px;
                border-radius: 25px;
                transition: all 0.3s;
                font-weight: 500;
            }

            .auth-links a:hover {
                background: rgba(255,255,255,0.2);
                transform: translateY(-2px);
            }

            .nav-menu {
                display: flex;
                gap: 30px;
                list-style: none;
            }

            .nav-menu a {
                color: white;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s;
                position: relative;
            }

            .nav-menu a:hover {
                color: #ffd700;
            }

            .nav-menu a::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 0;
                height: 2px;
                background: #ffd700;
                transition: width 0.3s;
            }

            .nav-menu a:hover::after {
                width: 100%;
            }

            .hero {
                background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1600') center/cover;
                height: 600px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                color: white;
            }

            .hero-content h1 {
                font-size: 56px;
                margin-bottom: 20px;
                animation: fadeInUp 1s;
            }

            .hero-content p {
                font-size: 24px;
                margin-bottom: 30px;
                animation: fadeInUp 1.2s;
            }

            .btn-primary {
                background: #ffd700;
                color: #333;
                padding: 15px 40px;
                border: none;
                border-radius: 30px;
                font-size: 18px;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s;
                text-decoration: none;
                display: inline-block;
                animation: fadeInUp 1.4s;
            }

            .btn-primary:hover {
                background: #ffed4e;
                transform: translateY(-3px);
                box-shadow: 0 10px 20px rgba(255,215,0,0.3);
            }

            .section {
                padding: 80px 20px;
                max-width: 1200px;
                margin: 0 auto;
            }

            .section-title {
                text-align: center;
                font-size: 42px;
                margin-bottom: 20px;
                color: #667eea;
            }

            .section-subtitle {
                text-align: center;
                font-size: 18px;
                color: #666;
                margin-bottom: 60px;
            }

            .about-content {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 40px;
                align-items: center;
            }

            .about-image {
                width: 100%;
                height: 400px;
                object-fit: cover;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            }

            .about-text {
                font-size: 18px;
                line-height: 1.8;
                color: #555;
            }

            .rooms-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
            }

            .room-card {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                transition: all 0.3s;
            }

            .room-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            }

            .room-image {
                width: 100%;
                height: 250px;
                object-fit: cover;
            }

            .room-content {
                padding: 25px;
            }

            .room-title {
                font-size: 24px;
                margin-bottom: 10px;
                color: #333;
            }

            .room-price {
                color: #667eea;
                font-size: 28px;
                font-weight: bold;
                margin: 15px 0;
            }

            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 30px;
            }

            .service-card {
                text-align: center;
                padding: 40px 20px;
                background: white;
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                transition: all 0.3s;
            }

            .service-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(102,126,234,0.3);
            }

            .service-icon {
                font-size: 48px;
                margin-bottom: 20px;
            }

            .service-title {
                font-size: 22px;
                margin-bottom: 15px;
                color: #333;
            }

            .testimonials {
                background: #f8f9fa;
            }

            .testimonial-card {
                background: white;
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                margin-bottom: 20px;
            }

            .testimonial-text {
                font-style: italic;
                font-size: 18px;
                margin-bottom: 20px;
                color: #555;
            }

            .testimonial-author {
                font-weight: bold;
                color: #667eea;
            }

            .footer {
                background: #2c3e50;
                color: white;
                text-align: center;
                padding: 30px 20px;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .topbar-container {
                    flex-direction: column;
                    gap: 15px;
                }

                .nav-menu {
                    flex-direction: column;
                    gap: 10px;
                    text-align: center;
                }

                .hero-content h1 {
                    font-size: 36px;
                }

                .about-content {
                    grid-template-columns: 1fr;
                }
            }
        </style>";
}

function renderAboutSection() {
    echo'
    <section id="about" class="section">
        <h2 class="section-title">About Our Hotel</h2>
        <p class="section-subtitle">Discover luxury and comfort in every corner</p>
        <div class="about-content">
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800" alt="Hotel Lobby" class="about-image">
            <div class="about-text">
                <p>Welcome to our luxury hotel, where elegance meets comfort. Established in 1995, we have been providing world-class hospitality to travelers from around the globe.</p>
                <p>Our hotel features state-of-the-art facilities, beautifully designed rooms, and exceptional service that will make your stay unforgettable. Whether youre here for business or leisure, we ensure every moment is special.</p>
                <p>Located in the heart of the city, we offer easy access to major attractions, shopping districts, and business centers. Our dedicated staff is available 24/7 to cater to your every need.</p>
            </div>
        </div>
    </section>
    ';
}

// Rooms section
function renderRoomsSection_logged_in() {

   include("database.php");
  
       $sql = "
        SELECT 
            r.id,
            r.room_number,
            r.type_id,
            rt.name AS room_type,
            rt.price
        FROM rooms r
        JOIN room_types rt ON r.type_id = rt.id
        WHERE r.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    echo'
    <section id="rooms" class="section" style="background: #f8f9fa;">
        <h2 class="section-title">Our Rooms</h2>
        <p class="section-subtitle">Choose from our selection of premium accommodations</p>
        <div class="rooms-grid">
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600" alt="Deluxe Room" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">🛏️ Single Room</h3>
                    <p>A comfortable and compact room ideal for solo travelers</p>
                    <p class="room-price">NPR 2,000</p>
                    <a href="../customer/create_booking.php" class="btn-primary">Book Now</a>
                </div>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600" alt="Executive Suite" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">Double Room</h3>
                    <p>Perfect for couples or two guests, featuring a cozy bed</p>
                    <p class="room-price">NPR 3,500</p>
                    <a href="../customer/create_booking.php" class="btn-primary">Book Now</a>
                </div>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600" alt="Presidential Suite" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">Deluxe Room</h3>
                    <p>Spacious room with modern amenities and city view</p>
                    <p class="room-price">NPR 5,000</p>
                    <a href="../customer/create_booking.php" class="btn-primary">Book Now</a>
                </div>
            </div>
                        <div class="room-card">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600" alt="Deluxe Room" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">Suite Room</h3>
                    <p>A premium room with separate living space, designed for guests seeking luxury</p>
                    <p class="room-price">NPR 7,000</p>
                    <a href="../customer/create_booking.php" class="btn-primary">Book Now</a>

                </div>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600" alt="Executive Suite" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">👨‍👩‍👧 Family Room</h3>
                    <p>Comfortable and practical, it’s ideal for parents with children or groups traveling together.</p>
                    <p class="room-price">NPR 6,000</p>
                    <a href="#" class="btn-primary">Book Now</a>
                </div>
            </div>

        </div>
    </section>
    ';
}

// Services section
function renderServicesSection() {
    echo'
    <section id="services" class="section">
        <h2 class="section-title">Best Services</h2>
        <p class="section-subtitle">We provide exceptional services to make your stay memorable</p>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">🍽️</div>
                <h3 class="service-title">Fine Dining</h3>
                <p>Experience world-class cuisine at our multiple restaurants</p>
            </div>
            <div class="service-card">
                <div class="service-icon">💆</div>
                <h3 class="service-title">Spa & Wellness</h3>
                <p>Relax and rejuvenate at our luxury spa center</p>
            </div>
            <div class="service-card">
                <div class="service-icon">🏊</div>
                <h3 class="service-title">Swimming Pool</h3>
                <p>Enjoy our rooftop infinity pool with stunning views</p>
            </div>
            <div class="service-card">
                <div class="service-icon">💪</div>
                <h3 class="service-title">Fitness Center</h3>
                <p>Stay fit with our modern gym equipment</p>
            </div>
            <div class="service-card">
                <div class="service-icon">🚗</div>
                <h3 class="service-title">Valet Parking</h3>
                <p>Convenient parking service available 24/7</p>
            </div>
            <div class="service-card">
                <div class="service-icon">📶</div>
                <h3 class="service-title">Free WiFi</h3>
                <p>High-speed internet access throughout the property</p>
            </div>
        </div>
    </section>
    ';
}

// Testimonials section
function renderTestimonialsSection() {
   echo'
    <section id="testimonials" class="section testimonials">
        <h2 class="section-title">Best Reviews</h2>
        <p class="section-subtitle">What our guests say about us</p>
        <div class="testimonial-card">
            <p class="testimonial-text">"An absolutely wonderful experience! The staff was incredibly friendly and the rooms were immaculate. The rooftop pool was the highlight of our stay. Will definitely return!"</p>
            <p class="testimonial-author">- Sarah Johnson, New York</p>
        </div>
        <div class="testimonial-card">
            <p class="testimonial-text">"Best hotel I have ever stayed in. The attention to detail is remarkable, and the service is impeccable. The spa treatments were heavenly. Highly recommended!"</p>
            <p class="testimonial-author">- Michael Chen, San Francisco</p>
        </div>
        <div class="testimonial-card">
            <p class="testimonial-text">"Perfect location, luxurious rooms, and outstanding dining options. The concierge went above and beyond to make our anniversary special. Five stars!"</p>
            <p class="testimonial-author">- Emma Rodriguez, Miami</p>
        </div>
    </section>
    ';
}

// Footer
function renderFooter() {
    echo'
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Luxury Hotel. All rights reserved.</p>
        <p>Contact: info@luxuryhotel.com | Phone: +1 (555) 123-4567</p>
    </footer>
    </body>
    </html>
   ';
}
// Updated renderTopBar function - Login link triggers popup
function renderTopBar() {
 echo'
    <nav class="topbar">
        <div class="topbar-container">
            <div class="auth-links">
                <a href="#" onclick="event.preventDefault(); openLogin();">Login</a>
                <a href="/hms/system/register.php">Sign Up</a>
            </div>
            <ul class="nav-menu">
                <li><a href="#about">About Hotel</a></li>
                <li><a href="#rooms">Our Rooms</a></li>
                <li><a href="#services">Best Services</a></li>
                <li><a href="#testimonials">Best Reviews</a></li>
            </ul>
        </div>
    </nav>
   ';
}

// Updated renderRoomsSection - Book Now buttons trigger login popup
function renderRoomsSection() {
    echo '
    <section id="rooms" class="section" style="background: #f8f9fa;">
        <h2 class="section-title">Our Rooms</h2>
        <p class="section-subtitle">Choose from our selection of premium accommodations</p>

        <div class="rooms-grid">
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600" alt="Deluxe Room" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">🛏️ Single Room</h3>
                    <p>A comfortable and compact room ideal for solo travelers</p>
                    <p class="room-price">NPR 2,000</p>
                    <a href="#" class="btn-primary" onclick="event.preventDefault(); openLogin();">Book Now</a>
                </div>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600" alt="Executive Suite" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">Double Room</h3>
                    <p>Perfect for couples or two guests, featuring a cozy bed</p>
                    <p class="room-price">NPR 3,500</p>
                    <a href="#" class="btn-primary" onclick="event.preventDefault(); openLogin();">Book Now</a>
                </div>
            </div>
            <div class="room-card">
                 <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600" alt="Presidential Suite" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">Deluxe Room</h3>
                    <p>Spacious room with modern amenities and city view</p>
                    <p class="room-price">NPR 5,000</p>
                    <a href="#" class="btn-primary" onclick="event.preventDefault(); openLogin();">Book Now</a>
                </div>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600" alt="Deluxe Room" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">Suite Room</h3>
                    <p>A premium room with separate living space, designed for guests seeking luxury</p>
                    <p class="room-price">NPR 7,000</p>
                    <a href="#" class="btn-primary" onclick="event.preventDefault(); openLogin();">Book Now</a>
                </div>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600" alt="Executive Suite" class="room-image">
                <div class="room-content">
                    <h3 class="room-title">👨‍👩‍👧 Family Room</h3>
                    <p>Comfortable and practical, it\'s ideal for parents with children or groups traveling together.</p>
                    <p class="room-price">NPR 6,000</p>
                    <a href="#" class="btn-primary" onclick="event.preventDefault(); openLogin();">Book Now</a>
                </div>
            </div>
        </div>
    </section>';
}

// Updated renderHeroSection - Book Your Stay button triggers login
function renderHeroSection() {
    echo'
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Destr0yer Hotel</h1>
            <p>Experience comfort and elegance like never before</p>
            <a href="#" class="btn-primary";">Book Your Stay</a>
        </div>
    </section>
    ';
}

// Keep all other functions the same (renderHeader, renderAboutSection, etc.)

?>                                                                                                         