<?php
session_start();
include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$owner_id = $_SESSION['user']['id'];

$sql = "SELECT b.*, r.title, r.location, u.name AS customer_name
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        JOIN users u ON b.user_id = u.id
        WHERE r.owner_id='$owner_id'
        ORDER BY b.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bookings</title>

    <link rel="stylesheet" href="css/owner.css">
    <link rel="stylesheet" href="css/bookings.css">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
</head>

<body>

<div class="content">

    <div class="nav">
        <h1 class="logo">🏠 ROOM RENTAL</h1>

        <a href="owner.php">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a class="active" href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main">

        <div class="topbar">
            <h2>Bookings</h2>
            <p>Manage customer bookings</p>
        </div>

        <div class="booking-grid">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <?php
            $status = strtolower(trim($row['status'] ?? 'pending'));

            if($status == "accepted"){
                $label = "Accepted";
            } elseif($status == "rejected"){
                $label = "Rejected";
            } else {
                $label = "Pending";
            }
            ?>

            <div class="booking-card">

                <h3>🏠 <?= $row['title'] ?></h3>
                <p>📍 <?= $row['location'] ?></p>

                <p><b>Customer:</b> <?= $row['customer_name'] ?></p>
                <p><b>Date:</b> <?= $row['booking_date'] ?></p>

                <p><b>Status:</b>
                    <span class="status <?= $status ?>">
                        <?= $label ?>
                    </span>
                </p>

                <div class="actions">

                    <?php if($status == "pending") { ?>

                        <a class="accept"
                           href="update_booking.php?id=<?= $row['id'] ?>&status=accepted">
                           Accept
                        </a>

                        <a class="reject"
                           href="update_booking.php?id=<?= $row['id'] ?>&status=rejected"
                           onclick="return confirm('Are you sure?')">
                           Reject
                        </a>

                    <?php } else { ?>

                        <span class="status <?= $status ?>">
                            <?= $label ?>
                        </span>

                    <?php } ?>

                </div>

            </div>

        <?php } ?>

        </div>

    </div>

</div>

</body>
</html>