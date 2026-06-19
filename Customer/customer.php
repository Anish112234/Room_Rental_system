<?php
session_start();
include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];

/* 🔥 SEARCH LOGIC (IMPORTANT - MUST BE HERE) */
$location = $_GET['location'] ?? '';
$price = $_GET['price'] ?? '';

$sql = "SELECT * FROM rooms WHERE status='available'";

if(!empty($location)){
    $sql .= " AND location LIKE '%$location%'";
}

if(!empty($price)){
    $sql .= " AND price <= '$price'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="customer.css">
    <title>Customer Dashboard</title>
</head>

<body>

<!-- HEADER -->
<div class="head">

    <div id="logo">Room Rental</div>

    <nav class="content">
        <a href="customer.php" class="home">Home</a>
        <a href="#rooms" class="rooms">Rooms</a>
        <a href="my_bookings.php" class="about1">My Bookings</a>
        <a href="#how" class="work">How It Works</a>
        <a href="#contact" class="contact">Contact</a>
    </nav>

    <div class="portel">
        <span style="margin-right:10px;">Hi, <?= $user['name'] ?></span>

        <a href="/Room_Rental_System/auth/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           Logout
        </a>
    </div>

</div>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome Back, <?= $user['name'] ?> 👋</h1>
        <p>Find and book your perfect room easily</p>
        <a href="#rooms" class="btn">Browse Rooms</a>
    </div>
</section>

<!-- SEARCH -->
<section class="search-section">
    <h2>Search Rooms</h2>

    <form method="GET" class="search-box">

        <input type="text" name="location" placeholder="Location"
               value="<?= htmlspecialchars($location) ?>">

        <input type="number" name="price" placeholder="Max Price"
               value="<?= htmlspecialchars($price) ?>">

        <button type="submit">Search</button>

        <a href="customer.php" class="btn" style="text-decoration:none;">
            Reset
        </a>

    </form>
</section>

<!-- ROOMS -->
<section class="rooms-section" id="rooms">
    <h2>Available Rooms</h2>

    <div class="room-grid">

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
        ?>

        <div class="room-card">

            <?php if(!empty($row['image'])) { ?>
                <img src="uploads/<?= $row['image'] ?>" alt="Room">
            <?php } else { ?>
                <img src="uploads/default.png" alt="Room">
            <?php } ?>

            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p>📍 <?= htmlspecialchars($row['location']) ?></p>
            <p>💰 Rs <?= htmlspecialchars($row['price']) ?>/month</p>

            <p style="font-size:12px; color:gray;">
                <?= htmlspecialchars($row['description']) ?>
            </p>

            <a href="book_room.php?id=<?= $row['id'] ?>" class="btn">
                Book Now
            </a>

        </div>

        <?php
            }
        } else {
            echo "<p style='text-align:center;'>No rooms found.</p>";
        }
        ?>

    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how" id="how">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">Search Room</div>
        <div class="step">Book Room</div>
        <div class="step">Move In</div>
    </div>
</section>

<!-- CONTACT -->
<section class="about" id="contact">
    <h2>Contact</h2>
    <p>Email: support@roomrental.com</p>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© 2026 Room Rental System</p>
</footer>

</body>
</html>