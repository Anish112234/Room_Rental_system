
<?php
session_start();
include("../dbconnection.php");

/* ADMIN CHECK  */

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];

/*  GET BOOKINGS */

$query = "SELECT
            bookings.*,
            users.name AS customer_name,
            rooms.title AS room_title,
            rooms.location AS room_location
          FROM bookings
          INNER JOIN users
            ON bookings.user_id = users.id
          INNER JOIN rooms
            ON bookings.room_id = rooms.id
          ORDER BY bookings.id DESC";

$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Bookings</title>

<link rel="stylesheet" href="../Owners/css/owner.css">
<link rel="stylesheet" href="css/bookingtable.css">
<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<!--HEADER -->

<header class="head">

  <div id="logo"><img src="../logo/Room_rental.png"></div>
     <h2 id="admin">Admin Panel</h2>

<div class="menu-toggle" id="menu-toggle">&#9776;</div>

<nav class="content" id="nav-menu">

<a href="admin.php">Dashboard</a>

<a href="users.php">Users</a>

<a href="owners.php">Owners</a>

<a href="rooms.php">Rooms</a>

<a href="bookings.php" class="home">Bookings</a>

<a href="profile.php">Profile</a>

<div class="mobile-user">

<span>Admin : <?= htmlspecialchars($user['name']) ?></span>

<a href="/Room_Rental_System/auth/logout.php"
onclick="return confirm('Logout?')">

Logout

</a>

</div>

</nav>

<div class="portel">

<span>Admin : <?= htmlspecialchars($user['name']) ?></span>

<a href="/Room_Rental_System/auth/logout.php"
onclick="return confirm('Logout?')">

Logout

</a>

</div>

</header>

<!--  MAIN  -->

<div class="main-content">

<div class="topbar">

<h2>Manage Bookings</h2>

<p>View all room bookings.</p>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Room</th>

<th>Location</th>

<th>Booking Date</th>

<th>Check In</th>

<th>Check Out</th>

<th>Guests</th>

<th>Phone</th>

<th>Payment</th>

<th>Payment Status</th>

<th>Status</th>

<th>Action</th>

</tr>

<?php

if(mysqli_num_rows($data)>0){

while($row=mysqli_fetch_assoc($data)){

?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['customer_name']); ?></td>

<td><?= htmlspecialchars($row['room_title']); ?></td>

<td><?= htmlspecialchars($row['room_location']); ?></td>

<td><?= htmlspecialchars($row['booking_date']); ?></td>

<td><?= htmlspecialchars($row['check_in']); ?></td>

<td><?= htmlspecialchars($row['check_out']); ?></td>

<td><?= htmlspecialchars($row['guests']); ?></td>

<td><?= htmlspecialchars($row['phone']); ?></td>

<td><?= htmlspecialchars($row['payment_method']); ?></td>

<td>

<?php

if($row['payment_status']=="Verified"){

echo "<span class='badge verified'>Verified</span>";

}elseif($row['payment_status']=="Pending"){

echo "<span class='badge pending'>Pending</span>";

}else{

echo "<span class='badge failed'>Failed</span>";

}

?>

</td>

<td>

<?php

if($row['status']=="accepted"){

echo "<span class='badge accepted'>Accepted</span>";

}elseif($row['status']=="pending"){

echo "<span class='badge waiting'>Pending</span>";

}else{

echo "<span class='badge cancelled'>Cancelled</span>";

}

?>

</td>

<td>

<a href="delete_booking.php?id=<?= $row['id']; ?>"
class="delete-btn"
onclick="return confirm('Delete this booking?')">

Delete

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="13" class="no-data">

No bookings found.

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<script>

const menu=document.getElementById("menu-toggle");
const nav=document.getElementById("nav-menu");

menu.onclick=function(){

nav.classList.toggle("show");

};

</script>

</body>

</html>

