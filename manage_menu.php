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
      $sql = "INSERT INTO menu_items (name, description, price, image) VALUES ('$name', '$description', '$price', '$target')";
      if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>New menu item added successfully!</p>";
      } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
      }
    } else {
      echo "<p class='error'>Failed to upload image.</p>";
    }
  }

  if (isset($_POST['edit_item'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    if ($image) {
      $target = "uploads/" . basename($image);
      move_uploaded_file($_FILES['image']['tmp_name'], $target);
      $sql = "UPDATE menu_items SET name='$name', description='$description', price='$price', image='$target' WHERE id='$id'";
    } else {
      $sql = "UPDATE menu_items SET name='$name', description='$description', price='$price' WHERE id='$id'";
    }
    if ($conn->query($sql) === TRUE) {
      echo "<p class='success'>Menu item updated successfully!</p>";
    } else {
      echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
  }

  if (isset($_POST['delete_item'])) {
    $id = $_POST['id'];
    // Delete associated order_items first to avoid foreign key constraint violation
    $sql = "DELETE FROM order_items WHERE menu_item_id='$id'";
    $conn->query($sql);
    // Delete menu item
    $sql = "DELETE FROM menu_items WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
      echo "<p class='success'>Menu item deleted successfully!</p>";
    } else {
      echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Manage Menu Items</title>
</head>
<body>
  <nav class="navbar">
    <ul>
      <li><a href="index.html">Home</a></li>
      <li><a href="menu.php">Menu</a></li>
      <li><a href="register.php">Register</a></li>
      <li><a href="login.php">Login</a></li>
      <li><a href="admin.php">Admin</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
  <div class="container">
    <h2>Manage Menu Items</h2>

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

    <div class="menu-container">
      <h3>Manage Existing Menu Items</h3>
      <?php
      $sql = "SELECT * FROM menu_items";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='menu-item'>";
          echo "<form method='post' action='' enctype='multipart/form-data'>";
          echo "<input type='hidden' name='id' value='".$row['id']."'>";
          echo "<label for='name'>Name</label>";
          echo "<input type='text' name='name' value='".$row['name']."' required>";
          echo "<label for='description'>Description</label>";
          echo "<textarea name='description' required>".$row['description']."</textarea>";
          echo "<label for='price'>Price</label>";
          echo "<input type='text' name='price' value='".$row['price']."' required>";
          echo "<label for='image'>Image</label>";
          echo "<input type='file' name='image'>";
          echo "<img src='".$row['image']."' alt='".$row['name']."' style='width:100px;height:100px;'><br>";
          echo "<button type='submit' name='edit_item'>Edit Item</button>";
          echo "<button type='submit' name='delete_item'>Delete Item</button>";
          echo "</form>";
          echo "</div>";
        }
      } else {
        echo "<p>No menu items found.</p>";
      }
      ?>
    </div>

  </div>
</body>
</html>
