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

$total_rooms = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rooms WHERE owner_id='$owner_id'"));

$total_bookings = mysqli_num_rows(mysqli_query($conn, "
    SELECT b.* 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    WHERE r.owner_id='$owner_id'
"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="css/owner.css">
</head>
<body>

<div class="content">

    <!-- SIDEBAR -->
    <div class="nav">

        <div class="logo">
            <h1>🏠 ROOM RENTAL</h1>
        </div>

        <a class="active" href="owner.php">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a href="profile.php">Profile</a>

        <a href="/Room_Rental_System/Owners/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           Logout
        </a>

    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <h2>Welcome, <?= $user['name'] ?> 👋</h2>
            <p>Owner Dashboard</p>
        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Rooms</h3>
                <p><?= $total_rooms ?></p>
            </div>

            <div class="card">
                <h3>Total Bookings</h3>
                <p><?= $total_bookings ?></p>
            </div>

        </div>

    </div>

</div>

</body>
</html>