<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $admin_username = $_POST['username'];
  $admin_password = $_POST['password'];

  // Replace with your admin username and password
  $correct_username = "admin";
  $correct_password = "admin123";

  if ($admin_username === $correct_username && $admin_password === $correct_password) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: admin.php");
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Admin Login</title>
</head>
<body>
   <?php include 'navbar.php'; ?>

  <div class="container">
    <div class="form-container">
      <h3>Admin Login</h3>
      <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
      <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" name="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
   <footer class="footer">
    <p>&copy; 2024 Your Restaurant. All rights reserved.</p>
    <p><a href="contact.html">Contact Us</a> | <a href="about.html">About Us</a></p>
  </footer>
</body>
</html>
