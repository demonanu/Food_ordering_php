<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>My Orders</title>
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="container">
    <h2>My Orders</h2>
    <?php
    $sql = "SELECT orders.id, orders.status, orders.created_at, GROUP_CONCAT(menu_items.name SEPARATOR ', ') AS items
            FROM orders
            JOIN order_items ON orders.id = order_items.order_id
            JOIN menu_items ON order_items.menu_item_id = menu_items.id
            WHERE orders.user_id = '$user_id'
            GROUP BY orders.id, orders.status, orders.created_at";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<div class='order'>";
        echo "<p><strong>Order ID:</strong> ".$row['id']."</p>";
        echo "<p><strong>Items:</strong> ".$row['items']."</p>";
        echo "<p><strong>Status:</strong> ".$row['status']."</p>";
        echo "<p><strong>Ordered at:</strong> ".$row['created_at']."</p>";
        echo "</div>";
      }
    } else {
      echo "<p>No orders found.</p>";
    }
    ?>
  </div>

  <footer class="footer">
    <p>&copy; 2024 Your Restaurant. All rights reserved.</p>
    <p><a href="contact.html">Contact Us</a> | <a href="about.html">About Us</a></p>
  </footer>
</body>
</html>
