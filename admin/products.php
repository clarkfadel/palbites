<?php
$products_file = '../data/products.json';

if (file_exists($products_file) && filesize($products_file) > 0) {
    $products = json_decode(file_get_contents($products_file), true);
} else {
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Products</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</head>
<body>

<section class="container">
    <nav>
        <div class="nav">
            <div class="nav-img">
                <img src="../images/logo.png" alt="">
            </div>
            <div class="nav-list">
                <ul class="nav-ul">
                    <li><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a></li>
                    <li><a href="products.php" class="active"><i class="fa-solid fa-box-open" ></i>Products</a></li>
                    <li><a href="orders.php"><i class="fa-solid fa-bag-shopping"></i>Orders</a></li>
                    <li><a href="restock_suggestion.php"><i class="fa-solid fa-arrow-trend-up"></i>Stock</a></li>
                    <li><a href="logout.php"><i class="fa-solid fa-user"></i>Logout</a></li>
                </ul>   
            </div>
        </div>
    </nav>

    <div>
        <h2>Product Dashboard</h2>
        <a href="add_product.php">Add New Product</a>
        <table border="1">
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th><i class="fa-solid fa-trash"></i></th>
            </tr>

            <?php if (!empty($products)): ?>
                <?php foreach ($products as $index => $product): ?>
                    <tr>
                        <td><input type="checkbox" class="product-checkbox"></td>
                        <td><img src="../data/uploads/<?= htmlspecialchars($product['photo']) ?>" width="50"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars(implode(", ", $product['categories'])) ?></td>
                        <td>₱<?= htmlspecialchars($product['price']) ?></td>
                        <td><?= htmlspecialchars($product['stock']) ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="dropdown-btn">...</button>
                                <div class="dropdown-content">
                                    <a href="edit_product.php?index=<?= $index ?>">Edit</a>
                                    <a href="delete_product.php?index=<?= $index ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No products found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</section>


<script>
document.getElementById('select-all').addEventListener('click', function() {
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>

</body>
</html>
