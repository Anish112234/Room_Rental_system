<?php
session_start();
include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$room_id = $_GET['id'];

/* check if room exists */
$room = mysqli_query($conn, "SELECT * FROM rooms WHERE id='$room_id'");
if(mysqli_num_rows($room) == 0){
    die("Room not found");
}

/* check duplicate booking */
$check = mysqli_query($conn, "SELECT * FROM bookings 
            WHERE user_id='$user_id' AND room_id='$room_id'");

if(mysqli_num_rows($check) > 0){
    echo "<script>
        alert('You already booked this room!');
        window.location='customer.php';
    </script>";
    exit();
}

/* insert booking */
$date = date("Y-m-d");

$sql = "INSERT INTO bookings(user_id, room_id, booking_date, status)
        VALUES('$user_id', '$room_id', '$date', 'pending')";

if(mysqli_query($conn, $sql)){
    echo "<script>
        alert('Room booked successfully!');
        window.location='my_bookings.php';
    </script>";
}else{
    echo "Error: " . mysqli_error($conn);
}
?>