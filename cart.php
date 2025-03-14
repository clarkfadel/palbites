<?php
session_start();

if (!isset($_SESSION['user'])) {
    die("User not logged in!");
}

$userFolder = "auth/users/" . $_SESSION['user'];
$cartFile = "$userFolder/cart.json";

$cart = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];

$subtotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
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

    <section>
        <div class="cart">
            <h1 class="cart-h1">Cart</h1>
            <div class="cart-header">
                <h1>Product</h1>
                <h1>Price</h1>
                <h1>Quantity</h1>
                <h1>Total</h1>
                <h1>Action</h1>
            </div>

            <div class="cart-container" id="cartContainer">
                <?php if (empty($cart)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($cart as $index => $item): 
                        $itemTotal = $item['price'] * $item['quantity'];
                        $subtotal += $itemTotal;
                    ?>
                    <div class="cart-content">
                        <div class="cart-img">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="cart-description">
                                <h2><?= htmlspecialchars($item['name']) ?></h2>
                            </div>
                        </div>
                        <div class="cart-price">
                            <h4>₱<?= number_format($item['price'], 2) ?></h4>
                        </div>
                        <div class="cart-quantity">
                            <button onclick="updateQuantity(<?= $index ?>, -1)">-</button>
                            <input type="text" value="<?= $item['quantity'] ?>" readonly>
                            <button onclick="updateQuantity(<?= $index ?>, 1)">+</button>
                        </div>
                        <div class="cart-total">
                            <h4>₱<?= number_format($itemTotal, 2) ?></h4>
                        </div>
                        <div>
                            <button class="delete-button" onclick="deleteItem(<?= $index ?>)">Remove</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="cart-bottom">
            <div class="cart-left-side">
                <div class="cart-subtotal">
                    <h1>Subtotal</h1>
                    <h2 id="subtotal">₱<?= number_format($subtotal, 2) ?></h2>
                </div>
                <div class="cart-checkout">
                    <a href="products.php">Shop more</a>
                    <button onclick="window.location.href='checkout.php'" id="checkoutButton">Checkout</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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


    <script>
        document.getElementById("checkoutButton").addEventListener("click", function() {
            fetch("checkout.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Order placed successfully! Order Number: " + data.order_number);
                    window.location.href = "profile.php?section=order-status";
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Checkout failed:", error));
        });
    </script>

    <script src="js/nav.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/chatbot.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>