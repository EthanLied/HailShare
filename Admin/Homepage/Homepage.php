<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hailshare Admin – Dashboard</title>
    <!-- Universal template (two levels up to root) -->
    <link rel="stylesheet" href="../../shadCNTemplate.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

<!-- Sidebar -->
<div id="navbar">
    <div class="navbarItem">
        <span class="material-symbols-outlined" id="hamburgerMenuNavbarIcon" onclick="toggleNavbar()">menu</span>
        <a href="Homepage.php"><h3>Hailshare Admin</h3></a>
    </div>
    <a href="../Account%20List/AccountList.php">
        <div class="navbarItem"><span class="material-symbols-outlined">group</span><p>Account List</p></div>
    </a>
    <a href="../Admin%20Profile/Admin.php">
        <div class="navbarItem"><span class="material-symbols-outlined">admin_panel_settings</span><p>Admin Profile</p></div>
    </a>
</div>

<div id="content">
    <!-- ============ HERO SECTION ============ -->
    <section class="hero-section">
        <video class="hero-video" autoplay muted playsinline loop>
            <source src="https://cdn.pixabay.com/vimeo/310448957/people-2383275-hd.mp4" type="video/mp4">
            <source src="https://player.vimeo.com/external/333996839.hd.mp4?s=9c3bfc45e37e91fe8ab6b4ff9bfb1e58&profile_id=175" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-text-wrapper">
                <h1 class="hero-title">Your Journey, Shared Smart</h1>
                <p class="hero-subtitle">Connect with travelers. Save money. Reduce emissions.</p>
                <div class="hero-cta-group">
                    <a href="#features"><button class="cta-primary">Find Your Ride</button></a>
                    <a href="#how-it-works"><button class="cta-secondary">See How It Works</button></a>
                </div>
            </div>
            <div class="scroll-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </section>

    <!-- ============ VALUE PROPOSITION SECTION ============ -->
    <section class="value-section">
        <div class="value-content fade-in-element">
            <h2>Why Hailshare?</h2>
            <p class="section-subtitle">Smart ride-sharing for a smarter future</p>
            
            <div class="value-grid">
                <div class="value-card value-card-1">
                    <div class="value-icon">💰</div>
                    <h3>Save Up to 50%</h3>
                    <p>Split fuel, tolls, and parking costs with other travelers on your route.</p>
                </div>
                <div class="value-card value-card-2">
                    <div class="value-icon">🌍</div>
                    <h3>Eco-Friendly</h3>
                    <p>Fewer cars on the road means lower emissions and less traffic for everyone.</p>
                </div>
                <div class="value-card value-card-3">
                    <div class="value-icon">🤝</div>
                    <h3>Community Built</h3>
                    <p>Connect with verified users, build reputation, and make lasting connections.</p>
                </div>
                <div class="value-card value-card-4">
                    <div class="value-icon">🛡️</div>
                    <h3>100% Safe</h3>
                    <p>Verified profiles, 24/7 support, and secure messaging keep you protected.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ HOW IT WORKS - STICKY VIDEO SECTION ============ -->
    <section class="how-it-works" id="how-it-works">
        <div class="how-it-works-container">
            <!-- Video Column -->
            <div class="video-column">
                <div class="sticky-video-wrapper">
                    <video class="how-video" id="howVideo" muted playsinline>
                        <source src="https://cdn.pixabay.com/vimeo/284896051/road-3863490-hd.mp4" type="video/mp4">
                        <source src="https://player.vimeo.com/external/285295435.hd.mp4?s=9aa2b6e03e0b28e1f2e0c0e0e0e0e0e&profile_id=175" type="video/mp4">
                    </video>
                    <div class="video-overlay"></div>
                </div>
            </div>

            <!-- Steps Column -->
            <div class="steps-column">
                <h2>How It Works</h2>
                <p class="section-subtitle">Four simple steps to share your ride</p>

                <div class="step-item step-1 scroll-step" data-step="1">
                    <div class="step-number">01</div>
                    <div class="step-content">
                        <h3>🚗 Drivers Post Rides</h3>
                        <p>Share your route, departure time, available seats, and suggested price. In just 30 seconds, your ride is live.</p>
                    </div>
                </div>

                <div class="step-item step-2 scroll-step" data-step="2">
                    <div class="step-number">02</div>
                    <div class="step-content">
                        <h3>🔍 Passengers Search & Join</h3>
                        <p>Passengers instantly see rides on their route. They check your profile, see the pickup location on the map, and join your ride.</p>
                    </div>
                </div>

                <div class="step-item step-3 scroll-step" data-step="3">
                    <div class="step-number">03</div>
                    <div class="step-content">
                        <h3>💬 Chat & Coordinate</h3>
                        <p>Message directly in the app to confirm details, share music preferences, and get to know your co-riders.</p>
                    </div>
                </div>

                <div class="step-item step-4 scroll-step" data-step="4">
                    <div class="step-number">04</div>
                    <div class="step-content">
                        <h3>⭐ Rate & Build Trust</h3>
                        <p>After your ride, rate each other. Our community rating system ensures everyone stays accountable and trustworthy.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FEATURES SECTION WITH PARALLAX ============ -->
    <section class="features-section" id="features">
        <div class="features-header fade-in-element">
            <h2>Powerful Features</h2>
            <p class="section-subtitle">Everything you need for a seamless ride-sharing experience</p>
        </div>

        <div class="features-grid">
            <!-- Feature 1 -->
            <div class="feature-card feature-card-1">
                <div class="feature-video-bg">
                    <video autoplay muted loop playsinline>
                        <source src="https://cdn.pixabay.com/vimeo/293941921/technology-2580836-hd.mp4" type="video/mp4">
                    </video>
                    <div class="feature-overlay"></div>
                </div>
                <div class="feature-content">
                    <h3>Smart Matching</h3>
                    <p>Our algorithm instantly matches drivers and passengers on the same routes, saving time and money for everyone.</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card feature-card-2">
                <div class="feature-video-bg">
                    <video autoplay muted loop playsinline>
                        <source src="https://cdn.pixabay.com/vimeo/298589139/gps-3002872-hd.mp4" type="video/mp4">
                    </video>
                    <div class="feature-overlay"></div>
                </div>
                <div class="feature-content">
                    <h3>Real-Time Tracking</h3>
                    <p>See your driver's live location on the map. Never wonder where your ride is—transparent, secure, and instant.</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card feature-card-3">
                <div class="feature-video-bg">
                    <video autoplay muted loop playsinline>
                        <source src="https://cdn.pixabay.com/vimeo/335735402/security-3617512-hd.mp4" type="video/mp4">
                    </video>
                    <div class="feature-overlay"></div>
                </div>
                <div class="feature-content">
                    <h3>Secure Payments</h3>
                    <p>All payments are encrypted and processed securely. Split fares automatically or pay what you agreed upfront.</p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="feature-card feature-card-4">
                <div class="feature-video-bg">
                    <video autoplay muted loop playsinline>
                        <source src="https://cdn.pixabay.com/vimeo/328697717/people-3589951-hd.mp4" type="video/mp4">
                    </video>
                    <div class="feature-overlay"></div>
                </div>
                <div class="feature-content">
                    <h3>Community Rating</h3>
                    <p>Build your reputation. Every ride, every rating counts. Verified profiles and transparent feedback create trust.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STATS SECTION ============ -->
    <section class="stats-section">
        <div class="stats-content">
            <h2>Hailshare by the Numbers</h2>
            <div class="stats-grid">
                <div class="stat-card stat-card-fade">
                    <h3 class="stat-number">50K+</h3>
                    <p>Active Riders</p>
                </div>
                <div class="stat-card stat-card-fade">
                    <h3 class="stat-number">$2.5M+</h3>
                    <p>Saved Together</p>
                </div>
                <div class="stat-card stat-card-fade">
                    <h3 class="stat-number">100K+</h3>
                    <p>Rides Shared</p>
                </div>
                <div class="stat-card stat-card-fade">
                    <h3 class="stat-number">500 Tons</h3>
                    <p>CO2 Reduced</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CTA SECTION WITH VIDEO BACKGROUND ============ -->
    <section class="final-cta-section">
        <video autoplay muted playsinline loop class="final-cta-video">
            <source src="https://cdn.pixabay.com/vimeo/310386116/city-3349640-hd.mp4" type="video/mp4">
        </video>
        <div class="final-cta-overlay"></div>
        <div class="final-cta-content fade-in-element">
            <h2>Ready to Save Money & Help the Planet?</h2>
            <p>Join thousands of smart travelers choosing Hailshare today.</p>
            <div class="cta-button-group">
                <a href="../../RWDD PROJECT/RWDD CODE/HailShare/">
                    <button class="cta-primary">Get Started Now</button>
                </a>
            </div>
        </div>
    </section>

    <!-- ============ FOOTER SECTION ============ -->
    <footer class="footer-section">
        <div class="footer-content">
            <div class="footer-column">
                <h4>About Hailshare</h4>
                <p>Smart ride-sharing for cost-conscious travelers who care about community and the environment.</p>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="../../RWDD PROJECT/RWDD CODE/HailShare/">Download App</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Contact</h4>
                <p>Email: support@hailshare.com</p>
                <p>Phone: 1-800-HAILSHARE</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Hailshare. All rights reserved.</p>
        </div>
    </footer>
</div>

</body>
</html>