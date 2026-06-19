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

$owner_id = $_SESSION['user']['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/add.css">
    <title>Add Room</title>
</head>

<body>

<div class="container">
<form action="" method="POST" enctype="multipart/form-data">

    <label>Room Title</label>
    <input type="text" name="title" required>

    <label>Location</label>
    <input type="text" name="location" required>

    <label>Room Price</label>
    <input type="number" name="price" required>

    <label>Description</label>
    <textarea name="description" required></textarea>

    <label>Upload image</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" name="submit">Add Room</button>

</form>
</div>

<?php
if(isset($_POST['submit'])){

    // ✅ LOGIN CHECK (IMPORTANT FIX)
    if(!isset($_SESSION['user']['id'])){
        die("You must login first");
    }

    $owner_id = $_SESSION['user']['id'];

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // ✅ SAFE IMAGE HANDLING
    $image = time() . "_" . $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    move_uploaded_file($temp, "../uploads/" . $image);

    $sql = "INSERT INTO rooms(owner_id, title, location, price, description, image)
            VALUES('$owner_id', '$title', '$location', '$price', '$description', '$image')";

    if(mysqli_query($conn, $sql)){
        echo "<script>
            alert('Room added successfully!');
            window.location.href='add.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

</body>
</html>