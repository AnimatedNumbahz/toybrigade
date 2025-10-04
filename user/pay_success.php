<?php
include "connection.php";
include "auth_session.php";

// Get order ID from URL parameter (if using Xendit's callback)
$order_id = $_GET['order_id'] ?? null;

// Check if we have order data in session
if (!isset($_SESSION['current_order'])) {
    header("Location: cart.php");
    exit();
}

$order_data = $_SESSION['current_order'];
$customerID = $_SESSION['customerID'];

// Create the order in database ONLY NOW (after successful payment)
$fullname = $order_data['customer_info']['firstName'] . ' ' . $order_data['customer_info']['lastName'];
$email = $order_data['customer_info']['email'];
$phone = $order_data['customer_info']['phone'];
$address = $order_data['customer_info']['address'];
$total = $order_data['total'];

$order_query = $conn->prepare("INSERT INTO Orders (customerID, fullname, email, phone, address, total, status) 
                               VALUES (?, ?, ?, ?, ?, ?, 'Paid')");
$order_query->bind_param("issssd", $customerID, $fullname, $email, $phone, $address, $total);

if ($order_query->execute()) {
    $orderID = $order_query->insert_id;

    // Insert order items
    foreach ($order_data['items'] as $item) {
        $productID = $item['productID'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $lineTotal = $quantity * $price;

        $item_query = $conn->prepare("INSERT INTO OrderItems (orderID, productID, quantity, price, lineTotal) 
                                      VALUES (?, ?, ?, ?, ?)");
        $item_query->bind_param("iiidd", $orderID, $productID, $quantity, $price, $lineTotal);
        $item_query->execute();
    }

    // Clear the cart for these items
    $productIDs = array_column($order_data['items'], 'productID');
    if (!empty($productIDs)) {
        $placeholders = implode(',', array_fill(0, count($productIDs), '?'));
        $delete_cart_query = $conn->prepare("DELETE FROM cart WHERE customerID = ? AND productID IN ($placeholders)");
        $params = array_merge([$customerID], $productIDs);
        $delete_cart_query->bind_param(str_repeat('i', count($params)), ...$params);
        $delete_cart_query->execute();
    }

    // Clear the session
    unset($_SESSION['current_order']);

    // Display success message
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Payment Successful</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-success">
                <h4>Payment Successful! 🎉</h4>
                <p>Thank you for your order. Your payment has been processed successfully.</p>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <p><strong>Order ID:</strong> #<?php echo $orderID; ?></p>
                    <p><strong>Total Paid:</strong> ₱<?php echo number_format($total, 2); ?></p>
                    <p><strong>Status:</strong> Paid</p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="shop.php" class="btn btn-pastel">Continue Shopping</a>
                <a href="orders.php" class="btn btn-outline-secondary">View My Orders</a>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Error creating order. Please contact support.";
}
?>