<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

if(!isset($_SESSION['user'])){
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}
?>
<?php
include("dbconnection.php");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM rooms WHERE id='$id'");

header("Location: myrooms.php");
exit();
?>