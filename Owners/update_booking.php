<?php
session_start();
include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$owner_id = $_SESSION['user']['id'];

/* Validate request */
if(!isset($_GET['id']) || !isset($_GET['status'])){
    echo "<script>
        alert('Invalid request!');
        window.location='bookings.php';
    </script>";
    exit();
}

$booking_id = intval($_GET['id']);
$status = strtolower(trim($_GET['status']));

/* Allow only accepted / rejected */
$allowed_status = ['accepted', 'rejected'];
if(!in_array($status, $allowed_status)){
    echo "<script>
        alert('Invalid booking status!');
        window.location='bookings.php';
    </script>";
    exit();
}

/*
    Check that:
    1) booking exists
    2) booking belongs to a room of this owner
*/
$check_sql = "SELECT b.*, r.owner_id
              FROM bookings b
              JOIN rooms r ON b.room_id = r.id
              WHERE b.id = '$booking_id'
              AND r.owner_id = '$owner_id'
              LIMIT 1";

$check_result = mysqli_query($conn, $check_sql);

if(mysqli_num_rows($check_result) == 0){
    echo "<script>
        alert('Booking not found or access denied!');
        window.location='bookings.php';
    </script>";
    exit();
}

$booking = mysqli_fetch_assoc($check_result);

/* Only pending booking can be updated */
if(strtolower($booking['status']) != 'pending'){
    echo "<script>
        alert('Only pending bookings can be updated!');
        window.location='bookings.php';
    </script>";
    exit();
}

/* Optional payment status update */
$payment_status = $booking['payment_status'];

if($status == 'accepted'){
    if(strtolower($booking['payment_method']) == 'cash on arrival'){
        $payment_status = 'Pending';
    } else {
        $payment_status = 'Verified';
    }
} elseif($status == 'rejected'){
    $payment_status = 'Rejected';
}

/* Update booking */
$update_sql = "UPDATE bookings 
               SET status = '$status',
                   payment_status = '$payment_status'
               WHERE id = '$booking_id'";

if(mysqli_query($conn, $update_sql)){

    $message = ($status == 'accepted') 
        ? "Booking accepted successfully!" 
        : "Booking rejected successfully!";

    echo "<script>
        alert('$message');
        window.location='bookings.php';
    </script>";
    exit();

} else {
    echo "<script>
        alert('Database error while updating booking!');
        window.location='bookings.php';
    </script>";
    exit();
}
?>