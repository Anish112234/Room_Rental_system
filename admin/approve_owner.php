<?php
session_start();
include("../dbconnection.php");
include("../includes/mail.php");//for mail connect

/* Admin Check */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

/* Check ID */
if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];


/* Get Owner Information */
$getOwner = mysqli_query(
    $conn,
    "SELECT name, email FROM users
     WHERE id='$id' AND role='owner'"
);

$owner = mysqli_fetch_assoc($getOwner);


    $query = "UPDATE users
              SET owner_status='approved'
              WHERE id='$id' AND role='owner'";

    if (mysqli_query($conn, $query)) {

    //mail send garna ko laghi
    sendMail(
    $owner['email'],
    "Owner Account Approved",
    "
    <h2>Congratulations!</h2>

    <p>Hello <b>{$owner['name']}</b>,</p>

    <p>Your owner account has been <b>approved</b> by the administrator.</p>

    <p>You can now log in to the Room Rental System and start adding your rooms.</p>

    <br>

    <p>Best Regards,<br><b>Room Rental System</b></p>
    "
);

        echo "<script>
                alert('Owner approved successfully.');
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