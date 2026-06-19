<?php
session_start();
include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$sql = "SELECT b.*, r.title, r.location, r.price
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id='$user_id'
        ORDER BY b.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="customer.css">
</head>

<body>

<h2>My Bookings</h2>

<div class="room-grid">

<?php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
?>

<div class="room-card">

    <h3><?= $row['title'] ?></h3>
    <p>📍 <?= $row['location'] ?></p>
    <p>💰 Rs <?= $row['price'] ?></p>

    <p>Date: <?= $row['booking_date'] ?></p>

    <p>Status:
        <b><?= $row['status'] ?></b>
    </p>

</div>

<?php
    }
}else{
    echo "<p>No bookings yet</p>";
}
?>

</div>

</body>
</html>