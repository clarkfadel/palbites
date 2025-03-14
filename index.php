<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/pre-home.css">
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
            </div>
            -->
            <div class="nav-links">
                <ul class="nav-ul">
                    <li><a href="auth/login.php">Login</a></li>
                    <li><a href="auth/signup.php">Sign Up</a></li>
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

                <a href="login.php" class="best-content">
                    <div>
                        <img src="images/food/brownies.svg" alt="">
                        <h1>Brownies</h1>
                    </div>
                </a>

                <a href="login.php" class="best-content">
                    <div>
                        <img src="images/food/croissant.svg" alt="">
                        <h1>Croissant</h1>
                    </div>
                </a>

                <a href="login.php" class="best-content">
                    <div>
                        <img src="images/food/pain au chocolat.svg" alt="">
                        <h1>Pain au chocolat</h1>
                    </div>
                </a>

            </div>
            <div class="best-link-container">
                <a href="" class="best-link">View More</a>
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
                <a href="login.php">Order Now</a>
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

    <script src="js/index.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>