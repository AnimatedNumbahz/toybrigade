<?php
include "auth_session.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4>Payment Failed</h4>
            <p>There was an issue processing your payment. Please try again.</p>
            <p class="mb-0"><small>Your cart items have been preserved. You can try the payment again.</small></p>
        </div>
        
        <div class="mt-3">
            <a href="checkout.php" class="btn btn-pastel">Back to Checkout</a>
            <a href="cart.php" class="btn btn-outline-secondary">Back to Cart</a>
        </div>
    </div>
</body>
</html>