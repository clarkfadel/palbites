<?php
session_start();

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['order']) || empty($_GET['order'])) {
    echo "Error: No order specified.";
    exit;
}

$order_number = $_GET['order'];
$user_folder = "auth/users/" . $_SESSION['user'];
$orders_file = "$user_folder/orders.json";
$history_file = "$user_folder/order_history.json";

$orders = file_exists($orders_file) ? json_decode(file_get_contents($orders_file), true) : [];
$order_history = file_exists($history_file) ? json_decode(file_get_contents($history_file), true) : [];

$all_orders = array_merge($orders, $order_history);
$order = array_filter($all_orders, fn($o) => $o["order_number"] == $order_number);
$order = reset($order); 

if (!$order) {
    echo "Error: Order not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Order Details</h1>
    <p><strong>Order Number:</strong> <?= htmlspecialchars($order["order_number"]) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($order["status"]) ?></p>
    <p><strong>Order Date:</strong> <?= htmlspecialchars($order["date"]) ?></p>

    <?php if (!empty($order["shipped_date"])) : ?>
        <p><strong>Shipped Date:</strong> <?= htmlspecialchars($order["shipped_date"]) ?></p>
    <?php endif; ?>

    <h2>Items:</h2>
    <ul>
        <?php foreach ($order["items"] as $item) : ?>
            <li><?= htmlspecialchars($item["name"]) ?> - Quantity: <?= $item["quantity"] ?></li>
        <?php endforeach; ?>
    </ul>

    <a href="profile.php?section=order-status">Back</a>
</body>
</html>
