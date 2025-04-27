<?php 
include 'connection.php';

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM suppliers WHERE id=$id");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch products
$stmt = $connection->prepare("SELECT product_name,products.id FROM products,productSupplier WHERE productSupplier.supplier=$id AND productSupplier.product=products.id");
$stmt->execute();
$productsRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

$row['products']=array_column($productsRow, 'id');

echo json_encode($row);
?>