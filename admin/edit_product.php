<?php
$index = $_GET['index'];
$products = json_decode(file_get_contents('../data/products.json'), true);
$product = $products[$index];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>

<h2>Edit Product</h2>
<form action="update_product.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="index" value="<?= $index ?>">
    <input type="hidden" name="old_photo" value="<?= htmlspecialchars($product['photo']) ?>">

    <label>Product Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>

    <label>Current Image:</label><br>
    <img src="../data/uploads/<?= htmlspecialchars($product['photo']) ?>" width="100"><br>

    <label>Upload New Image (optional):</label>
    <input type="file" name="photo"><br>

    <label>Product Categories:</label><br>
    <?php
    $categories = ["bread", "loaves", "pastries", "cookies", "bars", "baked treats"];
    foreach ($categories as $category) {
        $checked = in_array($category, $product['categories']) ? 'checked' : '';
        echo '<input type="checkbox" name="categories[]" value="' . $category . '" ' . $checked . '> ' . ucfirst($category) . '<br>';
    }
    ?>

    <label>Price ($):</label>
    <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required><br>

    <label>Stock:</label>
    <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required><br>

    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br>

    <button type="submit">Update Product</button>
</form>

</body>
</html>
