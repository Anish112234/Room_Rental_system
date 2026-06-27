<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include("dbconnection.php");

/* Login check */
if (!isset($_SESSION['user']['id'])) {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];
$owner_id = $user['id'];

/* Handle form submit */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = trim($_POST['title'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    /* Basic validation */
    if (empty($title) || empty($location) || empty($price) || empty($description)) {
        echo "<script>
                alert('Please fill all fields!');
                window.location='add.php';
              </script>";
        exit();
    }

    if (!is_numeric($price) || $price <= 0) {
        echo "<script>
                alert('Please enter a valid room price!');
                window.location='add.php';
              </script>";
        exit();
    }

    /* Image validation */
    if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
        echo "<script>
                alert('Please upload a room image!');
                window.location='add.php';
              </script>";
        exit();
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];

    $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_extensions)) {
        echo "<script>
                alert('Only JPG, JPEG, PNG and WEBP images are allowed!');
                window.location='add.php';
              </script>";
        exit();
    }

    if ($image_size > 5 * 1024 * 1024) {
        echo "<script>
                alert('Image size must be less than 5MB!');
                window.location='add.php';
              </script>";
        exit();
    }

    /* Safe image name */
    $new_image_name = time() . "_" . uniqid() . "." . $ext;
    $upload_path = "../uploads/" . $new_image_name;

    if (!move_uploaded_file($image_tmp, $upload_path)) {
        echo "<script>
                alert('Failed to upload image!');
                window.location='add.php';
              </script>";
        exit();
    }

    /* Escape inputs */
    $title = mysqli_real_escape_string($conn, $title);
    $location = mysqli_real_escape_string($conn, $location);
    $description = mysqli_real_escape_string($conn, $description);
    $price = mysqli_real_escape_string($conn, $price);

    /* Insert room */
    $sql = "INSERT INTO rooms (owner_id, title, location, price, description, image, status)
            VALUES ('$owner_id', '$title', '$location', '$price', '$description', '$new_image_name', 'available')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Room added successfully!');
                window.location='myrooms.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Database error while adding room!');
                window.location='add.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>

    <!-- Common owner layout -->
    <link rel="stylesheet" href="css/owner.css">

    <!-- Add room page style -->
    <link rel="stylesheet" href="css/add.css">
</head>
<body>

<!-- HEADER -->
<header class="head">

    <div id="logo">Room Rental</div>

    <div class="menu-toggle" id="menu-toggle">&#9776;</div>

    <nav class="content" id="nav-menu">
        <a href="owner.php">Dashboard</a>
        <a href="myrooms.php">My Rooms</a>
        <a href="bookings.php">Bookings</a>
        <a href="add.php" class="home">Add Room</a>
        <a href="profile.php">Profile</a>

        <!-- Mobile user block -->
        <div class="mobile-user">
            <span>Hi, <?= htmlspecialchars($user['name']) ?></span>
            <a href="/Room_Rental_System/Owners/logout.php"
               onclick="return confirm('Are you sure you want to logout?')">
               Logout
            </a>
        </div>
    </nav>

    <div class="portel">
        <span class="welcome-text">Hi, <?= htmlspecialchars($user['name']) ?></span>
        <a href="/Room_Rental_System/Owners/logout.php"
           onclick="return confirm('Are you sure you want to logout?')">
           Logout
        </a>
    </div>

</header>

<!-- PAGE CONTENT -->
<section class="page-section">
    <div class="page-container">

        <div class="page-header">
            <h2>Add New Room</h2>
            <p>List a new room for customers</p>
        </div>

        <div class="add-room-wrapper">
            <form method="POST" enctype="multipart/form-data" class="add-room-form">

                <div class="form-group">
                    <label for="title">Room Title</label>
                    <input type="text" id="title" name="title" placeholder="Enter room title" required>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="Enter room location" required>
                </div>

                <div class="form-group">
                    <label for="price">Room Price</label>
                    <input type="number" id="price" name="price" placeholder="Enter room price" min="1" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Write room details..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" required>
                </div>

                <button type="submit" class="submit-btn">Add Room</button>

            </form>
        </div>

    </div>
</section>

<script src="js/owner.js"></script>
</body>
</html>