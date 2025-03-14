<?php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$users_folder = "../auth/users";
$pending_orders = [];
$sales_today = 0;
$daily_quota = 3000;
$product_sales = [];

if (is_dir($users_folder)) {
    foreach (scandir($users_folder) as $user) {
        if ($user === '.' || $user === '..') continue;

        $orders_file = "$users_folder/$user/orders.json";
        if (file_exists($orders_file)) {
            $user_orders = json_decode(file_get_contents($orders_file), true) ?? [];
            foreach ($user_orders as $order) {
                if ($order['status'] !== "Delivered") {
                    $order['customer'] = $user;
                    $pending_orders[] = $order;
                }
            }
        }

        $history_file = "$users_folder/$user/order_history.json";
        if (file_exists($history_file)) {
            $user_completed_orders = json_decode(file_get_contents($history_file), true) ?? [];
            foreach ($user_completed_orders as $order) {
                $order_total = 0;
                if (isset($order["items"])) {
                    foreach ($order["items"] as $item) {
                        $item_total = ($item["price"] ?? 0) * ($item["quantity"] ?? 0);
                        $order_total += $item_total;

                        $product_name = $item["name"];
                        $product_sales[$product_name] = ($product_sales[$product_name] ?? 0) + $item["quantity"];
                    }
                }

                if (date("Y-m-d") === date("Y-m-d", strtotime($order["shipped_date"]))) {
                    $sales_today += $order_total;
                }
            }
        }
    }
}

if (isset($_GET['bestsellers'])) {
    header('Content-Type: application/json');
    echo json_encode(["top_products" => $top_products]);
    exit;
}

arsort($product_sales);
$top_products = array_slice($product_sales, 0, 10, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <section class="container">
        <nav>
            <div class="nav">
                <div class="nav-img">
                    <img src="../images/logo.png" alt="">
                </div>
                <div class="nav-list">
                    <ul class="nav-ul">
                        <li><a href="dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i>Dashboard</a></li>
                        <li><a href="products.php"><i class="fa-solid fa-box-open"></i>Products</a></li>
                        <li><a href="orders.php"><i class="fa-solid fa-bag-shopping"></i>Orders</a></li>
                        <li><a href="restock_suggestion.php"><i class="fa-solid fa-arrow-trend-up"></i>Stock</a></li>
                        <li><a href="logout.php"><i class="fa-solid fa-user"></i>Logout</a></li>
                    </ul>   
                </div>
            </div>
        </nav>

        <div class="right-side">
            <h1 class="admin-h1">Welcome Admin</h1>

            <div class="quota">
                <h2>Daily Sales Quota</h2>
                <p>Today's Sales: ₱<?= number_format($sales_today, 2) ?></p>
                <p>Quota Target: ₱<?= number_format($daily_quota, 2) ?></p>
                <p>Status: <?= $sales_today >= $daily_quota ? "Quota Met! 🎉" : "Below Quota 😞" ?></p>
            </div>

            <div class="best-seller">
                <h2>Top 10 Best Sellers</h2>
                    <?php if (!empty($top_products)) : ?>
                        <table border="1">
                            <tr>
                                <th>Rank</th>
                                <th>Product Name</th>
                                <th>Quantity Sold</th>
                            </tr>
                            <?php 
                            $rank = 1;
                            foreach ($top_products as $product => $quantity) : ?>
                                <tr>
                                    <td><?= $rank ?></td>
                                    <td><?= htmlspecialchars($product) ?></td>
                                    <td><?= $quantity ?></td>
                                </tr>
                            <?php 
                            $rank++;
                            endforeach; ?>
                        </table>
                    <?php else : ?>
                        <p>No sales data available yet.</p>
                    <?php endif; ?>
            </div>

            <div class="orders">
                <h2>Pending Orders</h2>
                    <table border="1">
                        <tr>
                            <th>Order Number</th>
                            <th>View Order</th>
                        </tr>
                        <?php if (empty($pending_orders)) : ?>
                            <tr><td colspan="2">No pending orders.</td></tr>
                        <?php else : ?>
                            <?php foreach ($pending_orders as $order) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_number']) ?></td>
                                    <td>
                                        <a href="view_order.php?customer=<?= urlencode($order['customer']) ?>&order=<?= urlencode($order['order_number']) ?>">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
            </div>
        </div>
    </section>

    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>
