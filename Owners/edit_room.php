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

$id = $_GET['id'];

// (optional safety) ensure only owner can edit their own room
$owner_id = $_SESSION['user']['id'];

$result = mysqli_query($conn, "SELECT * FROM rooms WHERE id='$id' AND owner_id='$owner_id'");
$row = mysqli_fetch_assoc($result);

if(!$row){
    echo "Room not found or unauthorized access";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
</head>
<body>

<h2>Edit Room</h2>

<form method="POST">
    <input type="text" name="title" value="<?= $row['title'] ?>" required><br>
    <input type="text" name="location" value="<?= $row['location'] ?>" required><br>
    <input type="number" name="price" value="<?= $row['price'] ?>" required><br>
    <textarea name="description"><?= $row['description'] ?></textarea><br>

    <button name="update">Update</button>
</form>

<?php
if(isset($_POST['update'])){
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    mysqli_query($conn, "UPDATE rooms 
        SET title='$title', location='$location', price='$price', description='$description'
        WHERE id='$id'");

    echo "<script>alert('Updated');window.location='myrooms.php';</script>";
}
?>

</body>
</html>