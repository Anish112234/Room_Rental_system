<?php
session_start();
include("../dbconnection.php");

/* ================= ADMIN CHECK ================= */

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];

$message = "";
$error = "";

/* ================= UPDATE PROFILE ================= */

if (isset($_POST['update'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    $id = $user['id'];

    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {

        /* CHECK DUPLICATE EMAIL */
        $check = mysqli_query($conn,
            "SELECT id FROM users WHERE email='$email' AND id!='$id'");

        if (mysqli_num_rows($check) > 0) {
            $error = "Email already exists.";
        } else {

            /* UPDATE WITHOUT PASSWORD */
            if (empty($password)) {

                $stmt = mysqli_prepare(
                    $conn,
                    "UPDATE users SET name=?, email=? WHERE id=?"
                );

                mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $id);

            } else {

                if ($password != $confirm) {

                    $error = "Passwords do not match.";

                } else {

                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = mysqli_prepare(
                        $conn,
                        "UPDATE users SET name=?, email=?, password=? WHERE id=?"
                    );

                    mysqli_stmt_bind_param(
                        $stmt,
                        "sssi",
                        $name,
                        $email,
                        $hash,
                        $id
                    );
                }
            }

            if (empty($error)) {

                if (mysqli_stmt_execute($stmt)) {

                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['email'] = $email;

                    $user = $_SESSION['user'];

                    $message = "Profile updated successfully.";

                } else {
                    $error = "Failed to update profile.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Profile</title>

<link rel="stylesheet" href="../Owners/css/owner.css">
<link rel="stylesheet" href="css/profile.css">
<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<header class="head">

 <div id="logo"><img src="../logo/Room_rental.png"></div>
     <h2 id="admin">Admin Panel</h2>

<div class="menu-toggle" id="menu-toggle">&#9776;</div>

<nav class="content" id="nav-menu">

<a href="admin.php">Dashboard</a>
<a href="users.php">Users</a>
<a href="owners.php">Owners</a>
<a href="rooms.php">Rooms</a>
<a href="bookings.php">Bookings</a>
<a href="profile.php" class="home">Profile</a>

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

<div class="main-content">

<div class="profile-box">

<h2>Admin Profile</h2>

<?php if($message){ ?>
<div class="success"><?= $message ?></div>
<?php } ?>

<?php if($error){ ?>
<div class="error"><?= $error ?></div>
<?php } ?>

<form method="POST">

<label>Name</label>
<input type="text" name="name"
value="<?= htmlspecialchars($user['name']) ?>" required>

<label>Email</label>
<input type="email" name="email"
value="<?= htmlspecialchars($user['email']) ?>" required>

<label>Role</label>
<input type="text"
value="<?= htmlspecialchars($user['role']) ?>" readonly>

<label>New Password</label>
<input type="password" name="password"
placeholder="Leave blank to keep current password">

<label>Confirm Password</label>
<input type="password" name="confirm_password"
placeholder="Confirm new password">

<button type="submit" name="update">
Update Profile
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