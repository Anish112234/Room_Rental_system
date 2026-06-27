<?php
session_start();
include("../dbconnection.php");

/* 🔐 ADMIN CHECK */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

/* CHECK ID */
if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    $query = "UPDATE users
              SET owner_status='rejected'
              WHERE id='$id' AND role='owner'";

    if (mysqli_query($conn, $query)) {

        echo "<script>
                alert('Owner rejected successfully.');
                window.location='owners.php';
              </script>";

    } else {

        echo "<script>
                alert('Something went wrong.');
                window.location='owners.php';
              </script>";

    }

} else {

    header("Location: owner.php");
    exit();

}
?>