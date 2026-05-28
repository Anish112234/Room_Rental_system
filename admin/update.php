
<?php
include("dbconnection.php");
$id = $_GET['id'];
   $query = "SELECT*FROM users where id= '$id'";
    $data =mysqli_query($conn,$query);

     $total = mysqli_num_rows($data);
     $result =mysqli_fetch_assoc($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="update.css">
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
       <div class="field">
       <div>
        <label> Full Name</label><br>
        <input class="txt" type="text" placeholder="Enter Your Full Name" name="Fname" value="<?php echo $result['name'] ?>"><br><br>
       </div>
        <div>
        <label> Email</label><br>
        <input class="txt" type="email" placeholder="Enter Your Full Name" name="email" value="<?php echo $result['email'] ?>" ><br><br>
       </div>
        <div>
        <label>Password</label><br>
        <input class="txt" type="password" placeholder="Enter Your Full Name" name="pass" value="<?php echo $result['email'] ?>" ><br><br>
       </div>
        <div>
        <label> Confirm Password</label><br>
        <input class="txt" type="password" placeholder="Enter Your Full Name" name="Cpass" value="<?php echo $result['email'] ?>" ><br>
       </div>
       <div class="role">
        <a>ROLE:</a><br><br>
        <input type="radio" value="Customer" name="role" required>Customer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" value="Owner" name="role" required>Owner 
        <br><br>
       </div>
       <div>
        <input class="submit" type="submit" value="UPDATE" name="update">
       </div>
       </div>
    </div>
    </form>
</div>
<?php
    if(isset($_POST ['update'])){
     $FNAME = $_POST['Fname'];
     $EMAIL = $_POST['email'];
     $PASSWORD = $_POST['pass'];
     $Cpass = $_POST['Cpass'];
     $role = $_POST['role'];
    
     $query = "UPDATE users set
     name='$FNAME',
      email='$EMAIL',
      Password='$PASSWORD',
     Cpassword='$Cpass',
     role='$role'
      WHERE id='$id'";
     
     $data = mysqli_query($conn,$query);
     if($data){
        echo "<script>
            alert(' Record updated Regester');
        </script>";
        header("location:users.php");
     }
     else{
        echo "Failed to update";
     }
    }
?>
</body>
</html>