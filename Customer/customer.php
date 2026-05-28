
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="Customer.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="Full">
    <div class="nav">
        <div class="logo">
 <h1><i class="fa-regular fa-house"></i>ROOM RENTAL</h1>
   </div>
   <div>
    <a href="#" class="Profile"><i class="fa-solid fa-user"></i></a>Profile
   </div>   
   <div> 
   <i class="fa-regular fa-house"></i><a href="customer.php">Dashboard</a>
</div>
<div>
<i class="fa-brands fa-chrome"></i><a href="#">Browse Rooms</a>
</div>
<div>
<i class="fa-solid fa-bookmark"></i></i><a href="#">My bookings</a>
</div>
<div>
  <i class="fa-regular fa-heart"></i> <a href="#"> Wishlist</a>
    </div>
    <div>
       <i class="fa-regular fa-message"></i><a href="#">Message</a>
    </div>
    <div>
        <i class="fa-regular fa-circle-user"></i><a href="#">Profile</a>
    </div>
    <div>
        <i class="fa-solid fa-gear"></i><a href="#">setting</a>
    </div>
    <div>
       <i class="fa-solid fa-right-from-bracket"></i> <a href="#">Logout</a>
    </div>
</div>
<div class="dashboard">
    <div>
       Customer Dashboard <a class="Top"><i class="fa-regular fa-bell"></i> <i class="fa-solid fa-user"></i>name </a>
    </div>
    <div class="Find">
      <h1>Find Yours Perfect Rooms</h1>
      <div class="search">
      <div class="srch">
        <label>Location</label><br>
        <input class="txt"  type="text" placeholder="Enter location">
      </div>
        <div class="srch">
        <label>Check In</label><br>
        <input class="txt" type="text" placeholder="Enter location">
      </div>
        <div class="srch">
        <label>Check out</label><br>
        <input class="txt"  type="text" placeholder="Enter location">
      </div>
        <div class="srch">
          <label>Guest</label><br>
        <select>
          <option class="txt" >Guest1</option>
          <option class="txt" >Guest2</option>
          <option class="txt" >Guest3</option>
          <option class="txt" >Guest4</option>
        </select>
      </div>
      <div class="srch">
        <button class="btn" name="search">Search</button>
      </div>
      </div>
    </div>
</div>
</div>
</script>
</body>
</html>