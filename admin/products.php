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
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/0a27fbe28a.js" crossorigin="anonymous"></script>
</head>
<body>

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

<script>
document.getElementById('select-all').addEventListener('click', function() {
    document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>

</body>
</html>
