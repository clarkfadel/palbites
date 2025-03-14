<?php
$index = $_GET['index'];
$products = json_decode(file_get_contents('../data/products.json'), true);
array_splice($products, $index, 1);
file_put_contents('../data/products.json', json_encode($products, JSON_PRETTY_PRINT));
header('Location: products.php');
?>
