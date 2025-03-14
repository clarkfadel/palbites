<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/about.css">
    <title>Palbites - About</title>
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
        <div class="about">
            <h1 class="about-h1">A little thing about us!</h1>
            <div class="about-container">
                <div class="about-left-side">
                    <div class="about-content">
                        <div class="about-question">
                            <h1>What is Palbites?</h1>
                            <h2 class="toggle">+</h2>
                        </div>
                        <p class="about-text">Palbites is a bakeshop dedicated to crafting fresh, high-quality pastries, bread, and cakes for every occasion. We believe that every bite should bring joy and warmth to our customers.</p>
                    </div>
                    <div class="about-content">
                        <div class="about-question">
                            <h1>What is our story?</h1>
                            <h2 class="toggle">+</h2>
                        </div>
                        <p class="about-text">What started as a small home bakery grew into a beloved shop known for its handcrafted treats. We continue to bake with passion, using the finest ingredients and time-tested recipes.</p>
                    </div>
                    <div class="about-content">
                        <div class="about-question">
                            <h1>What do we offer?</h1>
                            <h2 class="toggle">+</h2>
                        </div>
                        <p class="about-text">We offer freshly baked bread, delicious pastries, and cookies made with love. Our products are perfect for everyday treats or special celebrations.</p>
                    </div>
                </div>
                <div class="about-img">
                    <img src="images/about-bg.svg" alt="">
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

    <script src="js/nav.js"></script>
    <script src="js/about.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/chatbot.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>