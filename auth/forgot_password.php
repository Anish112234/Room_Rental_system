
<?php
session_start();
include("dbconnection.php");
include("../includes/mail.php");

$message = "";
$error = "";

if(isset($_POST['submit'])){

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    if(empty($email)){

        $error = "Please enter your email.";

    }else{

        $check = mysqli_query(
            $conn,
            "SELECT id,name,email FROM users WHERE email='$email'"
        );

        if(mysqli_num_rows($check) == 1){

            $user = mysqli_fetch_assoc($check);

            $token = bin2hex(random_bytes(32));

            $expiry = date(
                "Y-m-d H:i:s",
                strtotime("+1 hour")
            );

            mysqli_query(
                $conn,
                "UPDATE users
                 SET reset_token='$token',
                     reset_token_expiry='$expiry'
                 WHERE id='{$user['id']}'"
            );

            $link =
            "http://localhost/Room_Rental_System/auth/reset_password.php?token=$token";

            $subject = "Reset Your Password";

            $body = "
            
<div class='logo'>

<h1>
🏠 Room Rental
<br>
&nbsp;&nbsp;&nbsp;&nbsp;System
</h1>

</div>


            <h2>Room Rental System</h2>

            <p>Hello <b>{$user['name']}</b>,</p>

            <p>We received a request to reset your password.</p>

            <p>Click the button below to reset it:</p>

            <p>
                <a href='$link'
                style='background:#0d6efd;
                color:#fff;
                padding:12px 20px;
                text-decoration:none;
                border-radius:6px;'>
                Reset Password
                </a>
            </p>

            <p>Or copy this link:</p>

            <p>$link</p>

            <p>This link expires in <b>1 hour</b>.</p>

            <p>If you didn't request this,
            please ignore this email.</p>

            <br>

            <p>Regards,<br>
            <b>Room Rental System</b></p>
            ";

            if(sendMail($email,$subject,$body)){

                $message =
                "Password reset link has been sent to your email.";

            }else{

                $error =
                "Unable to send email.";

            }

        }else{

            $error = "Email not found.";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Forgot Password</title>

<link rel="stylesheet"
href="css/forgot_password.css">

</head>

<body>

<div class="container">

<form method="POST" class="form-box">

<h2>Forgot Password</h2>

<p>
Enter your registered email address.
</p>

<?php
if($message!=""){
?>
<div class="success">
<?= $message ?>
</div>
<?php
}
?>

<?php
if($error!=""){
?>
<div class="error">
<?= $error ?>
</div>
<?php
}
?>

<label>Email</label>

<input
type="email"
name="email"
placeholder="Enter your email"
required>

<button
type="submit"
name="submit">

Send Reset Link

</button>

<div class="login-link">

<a href="login.php">

Back to Login

</a>

</div>

</form>

</div>

</body>
</html>

