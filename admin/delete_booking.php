
<?php
session_start();
include("../dbconnection.php");

/* ADMIN CHECK*/

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

/*  DELETE BOOKING */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: bookings.php");
    exit();
}

$id = (int)$_GET['id'];

/* Check booking exists */
$check = mysqli_query($conn, "SELECT id FROM bookings WHERE id = $id");

if (mysqli_num_rows($check) > 0) {

    $delete = mysqli_query($conn, "DELETE FROM bookings WHERE id = $id");

    if ($delete) {
        header("Location: bookings.php?success=deleted");
        exit();
    } else {
        header("Location: bookings.php?error=deletefailed");
        exit();
    }

} else {

    header("Location: bookings.php?error=notfound");
    exit();

}
?>

