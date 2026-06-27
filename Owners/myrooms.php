
<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include("dbconnection.php");

if(!isset($_SESSION['user']['id'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];
$owner_id = $user['id'];

$sql = "SELECT * FROM rooms WHERE owner_id='$owner_id' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rooms</title>

    <!-- Common Owner Layout -->
    <link rel="stylesheet" href="css/owner.css">

    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="css/myrooms.css">
</head>
<body>

<header class="head">

    <div id="logo">Room Rental</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">
        <a href="owner.php">Dashboard</a>
        <a href="myrooms.php" class="home">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php">Add Room</a>
        <a href="profile.php">Profile</a>

        <div class="mobile-user">
            <span>Hi, <?= htmlspecialchars($user['name']) ?></span>
            <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
        </div>
    </nav>

    <div class="portel">
        <span class="welcome-text">Hi, <?= htmlspecialchars($user['name']) ?></span>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
    </div>

</header>

<!-- PAGE HEADER -->
<section class="dashboard-section page-top">
    <h2>My Rooms</h2>
    <p>Manage all your listed rooms from here.</p>
</section>

<!-- ROOM LIST -->
<section class="rooms-page-section">
    <div class="room-grid">

        <?php if(mysqli_num_rows($result) > 0){ ?>
            
            <?php while($row = mysqli_fetch_assoc($result)) { ?>

                <div class="room-card">

                    <div class="room-image">
                        <img src="../uploads/<?= htmlspecialchars($row['image'] ?: 'default.png') ?>" alt="Room Image">
                    </div>

                    <div class="room-info">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p>📍 <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                        <p>💰 <strong>Price:</strong> Rs <?= htmlspecialchars($row['price']) ?></p>

                        <p>
                            <strong>Status:</strong>
                            <?php if(strtolower($row['status']) == 'available'){ ?>
                                <span class="status available">Available</span>
                            <?php } else { ?>
                                <span class="status unavailable"><?= htmlspecialchars(ucfirst($row['status'])) ?></span>
                            <?php } ?>
                        </p>

                        <?php if(!empty($row['description'])){ ?>
                            <p class="room-desc"><?= htmlspecialchars($row['description']) ?></p>
                        <?php } ?>
                    </div>

                    <div class="room-actions">
                        <a class="edit-btn" href="edit_room.php?id=<?= $row['id'] ?>">Edit</a>
                        <a class="delete-btn"
                           href="delete_room.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Are you sure you want to delete this room?')">
                           Delete
                        </a>
                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="empty-room-box">
                <h3>No rooms added yet 🏠</h3>
                <p>You haven’t listed any rooms yet. Add your first room now.</p>
                <a href="add.php" class="btn">Add Room</a>
            </div>

        <?php } ?>

    </div>
</section>

<footer class="footer">
    <p>© 2026 Room Rental | Owner Panel</p>
</footer>

<script>
const menuToggle = document.getElementById("menu-toggle");
const navMenu = document.getElementById("nav-menu");

if(menuToggle && navMenu){
    menuToggle.addEventListener("click", function(){
        navMenu.classList.toggle("show");
    });
}
</script>

</body>
</html>

