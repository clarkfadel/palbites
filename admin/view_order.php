<?php
session_start();

// Ensure only admin can access
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

// Check if customer and order are provided
if (!isset($_GET['customer']) || !isset($_GET['order'])) {
    die("Error: Missing order number or customer.");
}

$customer = $_GET['customer'];
$order_number = $_GET['order'];
$orders_file = "../auth/users/$customer/orders.json";
$order_history_file = "../auth/users/$customer/order_history.json";

// Load orders
$orders = file_exists($orders_file) ? json_decode(file_get_contents($orders_file), true) : [];
$order_history = file_exists($order_history_file) ? json_decode(file_get_contents($order_history_file), true) : [];
$all_orders = array_merge($orders, $order_history);

// Find the order
$order = null;
foreach ($all_orders as $o) {
    if ($o['order_number'] == $order_number) {
        $order = $o;
        break;
    }
}

// If order is not found
if (!$order) {
    die("Error: Order not found.");
}

// Calculate overall total
$overall_total = 0;
foreach ($order['items'] as $item) {
    $overall_total += $item['quantity'] * $item['price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>View Order</title>
</head>
<body>
    <h1>Order Details</h1>
    <p><strong>Customer:</strong> <?= htmlspecialchars($customer) ?></p>
    <p><strong>Order Number:</strong> <?= htmlspecialchars($order['order_number']) ?></p>
    <p><strong>Order Date:</strong> <?= htmlspecialchars($order['date']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

    <h2>Items</h2>
    <ul>
        <?php foreach ($order['items'] as $item) : ?>
            <li><?= htmlspecialchars($item['name']) ?> - <?= htmlspecialchars($item['quantity']) ?> x $<?= htmlspecialchars($item['price']) ?></li>
        <?php endforeach; ?>
    </ul>

    <h3><strong>Overall Total:</strong> $<?= number_format($overall_total, 2) ?></h3>

    <?php if ($order['status'] !== "Delivered") : ?>
        <form action="update_order.php" method="POST">
            <input type="hidden" name="customer" value="<?= htmlspecialchars($customer) ?>">
            <input type="hidden" name="order_number" value="<?= htmlspecialchars($order['order_number']) ?>">
            <button type="submit" name="action" value="ship">Ship Order</button>
            <button type="submit" name="action" value="cancel">Cancel Order</button>
        </form>
    <?php endif; ?>

    <a href="orders.php">Back to Orders</a>
</body>
</html>
