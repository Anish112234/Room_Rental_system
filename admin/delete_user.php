<?php
session_start();
include("../dbconnection.php");

if(!isset($_SESSION['user'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

if($_SESSION['user']['role'] != 'admin'){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
}

header("Location: users.php");
exit();
?>