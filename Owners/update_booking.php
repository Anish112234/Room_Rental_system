<?php
session_start();
include("dbconnection.php");

// Check login
if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

// Validate input
if(!isset($_GET['id']) || !isset($_GET['status'])){
    header("Location: bookings.php");
    exit();
}

$id = intval($_GET['id']);
$status = strtolower(trim($_GET['status']));

// Allow only valid values
$allowed = ['accepted', 'rejected'];
if(!in_array($status, $allowed)){
    die("Invalid status");
}

// Update booking
$sql = "UPDATE bookings SET status='$status' WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if(!$result){
    die("DB Error: " . mysqli_error($conn));
}

// Redirect back
header("Location: bookings.php");
exit();
?>