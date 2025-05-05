<?php 
include 'connection.php';

$id = $_GET['id'];

// Fetch supplier
$stmt = $connection->prepare("SELECT supplier_name,suppliers.id
    FROM suppliers,productSupplier 
    WHERE productSupplier.product=$id AND productSupplier.supplier=suppliers.id");
$stmt->execute();
$suppliersRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($suppliersRow);
?>