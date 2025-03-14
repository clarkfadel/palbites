<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $province = $_POST['province'];
    $city = $_POST['city'];

    $userDir = "users/" . $username;

    if (!file_exists($userDir)) {
        mkdir($userDir, 0777, true);
        file_put_contents("$userDir/user.json", json_encode([
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'address' => $address,
            'province' => $province,
            'city' => $city
        ]));

        // Initialize empty cart, order status, and history
        file_put_contents("$userDir/cart.json", json_encode([]));
        file_put_contents("$userDir/order_status.json", json_encode([]));
        file_put_contents("$userDir/order_history.json", json_encode([]));

        $_SESSION['user'] = $username;
        header("Location: ../profile.php");
        exit;
    } else {
        echo "Username already exists!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/signup.css">
    <title>Palbites</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-bar">
            <div class="nav-image">
                <a href="index"><img src="../images/logo.png" alt="Palbites logo" class="nav-logo"></a>
            </div>
            <div class="nav-links">
                <ul class="nav-ul">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sign-up">
        <h1>Sign Up</h1>
        <form action="signup.php" method="POST">
            <div class="d1">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="d2">
                <input type="tel" name="phone" placeholder="Phone Number" required>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="d3">
                <input type="text" name="address" placeholder="Street Address" required>
            </div>
            <div class="d4">
                <input type="text" name="province" placeholder="Province" required>
                <input type="text" name="city" placeholder="City" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <script src="../js/nav.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>
