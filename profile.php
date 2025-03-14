<?php
session_start();

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$user_folder = "auth/users/" . $_SESSION['user'];
$user_file = "$user_folder/user.json";
$orders_file = "$user_folder/orders.json";
$history_file = "$user_folder/order_history.json";

// Load user data
$user = file_exists($user_file) ? json_decode(file_get_contents($user_file), true) : null;
if (!$user) {
    echo "Error: User data not found.";
    exit;
}

// Load active orders
$orders = file_exists($orders_file) ? json_decode(file_get_contents($orders_file), true) : [];

// Load order history
$order_history = file_exists($history_file) ? json_decode(file_get_contents($history_file), true) : [];

// Ensure both are arrays
$orders = is_array($orders) ? $orders : [];
$order_history = is_array($order_history) ? $order_history : [];

// Separate order status and completed orders
$order_status = array_filter($orders, fn($order) => isset($order["status"]) && $order["status"] !== "Delivered");

// Determine active section
$activeSection = $_GET['section'] ?? 'profile';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
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

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <section>
        <div class="profile">
            <h1 class="profile-h1">Welcome <?= htmlspecialchars($user['username']) ?>!</h1>
            <div class="profile-options">
                <h2 class="<?= $activeSection === 'profile' ? 'active' : '' ?>" data-section="profile">Profile Settings</h2>
                <h2 class="<?= $activeSection === 'order-status' ? 'active' : '' ?>" data-section="order-status">Order Status</h2>
                <h2 class="<?= $activeSection === 'order-history' ? 'active' : '' ?>" data-section="order-history">Order History</h2>
            </div>

            <!-- Profile Settings -->
            <div class="profile-form section-content" id="profile" style="display: <?= $activeSection === 'profile' ? 'block' : 'none' ?>;">
                <form action="auth/update_profile.php" method="POST" class="profile-form-content">
                    <div class="profile-top">
                        <div class="profile-top-content p1">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['username']) ?>" readonly required>
                        </div>
                        <div class="profile-top-content p2">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="profile-top-content p3">
                            <label for="contact">Contact Number:</label>
                            <input type="tel" id="contact" name="contact" value="<?= htmlspecialchars($user['phone']) ?>" required>
                        </div>
                        <div class="profile-top-content p4">
                            <label for="password">New Password:</label>
                            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                    </div>

                    <div class="profile-bottom">
                        <div class="profile-bottom-content">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>
                        </div>
                        <div class="profile-bottom-content">
                            <label for="province">Province:</label>
                            <input type="text" id="province" name="province" value="<?= htmlspecialchars($user['province']) ?>">
                        </div>
                        <div class="profile-bottom-content">
                            <label for="city">City:</label>
                            <input type="text" id="city" name="city" value="<?= htmlspecialchars($user['city']) ?>">
                        </div>
                    </div>

                    <div class="profile-buttons">   
                        <button type="submit">Save Changes</button>
                        <a href="auth/logout.php">Logout</a>
                    </div>
                </form>
            </div>

            <!-- Order Status -->
            <div class="order-status section-content" id="order-status" style="display: <?= $activeSection === 'order-status' ? 'block' : 'none' ?>;">
                <div class="order-status-top">
                    <h1>Order Number</h1>
                    <h1>Order Status</h1>
                    <h1>View</h1>
                </div>

                <?php if (empty($order_status)) : ?>
                    <p>No active orders.</p>
                <?php else : ?>
                    <?php foreach ($order_status as $order) : ?>
                        <div class="order-status-content">
                            <h2><?= htmlspecialchars($order["order_number"]) ?></h2>
                            <h2><?= htmlspecialchars($order["status"]) ?></h2>
                            <a href="view_order.php?order=<?= $order['order_number'] ?>"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Order History -->
            <div class="order-history section-content" id="order-history" style="display: <?= $activeSection === 'order-history' ? 'block' : 'none' ?>;">
                <div class="order-history-top">
                    <h1>Order Number</h1>
                    <h1>Order Status</h1>
                    <h1>Shipped Date</h1>
                    <h1>View</h1>
                </div>

                <?php if (empty($order_history)) : ?>
                    <p>No order history.</p>
                <?php else : ?>
                    <?php foreach ($order_history as $order) : ?>
                        <div class="order-history-content">
                            <h2><?= htmlspecialchars($order["order_number"]) ?></h2>
                            <h2><?= htmlspecialchars($order["status"]) ?></h2>
                            <h2><?= $order["shipped_date"] ? htmlspecialchars(date("F j, Y", strtotime($order["shipped_date"]))) : "Processing" ?></h2>
                            <a href="view_order.php?order=<?= $order['order_number'] ?>"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
        document.addEventListener("DOMContentLoaded", function () {
            const options = document.querySelectorAll(".profile-options h2");
            options.forEach(option => {
                option.addEventListener("click", function () {
                    const section = this.getAttribute("data-section");
                    window.location.href = "?section=" + section;
                });
            });
        });
    </script>

    <script src="js/nav.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/chatbot.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>
