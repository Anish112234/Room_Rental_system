<?php
session_start();
include("../dbconnection.php");

/* (security ko laghi) ADMIN CHECK */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];

/* GET ONLY OWNERS */
$query = "SELECT * FROM users
          WHERE role='owner'
          ORDER BY id DESC";

$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Owners</title>

<link rel="stylesheet" href="../Owners/css/owner.css">
<link rel="stylesheet" href="css/usertable.css">

</head>
<body>

<!--HEADER  -->

<header class="head">

    <div id="logo">Admin Panel</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">

        <a href="admin.php">Dashboard</a>

        <a href="users.php">Users</a>

        <a href="owners.php" class="home">Owners</a>

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

<!--  MAIN -->

<div class="main-content">

    <div class="topbar">
        <h2>Manage Owners</h2>
        <p>Approve or reject owner registrations</p>
    </div>

    <div class="table-box">

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php if(mysqli_num_rows($data)>0){ ?>

                <?php while($row=mysqli_fetch_assoc($data)){ ?>

                <tr>

                    <td><?= $row['id'] ?></td>

                    <td><?= htmlspecialchars($row['name']) ?></td>

                    <td><?= htmlspecialchars($row['email']) ?></td>

                    <td>

                        <?php
                        if($row['owner_status']=="pending"){
                            echo "<span style='color:#f59e0b;font-weight:bold;'>Pending</span>";
                        }
                        elseif($row['owner_status']=="approved"){
                            echo "<span style='color:green;font-weight:bold;'>Approved</span>";
                        }
                        else{
                            echo "<span style='color:red;font-weight:bold;'>Rejected</span>";
                        }
                        ?>

                    </td>

                    <td>

                        <?php if($row['owner_status']=="pending"){ ?>

                            <a class="edit-btn"
                               href="approve_owner.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('Approve this owner?')">
                                Approve
                            </a>

                            <a class="delete-btn"
                               href="reject_owner.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('Reject this owner?')">
                                Reject
                            </a>

                        <?php } elseif($row['owner_status']=="approved"){ ?>

                            <span style="color:green;font-weight:bold;">
                                Approved ✓
                            </span>

                        <?php } else { ?>

                            <span style="color:red;font-weight:bold;">
                                Rejected ✗
                            </span>

                        <?php } ?>

                    </td>

                </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td colspan="5" style="text-align:center;">
                        No owner accounts found.
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