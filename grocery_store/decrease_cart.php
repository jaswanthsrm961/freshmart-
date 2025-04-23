<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['productId'] ?? null;

    if ($productId) {
        // Get current quantity
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $_SESSION['user_id'], $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $newQty = $row['quantity'] - 1;

            if ($newQty > 0) {
                // Update quantity
                $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $updateStmt->bind_param("iii", $newQty, $_SESSION['user_id'], $productId);
                $updateStmt->execute();
            } else {
                // Remove item from cart
                $deleteStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
                $deleteStmt->bind_param("ii", $_SESSION['user_id'], $productId);
                $deleteStmt->execute();
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not in cart']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}