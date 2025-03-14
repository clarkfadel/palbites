<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $products_file = '../data/products.json';

    $name = $_POST['name'];
    $categories = isset($_POST['categories']) ? $_POST['categories'] : []; // Get selected categories
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    // Handle File Upload
    $photo = $_FILES['photo'];
    $photoName = time() . "_" . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], "../data/uploads/$photoName");

    // Load existing products
    $products = file_exists($products_file) ? json_decode(file_get_contents($products_file), true) : [];

    // Add new product
    $products[] = [
        'name' => $name,
        'photo' => $photoName,
        'categories' => $categories, // Store as an array
        'price' => $price,
        'stock' => $stock,
        'description' => $description
    ];

    file_put_contents($products_file, json_encode($products, JSON_PRETTY_PRINT));

    header('Location: products.php');
}
?>
