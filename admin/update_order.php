<?php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

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

foreach (scandir($users_folder) as $user_folder) {
    if ($user_folder === "." || $user_folder === "..") continue;

    $orders_file = "$users_folder/$user_folder/orders.json";
    
    if (file_exists($orders_file)) {
        $orders = json_decode(file_get_contents($orders_file), true);
        if (!$orders) continue; 

        foreach ($orders as $index => &$order) {
            if ((string)$order["order_number"] === (string)$order_number) {
                if ($action === "ship") {
                    $order["status"] = "Delivered";
                    $order["shipped_date"] = date("Y-m-d H:i:s");
                } elseif ($action === "cancel") {
                    $order["status"] = "Cancelled"; 
                }
                
                if ($order["status"] === "Delivered") {
                    $completed_orders = json_decode(file_get_contents("$users_folder/$user_folder/order_history.json"), true) ?? [];
                    $completed_orders[] = $order;

                    unset($orders[$index]);

                    file_put_contents("$users_folder/$user_folder/order_history.json", json_encode($completed_orders, JSON_PRETTY_PRINT));
                }

                $found = true;
                break;
            }
        }

        if ($found) {
            file_put_contents($orders_file, json_encode(array_values($orders), JSON_PRETTY_PRINT)); 
        }
    }
}

if ($found) {
    echo "Order updated successfully.";
} else {
    die("Error: Order not found. Order Number: " . htmlspecialchars($order_number));
}
?>
