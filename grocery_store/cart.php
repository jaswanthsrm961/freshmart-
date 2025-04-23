<?php session_start(); include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Your Cart - FreshMart</title>
  <link rel="stylesheet" href="css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <header>
    <h1>FreshMart</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="cart.php" class="active">Cart</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
      <?php endif; ?>
    </nav>
  </header>

  <section class="cart-container">
    <h2>Your Shopping Cart</h2>
<?php
$user_id = $_SESSION['user_id'] ?? 0;
$result = $conn->query("SELECT p.id, p.name, c.quantity, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");
$total = 0;
while ($row = $result->fetch_assoc()) {
  $subtotal = $row['quantity'] * $row['price'];
  $total += $subtotal;
  echo "<div class='cart-card'>";
  echo "  <div class='cart-card-image'>";
  echo "    <img src='{$row['image']}' alt='{$row['name']}'>";
  echo "  </div>";
  echo "  <div class='cart-card-body'>";
  echo "    <h3 class='cart-card-title'>{$row['name']}</h3>";
  echo "    <p class='cart-card-price'>Rs" . number_format($row['price'], 2) . "</p>";
  echo "    <div class='cart-card-quantity'>";
  echo "      <form method='post' action='update_cart.php'>";
  echo "        <input type='hidden' name='product_id' value='{$row['id']}'>";
  echo "        <button type='submit' name='action' value='decrease'>-</button>";
  echo "        <span>{$row['quantity']}</span>";
  echo "        <button type='submit' name='action' value='increase'>+</button>";
  echo "      </form>";
  echo "    </div>";
  echo "    <p class='cart-card-subtotal'>Subtotal: Rs" . number_format($subtotal, 2) . "</p>";
  echo "    <a href='remove_from_cart.php?id={$row['id']}' class='cart-card-remove'>Remove</a>";
  echo "  </div>";
  echo "</div>";
}
if ($result->num_rows === 0) {
    echo "<p class='empty-cart-message'>Your cart is currently empty.</p>";
}
echo "<div class='cart-total'><h3>Total: Rs" . number_format($total, 2) . "</h3></div>";
?>
  <div class="cart-actions">
      <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
      <?php if ($total > 0): ?>
          <a href="checkout.php" class="btn">Proceed to Checkout</a> 
      <?php endif; ?>
  </div>
 </section>

 <?php include 'footer.php'; // Assuming you have a common footer ?>

</body>
</html>