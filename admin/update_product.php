<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $products_file = '../data/products.json';
    $index = $_POST['index'];
    $products = json_decode(file_get_contents($products_file), true);

    $products[$index]['name'] = $_POST['name'];
    $products[$index]['categories'] = isset($_POST['categories']) ? $_POST['categories'] : [];
    $products[$index]['price'] = $_POST['price'];
    $products[$index]['stock'] = $_POST['stock'];
    $products[$index]['description'] = $_POST['description'];

    // Handle Image Upload
    if (!empty($_FILES['photo']['name'])) {
        $old_photo = $products[$index]['photo']; // Get old image
        $photo = $_FILES['photo'];
        $photoName = time() . "_" . basename($photo['name']);
        $photoPath = "../data/uploads/$photoName";

        // Move new file
        if (move_uploaded_file($photo['tmp_name'], $photoPath)) {
            // Delete old image if it exists
            $oldPhotoPath = "../data/uploads/$old_photo";
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }

            // Update image in JSON
            $products[$index]['photo'] = $photoName;
        }
    }

    file_put_contents($products_file, json_encode($products, JSON_PRETTY_PRINT));

    header('Location: products.php');
}
?>
