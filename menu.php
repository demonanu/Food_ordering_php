<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $menu_item_id = $_POST['menu_item_id'];
  $quantity = $_POST['quantity'];

  $sql = "INSERT INTO orders (user_id) VALUES ('$user_id')";
  if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id;
    $sql = "INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES ('$order_id', '$menu_item_id', '$quantity')";
    if ($conn->query($sql) === TRUE) {
      echo "<p class='success'>Order placed successfully!</p>";
    } else {
      echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
  } else {
    echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Menu</title>
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h2>Menu</h2>
    <div class="menu-container">
      <?php
      $sql = "SELECT * FROM menu_items";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          
          echo "<div class='menu-item-card'>";
          echo "<h3>".$row['name']."</h3>";
          echo "<img src='".$row['image']."' alt='".$row['name']."' style='width:100px;height:100px;'><br>";
          echo "<p>".$row['description']."</p>";
          echo "<p>Price: ".$row['price']."</p>";
          echo "<form method='post' action=''>";
          echo "<input type='hidden' name='menu_item_id' value='".$row['id']."'>";
          echo "Quantity: <input type='number' name='quantity' value='1' min='1'><br>";
          if (!isset($_SESSION['user_id'])) {
            echo "<button type='submit' disabled>Login to Order</button>";
          } else {
            echo "<button type='submit'>Order</button>";
          }
          echo "</form>";
          echo "</div>";
          echo "<!-- Debug info: $image_path -->"; // Output debug info to check image path
        }
      } else {
        echo "<p>No items found.</p>";
      }
      ?>
      <?php if (!isset($_SESSION['user_id'])): ?>
        <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to place an order.</p>
      <?php endif; ?>
    </div>
  </div>

  <footer class="footer">
    <p>&copy; 2024 Your Restaurant. All rights reserved.</p>
    <p><a href="contact.html">Contact Us</a> | <a href="about.html">About Us</a></p>
  </footer>
</body>
</html>
