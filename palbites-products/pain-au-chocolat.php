<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/product-view.css">
    <title>Palbites</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-bar">
            <div class="nav-image">
                <a href="index"><img src="../images/logo.png" alt="Palbites logo" class="nav-logo"></a>
            </div>
            <div class="nav-toggle" id="navToggle">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <div class="nav-links">
                <ul class="nav-ul">
                    <li><a href="../home.php">Home</a></li>
                    <li><a href="../about.php">About</a></li>
                    <li><a href="../products.php">Products</a></li>
                    <li><a href="../profile.php">Profile</a></li>
                    <li><a href="../cart.php"><i class="fa-solid fa-bag-shopping"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Overlay (very important for navigation) -->
    <div class="overlay" id="overlay"></div>


    <section>
        <div class="product">
            <div class="product-img">
                <img src="../images/food/pain au chocolat.svg" alt="">
            </div>
            <div class="product-content">
                <h1>Pain au chocolat</h1>
                <h6>341937840</h6>
                <h2><span>₱</span>95.00</h2>
                <p>Pain au chocolat, meaning "bread with chocolate" in French, is a popular Viennoiserie pastry consisting of a cuboid-shaped, yeast-leavened laminated dough with chocolate inside, similar to a chocolate croissant.</p>
                <div class="product-quantity">
                    <h3>Quantity:</h3>
                    <div class="product-quantity-selector">
                        <button>-</button>
                        <input type="text" value="1">
                        <button>+</button>
                    </div>
                </div>
                <div class="product-button">
                    <h4 class="add-to-cart" data-name="<?= htmlspecialchars($product["name"]) ?>">Add to Cart</h4>
                    <a href="">Buy Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer">
            <div class="footer-logo">
                <img src="../images/logo.png" alt="">
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

    
    <script src="../js/nav.js"></script>
    <script src="js/cart.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>