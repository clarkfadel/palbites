<?php
session_start();

// Load sales data from order history
$users_folder = "auth/users";
$product_sales = [];

if (is_dir($users_folder)) {
    foreach (scandir($users_folder) as $user) {
        if ($user === '.' || $user === '..') continue;

        $history_file = "$users_folder/$user/order_history.json";
        if (file_exists($history_file)) {
            $user_orders = json_decode(file_get_contents($history_file), true) ?? [];
            foreach ($user_orders as $order) {
                foreach ($order['items'] as $item) {
                    $product_name = $item['name'];
                    $product_image = $item['image']; // Full image URL

                    if (!isset($product_sales[$product_name])) {
                        $product_sales[$product_name] = [
                            'name' => $product_name,  // Ensure correct name association
                            'quantity' => 0,
                            'image' => $product_image
                        ];
                    }

                    $product_sales[$product_name]['quantity'] += $item['quantity'];
                }
            }
        }
    }
}

// Sort products by sales volume (top 3)
usort($product_sales, fn($a, $b) => $b['quantity'] - $a['quantity']);
$best_sellers = array_slice($product_sales, 0, 3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Palbites</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-bar">
            <div class="nav-image">
                <a href="index"><img src="images/logo.png" alt="Palbites logo" class="nav-logo"></a>
            </div>
            <div class="nav-toggle" id="navToggle">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <div class="nav-links">
                <ul class="nav-ul">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="cart.php" class="cart-icon"><i class="fa-solid fa-bag-shopping"></i><span class="cart-counter">0</span></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overlay (very important for navigation) -->
    <div class="overlay" id="overlay"></div>

    <!-- Header -->
    <header class="header">
        <div class="header-h1">
            <h1>Bite.</h1>
            <h1>Taste.</h1>
            <h1>Enjoy.</h1>
        </div>
        <div class="header-img">
            <img src="images/header-bg.png" alt="">
        </div>
    </header>

    <!-- Best Seller -->
    <section>
        <div class="best-seller">
            <h1 class="best-h1">Our Best Sellers</h1>
            <div class="best-container">
                <?php if (!empty($best_sellers)) : ?>
                    <?php foreach ($best_sellers as $product) : ?>
                        <a href="products.php" class="best-content">
                            <div>
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <h1><?= htmlspecialchars($product['name']) ?></h1> <!-- Now correctly matches the image -->
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No best sellers yet.</p>
                <?php endif; ?>
            </div>
            <div class="best-link-container">
                <a href="products.php" class="best-link">View More</a>
            </div>
        </div>
    </section>

    <!-- Order Ahead -->
    <section>
        <div class="order-ahead">
            <div class="order-content">
                <h2>ORDER AHEAD</h2>
                <h1>Order Pickup or Delivery</h1>
                <p>Indulge in warm, freshly baked treats without the wait. Place your order online and choose how you enjoy it—grab it fresh in-store or have it delivered straight to your doorstep!</p>
                <a href="">Order Now</a>
            </div>
        </div>
    </section>

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
    </footer>

    <!-- Floating Chatbot -->
    <section>
        <div id="chatbot-icon" onclick="toggleChatbot()">
            <i class="fa-solid fa-comment"></i>
        </div>
        <div id="chatbot-container" class="hidden">
            <div class="chatbot-header">
                <h3>PalBot Chat</h3>
                <button onclick="toggleChatbot()">✖</button>
            </div>
            <div id="chatbot-messages"></div>

            <div class="chatbot-questions">
                <button onclick="sendPredefinedMessage('What are your best sellers?')">Best Sellers</button>
                <button onclick="sendPredefinedMessage('What are your store hours?')">Store Hours</button>
                <button onclick="sendPredefinedMessage('Where is your location?')">Location</button>
                <button onclick="sendPredefinedMessage('How can I track my order?')">Order Tracking</button>
                <button onclick="sendPredefinedMessage('What is Palbites?')">What is Palbites?</button>
                <button onclick="sendPredefinedMessage('What is your story?')">Our Story</button>
                <button onclick="sendPredefinedMessage('What do you offer?')">What We Offer</button>
            </div>

            <div class="chatbot-input">
                <input type="text" id="chatbot-input" placeholder="Type a message..." onkeypress="handleKeyPress(event)">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </section>



    <script src="js/nav.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/chatbot.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>