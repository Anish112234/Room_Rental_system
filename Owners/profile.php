<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

if(!isset($_SESSION['user'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}
?>
<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/owner.css">
</head>
<body>

<div class="content">

    <!-- SIDEBAR (same as owner) -->
    <div class="nav">
        <h1 class="logo">🏠 ROOM RENTAL</h1>

        <a href="owner.php">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a class="active" href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <h2>My Profile</h2>
            <p>Account details</p>
        </div>

        <div class="profile-card">

            <div class="avatar">
                <?= strtoupper(substr($user['name'],0,1)) ?>
            </div>

            <h2><?= $user['name'] ?></h2>

            <div class="info">
                <p><b>Email:</b> <?= $user['email'] ?></p>
                <p><b>Role:</b> <?= $user['role'] ?></p>
                <p><b>User ID:</b> <?= $user['id'] ?></p>
            </div>

        </div>

    </div>

</div>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/owner.css">
</head>
<body>

<div class="content">

    <!-- SIDEBAR (same as owner) -->
    <div class="nav">
        <h1 class="logo">🏠 ROOM RENTAL</h1>

        <a href="owner.php">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a class="active" href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <h2>My Profile</h2>
            <p>Account details</p>
        </div>

        <div class="profile-card">

            <div class="avatar">
                <?= strtoupper(substr($user['name'],0,1)) ?>
            </div>

            <h2><?= $user['name'] ?></h2>

            <div class="info">
                <p><b>Email:</b> <?= $user['email'] ?></p>
                <p><b>Role:</b> <?= $user['role'] ?></p>
                <p><b>User ID:</b> <?= $user['id'] ?></p>
            </div>

        </div>

    </div>

</div>

</body>
</html>