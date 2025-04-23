<?php include 'db.php'; session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>FreshMart - Your Local Grocery Store</title>
  <link rel="stylesheet" href="css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="js/scripts.js"></script> 
</head>
<body>
  <header>
    <h1>Welcome to FreshMart</h1>
    <nav>
      <a href="index.php" class="active">Home</a>
      <a href="cart.php">Cart</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
      <?php endif; ?>
    </nav>
  </header>

  <div class="hero-banner">
    <h2>Fresh & Healthy Groceries</h2>
    <p>Delivered right to your doorstep</p>
  </div>

  <section class="categories">
    <h2>Shop by Category</h2>
    <div class="category-cards">
      <?php
      $category_query = "SELECT * FROM categories ORDER BY name";
      $category_result = mysqli_query($conn, $category_query);
      while($category = mysqli_fetch_assoc($category_result)) {
        echo "<a href='product_list.php?category={$category['id']}' class='category-card'>";
        echo "<img src='images/{$category['image']}' alt='{$category['name']}' class='category-icon'>";
        echo "<h3>{$category['name']}</h3>";
        echo "<p>{$category['description']}</p>";
        echo "</a>";
      }
      ?>
    </div>
  </section>

  <section class="featured-products">
    <h2>Featured Products</h2>
    <div class="product-grid">
      <?php
        $featured_query = "SELECT * FROM products WHERE featured = 1 LIMIT 4";
        $featured_result = mysqli_query($conn, $featured_query);
        while($product = mysqli_fetch_assoc($featured_result)) {
          echo "<div class='product-card'>";
          echo "<img src='{$product['image']}' alt='{$product['name']}'>";
          echo "<h3>{$product['name']}</h3>";
          echo "<p class='price'>Rs".number_format($product['price'], 2)."</p>";
          echo "<button onclick='addToCart({$product['id']})' class='btn'>Add to Cart</button>";
          echo "</div>";
        }
      ?>
    </div>
  </section>

  <footer>
    <div class="footer-content">
      <div class="footer-section">
        <h3>About FreshMart</h3>
        <p>Your local source for fresh groceries and quality products.</p>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <a href="index.php">Home</a>
        <a href="cart.php">Cart</a>
        <a href="login.php">Login</a>
      </div>
      <div class="footer-section">
        <h3>Contact Us</h3>
        <p>Email: jaswanthseedrala123@gmail.com</p>
        <p>Phone: 9866814322</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2023 FreshMart. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>