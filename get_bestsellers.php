<?php
session_start();
$username = $_SESSION['username'] ?? null;

if (!$username) {
    die(json_encode(["error" => "User not logged in"]));
}

$order_history_path = __DIR__ . "auth/users/{$username}/order_history.json";

if (!file_exists($order_history_path)) {
    die(json_encode(["error" => "Order history not found for user: $username"]));
}

$order_history = json_decode(file_get_contents($order_history_path), true);

if (!$order_history) {
    die(json_encode(["error" => "Failed to decode order history"]));
}

$best_sellers = [];
foreach ($order_history as $order) {
    foreach ($order['items'] as $item) {
        $item_name = $item['name'];
        if (!isset($best_sellers[$item_name])) {
            $best_sellers[$item_name] = [
                "name" => $item_name,
                "price" => $item['price'],
                "image" => $item['image'],
                "quantity" => 0
            ];
        }
        $best_sellers[$item_name]['quantity'] += $item['quantity'];
    }
}

usort($best_sellers, function ($a, $b) {
    return $b['quantity'] - $a['quantity'];
});

echo json_encode(array_values($best_sellers));
?>
