<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>

<h2>Add New Product</h2>
<form action="save_product.php" method="post" enctype="multipart/form-data">
    <label>Product Name:</label>
    <input type="text" name="name" required><br>

    <label>Product Image:</label>
    <input type="file" name="photo" required><br>

    <label>Product Categories:</label><br>
    <?php
    $categories = ["bread", "loaves", "pastries", "cookies", "bars", "baked treats"];
    foreach ($categories as $category) {
        echo '<input type="checkbox" name="categories[]" value="' . $category . '"> ' . ucfirst($category) . '<br>';
    }
    ?>

    <label>Price ($):</label>
    <input type="number" name="price" step="0.01" required><br>

    <label>Stock:</label>
    <input type="number" name="stock" required><br>

    <label>Description:</label>
    <textarea name="description"></textarea><br>

    <button type="submit">Save Product</button>
</form>

</body>
</html>
