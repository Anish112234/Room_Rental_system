<?php
include("dbconnection.php");
include("../includes/mail.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="register.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>

<div class="details">

<form method="POST">

<div class="logo">
<h1>
Room Rental
<br>
&nbsp;&nbsp;&nbsp;&nbsp;System
</h1>
</div>

<div>
<h2 class="head1">Create Account</h2>
<p class="head2">Register a new account</p>
</div>

<div class="field">

<label>Full Name</label><br>
<input class="txt"
type="text"
name="Fname"
placeholder="Enter Your Full Name"
required>

<br><br>

<label>Email</label><br>
<input class="txt"
type="email"
name="email"
placeholder="Enter Email"
required>

<br><br>

<label>Password</label><br>
<input class="txt"
type="password"
name="pass"
placeholder="Enter Password"
required>

<br><br>

<label>Confirm Password</label><br>
<input class="txt"
type="password"
name="Cpass"
placeholder="Confirm Password"
required>

<br><br>

<div class="role">

<p><b>Register As</b></p>

<label>
<input type="radio"
name="role"
value="customer"
required>
Customer
</label>

&nbsp;&nbsp;

<label>
<input type="radio"
name="role"
value="owner">
Owner
</label>

</div>

<input class="submit"
type="submit"
name="submit"
value="Register">

</div>

<div class="acc">
Already have an account?
<a href="login.php">Login Here</a>
</div>

</form>

</div>

<?php

if(isset($_POST['submit'])){

    $name = mysqli_real_escape_string($conn, trim($_POST['Fname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['pass'];
    $confirm = $_POST['Cpass'];
    $role = strtolower($_POST['role']);

    /* Password Match */
    if($password != $confirm){

        echo "<script>
        alert('Passwords do not match');
        window.location='register.php';
        </script>";
        exit();

    }

    /* k Email Exists xa database ma already  */
    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check)>0){

        echo "<script>
        alert('Email already exists');
        window.location='register.php';
        </script>";
        exit();

    }

    /* Encrypt Password(security ko laghi) */
    $password = password_hash($password,PASSWORD_DEFAULT);

    /* Owner Approval */
    if($role=="owner"){
        $status="pending";
    }else{
        $status="approved";
    }

    /* Insert User */
    $sql="INSERT INTO users(name,email,password,role,owner_status)
          VALUES('$name','$email','$password','$role','$status')";

    if(mysqli_query($conn,$sql)){

    if($role=="owner"){

        sendMail(
            $email,
            "Owner Registration Submitted",
            "
            <h2>Welcome to Room Rental System</h2>

            <p>Hello <b>$name</b>,</p>

            <p>Your owner account has been created successfully.</p>

            <p><b>Status:</b> Pending Admin Approval</p>

            <p>Please wait for the admin to approve your account.</p>

            <p>You will receive another email after approval.</p>

            "
        );

    }else{

        sendMail(
            $email,
            "Registration Successful",
            "
            <h2>Welcome to Room Rental System</h2>

            <p>Hello <b>$name</b>,</p>

            <p>Your customer account has been created successfully.</p>

            <p>You can now log in and use the system.</p>

            "
        );

    }

    if($role=="owner"){

        echo "<script>
        alert('Owner registration submitted successfully. Please wait for Admin approval.');
        window.location='login.php';
        </script>";

    }else{

        echo "<script>
        alert('Registration Successful');
        window.location='login.php';
        </script>";

    }

}else{

    echo "<script>
    alert('Registration Failed');
    window.location='register.php';
    </script>";

}

}

?>

</body>
</html>