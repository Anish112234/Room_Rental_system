<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];
$owner_id = $user['id'];

/* Stats */
$total_rooms = mysqli_num_rows(mysqli_query($conn, "
    SELECT * FROM rooms 
    WHERE owner_id='$owner_id'
"));

$total_bookings = mysqli_num_rows(mysqli_query($conn, "
    SELECT b.* 
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE r.owner_id='$owner_id'
"));

$pending_bookings = mysqli_num_rows(mysqli_query($conn, "
    SELECT b.*
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE r.owner_id='$owner_id' AND b.status='pending'
"));

$approved_bookings = mysqli_num_rows(mysqli_query($conn, "
    SELECT b.*
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE r.owner_id='$owner_id' AND b.status='approved'
"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="css/owner.css">
</head>
<body>

<header class="head">

    <div id="logo">Room Rental</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">
        <a href="owner.php" class="home">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a href="profile.php">Profile</a>

        <div class="mobile-user">
            <span>Hi, <?= htmlspecialchars($user['name']) ?></span>
            <a href="/Room_Rental_System/Owners/logout.php"
               onclick="return confirm('Are you sure you want to logout?')">
               Logout
            </a>
        </div>
    </nav>

    <div class="portel">
        <span class="welcome-text">Hi, <?= htmlspecialchars($user['name']) ?></span>
        <a href="/Room_Rental_System/Owners/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           Logout
        </a>
    </div>

</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome <?= htmlspecialchars($user['name']) ?> 👋</h1>
        <p>Manage your rooms, bookings and rental business from one place.</p>
        <a href="#dashboard" class="btn">Go to Dashboard</a>
    </div>
</section>

<!-- STATS -->
<section class="dashboard-section" id="dashboard">
    <h2>Owner Dashboard</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Rooms</h3>
            <p><?= $total_rooms ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Bookings</h3>
            <p><?= $total_bookings ?></p>
        </div>

        <div class="stat-card">
            <h3>Pending Bookings</h3>
            <p><?= $pending_bookings ?></p>
        </div>

        <div class="stat-card">
            <h3>Approved Bookings</h3>
            <p><?= $approved_bookings ?></p>
        </div>
    </div>
</section>

<!-- QUICK ACTIONS -->
<section class="action-section">
    <h2>Quick Actions</h2>

    <div class="action-grid">
        <div class="action-card">
            <h3>Add New Room</h3>
            <p>Create a new room listing for customers.</p>
            <a href="add.php" class="btn">Add Room</a>
        </div>

        <div class="action-card">
            <h3>Manage My Rooms</h3>
            <p>View, edit and manage all your listed rooms.</p>
            <a href="myrooms.php" class="btn">My Rooms</a>
        </div>

        <div class="action-card">
            <h3>Booking Requests</h3>
            <p>Check booking requests from customers and take action.</p>
            <a href="bookings.php" class="btn">View Bookings</a>
        </div>

        <div class="action-card">
            <h3>My Profile</h3>
            <p>Update your owner profile and account details.</p>
            <a href="profile.php" class="btn">Profile</a>
        </div>
    </div>
</section>

<!-- INFO SECTION -->
<section class="info-section">
    <h2>Owner Tips</h2>

    <div class="tips-grid">
        <div class="tip-box">
            <h3>📸 Add Good Photos</h3>
            <p>Rooms with clear photos usually get more bookings.</p>
        </div>

        <div class="tip-box">
            <h3>📝 Write Full Details</h3>
            <p>Add proper description, location and pricing for better trust.</p>
        </div>

        <div class="tip-box">
            <h3>⚡ Respond Fast</h3>
            <p>Check booking requests regularly so customers don’t leave.</p>
        </div>
    </div>
</section>

<!-- CONTACT / FOOTER STYLE LIKE CUSTOMER -->
<section class="about" id="contact">
    <h2>Owner Support</h2>
    <p>Need help managing your listings? Contact support@roomrental.com</p>
</section>

<footer class="footer">
    <p>© 2026 Room Rental | Owner Panel</p>
</footer>

<script src="js/owner.js"></script>
</body>
</html>