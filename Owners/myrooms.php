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

$owner_id = $_SESSION['user']['id'];

$result = mysqli_query($conn, "SELECT * FROM rooms WHERE owner_id='$owner_id'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Rooms</title>

    <!-- COMMON LAYOUT CSS -->
    <link rel="stylesheet" href="css/owner.css">

    <!-- PAGE SPECIFIC CSS -->
    <link rel="stylesheet" href="css/myrooms.css">
</head>

<body>

<div class="content">

    <!-- SIDEBAR -->
    <div class="nav">
        <h1 class="logo">🏠 ROOM RENTAL</h1>

        <a href="owner.php">Dashboard</a>
        <a class="active" href="myrooms.php">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <h2>My Rooms</h2>
            <p>Manage your properties</p>
        </div>

        <div class="room-grid">

            <?php if(mysqli_num_rows($result) == 0){ ?>
                <div class="empty">
                    <h3>No rooms added yet 🏠</h3>
                </div>
            <?php } ?>

            <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <div class="room-card">

                <img src="../uploads/<?= $row['image'] ?>">

                <div class="info">
                    <h3><?= $row['title'] ?></h3>
                    <p>📍 <?= $row['location'] ?></p>
                    <p>💰 Rs <?= $row['price'] ?></p>
                    <p>Status: <?= $row['status'] ?></p>
                </div>

                <div class="actions">
                    <a class="edit" href="edit_room.php?id=<?= $row['id'] ?>">Edit</a>
                    <a class="delete" href="delete_room.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this room?')">Delete</a>
                </div>

            </div>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>