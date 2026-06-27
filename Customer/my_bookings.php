<!-- kun kun book garayako xa dhakauxa-->
<?php
session_start();
include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $_SESSION['user']['id'];

$sql = "SELECT b.*, r.title, r.location, r.price, r.image
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id='$user_id'
        ORDER BY b.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="booking.css">
</head>
<body>

<!-- HEADER -->
<header class="head">

    <div id="logo">Room Rental</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">
        <a href="customer.php">Home</a>
        <a href="customer.php#rooms">Rooms</a>
        <a href="my_bookings.php" class="active">My Bookings</a>
        <a href="customer.php#contact">Contact</a>

        <div class="mobile-user">
            <span>Hi, <?= htmlspecialchars($user['name']) ?></span>
            <a href="/Room_Rental_System/auth/logout.php">Logout</a>
        </div>
    </nav>

    <div class="portel">
        <span class="welcome-text">Hi, <?= htmlspecialchars($user['name']) ?></span>
        <a href="/Room_Rental_System/auth/logout.php">Logout</a>
    </div>

</header>

<!-- PAGE HERO -->
<section class="page-banner">
    <h1>My Bookings</h1>
    <p>Track your booked rooms, payment details, and booking status.</p>
</section>

<!-- BOOKINGS -->
<section class="booking-section">
    <div class="room-grid">

        <?php if(mysqli_num_rows($result) > 0){ ?>

            <?php while($row = mysqli_fetch_assoc($result)){ ?>

                <div class="room-card">

                    <!-- Room image -->
                    <img src="/Room_Rental_System/uploads/<?= htmlspecialchars($row['image'] ?: 'default.png') ?>" alt="Room Image">

                    <!-- ROOM HEADER -->
                    <div class="room-header">
                        <h3><?= htmlspecialchars($row['title']); ?></h3>
                        <p>📍 <?= htmlspecialchars($row['location']); ?></p>
                        <p>💰 Rs <?= htmlspecialchars($row['price']); ?></p>
                    </div>

                    <div class="divider"></div>

                    <!-- BOOKING INFO -->
                    <div class="room-details">
                        <h4>Booking Details</h4>
                        <p><strong>Booked Date:</strong> <?= htmlspecialchars($row['booking_date']); ?></p>
                        <p><strong>Check-in:</strong> <?= htmlspecialchars($row['check_in']); ?></p>
                        <p><strong>Check-out:</strong> <?= htmlspecialchars($row['check_out']); ?></p>
                        <p><strong>Guests:</strong> <?= htmlspecialchars($row['guests']); ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']); ?></p>

                        <?php if(!empty($row['special_request'])){ ?>
                            <p><strong>Special Request:</strong> <?= htmlspecialchars($row['special_request']); ?></p>
                        <?php } ?>
                    </div>

                    <div class="divider"></div>

                    <!-- PAYMENT INFO -->
                    <div class="room-payment">
                        <h4>Payment Details</h4>
                        <p><strong>Method:</strong> <?= htmlspecialchars($row['payment_method']); ?></p>
                        <p><strong>Payment Status:</strong> <?= htmlspecialchars($row['payment_status']); ?></p>

                        <?php if(!empty($row['transaction_id'])) { ?>
                            <p><strong>Transaction ID:</strong> <?= htmlspecialchars($row['transaction_id']); ?></p>
                        <?php } ?>
                    </div>

                    <div class="divider"></div>

                    <!-- STATUS + ACTION -->
                    <div class="room-footer">

                        <span class="status <?= strtolower($row['status']); ?>">
                            <?= strtoupper($row['status']); ?>
                        </span>

                        <?php if($row['status'] == 'pending'){ ?>
                            <form method="POST" action="cancel_booking.php" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                                <button type="submit" class="cancel-btn">Cancel Booking</button>
                            </form>
                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="empty-booking">
                <h3>No bookings yet</h3>
                <p>You haven’t booked any room yet.</p>
                <a href="customer.php#rooms" class="btn">Browse Rooms</a>
            </div>

        <?php } ?>

    </div>
</section>

<footer class="footer">
    <p>© 2026 Room Rental</p>
</footer>

<script src="booking.js"></script>
</body>
</html>