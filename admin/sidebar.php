<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <div class="logo">
        <h1>🏠 ROOM RENTAL</h1>
        <p>Admin Panel</p>
    </div>

    <nav class="menu">

        <a href="admin.php" class="<?= $currentPage=='admin.php'?'active':'' ?>">
            📊 Dashboard
        </a>

        <a href="users.php" class="<?= $currentPage=='users.php'?'active':'' ?>">
            👥 Users
        </a>

        <a href="owners.php" class="<?= $currentPage=='owners.php'?'active':'' ?>">
            🧑‍💼 Owners
        </a>

        <a href="rooms.php" class="<?= $currentPage=='rooms.php'?'active':'' ?>">
            🏠 Rooms
        </a>

        <a href="bookings.php" class="<?= $currentPage=='bookings.php'?'active':'' ?>">
            📅 Bookings
        </a>

        <a href="settings.php" class="<?= $currentPage=='settings.php'?'active':'' ?>">
            ⚙ Settings
        </a>

        <a href="profile.php" class="<?= $currentPage=='profile.php'?'active':'' ?>">
            👤 Profile
        </a>

        <a href="/Room_Rental_System/auth/logout.php"
           onclick="return confirm('Logout?')">
           🚪 Logout
        </a>

    </nav>

</div>