<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$userFolder = "auth/users/" . $_SESSION['user'];
$cartFile = "$userFolder/cart.json";

if (!file_exists($userFolder)) {
    mkdir($userFolder, 0777, true);
}

if (!file_exists($cartFile)) {
    file_put_contents($cartFile, json_encode([]));
}

$cart = json_decode(file_get_contents($cartFile), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'getCart') {
    echo json_encode($cart);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'add') {
    $input = json_decode(file_get_contents("php://input"), true);
    
    foreach ($cart as $item) {
        if ($item['name'] === $input['name']) {
            echo json_encode(["message" => "This item is already in your cart!"]);
            exit;
        }
    }

    $input['quantity'] = 1;
    $cart[] = $input;
    file_put_contents($cartFile, json_encode($cart));

    echo json_encode(["message" => "{$input['name']} has been added to your cart!"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'updateQuantity') {
    $input = json_decode(file_get_contents("php://input"), true);
    $index = $input['index'];
    $change = $input['change'];

    if (isset($cart[$index])) {
        $cart[$index]['quantity'] += $change;
        if ($cart[$index]['quantity'] <= 0) {
            array_splice($cart, $index, 1);
        }
        file_put_contents($cartFile, json_encode($cart));
    }

    echo json_encode(["message" => "Cart updated"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'delete') {
    $input = json_decode(file_get_contents("php://input"), true);
    $index = $input['index'];

    if (isset($cart[$index])) {
        array_splice($cart, $index, 1);
        file_put_contents($cartFile, json_encode($cart));
    }

    echo json_encode(["message" => "Item removed from cart"]);
    exit;
}
?>
