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
?>

<h2>Settings</h2>

<p>Change password feature can be added here later.</p>