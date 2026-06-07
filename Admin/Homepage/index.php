<?php
// ============ PHP SESSION & INITIALIZATION ============
session_start();

require_once __DIR__ . '/../Database/DBConnection.php';

$db = new DatabaseConnection();
$dashboard_stats = $db->getDashboardStats();
$cookie_user_id = filter_input(INPUT_COOKIE, 'user_id', FILTER_VALIDATE_INT);
if ($cookie_user_id === null || $cookie_user_id === false) {
    $cookie_user_id = isset($_COOKIE['user_id']) ? filter_var($_COOKIE['user_id'], FILTER_VALIDATE_INT) : false;
}
$current_user = $cookie_user_id && $cookie_user_id > 0 ? $db->getAccountById($cookie_user_id) : null;
$profile_url = '#';

if ($current_user) {
    switch (intval($current_user['role_id'] ?? 0)) {
        case 1:
            $profile_url = '/hailshare/Customer/rideList/index.php';
            break;
        case 2:
            $profile_url = '/hailshare/Staff/ride-list-staff/index.php';
            break;
        case 3:
            $profile_url = '/hailshare/Admin/Admin%20Profile/Admin.php';
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'logout') {
    setcookie('user_id', '', time() - 3600, '/');
    $_SESSION = [];
    session_destroy();
    $db->close();
    header('Location: index.php');
    exit();
}

// Define base paths
$base_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/Admin/';

// Check admin authentication (simple check - in production use database)
if (!isset($_SESSION['admin_logged_in'])) {
    // You can redirect to login if needed
    // header('Location: ../UserAuth/login.php');
    // exit();
}

// Site configuration
$site_config = [
    'name' => 'Hailshare Admin Dashboard',
    'tagline' => 'Smart Ride-Sharing Platform',
    'active_page' => 'home'
];

function formatStatNumber($number) {
    if ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    }

    return (string) $number;
}

// Log page visit (optional)
error_log('Admin Dashboard visited at ' . date('Y-m-d H:i:s'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_config['name']; ?> – Dashboard</title>
    <!-- Universal template (two levels up to root) -->
    <link rel="stylesheet" href="../../shadCNTemplate.css">
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="style.css?v=hero-preview-1">
    <script src="script.js?v=hero-preview-1" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

<!-- Top Navigation Bar -->
<nav id="topNav">
    <div class="nav-container">
        <div class="nav-left">
            <a href="index.php" class="logo">
                <h2>Hailshare</h2>
            </a>
            <div class="nav-links">
                <a href="index.php" class="nav-link">Home</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#how-it-works" class="nav-link">How It Works</a>
            </div>
        </div>
        <div class="nav-right">
            <?php if ($current_user): ?>
                <div class="profile-menu">
                    <button type="button" class="profile-trigger" aria-label="Open profile menu" aria-expanded="false" title="Profile">
                        <span class="material-symbols-outlined profile-icon">account_circle</span>
                    </button>
                    <div class="profile-dropdown" hidden>
                        <a href="<?php echo htmlspecialchars($profile_url); ?>">
                            <span class="material-symbols-outlined">person</span>
                            <span>Profile</span>
                        </a>
                        <form method="POST" class="profile-logout-form">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="profile-logout-button">
                                <span class="material-symbols-outlined">logout</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="../../UserAuth/hailshare/registration%201.php"><button class="btnNormal nav-btn">Sign Up</button></a>
                <a href="../../UserAuth/hailshare/login.php"><button class="btnStrong nav-btn">Login</button></a>
            <?php endif; ?>
        </div>
    </div>
</nav>

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
            <div class="hero-product-preview" aria-label="Ride list preview">
                <img src="assets/ride-list-preview.jpeg" alt="Hailshare ride list search and available rides preview">
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

    <!-- ============ HOW IT WORKS SECTION ============ -->
    <section class="how-it-works" id="how-it-works">
        <div class="how-it-works-container">
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
                <div class="feature-icon-wrap">
                    <span class="material-symbols-outlined">route</span>
                </div>
                <div class="feature-content">
                    <h3>Smart Matching</h3>
                    <p>Our algorithm instantly matches drivers and passengers on the same routes, saving time and money for everyone.</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card feature-card-2">
                <div class="feature-icon-wrap">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <div class="feature-content">
                    <h3>Real-Time Tracking</h3>
                    <p>See your driver's live location on the map. Never wonder where your ride is—transparent, secure, and instant.</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card feature-card-3">
                <div class="feature-icon-wrap">
                    <span class="material-symbols-outlined">encrypted</span>
                </div>
                <div class="feature-content">
                    <h3>Secure Payments</h3>
                    <p>All payments are encrypted and processed securely. Split fares automatically or pay what you agreed upfront.</p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="feature-card feature-card-4">
                <div class="feature-icon-wrap">
                    <span class="material-symbols-outlined">verified_user</span>
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
                    <h3 class="stat-number"><?php echo formatStatNumber($dashboard_stats['active_riders']); ?></h3>
                    <p>Active Accounts</p>
                </div>
                <div class="stat-card stat-card-fade">
                    <h3 class="stat-number"><?php echo formatStatNumber($dashboard_stats['rides_shared']); ?></h3>
                    <p>Rides Shared</p>
                </div>
                <div class="stat-card stat-card-fade">
                    <h3 class="stat-number"><?php echo formatStatNumber($dashboard_stats['completed_rides']); ?></h3>
                    <p>Completed Rides</p>
                </div>
                <div class="stat-card stat-card-fade">
                    <h3 class="stat-number"><?php echo formatStatNumber($dashboard_stats['support_requests']); ?></h3>
                    <p>Support Requests</p>
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
                    <li><a href="#contact">Get In Touch</a></li>
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

<?php $db->close(); ?>
</body>
</html>
