<?php
session_start();
include("../dbconnection.php");

/* 🔐 ADMIN CHECK */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];

/* GET ALL ROOMS WITH OWNER NAME */
$query = "SELECT rooms.*, users.name AS owner_name
          FROM rooms
          INNER JOIN users
          ON rooms.owner_id = users.id
          ORDER BY rooms.id DESC";

$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Rooms</title>

<link rel="stylesheet" href="../Owners/css/owner.css">
<link rel="stylesheet" href="css/roomstable.css">
<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<!--HEADER  -->

<header class="head">

 <div id="logo"><img src="../logo/Room_rental.png"></div>
     <h2 id="admin">Admin Panel</h2>

<div class="menu-toggle" id="menu-toggle">&#9776;</div>

<nav class="content" id="nav-menu">

<a href="admin.php">Dashboard</a>

<a href="users.php">Users</a>

<a href="owners.php">Owners</a>

<a href="rooms.php" class="home">Rooms</a>

<a href="bookings.php">Bookings</a>

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

<!--MAIN -->

<div class="main-content">

<div class="topbar">

<h2>Manage Rooms</h2>

<p>View all rooms uploaded by owners.</p>

</div>

<div class="table-box">

<table>

<tr>

<th>ID</th>

<th>Image</th>

<th>Title</th>

<th>Owner</th>

<th>Location</th>

<th>Price</th>

<th>Status</th>

<th>Action</th>

</tr>

<?php
if(mysqli_num_rows($data)>0){

while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $row['id'] ?></td>

<td>

<img
src="../uploads/<?= htmlspecialchars($row['image']) ?>"
width="90"
height="70"
style="object-fit:cover;border-radius:8px;">

</td>

<td>

<?= htmlspecialchars($row['title']) ?>

</td>

<td>

<?= htmlspecialchars($row['owner_name']) ?>

</td>

<td><?= htmlspecialchars($row['location']) ?></td>

<td>
    Rs. <?= number_format($row['price']) ?>
</td>

<td>
    <?php if($row['status']=="available"){ ?>
        <span class="status available">Available</span>
    <?php }else{ ?>
        <span class="status unavailable">
            <?= ucfirst($row['status']) ?>
        </span>
    <?php } ?>
</td>

<td>

    <a class="delete-btn"
       href="delete_rooms.php?id=<?= $row['id'] ?>"
       onclick="return confirm('Are you sure you want to delete this room?')">
        Delete
    </a>

</td>

</tr>

<?php
    }
}else{
?>

<tr>

<td colspan="8" style="text-align:center;">
    No rooms found.
</td>

</tr>

<?php
}
?>

</table>

</div>

</div>

<script>

const menuToggle=document.getElementById("menu-toggle");
const navMenu=document.getElementById("nav-menu");

menuToggle.addEventListener("click",function(){

    navMenu.classList.toggle("show");

});

</script>

</body>
</html>

