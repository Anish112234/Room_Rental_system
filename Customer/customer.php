
<?php
session_start();
include("dbconnection.php");

if (!isset($_SESSION['user']['id'])) {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];

/*ROOM AVAILABILITY CHECK 
   This checks if the room is occupied TODAY.
   If today falls inside any non-cancelled booking, room = booked.
*/
function checkRoomAvailability($conn, $room_id) {
    $today = date("Y-m-d");
    $room_id = intval($room_id);

    $sql = "SELECT id FROM bookings
            WHERE room_id = ?
            AND status != 'cancelled'
            AND ? >= check_in
            AND ? < check_out
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $room_id, $today, $today);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_num_rows($result) == 0;
}

/*filter based on price and location*/
$location = trim($_GET['location'] ?? '');
$price = trim($_GET['price'] ?? '');

/*ROOM SEARCH QUERY  */
$sql = "SELECT * FROM rooms WHERE status = 'available'";
$params = [];
$types = "";
if (!empty($location)) {
    $sql .= " AND location LIKE ?";
    $location_param = "%" . $location . "%";
    $params[] = $location_param;
    $types .= "s";
}
if (!empty($price) && is_numeric($price)) {
    $sql .= " AND price <= ?";
    $params[] = $price;
    $types .= "d";
}
$sql .= " ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>

<header class="head">

    <div id="logo">Room Rental</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">
        <a href="customer.php" class="home">Home</a>
        <a href="#rooms">Rooms</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="profile.php">Profile</a>
        <a href="#how">How</a>
        <a href="#contact">Contact</a>
        
        <div class="mobile-user">
            <span>Hi, <?= htmlspecialchars($user['name']) ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="portel">
        <span class="welcome-text">Hi, <?= htmlspecialchars($user['name']) ?></span>
      <a href="../auth/logout.php"
   onclick="return confirm('Are you sure you want to logout?')">
    Logout
</a>
    </div>

</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome <?= htmlspecialchars($user['name']) ?></h1>
        <p>Find your perfect room</p>
        <a href="#rooms" class="btn">Browse Rooms</a>
    </div>
</section>

<!-- SEARCH -->
<section class="search-section">
    <h2>Search Rooms</h2>

    <form class="search-box" method="GET">
        <input 
            type="text" 
            name="location" 
            placeholder="Location"
            value="<?= htmlspecialchars($location) ?>"
        >

        <input 
            type="number" 
            name="price" 
            placeholder="Max Price"
            value="<?= htmlspecialchars($price) ?>"
        >

        <button type="submit">Search</button>
        <a href="customer.php" class="btn reset-btn">Reset</a>
    </form>
</section>

<!-- ROOMS -->
<section class="rooms-section" id="rooms">
    <h2>Available Rooms</h2>

    <div class="room-grid">

        <?php if (mysqli_num_rows($result) > 0) { ?>
            
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                
                <?php $available = checkRoomAvailability($conn, $row['id']); ?>

                <div class="room-card">

                    <img src="/Room_Rental_System/uploads/<?= htmlspecialchars(!empty($row['image']) ? $row['image'] : 'default.png') ?>" alt="Room Image">

                    <!-- Availability badge -->
                    <?php if ($available) { ?>
                      <span class="status available">🟢 Available</span>
                    <?php } else { ?>
                       <span class="status booked">🔴 Booked</span>
                    <?php } ?>

                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p>📍 <?= htmlspecialchars($row['location']) ?></p>
                    <p>💰 Rs <?= htmlspecialchars($row['price']) ?></p>

                    <p class="room-desc"><?= htmlspecialchars($row['description']) ?></p>

                    <!-- Book button -->
                    <?php if ($available) { ?>
                        <a href="book_room.php?id=<?= $row['id'] ?>" class="btn">Book Now</a>
                    <?php } else { ?>
                        <button class="btn" style="background:gray;cursor:not-allowed;" disabled>
                            Not Available
                        </button>
                    <?php } ?>

                </div>

            <?php } ?>

        <?php } else { ?>
            <p style="text-align:center; width:100%; font-size:16px; color:#666;">
                No rooms found for your search.
            </p>
        <?php } ?>

    </div>
</section>

<!-- HOW -->
<section class="how" id="how">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">Search</div>
        <div class="step">Book</div>
        <div class="step">Move</div>
    </div>
</section>

<!-- CONTACT -->
<section class="about" id="contact">
    <h2>Contact</h2>
    <p>support@roomrental.com</p>
</section>

<footer class="footer">
    <p>© 2026 Room Rental</p>
</footer>

<script src="customer.js"></script>

</body>
</html>

