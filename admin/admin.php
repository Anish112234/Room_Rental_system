<?php
session_start();
include("../dbconnection.php");

/* AUTH CHECK */
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user']; // ✅ FIXED

/* STATS */
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_owners = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='owner'"));
$total_rooms = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM rooms"));
$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings"));

$pending_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE status='pending'"));
$cancelled_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE status='cancelled'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<!-- OWNER STYLE REUSED -->
<link rel="stylesheet" href="../Owners/css/owner.css">
<link rel="stylesheet" href="css/admin.css">
</head>

<body>

<!-- HEADER -->
<header class="head">

     <div id="logo"><img src="../logo/Room_rental.png"></div>
     <h2 id="admin">Admin Panel</h2>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">

        <a href="admin.php" class="home">Dashboard</a>
        <a href="users.php">Users</a>
        <a href="owners.php">Owners</a>
        <a href="rooms.php">Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="profile.php">Profile</a>

        <div class="mobile-user">
            <span>Admin: <?= htmlspecialchars($user['name']) ?></span>
            <a href="../auth/logout.php">Logout</a>
        </div>

    </nav>

    <div class="portel">
        <span>Admin: <?= htmlspecialchars($user['name']) ?></span>
        <a href="../auth/logout.php">Logout</a>
    </div>

</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome Admin : <?= htmlspecialchars($user['name']) ?></h1>
        <p>Manage users, rooms, and bookings from one control panel</p>
    </div>
</section>

<!-- STATS -->
<section class="dashboard-section">

    <h2>System Overview</h2>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Users</h3>
            <p><?= $total_users ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Owners</h3>
            <p><?= $total_owners ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Rooms</h3>
            <p><?= $total_rooms ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Bookings</h3>
            <p><?= $total_bookings ?></p>
        </div>

        <div class="stat-card">
            <h3>Pending</h3>
            <p><?= $pending_bookings ?></p>
        </div>

        <div class="stat-card">
            <h3>Cancelled</h3>
            <p><?= $cancelled_bookings ?></p>
        </div>

    </div>
</section>

<!-- QUICK ACTIONS -->
<section class="action-section">

    <h2>Quick Actions</h2>

    <div class="action-grid">

        <div class="action-card">
            <h3>Users</h3>
            <p>Manage all users</p>
            <a href="users.php" class="btn">Open</a>
        </div>

        <div class="action-card">
            <h3>Rooms</h3>
            <p>Manage listings</p>
            <a href="rooms.php" class="btn">Open</a>
        </div>

        <div class="action-card">
            <h3>Bookings</h3>
            <p>Monitor bookings</p>
            <a href="bookings.php" class="btn">Open</a>
        </div>

    </div>

</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© 2026 Room Rental | Admin Panel</p>
</footer>

<script>
const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.getElementById("nav-menu");

menuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("show");
});
</script>

</body>
</html>