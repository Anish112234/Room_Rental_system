<?php
include("dbconnection.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="details">
    <form method="POST">
    <div >
       <div class="logo">
        <h1><i class="fa-regular fa-house"></i>Room Rental
       <br>&nbsp;&nbsp;&nbsp;&nbsp; System</h1>
       </div>
       <div>
        <a class="head1">Create Account<a><br>
        <a class="head2">Register a new accoount</a>
       </div>
       <div class="field">
       <div>
        <label> Full Name</label><br>
        <input class="txt" type="text" placeholder="Enter Your Full Name" name="Fname" required><br><br>
       </div>
        <div>
        <label> Email</label><br>
        <input class="txt" type="email" placeholder="Enter Your Full Name" name="email" required><br><br>
       </div>
        <div>
        <label>Password</label><br>
        <input class="txt" type="password" placeholder="Enter Your Full Name" name="pass" required><br><br>
       </div>
        <div>
        <label> Confirm Password</label><br>
        <input class="txt" type="password" placeholder="Enter Your Full Name" name="Cpass" required><br>
       </div>
   <div class="role">
    <p>Register As</p>

    <label>
        <input type="radio" name="role" value="Customer" required> Customer
    </label>

    <label>
        <input type="radio" name="role" value="Owner"> Owner
    </label>
</div>
       <div>
        <input class="submit" type="submit" value="Register" name="submit">
       </div>
       </div>
       <div class="acc">
        <p>Already have account?<a href="#">Login here</a></p>
       </div>
    </div>
    </form>
</div>
<?php
    if(isset($_POST ['submit'])){
     $FNAME = $_POST['Fname'];
     $EMAIL = $_POST['email'];
     $PASSWORD = $_POST['pass'];
     $Cpass = $_POST['Cpass'];
     $role = $_POST['role'];
       if($PASSWORD != $Cpass){
        echo "<script> 
             alert('Doesnot match Password');
        </script>";
     }
    
       
    $SQL = "INSERT INTO users(name,email,password,Cpassword,role)
     VALUES('$FNAME','$EMAIL','$PASSWORD','$Cpass','$role')";
     
     $data = mysqli_query($conn,$SQL);
     if($data){
        echo "<script>
            alert('Sucessfully Regester');
        </script>";
        header("location:login.php");
     }
     else{
        echo "Failed to insert";
     }
    }
?>
</body>
</html>