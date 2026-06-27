<?php
session_start();
include("../dbconnection.php");

/* (security ko laghi) ADMIN CHECK */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

/* CHECK ROOM ID */
if (!isset($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$id = (int)$_GET['id'];

/* GET ROOM IMAGE */
$result = mysqli_query($conn, "SELECT image FROM rooms WHERE id='$id'");

if (mysqli_num_rows($result) > 0) {

    $room = mysqli_fetch_assoc($result);

    /* DELETE IMAGE FROM uploads FOLDER */
    if (!empty($room['image'])) {

        $imagePath = "../uploads/" . $room['image'];

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

    }

    /* DELETE ROOM FROM DATABASE */
    $delete = mysqli_query($conn, "DELETE FROM rooms WHERE id='$id'");

    if ($delete) {

        echo "<script>
        alert('Room deleted successfully.');
        window.location='rooms.php';
        </script>";

    } else {

        echo "<script>
        alert('Failed to delete room.');
        window.location='rooms.php';
        </script>";

    }

} else {

    echo "<script>
    alert('Room not found.');
    window.location='rooms.php';
    </script>";

}
?>