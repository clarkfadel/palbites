<?php
session_start();

// Ensure only admin can access
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$users_folder = "../auth/users";
$pending_orders = [];
$completed_orders = [];

if (is_dir($users_folder)) {
    foreach (scandir($users_folder) as $user) {
        if ($user === '.' || $user === '..') continue;

        // Load active orders
        $orders_file = "$users_folder/$user/orders.json";
        if (file_exists($orders_file)) {
            $user_orders = json_decode(file_get_contents($orders_file), true) ?? [];
            foreach ($user_orders as $order) {
                $order['customer'] = $user; // Add customer name
                $pending_orders[] = $order; // Only pending orders are here
            }
        }

        // Load completed orders
        $history_file = "$users_folder/$user/order_history.json";
        if (file_exists($history_file)) {
            $user_completed_orders = json_decode(file_get_contents($history_file), true) ?? [];
            foreach ($user_completed_orders as $order) {
                $order['customer'] = $user; // Add customer name
                $completed_orders[] = $order;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Admin Orders</title>
</head>
<body>
    <h1>Admin Order Dashboard</h1>

    <h2>Pending Orders</h2>
    <table border="1">
        <tr>
            <th>Customer</th>
            <th>Order Number</th>
            <th>Order Date</th>
            <th>View Order</th>
        </tr>
        <?php if (empty($pending_orders)) : ?>
            <tr><td colspan="4">No pending orders.</td></tr>
        <?php else : ?>
            <?php foreach ($pending_orders as $order) : ?>
                <tr>
                    <td><?= htmlspecialchars($order['customer']) ?></td>
                    <td><?= htmlspecialchars($order['order_number']) ?></td>
                    <td><?= htmlspecialchars($order['date']) ?></td>
                    <td><a href="view_order.php?customer=<?= urlencode($order['customer']) ?>&order=<?= $order['order_number'] ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <h2>Completed Orders</h2>
    <table border="1">
        <tr>
            <th>Customer</th>
            <th>Order Number</th>
            <th>Shipped Date</th>
            <th>View Order</th>
        </tr>
        <?php if (empty($completed_orders)) : ?>
            <tr><td colspan="4">No completed orders.</td></tr>
        <?php else : ?>
            <?php foreach ($completed_orders as $order) : ?>
                <tr>
                    <td><?= htmlspecialchars($order['customer']) ?></td>
                    <td><?= htmlspecialchars($order['order_number']) ?></td>
                    <td><?= htmlspecialchars($order['shipped_date']) ?></td>
                    <td><a href="view_order.php?customer=<?= urlencode($order['customer']) ?>&order=<?= $order['order_number'] ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
