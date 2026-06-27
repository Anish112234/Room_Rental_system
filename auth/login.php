<?php
session_start();
include("../dbconnection.php");

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    // Find user by email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        // Verify password
        if(password_verify($password, $user['password'])){

            // Owner approval check
            if($user['role'] == 'owner'){

                if($user['owner_status'] == 'pending'){

                    echo "<script>
                    alert('Your owner account is waiting for admin approval.');
                    window.location='login.php';
                    </script>";
                    exit();

                }

                if($user['owner_status'] == 'rejected'){

                    echo "<script>
                    alert('Your owner account has been rejected by the admin.');
                    window.location='login.php';
                    </script>";
                    exit();

                }

            }
             //page redirect hune thau
            $_SESSION['user'] = $user;

            if($user['role'] == 'admin'){

                header("Location: /Room_Rental_System/Admin/admin.php");
                exit();

            }

            elseif($user['role'] == 'owner'){

                header("Location: /Room_Rental_System/Owners/owner.php");
                exit();

            }
             
            elseif($user['role'] == 'customer'){

                header("Location: /Room_Rental_System/Customer/customer.php");
                exit();

            }

            else{

                echo "<script>alert('Invalid user role.');</script>";

            }

        }else{

            echo "<script>alert('Invalid email or password');</script>";

        }

    }else{

        echo "<script>alert('Invalid email or password');</script>";

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