<?php
session_start();
include("dbconnection.php");
include("../includes/mail.php");


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


/* Get customer and room details */
$mail_sql = "
SELECT
u.name AS customer_name,
u.email AS customer_email,
r.title,
r.location
FROM bookings b
JOIN users u ON b.user_id = u.id
JOIN rooms r ON b.room_id = r.id
WHERE b.id='$booking_id'
LIMIT 1
";

$mail_result = mysqli_query($conn, $mail_sql);

if(mysqli_num_rows($mail_result)==1){

    $mail = mysqli_fetch_assoc($mail_result);

    if($status=="accepted"){

        $subject="Booking Approved - Room Rental System";

        $body="
        <h2>Your Booking Has Been Approved</h2>

        <p>Hello <b>{$mail['customer_name']}</b>,</p>

        <p>Good news! Your booking request has been approved by the owner.</p>

        <hr>

        <b>Room:</b> {$mail['title']}<br>
        <b>Location:</b> {$mail['location']}<br>
        <b>Status:</b> Accepted

        <hr>

        <p>Thank you for using Room Rental System.</p>
        ";

    }else{

        $subject="Booking Rejected - Room Rental System";

        $body="
        <h2>Booking Update</h2>

        <p>Hello <b>{$mail['customer_name']}</b>,</p>

        <p>Unfortunately, your booking request has been rejected by the owner.</p>

        <hr>

        <b>Room:</b> {$mail['title']}<br>
        <b>Location:</b> {$mail['location']}<br>

        <hr>

        <p>You can login and book another available room.</p>

        <p>Room Rental System</p>
        ";

    }

    sendMail(
        $mail['customer_email'],
        $subject,
        $body
    );

}



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