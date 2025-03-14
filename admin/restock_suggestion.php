<?php
session_start();

// Ensure only admin can access
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

$users_folder = "../auth/users";
$product_sales = [];
$weekly_sales = [];
$current_time = time();

// Scan all user orders
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
                    $week_number = date("o-W", $order_time); // Year-Week format

                    // Track total sales
                    if (!isset($product_sales[$product_name])) {
                        $product_sales[$product_name] = 0;
                    }
                    $product_sales[$product_name] += $quantity;

                    // Track sales per week
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

// Sort products by most sold overall
arsort($product_sales);

// Compute restock suggestions based on weekly average
$restock_suggestions = [];
foreach ($product_sales as $product => $total_sold) {
    $weeks_count = count($weekly_sales[$product]);
    $average_weekly_sales = $weeks_count > 0 ? $total_sold / $weeks_count : 0;
    $suggested_restock = max(10, ceil($average_weekly_sales * 2)); // Stock for 2 weeks minimum

    $restock_suggestions[] = [
        "product" => $product,
        "sold" => $total_sold,
        "average_weekly_sales" => round($average_weekly_sales, 2),
        "suggested_restock" => $suggested_restock
    ];
}

// Get best-seller
$best_seller = !empty($restock_suggestions) ? $restock_suggestions[0] : null;

// Detect rising product by checking sales growth over weeks
$rising_product = null;
foreach ($weekly_sales as $product => $weeks) {
    $sorted_weeks = array_keys($weeks);
    rsort($sorted_weeks); // Sort weeks from latest to oldest

    if (count($sorted_weeks) < 2) continue; // Need at least 2 weeks of data

    $latest_week = $sorted_weeks[0];
    $previous_week = $sorted_weeks[1];

    $latest_sales = $weeks[$latest_week] ?? 0;
    $previous_sales = $weeks[$previous_week] ?? 0;

    if ($previous_sales > 0) {
        $growth_percentage = (($latest_sales - $previous_sales) / $previous_sales) * 100;

        // If sales increased by 40% or more, it's a rising product
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
    <link rel="stylesheet" href="../css/admin.css">
    <title>Restock Suggestions</title>
</head>
<body>
    <h1>AI Restock Suggestions</h1>

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
</body>
</html>
