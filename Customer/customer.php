<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="customer.css">

    <title>Home Page</title>
</head>
<body>
<button id="darkMode">🌙 Dark Mode</button>
<!--Logo loding wait   -->
<div class="head">
    <div id="logo">Logo</div>

    <nav class="content">
        <a href="#index" class="home">Home</a>
        <a href="#" class="rooms">Rooms</a>
        <a href="#" class="about1">About Us</a>
        <a href="#" class="work">How It Work</a>
        <a href="#" class="contact">Contact us</a>
    </nav>

    <div class="portel">
        <a href="auth/login.php">Login</a>
        <a href="auth/register.php" class="register">Register</a>
    </div>
</div>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Room Rental System</h1>
        <p>Find affordable rooms easily in your city</p>
        <a href="#" class="btn">Explore Rooms</a>
    </div>
</section>

<!-- SEARCH  for location  btn -->
<section class="search-section">
    <h2>Search Rooms</h2>
    <div class="search-box">
        <input type="text" placeholder="Location">
        <input type="text" placeholder="Price range">
        <button>Search</button>
    </div>
</section>

<!-- ROOMS (DYNAMIC) fetch from Data base  -->
<section class="rooms-section">
    <h2>Available Rooms</h2>

    <div class="room-grid">

        <?php
        include("dbconnection.php");

        $sql = "SELECT * FROM rooms ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0){

            while($row = mysqli_fetch_assoc($result)){
        ?>

        <div class="room-card">

  <?php if(!empty($row['image'])) { ?>
    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>"
         alt="Room Image"
         style="width:100%; height:150px; object-fit:cover; border-radius:8px;">
<?php } else { ?>
    <img src="uploads/default.png"
         alt="Default Image"
         style="width:100%; height:150px; object-fit:cover; border-radius:8px;">
<?php } ?>

            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p>📍 <?php echo htmlspecialchars($row['location']); ?></p>
            <p>💰 Rs. <?php echo htmlspecialchars($row['price']); ?>/month</p>

            <p style="font-size:12px; color:gray;">
                <?php echo htmlspecialchars($row['description']); ?>
            </p>

            <a href="auth/login.php" class="btn">
                Book Now
            </a>

        </div>

        <?php
            }

        } else {
            echo "<p style='text-align:center;'>No rooms available right now.</p>";
        }
        ?>

    </div>
</section>
    </div>
</section>

<!-- HOW IT WORKS   (system description)   -->
<section class="how">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">Search Room</div>
        <div class="step">Contact Owner</div>
        <div class="step">Move In</div>
    </div>
</section>

<!-- ABOUT The System-->
<section class="about">
    <h2>About Us</h2>
    <p>
        This Room Rental System helps people find affordable rooms and helps owners list their rooms easily.
    </p>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© 2026 Room Rental System | All Rights Reserved</p>
</footer>

<script src="/Room_Rental_System/js/index.js"></script>
</body>
</html>