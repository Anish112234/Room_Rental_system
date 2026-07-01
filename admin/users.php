
<?php
session_start();
include("../dbconnection.php");

/* 🔐 ADMIN CHECK */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];

/* ROLE FILTER */
$role = $_GET['role'] ?? 'all';

if ($role == 'all') {
    $query = "SELECT * FROM users ORDER BY id DESC";
} else {
    $role = mysqli_real_escape_string($conn, $role);
    $query = "SELECT * FROM users WHERE role='$role' ORDER BY id DESC";
}

$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>

<link rel="stylesheet" href="../Owners/css/owner.css">
<link rel="stylesheet" href="css/usertable.css">
<link rel="stylesheet" href="css/admin.css">

</head>
<body>

<!-- HEADER -->

<header class="head">

   <div id="logo"><img src="../logo/Room_rental.png"></div>
     <h2 id="admin">Admin Panel</h2>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">

        <a href="admin.php">Dashboard</a>

        <a href="users.php" class="home">Users</a>

        <a href="owners.php">Owners</a>

        <a href="rooms.php">Rooms</a>

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

<!-- MAIN  -->

<div class="main-content">

    <div class="topbar">
        <h2>Manage Users</h2>
        <p>Welcome, <?= htmlspecialchars($user['name']) ?> 👋</p>
    </div>

    <!-- FILTER -->

    <div class="filter-box">

        <a href="users.php?role=all"
           class="<?= $role=='all' ? 'active-filter' : '' ?>">
           All
        </a>

        <a href="users.php?role=admin"
           class="<?= $role=='admin' ? 'active-filter' : '' ?>">
           Admin
        </a>

        <a href="users.php?role=owner"
           class="<?= $role=='owner' ? 'active-filter' : '' ?>">
           Owner
        </a>

        <a href="users.php?role=customer"
           class="<?= $role=='customer' ? 'active-filter' : '' ?>">
           Customer
        </a>

    </div>

    <!-- TABLE -->

    <div class="table-box">

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Action</th>
            </tr>

            <?php if(mysqli_num_rows($data)>0){ ?>

                <?php while($row=mysqli_fetch_assoc($data)){ ?>

                <tr>

                    <td><?= $row['id'] ?></td>

                    <td><?= htmlspecialchars($row['name']) ?></td>

                    <td><?= htmlspecialchars($row['email']) ?></td>

                    <td><?= ucfirst($row['role']) ?></td>

                    <td><?= $row['created_at'] ?? '-' ?></td>

                    <td>

                        <a class="edit-btn"
                           href="update.php?id=<?= $row['id'] ?>">
                            Edit
                        </a>

                        <a class="delete-btn"
                           href="delete_user.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this user?')">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="6" style="text-align:center;">
                        No users found
                    </td>
                </tr>

            <?php } ?>

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

