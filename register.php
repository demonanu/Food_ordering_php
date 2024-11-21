<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $email = $_POST['email'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];

  $sql = "INSERT INTO users (username, password, email, address, phone) VALUES ('$username', '$password', '$email', '$address', '$phone')";
  if ($conn->query($sql) === TRUE) {
    header("Location: login.php"); // Redirect to login page after registration
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Register</title>
</head>
<body>
  <div class="container">
    <div class="form-container">
      <h3>Register</h3>
      <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" name="username" required>
        <label for="password">Password</label>
        <input type="password" name="password" required>
        <label for="email">Email</label>
        <input type="email" name="email" required>
        <label for="address">Address</label>
        <textarea name="address" required></textarea>
        <label for="phone">Phone</label>
        <input type="text" name="phone" required>
        <button type="submit">Register</button>
      </form>
    </div>
  </div>
  <footer class="footer">
    <p>&copy; 2024 Your Restaurant. All rights reserved.</p>
    <p><a href="contact.html">Contact Us</a> | <a href="about.html">About Us</a></p>
  </footer>
</body>
</html>
