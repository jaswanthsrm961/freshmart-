<?php include 'db.php'; session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Products</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <h1>FreshMart Products</h1>
  <div class="search-sort">
    <input type="text" id="search" placeholder="Search products..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    <select id="sort">
      <option value="default">Sort By</option>
      <option value="price_asc">Price: Low to High</option>
      <option value="price_desc">Price: High to Low</option>
      <option value="name_asc">Name: A to Z</option>
      <option value="name_desc">Name: Z to A</option>
    </select>
  </div>
  <nav>
    <a href="index.php">Home</a>
    <a href="cart.php">Cart</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>

<section class="products">
  <?php
  $category_id = $_GET['category'] ?? 0;
  $sort = $_GET['sort'] ?? 'default';
$order_by = '';
switch($sort) {
  case 'price_asc':
    $order_by = 'ORDER BY price ASC';
    break;
  case 'price_desc':
    $order_by = 'ORDER BY price DESC';
    break;
  case 'name_asc':
    $order_by = 'ORDER BY name ASC';
    break;
  case 'name_desc':
    $order_by = 'ORDER BY name DESC';
    break;
}
$search = $_GET['search'] ?? '';
$search_term = "%$search%";
$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND name LIKE ? $order_by");
  $stmt->bind_param("is", $category_id, $search_term);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()): ?>
    <div class="card">
      <img src="<?php echo $row['image']; ?>" width="100">
      <h3><?php echo $row['name']; ?></h3>
      <p>Price: Rs<?php echo $row['price']; ?></p>
      <?php 
        $cart_qty = 0;
        if(isset($_SESSION['user_id'])) {
          $qty_query = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
          $qty_query->bind_param("ii", $_SESSION['user_id'], $row['id']);
          $qty_query->execute();
          $qty_result = $qty_query->get_result();
          if($qty_result->num_rows > 0) {
            $cart_qty = $qty_result->fetch_assoc()['quantity'];
          }
        }
      ?>
      <div class="cart-controls">
        <?php if ($cart_qty > 0): ?>
          <div class="quantity-controls">
            <button onclick="decreaseQuantity(<?php echo $row['id']; ?>)">-</button>
            <span class="cart-quantity" id="cart-qty-<?php echo $row['id']; ?>"><?php echo $cart_qty; ?></span>
            <button onclick="addToCart(<?php echo $row['id']; ?>)">+</button>
          </div>
        <?php else: ?>
          <button onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>
</section>
<script>
  document.getElementById('sort').addEventListener('change', function() {
    const sortValue = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
  });

  document.getElementById('search').addEventListener('input', function() {
    const searchValue = this.value;
    const url = new URL(window.location.href);
    if (searchValue) {
      url.searchParams.set('search', searchValue);
    } else {
      url.searchParams.delete('search');
    }
    window.location.href = url.toString();
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    searchInput.focus();
    searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
  });
</script>
<script src="js/scripts.js"></script>
</body>
</html>
