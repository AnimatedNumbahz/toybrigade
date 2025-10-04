<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customerID = $_SESSION['customerID'];
    $items = $_POST['items'];
    $total_amount = $_POST['total_amount'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // For each item, update product stock and create transaction record
        foreach ($items as $item) {
            // Update product stock - fixed query syntax
            $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE productID = ? AND stock >= ?");
            if (!$update_stock) {
                throw new Exception('Prepare failed: ' . $conn->error);
            }
            
            $update_stock->bind_param("iii", $item['quantity'], $item['productID'], $item['quantity']);
            $update_stock->execute();
            
            if ($update_stock->affected_rows === 0) {
                throw new Exception('Insufficient stock for one or more items');
            }
            
            // Create transaction record
            $insert_transaction = $conn->prepare("INSERT INTO transactions (customerID, productID, quantity, totalPrice, status) VALUES (?, ?, ?, ?, 'Pending')");
            if (!$insert_transaction) {
                throw new Exception('Prepare failed: ' . $conn->error);
            }
            
            $insert_transaction->bind_param("iiid", $customerID, $item['productID'], $item['quantity'], $item['subtotal']);
            $insert_transaction->execute();
            
            if ($insert_transaction->affected_rows === 0) {
                throw new Exception('Failed to create transaction record');
            }
        }
        
        // Clear cart - only remove the purchased items
        $productIDs = array_column($items, 'productID');
        $placeholders = implode(',', array_fill(0, count($productIDs), '?'));
        $clear_cart = $conn->prepare("DELETE FROM cart WHERE customerID = ? AND productID IN ($placeholders)");
        if (!$clear_cart) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        $types = str_repeat('i', count($productIDs) + 1);
        $params = array_merge([$customerID], $productIDs);
        $clear_cart->bind_param($types, ...$params);
        $clear_cart->execute();
        
        $conn->commit();
        
        // Redirect to success page
        header("Location: order_success.php");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
} else {
    header("Location: cart.php");
    exit();
}
?>