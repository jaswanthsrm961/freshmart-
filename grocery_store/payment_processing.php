<?php
session_start();
include 'db.php';

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Process payment (simulated)
// In a real application, you would integrate with a payment gateway here

// Get the latest order ID for this user
$user_id = $_SESSION['user_id'];
$order_query = "SELECT id FROM orders WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);
$order_id = $order['id'] ?? 0;

if ($order_id <= 0) {
    // Log the error for debugging
    error_log("Failed to retrieve order ID for user: " . $user_id);
    
    // Check if there's an order in the database
    $check_order = "SELECT id FROM orders WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
    $check_result = mysqli_query($conn, $check_order);
    
    if (mysqli_num_rows($check_result) > 0) {
        $order_data = mysqli_fetch_assoc($check_result);
        $order_id = $order_data['id'];
    } else {
        die(json_encode(['success' => false, 'message' => 'No order found for this user']));
    }
}

// Redirect to order confirmation
header("Location: order_confirmation.php?id=$order_id");
exit;
?>
        return false; // Invalid card number
    }
    
    // Validate expiry date format (MM/YY)
    if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $expiry_date, $matches)) {
        return false; // Invalid format
    }
    
    $current_year = date('y');
    $current_month = date('m');
    $expiry_month = $matches[1];
    $expiry_year = $matches[2];
    
    if ($expiry_year < $current_year || 
        ($expiry_year == $current_year && $expiry_month < $current_month)) {
        return false; // Card expired
    }
    
    // Validate CVV (3 or 4 digits)
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        return false; // Invalid CVV
    }
    
    return true; // All validations passed
}

function get_card_type($card_number) {
    $card_number = preg_replace('/\D/', '', $card_number);
    
    if (preg_match('/^4\d{12}(\d{3})?$/', $card_number)) {
        return 'Visa';
    } elseif (preg_match('/^5[1-5]\d{14}$/', $card_number)) {
        return 'MasterCard';
    } elseif (preg_match('/^3[47]\d{13}$/', $card_number)) {
        return 'American Express';
    } elseif (preg_match('/^3(0[0-5]|[68]\d)\d{11}$/', $card_number)) {
        return 'Diners Club';
    } elseif (preg_match('/^6(011|5\d{2})\d{12}$/', $card_number)) {
        return 'Discover';
    } elseif (preg_match('/^(2131|1800|35\d{3})\d{11}$/', $card_number)) {
        return 'JCB';
    } else {
        return 'Unknown';
    }
}
?>