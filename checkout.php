<?php
session_start();

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    die("Error: User not logged in.");
}

$user_folder = "auth/users/" . $_SESSION['user'];
$cart_file = "$user_folder/cart.json";
$orders_file = "$user_folder/orders.json";
$counter_file = "auth/orders_counter.txt"; // Global order counter

// Ensure user folder exists
if (!is_dir($user_folder)) {
    die("Error: User folder does not exist.");
}

// Load cart items
$cart = file_exists($cart_file) ? json_decode(file_get_contents($cart_file), true) : [];

if (empty($cart)) {
    die("<script>alert('Error: Your cart is empty.'); window.location.href='cart.php';</script>");
}

// Ensure the counter file exists
if (!file_exists($counter_file)) {
    file_put_contents($counter_file, "1");
}

// Read and increment the global order number
$order_number = str_pad(file_get_contents($counter_file), 9, "0", STR_PAD_LEFT);
if (file_put_contents($counter_file, (int)$order_number + 1) === false) {
    die("<script>alert('Error: Unable to update order counter.'); window.location.href='cart.php';</script>");
}

// Prepare order details
$order = [
    "order_number" => $order_number,
    "status" => "Pending",
    "items" => $cart,
    "date" => date("Y-m-d H:i:s"),
    "shipped_date" => null
];

// Load existing orders
$orders = file_exists($orders_file) ? json_decode(file_get_contents($orders_file), true) : [];
$orders[] = $order;

// Save orders back to file
if (file_put_contents($orders_file, json_encode($orders, JSON_PRETTY_PRINT)) === false) {
    die("<script>alert('Error: Unable to save order.'); window.location.href='cart.php';</script>");
}

// Clear the cart after checkout
if (file_put_contents($cart_file, json_encode([])) === false) {
    die("<script>alert('Error: Unable to clear cart.'); window.location.href='cart.php';</script>");
}

// Show confirmation popup and redirect to profile (or orders page)
echo "<script>alert('Order placed successfully! Your order number is $order_number.'); window.location.href='profile.php?section=order-status';</script>";
?>
