 <?php
session_start();
require("dbconnection.php");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === "admin@gmail.com" && $password === "admin@123") {
        $_SESSION['role'] = "admin";
          $_SESSION['email'] = $email;
           $_SESSION['email'] = "Admin";
        header("Location:/Room_Rental_System/admin/admin.php");
        exit();
    }
       //database base bata tannxa email rw pw milayako xa ki nai
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);


    if ($user) {
        if ($password == $user['password']) {

            $_SESSION['user'] = $user;

                  // redirect based on role
            if ($user['role'] == "customer") {
                header("Location:/Room_Rental_System/customer/customer.php");
            } elseif ($user['role'] == "owner") {
                header("Location: /Room_Rental_system/Owners/owner.php");
            }

            exit();

        } else {
            echo "<script>alert('Wrong password');</script>";
        }

    } else {
        echo "<script>alert('Email not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
   <div>
    <div class="logo">
    <h1><i class="fa-regular fa-house"></i>ROOM RENTAL</h1>
    </div>
    <div class="login">
    <form method="POST">
        <div class="head">
        <h1>Welcome Back<br><p class="lo">Login to yours accounts</p></h1>
        </div>
        <div class="field">
        <div class="text">
     <i class="fa-regular fa-envelope"></i>
            <input  class="txt" type="email" placeholder="Enter Your Email" name="email">
        </div>
     
            <div class="text">
                <i class="fa-solid fa-lock"></i>
            <input class="txt" type="password" placeholder="Enter Your Password" name="password">
        </div>
        <diV class="soj">
            <input class="box" type="checkbox">Remember me
            <a class="forget" href="#">Forget me</a>
        </diV>
        <div>
            <input class="sumb" type="submit" value="Login" name="login">
        </div>
        <div class="acc">
        <p class="do">don't have a account?<a class="register" href="#">Register here</a></p>
        </div>
        </div>
    </form>
    </div>
   </div>
  
</body>
</html>