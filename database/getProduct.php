<?php 
include 'connection.php';

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM products WHERE id=$id");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch suppliers
$stmt = $connection->prepare("SELECT supplier_name,suppliers.id FROM suppliers,productSupplier WHERE productSupplier.product=$id AND productSupplier.supplier=suppliers.id");
$stmt->execute();
$suppliersRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

$row['suppliers']=array_column($suppliersRow, 'id');

echo json_encode($row);
?>