<?php
require_once(__DIR__ . '/xendit/vendor/autoload.php');
include "connection.php";
include "auth_session.php";

use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

// Check if order data exists in session
if (!isset($_SESSION['current_order'])) {
    header("Location: cart.php");
    exit();
}

$order = $_SESSION['current_order'];
$orderID = $order['order_id'];

// Configure API key authorization
Configuration::setXenditKey('xnd_development_VdgDOp81RzYJiIIuQyUmRoVgL2A84eiGDiFRs8Te0kaenbTxw87i5r6D7o2zQVU');

// Create API instance
$apiInstance = new InvoiceApi();

try {
    // Prepare items for Xendit
    $items = [];
    foreach ($order['items'] as $item) {
        $items[] = [
            'name' => $item['productName'],
            'quantity' => (int)$item['quantity'],
            'price' => (float)$item['price'],
            'category' => 'Toys'
        ];
    }

    // Create invoice parameters with order ID in success URL
    $params = [
        'external_id' => 'toybrigade_' . $_SESSION['customerID'] . '_' . time(),
        'amount' => $order['total'],
        'description' => 'Toy Brigade Order - ' . count($items) . ' item(s)',
        'currency' => 'PHP',
        'items' => $items,
        'customer' => [
            'given_names' => $order['customer_info']['firstName'] . ' ' . $order['customer_info']['lastName'],
            'email' => $order['customer_info']['email'],
            'mobile_number' => $order['customer_info']['phone']
        ],
        'customer_notification_preference' => [
            'invoice_created' => ['email'],
            'invoice_reminder' => ['email'],
            'invoice_paid' => ['email'],
            'invoice_expired' => ['email']
        ],
        'success_redirect_url' => 'http://localhost/toybrigade/user/pay_success.php',
        'failure_redirect_url' => 'http://localhost/toybrigade/user/pay_fail.php',
        'fees' => [
            [
                'type' => 'Delivery',
                'value' => $order['shipping_cost']
            ]
        ]
    ];

    // Add shipping address if provided
    if (!empty($order['customer_info']['address'])) {
        $params['customer']['address'] = $order['customer_info']['address'];
    }

    // Create invoice
    $result = $apiInstance->createInvoice($params);
    
    // Get the checkout URL
    $checkoutUrl = $result['invoice_url'];
    
    // Redirect to Xendit Checkout page
    header('Location: ' . $checkoutUrl);
    exit();

} catch (\Xendit\XenditSdkException $e) {
    echo 'Exception when creating invoice: ' . $e->getMessage() . "\n";
    echo '<br><a href="checkout.php" class="btn btn-pastel">Back to Checkout</a>';
}
?>