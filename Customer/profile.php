
<?php
session_start();
include("dbconnection.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != "customer") {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];
$message = "";
$error = "";

if(isset($_POST['update'])){

    $name = trim($_POST['name']);

    if(empty($name)){

        $error = "Name cannot be empty.";

    }else{

        $id = $user['id'];

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users SET name=? WHERE id=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $name,
            $id
        );

        if(mysqli_stmt_execute($stmt)){

            $_SESSION['user']['name'] = $name;
            $user = $_SESSION['user'];

            $message = "Profile updated successfully.";

        }else{

            $error = "Failed to update profile.";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Customer Profile</title>

<link rel="stylesheet" href="customer.css">
<link rel="stylesheet" href="css/profile.css">

</head>

<body>

<header class="head">

<div id="logo">Room Rental</div>

<div class="menu-toggle" id="menu-toggle">&#9776;</div>

<nav class="content" id="nav-menu">

<a href="customer.php">Home</a>
<a href="my_bookings.php">My Bookings</a>
<a href="profile.php" class="home">Profile</a>

<div class="mobile-user">

<span>
Hi,
<?= htmlspecialchars($user['name']) ?>
</span>

<a href="../auth/logout.php">
Logout
</a>

</div>

</nav>

<div class="portel">

<span>
Hi,
<?= htmlspecialchars($user['name']) ?>
</span>

<a href="../auth/logout.php">
Logout
</a>

</div>

</header>

<div class="main-content">

<div class="profile-box">

<h2>Customer Profile</h2>

<?php if($message!=""){ ?>

<div class="success">

<?= $message ?>

</div>

<?php } ?>

<?php if($error!=""){ ?>

<div class="error">

<?= $error ?>

</div>

<?php } ?>

<form method="POST">

<label>Full Name</label>

<input
type="text"
name="name"
value="<?= htmlspecialchars($user['name']) ?>"
required>

<label>Email</label>

<input
type="email"
value="<?= htmlspecialchars($user['email']) ?>"
readonly>

<label>Role</label>

<input
type="text"
value="<?= htmlspecialchars($user['role']) ?>"
readonly>

<button
type="submit"
name="update">

Save Changes

</button>

</form>

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
