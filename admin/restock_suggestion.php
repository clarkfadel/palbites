<?php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$users_folder = "../auth/users";
$product_sales = [];
$weekly_sales = [];
$current_time = time();

if (is_dir($users_folder)) {
    foreach (scandir($users_folder) as $user) {
        if ($user === '.' || $user === '..') continue;

        $orders_file = "$users_folder/$user/orders.json";
        if (file_exists($orders_file)) {
            $user_orders = json_decode(file_get_contents($orders_file), true) ?? [];

            foreach ($user_orders as $order) {
                foreach ($order["items"] as $item) {
                    $product_name = $item["name"];
                    $quantity = $item["quantity"];
                    $order_time = strtotime($order["date"]);
                    $week_number = date("o-W", $order_time); 

                    if (!isset($product_sales[$product_name])) {
                        $product_sales[$product_name] = 0;
                    }
                    $product_sales[$product_name] += $quantity;

                    if (!isset($weekly_sales[$product_name])) {
                        $weekly_sales[$product_name] = [];
                    }
                    if (!isset($weekly_sales[$product_name][$week_number])) {
                        $weekly_sales[$product_name][$week_number] = 0;
                    }
                    $weekly_sales[$product_name][$week_number] += $quantity;
                }
            }
        }
    }
}

arsort($product_sales);

$restock_suggestions = [];
foreach ($product_sales as $product => $total_sold) {
    $weeks_count = count($weekly_sales[$product]);
    $average_weekly_sales = $weeks_count > 0 ? $total_sold / $weeks_count : 0;
    $suggested_restock = max(10, ceil($average_weekly_sales * 2)); 

    $restock_suggestions[] = [
        "product" => $product,
        "sold" => $total_sold,
        "average_weekly_sales" => round($average_weekly_sales, 2),
        "suggested_restock" => $suggested_restock
    ];
}

$best_seller = !empty($restock_suggestions) ? $restock_suggestions[0] : null;

$rising_product = null;
foreach ($weekly_sales as $product => $weeks) {
    $sorted_weeks = array_keys($weeks);
    rsort($sorted_weeks); 

    if (count($sorted_weeks) < 2) continue; 

    $latest_week = $sorted_weeks[0];
    $previous_week = $sorted_weeks[1];

    $latest_sales = $weeks[$latest_week] ?? 0;
    $previous_sales = $weeks[$previous_week] ?? 0;

    if ($previous_sales > 0) {
        $growth_percentage = (($latest_sales - $previous_sales) / $previous_sales) * 100;

        if ($growth_percentage >= 40) {
            $rising_product = [
                "product" => $product,
                "latest_sales" => $latest_sales,
                "previous_sales" => $previous_sales,
                "growth_percentage" => round($growth_percentage, 2)
            ];
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Restock Suggestions</title>
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
                        <li><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a></li>
                        <li><a href="products.php"><i class="fa-solid fa-box-open"></i>Products</a></li>
                        <li><a href="orders.php"><i class="fa-solid fa-bag-shopping"></i>Orders</a></li>
                        <li><a href="restock_suggestion.php" class="active"><i class="fa-solid fa-arrow-trend-up"></i>Stock</a></li>
                        <li><a href="logout.php"><i class="fa-solid fa-user"></i>Logout</a></li>
                    </ul>   
                </div>
            </div>
        </nav>

        <div>
            <h1>AI Restock Suggestions</h1>
            <div>
                <h2>Most Bought Products & Restock Suggestions</h2>
                <table border="1">
                    <tr>
                        <th>Product</th>
                        <th>Sold Quantity</th>
                        <th>Avg. Weekly Sales</th>
                        <th>Suggested Restock</th>
                    </tr>
                    <?php if (empty($restock_suggestions)) : ?>
                        <tr><td colspan="4">No sales data available.</td></tr>
                    <?php else : ?>
                        <?php foreach ($restock_suggestions as $suggestion) : ?>
                            <tr>
                                <td><?= htmlspecialchars($suggestion["product"]) ?></td>
                                <td><?= htmlspecialchars($suggestion["sold"]) ?></td>
                                <td><?= htmlspecialchars($suggestion["average_weekly_sales"]) ?></td>
                                <td><?= htmlspecialchars($suggestion["suggested_restock"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div>
            <?php if ($best_seller) : ?>
                <h2>Best-Selling Product</h2>
                <p><strong><?= htmlspecialchars($best_seller["product"]) ?></strong> with <strong><?= htmlspecialchars($best_seller["sold"]) ?></strong> total sales.</p>
            <?php else : ?>
                <p>No best-seller data available.</p>
            <?php endif; ?>

            <?php if ($rising_product) : ?>
                <h2>Possible Rising Product</h2>
                <p><strong><?= htmlspecialchars($rising_product["product"]) ?></strong> saw an increase of <strong><?= htmlspecialchars($rising_product["growth_percentage"]) ?>%</strong> in the last week (from <strong><?= htmlspecialchars($rising_product["previous_sales"]) ?></strong> to <strong><?= htmlspecialchars($rising_product["latest_sales"]) ?></strong> sales).</p>
            <?php else : ?>
                <p>No rising product detected.</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>
