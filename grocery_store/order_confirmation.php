<?php
session_start();
include 'db.php';

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get and validate order ID
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    die("Invalid order ID");
}

// Get order details with prepared statement
$order_query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $order_query);
mysqli_stmt_bind_param($stmt, "ii", $order_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$order_result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($order_result);
mysqli_stmt_close($stmt);

// Get order items
$items_query = "SELECT p.name, oi.quantity, oi.price FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = $order_id";
$items_result = mysqli_query($conn, $items_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation - FreshMart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>FreshMart</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section class="confirmation-container">
        <h2>Order Confirmation</h2>
        <div class="order-details">
            <h3>Thank you for your order!</h3>
            <p>Order #<?php echo $order_id; ?></p>
            <?php if ($order): ?>
                <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                <p>Total: Rs<?php echo number_format($order['total'], 2); ?></p>
            <?php else: ?>
                <div class="error-message">
                    <p>Order #<?php echo $order_id; ?> not found or you don't have permission to view it.</p>
                    <a href="orders.php" class="btn">View Your Orders</a>
                </div>
            <?php endif; ?>
            
            <h4>Order Items:</h4>
            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                <div class="order-item">
                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                    <span><?php echo $item['quantity']; ?> x Rs<?php echo number_format($item['price'], 2); ?></span>
                    <span>Rs<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
            <?php endwhile; ?>
        </div>
        <a href="index.php" class="btn">Continue Shopping</a>
    </section>
</body>
</html>