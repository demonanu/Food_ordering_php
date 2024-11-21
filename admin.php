<?php
session_start();
require 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
      $stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, image) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssis", $name, $description, $price, $target);
      
      if ($stmt->execute()) {
        echo "<p class='success'>New menu item added successfully!</p>";
      } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
      }
      $stmt->close();
    } else {
      echo "<p class='error'>Failed to upload image.</p>";
    }
  }

  if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
      echo "<p class='success'>Order status updated successfully!</p>";
    } else {
      echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Admin Panel</title>
</head>
<body>
  <nav class="navbar">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="menu.php">Menu</a></li>
      <li><a href="register.php">Register</a></li>
      <li><a href="login.php">Login</a></li>
      <li><a href="admin.php">Admin</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
  <div class="container">
    <h2>Admin Panel</h2>

    <div class="form-container">
      <h3>Add New Menu Item</h3>
      <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="add_item">
        <label for="name">Name</label>
        <input type="text" name="name" required>
        <label for="description">Description</label>
        <textarea name="description" required></textarea>
        <label for="price">Price</label>
        <input type="text" name="price" required>
        <label for="image">Image</label>
        <input type="file" name="image" required>
        <button type="submit">Add Item</button>
      </form>
    </div>

    <div class="order-container">
      <h3>Manage Orders</h3>
      <?php
      $sql = "SELECT orders.id, users.username, users.address, orders.status, orders.created_at
              FROM orders
              JOIN users ON orders.user_id = users.id";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='order'>";
          echo "<form method='post' action=''>";
          echo "<input type='hidden' name='order_id' value='".$row['id']."'>";
          echo "<p><strong>Order ID:</strong> ".$row['id']."</p>";
          echo "<p><strong>User:</strong> ".$row['username']."</p>";
          echo "<p><strong>Address:</strong> ".$row['address']."</p>";
          echo "<p><strong>Status:</strong> ".$row['status']."</p>";
          echo "<p><strong>Ordered at:</strong> ".$row['created_at']."</p>";
          echo "Change Status: <select name='status'>
            <option value='Pending' ".($row['status'] == 'Pending' ? 'selected' : '').">Pending</option>
            <option value='Order Accepted' ".($row['status'] == 'Order Accepted' ? 'selected' : '').">Order Accepted</option>
            <option value='Preparing' ".($row['status'] == 'Preparing' ? 'selected' : '').">Preparing</option>
            <option value='Out for Delivery' ".($row['status'] == 'Out for Delivery' ? 'selected' : '').">Out for Delivery</option>
            <option value='Delivered' ".($row['status'] == 'Delivered' ? 'selected' : '').">Delivered</option>
          </select>";
          echo "<button type='submit' name='update_status'>Update Status</button>";
          echo "</form>";
          echo "</div>";
        }
      } else {
        echo "<p>No orders found.</p>";
      }
      ?>
    </div>
  </div>
</body>
</html>
