<?php
session_start();
include 'db.php';

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$cart_query = "SELECT p.id, p.name, c.quantity, p.price FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);

// Calculate total
$total = 0;
while ($item = mysqli_fetch_assoc($cart_result)) {
    $total += $item['price'] * $item['quantity'];
}

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create order
    $order_query = "INSERT INTO orders (user_id, total, status) 
                    VALUES ($user_id, $total, 'pending')";
    mysqli_query($conn, $order_query);
    $order_id = mysqli_insert_id($conn);

    // Add order items
    mysqli_data_seek($cart_result, 0); // Reset result pointer
    while ($item = mysqli_fetch_assoc($cart_result)) {
        $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                             VALUES ($order_id, {$item['id']}, {$item['quantity']}, {$item['price']})";
        mysqli_query($conn, $order_item_query);
    }

    // Record payment
    $payment_method = 'Credit Card';
    $payment_query = "INSERT INTO payments (order_id, amount, payment_method, status) 
                      VALUES ($order_id, $total, '$payment_method', 'completed')";
    mysqli_query($conn, $payment_query);

    // Clear cart
    $clear_cart_query = "DELETE FROM cart WHERE user_id = $user_id";
    mysqli_query($conn, $clear_cart_query);

    // Redirect to confirmation
    header("Location: order_confirmation.php?id=$order_id");
    exit;
}

// Display checkout page
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - FreshMart</title>
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

    <section class="checkout-container">
        <h2>Checkout</h2>
        <div class="order-summary">
            <h3>Order Summary</h3>
            <?php 
            mysqli_data_seek($cart_result, 0); // Reset result pointer
            while ($item = mysqli_fetch_assoc($cart_result)): 
            ?>
                <div class="order-item">
                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                    <span><?php echo $item['quantity']; ?> x Rs<?php echo number_format($item['price'], 2); ?></span>
                    <span>Rs<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
            <?php endwhile; ?>
            <div class="order-total">
                <strong>Total: Rs<?php echo number_format($total, 2); ?></strong>
            </div>
        </div>

        <form method="post" action="checkout.php" class="checkout-form">
            <h3>Payment Information</h3>
            <div class="form-group">
                <label for="card_name">Name on Card</label>
                <input type="text" id="card_name" name="card_name" required>
            </div>
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="expiry">Expiry Date</label>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" required>
                </div>
            </div>
            <button type="submit" class="btn">Complete Purchase</button>
        </form>
    </section>
</body>
</html>