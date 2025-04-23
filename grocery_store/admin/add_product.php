<?php include '../db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Add Product</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<h2>Add New Product</h2>
<form method="POST" enctype="multipart/form-data">
  Name: <input type="text" name="name"><br>
  Price: <input type="text" name="price"><br>
  Category ID: <input type="text" name="category_id"><br>
  Image: <input type="file" name="image"><br>
  <input type="submit" name="add" value="Add Product">
</form>
<?php
if (isset($_POST['add'])) {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $category_id = $_POST['category_id'];
  $image = basename($_FILES['image']['name']);
  move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $image);
  $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, image) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sdis", $name, $price, $category_id, $image);
  if ($stmt->execute()) echo "Product added.";
  else echo "Failed to add product.";
}
?>
</body>
</html>