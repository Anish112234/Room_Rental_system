<?php
include("dbconnection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Table</title>
    <link rel="stylesheet" href="usertable.css">
</head>
<body>
    <?php
    $query = "SELECT*FROM users";
    $data =mysqli_query($conn,$query);
    if(!$data){
        die("failed:".mysqli_error($conn));
    }
    $total = mysqli_num_rows($data);
    if($total>0){
    }
    ?>
    <div class="details">
    <table border="1" width="100%">
        <tr>
            <th width="5%">id</th>
            <th width="22%">Fullname</th>
            <th width="23%">Email</th>
            <th width="9%">Password</th>
            <th width="9%">Role</th>
            <th width="19%">Created_Time</th>
            <th width="13%" colspan="2">Operations</th>
        </tr>
        <?php
        while($result =mysqli_fetch_assoc($data)){
            echo "<tr>
            <td>".$result['id']."</td>
            <td>".$result['name']."</td>
            <td>".$result['email']."</td>
            <td>".$result['password']."</td>
            <td>".$result['role']."</td>
            <td>".$result['created_at']."</td>
            <td><a href='update.php?id=$result[id]'>EDIT</a></td>
            <td><a href=''>DELETE</a></td>
          </tr>";
        }
echo "</table>";
?>
</div>
</body>
</html>