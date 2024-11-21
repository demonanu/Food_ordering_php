<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      header("Location: menu.php"); // Redirect to menu page after login
    } else {
      echo "Invalid password.";
    }
  } else {
    echo "No user found with this username.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Login</title>
</head>
<body>
  <div class="container">
    <div class="form-container">
      <h3>Login</h3>
      <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" name="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button><br>
        <h5>Dont have an account </h5><a href="register.php">Register</a>
      </form>
    </div>
  </div>
  <footer class="footer">
    <p>&copy; 2024 Your Restaurant. All rights reserved.</p>
    <p><a href="contact.html">Contact Us</a> | <a href="about.html">About Us</a></p>
  </footer>
</body>
</html>
