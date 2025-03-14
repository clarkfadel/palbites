<?php
$productsFile = "data/products.json";

if (!file_exists($productsFile)) {
    die("Error: products.json file not found!");
}

$products = json_decode(file_get_contents($productsFile), true);

if (!is_array($products)) {
    $products = [];
}

$selectedCategory = isset($_GET["category"]) ? $_GET["category"] : null;
$availableFilter = isset($_GET["available"]) ? true : false;
$searchQuery = isset($_GET["search"]) ? strtolower(trim($_GET["search"])) : "";

$filteredProducts = [];
foreach ($products as $product) {
    $productCategories = is_array($product["categories"]) ? $product["categories"] : [$product["categories"]];
    
    $categoryMatch = !$selectedCategory || in_array($selectedCategory, $productCategories);

    $availableMatch = !$availableFilter || ($product["stock"] > 0);
    
    $nameMatch = empty($searchQuery) || strpos(strtolower($product["name"]), $searchQuery) !== false;
    
    if ($categoryMatch && $availableMatch && $nameMatch) {
        $filteredProducts[] = $product;
    }
}

// Pagination setup
$productsPerPage = 9;
$totalFilteredProducts = count($filteredProducts);
$totalPages = ($totalFilteredProducts > 0) ? ceil($totalFilteredProducts / $productsPerPage) : 1;
$currentPage = isset($_GET["page"]) ? max(1, min($_GET["page"], $totalPages)) : 1;
$startIndex = ($currentPage - 1) * $productsPerPage;

// Paginate products
$paginatedProducts = array_slice($filteredProducts, $startIndex, $productsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/products.css">
    <title>Palbites - Products</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-bar">
            <div class="nav-image">
                <a href="../home.php"><img src="images/logo.png" alt="Palbites logo" class="nav-logo"></a>
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

    <!-- Products Section -->
    <section>
        <div class="products">
            <h1 class="products-h1">Our Products</h1>
            <div class="products-container">

                <!-- Filters -->
                <div class="products-left">
                    <div class="products-search">
                        <input type="text" id="searchBox" placeholder="Search..." value="<?= htmlspecialchars($searchQuery) ?>">
                        <button id="searchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                    <div class="products-filter">
                        <h2>Filters</h2>
                        <div class="products-filter-list">
                            <ul>
                                <li>
                                    <input type="checkbox" id="available" onchange="applyFilter()" <?= $availableFilter ? 'checked' : '' ?>>
                                    <label for="available">Available</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="category" id="bread" onchange="applyFilter('bread')" <?= $selectedCategory == "bread" ? 'checked' : '' ?>>
                                    <label for="bread">Bread</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="category" id="pastries" onchange="applyFilter('pastries')" <?= $selectedCategory == "pastries" ? 'checked' : '' ?>>
                                    <label for="pastries">Pastries</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="category" id="cookies" onchange="applyFilter('cookies')" <?= $selectedCategory == "cookies" ? 'checked' : '' ?>>
                                    <label for="cookies">Cookies</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="category" id="bars" onchange="applyFilter('bars')" <?= $selectedCategory == "bars" ? 'checked' : '' ?>>
                                    <label for="bars">Bars</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="category" id="baked-treats" onchange="applyFilter('baked treats')" <?= $selectedCategory == "baked treats" ? 'checked' : '' ?>>
                                    <label for="baked-treats">Baked Treats</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="products-right">
                    <h2 class="products-result-h2"><?= count($filteredProducts) ?> results</h2>
                    <div class="products-grid">

                        <?php if (empty($paginatedProducts)) : ?>
                            <p class="no-results">No products found.</p>
                        <?php else : ?>
                            <?php foreach ($paginatedProducts as $product) : ?>
                                <div class="products-content">
                                    <div class="products-content-img">
                                        <img src="data/uploads/<?= htmlspecialchars($product["photo"]) ?>" alt="<?= htmlspecialchars($product["name"]) ?>">
                                    </div>
                                    <h6 class="add-to-cart" data-name="<?= htmlspecialchars($product["name"]) ?>">+</h6>
                                    <a href="palbites-products/<?= urlencode($product['id']) ?>.php">
                                        <?= htmlspecialchars($product["name"]) ?>
                                    </a>
                                    <h2>
                                        <?= htmlspecialchars($selectedCategory && in_array($selectedCategory, $product["categories"]) ? $selectedCategory : $product["categories"][0]) ?>
                                    </h2>
                                    <div class="products-info">
                                        <h3><i class="fa-solid fa-box"></i> <?= htmlspecialchars($product["stock"]) ?> pieces available!</h3>
                                        <h4>₱<?= htmlspecialchars($product["price"]) ?></h4>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>

                    <!-- Pagination -->
                    <?php if ($totalFilteredProducts > 0 && $totalPages > 1) : ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1) : ?>
                                <a href="?page=<?= $currentPage - 1 ?>&category=<?= $selectedCategory ?>&available=<?= $availableFilter ? '1' : '' ?>&search=<?= urlencode($searchQuery) ?>">❮</a>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <a href="?page=<?= $i ?>&category=<?= $selectedCategory ?>&available=<?= $availableFilter ? '1' : '' ?>&search=<?= urlencode($searchQuery) ?>" class="<?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages) : ?>
                                <a href="?page=<?= $currentPage + 1 ?>&category=<?= $selectedCategory ?>&available=<?= $availableFilter ? '1' : '' ?>&search=<?= urlencode($searchQuery) ?>">❯</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
    <script src="js/cart.js"></script>
    <script src="js/product-list.js"></script>
    <script src="js/chatbot.js"></script>
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</body>
</html>