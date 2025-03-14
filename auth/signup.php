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
    <link rel="stylesheet" href="css/style.css">
    <title>Palbites</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-bar">
            <div class="nav-image">
                <a href="index"><img src="images/logo.png" alt="Palbites logo" class="nav-logo"></a>
            </div>
            <!--
            <div class="nav-toggle" id="navToggle">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div> -->
            <div class="nav-links">
                <ul class="nav-ul">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overlay (very important for navigation) -->
    <div class="overlay" id="overlay"></div>

    <form action="signup.php" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="phone" placeholder="Phone Number" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="address" placeholder="Street Address" required>
    <input type="text" name="province" placeholder="Province" required>
    <input type="text" name="city" placeholder="City" required>
    <button type="submit">Sign Up</button>
    </form>

    <!-- Footer 
    <footer>
        <div class="footer">
            <div class="footer-logo">
                <img src="images/logo.png" alt="">
            </div>
            <div class="footer-content">
                <div class="footer-quote">
                    <h1>“Le bonheur commence avec une bonne pâtisserie.”</h1>
                    <h3>“Happiness begins with a good pastry.”</h3>
                </div>
                <div class="footer-contact">
                    <h1>+63 (997) 904 1929</h1>
                    <h2>contact@palbites.com</h2>
                </div>
                <div class="footer-social">
                    <a href=""><i class="fa-brands fa-facebook"></i></a>
                    <a href=""><i class="fa-brands fa-instagram"></i></a>
                    <a href=""><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-copyright">
                <h1>© 2025 Palbites. All rights reserved.</h1>
            </div>
        </div>
    </footer> -->

    <script src="js/nav.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>
