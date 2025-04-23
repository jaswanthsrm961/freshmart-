<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Validate input
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

// Check if input is received and valid JSON
$json = file_get_contents("php://input");
if ($json === false) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Failed to read input data']));
}
if (trim($json) === '') {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Empty request body received']));
}

$data = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Invalid JSON format: ' . json_last_error_msg()]));
}

// Validate required fields
if (!$data || !isset($data['product_id']) || !isset($data['quantity'])) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Missing required fields (product_id and quantity)']));
}

// Validate field types
if (!is_numeric($data['product_id']) || !is_numeric($data['quantity'])) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'product_id and quantity must be numeric']));
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$data['product_id'];
$quantity = (int)$data['quantity'];

// Verify product exists
$product_check = $conn->prepare("SELECT id FROM products WHERE id = ?");
$product_check->bind_param("i", $product_id);
$product_check->execute();
$product_check->store_result();

if ($product_check->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Check if product already in cart
$cart_check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
$cart_check->bind_param("ii", $user_id, $product_id);
$cart_check->execute();
$cart_result = $cart_check->get_result();

if ($cart_result->num_rows > 0) {
    // Update existing item quantity
    $row = $cart_result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;
    
    $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    $update_stmt->execute();
    
    echo json_encode(['success' => true]);
    exit;
}


$query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) 
          ON DUPLICATE KEY UPDATE quantity = quantity + ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $user_id, $product_id, $quantity, $quantity);
$stmt->execute();

echo json_encode(['success' => true, 'message' => 'Added to cart!']);
?>