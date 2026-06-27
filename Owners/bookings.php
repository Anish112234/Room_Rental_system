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

/*
    Get all bookings for rooms that belong to this owner
    Also fetch customer name and room details
*/
$sql = "SELECT b.*, 
               r.title, 
               r.location, 
               r.price,
               u.name AS customer_name
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        JOIN users u ON b.user_id = u.id
        WHERE r.owner_id='$owner_id'
        ORDER BY b.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Bookings</title>

    <link rel="stylesheet" href="css/owner.css">
    <link rel="stylesheet" href="css/bookings.css">
</head>
<body>

<!-- HEADER -->
<header class="head">

    <div id="logo">Room Rental</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">
        <a href="owner.php">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a href="bookings.php" class="home">Bookings</a>
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

<!-- PAGE CONTENT -->
<section class="page-section">
    <div class="page-container">

        <div class="page-header">
            <h2>Bookings</h2>
            <p>Manage customer booking requests</p>
        </div>

        <div class="booking-grid">

            <?php if(mysqli_num_rows($result) > 0){ ?>

                <?php while($row = mysqli_fetch_assoc($result)) { ?>

                    <?php
                    $status = strtolower(trim($row['status'] ?? 'pending'));

                    if($status == "accepted"){
                        $label = "Accepted";
                    } elseif($status == "rejected"){
                        $label = "Rejected";
                    } elseif($status == "cancelled"){
                        $label = "Cancelled";
                    } else {
                        $label = "Pending";
                    }
                    ?>

                    <div class="booking-card">

                        <!-- ROOM INFO -->
                        <div class="booking-room">
                            <h3>🏠 <?= htmlspecialchars($row['title']) ?></h3>
                            <p>📍 <?= htmlspecialchars($row['location']) ?></p>
                            <p>💰 Rs <?= htmlspecialchars($row['price']) ?></p>
                        </div>

                        <hr>

                        <!-- CUSTOMER INFO -->
                        <div class="booking-section">
                            <h4>Customer Details</h4>
                            <p><strong>Name:</strong> <?= htmlspecialchars($row['customer_name']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                            <p><strong>Guests:</strong> <?= htmlspecialchars($row['guests']) ?></p>
                        </div>

                        <hr>

                        <!-- BOOKING INFO -->
                        <div class="booking-section">
                            <h4>Booking Details</h4>
                            <p><strong>Booked On:</strong> <?= htmlspecialchars($row['booking_date']) ?></p>
                            <p><strong>Check-in:</strong> <?= htmlspecialchars($row['check_in']) ?></p>
                            <p><strong>Check-out:</strong> <?= htmlspecialchars($row['check_out']) ?></p>
                        </div>

                        <hr>

                        <!-- PAYMENT INFO -->
                        <div class="booking-section">
                            <h4>Payment Details</h4>
                            <p><strong>Method:</strong> <?= htmlspecialchars($row['payment_method']) ?></p>
                            <p><strong>Payment Status:</strong> <?= htmlspecialchars($row['payment_status']) ?></p>

                            <?php if(!empty($row['transaction_id'])) { ?>
                                <p><strong>Transaction ID:</strong> <?= htmlspecialchars($row['transaction_id']) ?></p>
                            <?php } ?>
                        </div>

                        <?php if(!empty($row['special_request'])) { ?>
                            <hr>
                            <div class="booking-section">
                                <h4>Special Request</h4>
                                <p><?= nl2br(htmlspecialchars($row['special_request'])) ?></p>
                            </div>
                        <?php } ?>

                        <hr>

                        <!-- STATUS + ACTION -->
                        <div class="booking-footer">

                            <p>
                                <strong>Status:</strong>
                                <span class="status <?= $status ?>">
                                    <?= $label ?>
                                </span>
                            </p>

                            <div class="actions">
                                <?php if($status == "pending") { ?>

                                    <a class="accept"
                                       href="update_booking.php?id=<?= $row['id'] ?>&status=accepted"
                                       onclick="return confirm('Accept this booking?')">
                                       Accept
                                    </a>

                                    <a class="reject"
                                       href="update_booking.php?id=<?= $row['id'] ?>&status=rejected"
                                       onclick="return confirm('Reject this booking?')">
                                       Reject
                                    </a>

                                <?php } else { ?>
                                    <span class="done-action"><?= $label ?></span>
                                <?php } ?>
                            </div>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <div class="empty-booking">
                    <h3>No bookings found yet 📭</h3>
                    <p>When customers book your rooms, they will appear here.</p>
                </div>

            <?php } ?>

        </div>

    </div>
</section>

<script src="js/owner.js"></script>
</body>
</html>