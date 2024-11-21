<?php
session_start();
?>
<nav class="navbar">
    <ul class="left-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="#"><?php echo $_SESSION['username']; ?></a></li>
        <?php endif; ?>
    </ul>
    <ul class="right-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
        <?php else: ?>
            <li><a href="my_orders.php">My Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
        <li><a href="admin.php">Admin</a></li>
    </ul>
</nav>
