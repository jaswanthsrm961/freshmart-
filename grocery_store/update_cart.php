<?php
session_start();
include 'db.php';

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'] ?? 0;
    
    // Get current quantity
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_quantity = $row['quantity'] ?? 0;
    $stmt->close();
    
    // Update quantity based on action
    if ($_POST['action'] === 'increase') {
        $new_quantity = $current_quantity + 1;
    } elseif ($_POST['action'] === 'decrease' && $current_quantity > 1) {
        $new_quantity = $current_quantity - 1;
    } else {
        // If decreasing below 1, remove item instead
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
        header("Location: cart.php");
        exit;
    }
    
    // Update quantity in database
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: cart.php");
exit;
?>