<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "room_rental_system";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn){
    die (" failed to connect" . mysqli_connect_error());
}else{
    //echo "connect Sucessfully";
}
?>