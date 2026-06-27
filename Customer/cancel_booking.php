<?php
session_start();
include("dbconnection.php");

if (!isset($_SESSION['user']['id'])) {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

/* Only allow POST request */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my_bookings.php");
    exit();
}

if (!isset($_POST['booking_id']) || empty($_POST['booking_id'])) {
    echo "<script>
        alert('Invalid booking request!');
        window.location='my_bookings.php';
    </script>";
    exit();
}

$booking_id = intval($_POST['booking_id']);

/* Check if booking belongs to logged-in user */
$check = mysqli_query($conn, "
    SELECT * FROM bookings
    WHERE id = '$booking_id'
    AND user_id = '$user_id'
");

if (mysqli_num_rows($check) == 0) {
    echo "<script>
        alert('Booking not found or access denied!');
        window.location='my_bookings.php';
    </script>";
    exit();
}

$row = mysqli_fetch_assoc($check);

/* Only pending bookings can be cancelled */
if ($row['status'] !== 'pending') {
    echo "<script>
        alert('Only pending bookings can be cancelled!');
        window.location='my_bookings.php';
    </script>";
    exit();
}

/* Cancel booking */
$update = mysqli_query($conn, "
    UPDATE bookings
    SET status = 'cancelled'
    WHERE id = '$booking_id'
");

if ($update) {
    echo "<script>
        alert('Booking cancelled successfully!');
        window.location='my_bookings.php';
    </script>";
    exit();
} else {
    echo "<script>
        alert('Something went wrong while cancelling booking!');
        window.location='my_bookings.php';
    </script>";
    exit();
}
?>