<?php
session_start();

// Restrict access to logged-in admins only
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

// Validate input
if (!isset($_POST["order_number"]) || !isset($_POST["action"])) {
    die("Error: Missing required data.");
}

$order_number = $_POST["order_number"];
$action = $_POST["action"];
$users_folder = "../auth/users";

if (!is_dir($users_folder)) {
    die("Error: Users folder not found.");
}

$found = false;

// Scan through each user folder to find the order
foreach (scandir($users_folder) as $user_folder) {
    if ($user_folder === "." || $user_folder === "..") continue;

    $orders_file = "$users_folder/$user_folder/orders.json";
    
    if (file_exists($orders_file)) {
        $orders = json_decode(file_get_contents($orders_file), true);
        if (!$orders) continue; // Skip corrupted or empty files

        // Loop through orders to find the matching order number
        foreach ($orders as $index => &$order) {
            if ((string)$order["order_number"] === (string)$order_number) {
                if ($action === "ship") {
                    $order["status"] = "Delivered"; // Change status to Delivered
                    $order["shipped_date"] = date("Y-m-d H:i:s"); // Set shipped date
                } elseif ($action === "cancel") {
                    $order["status"] = "Cancelled"; // Mark as canceled
                }
                
                // Move to order history if delivered
                if ($order["status"] === "Delivered") {
                    $completed_orders = json_decode(file_get_contents("$users_folder/$user_folder/order_history.json"), true) ?? [];
                    $completed_orders[] = $order;

                    // Remove from active orders
                    unset($orders[$index]);

                    // Save order history
                    file_put_contents("$users_folder/$user_folder/order_history.json", json_encode($completed_orders, JSON_PRETTY_PRINT));
                }

                $found = true;
                break;
            }
        }

        // Save the updated orders if found
        if ($found) {
            file_put_contents($orders_file, json_encode(array_values($orders), JSON_PRETTY_PRINT)); // Reindex orders
        }
    }
}

if ($found) {
    echo "Order updated successfully.";
} else {
    die("Error: Order not found. Order Number: " . htmlspecialchars($order_number));
}
?>
