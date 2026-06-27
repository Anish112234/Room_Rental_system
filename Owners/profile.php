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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="css/owner.css">
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>

<!-- HEADER (same as other owner pages) -->
<header class="head">

    <div id="logo">Room Rental</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">

        <a href="owner.php">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a href="profile.php" class="home">Profile</a>

        <div class="mobile-user">
            <span>Hi, <?= htmlspecialchars($user['name']) ?></span>
            <a href="logout.php" onclick="return confirm('Logout?')">Logout</a>
        </div>

    </nav>

    <div class="portel">
        <span>Hi, <?= htmlspecialchars($user['name']) ?></span>
        <a href="logout.php" onclick="return confirm('Logout?')">Logout</a>
    </div>

</header>

<!-- PROFILE SECTION -->
<section class="profile-section">

    <div class="profile-card">

        <div class="avatar">
            <?= strtoupper(substr($user['name'], 0, 1)) ?>
        </div>

        <h2><?= htmlspecialchars($user['name']) ?></h2>

        <div class="info">
            <p><b>Email:</b> <?= htmlspecialchars($user['email']) ?></p>
            <p><b>Role:</b> <?= htmlspecialchars($user['role']) ?></p>
            <p><b>User ID:</b> <?= $user['id'] ?></p>
        </div>

        <a href="edit_profile.php" class="btn">Edit Profile</a>

    </div>

</section>

<footer class="footer">
    <p>© 2026 Room Rental | Owner Panel</p>
</footer>

<script>
const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.getElementById("nav-menu");

menuToggle.addEventListener("click", function(){
    navMenu.classList.toggle("show");
});
</script>

</body>
</html>