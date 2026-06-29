
<?php
session_start();
include("dbconnection.php");

$message = "";
$error = "";

if(!isset($_GET['token']) || empty($_GET['token'])){
    die("Invalid reset link.");
}

$token = mysqli_real_escape_string($conn,$_GET['token']);

$sql = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE reset_token='$token'"
);

if(mysqli_num_rows($sql)!=1){
    die("Invalid reset link.");
}

$user = mysqli_fetch_assoc($sql);

/* Check expiry using PHP */
if(strtotime($user['reset_token_expiry']) < time()){
    die("This password reset link has expired.");
}

if(isset($_POST['submit'])){

    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if($password != $confirm){

        $error = "Passwords do not match.";

    }elseif(strlen($password) < 6){

        $error = "Password must be at least 6 characters.";

    }else{

        $hash = password_hash($password,PASSWORD_DEFAULT);

        $update = mysqli_query(
            $conn,
            "UPDATE users
             SET password='$hash',
                 reset_token=NULL,
                 reset_token_expiry=NULL
             WHERE id='{$user['id']}'"
        );

        if($update){

            echo "<script>
            alert('Password updated successfully.');
            window.location='login.php';
            </script>";
            exit();

        }else{

            $error = "Unable to update password.";

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

<title>Reset Password</title>

<link rel="stylesheet" href="css/reset_password.css">

</head>

<body>

<div class="container">

<form method="POST" class="form-box">

<h2>Reset Password</h2>

<p>Create a new password.</p>

<?php if($error!=""){ ?>

<div class="error">
<?= $error ?>
</div>

<?php } ?>

<label>New Password</label>

<input
type="password"
name="password"
placeholder="Enter new password"
required>

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm password"
required>

<button
type="submit"
name="submit">

Reset Password

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

