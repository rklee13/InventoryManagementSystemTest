<?php

session_start();
include "connection.php";

$user = $_SESSION['user'];
$order_quantity = $_POST['quantity'];
$batch = time();
$success=false;
$message="";

try {
    foreach ($order_quantity as $productId => $supplier_quantity) {
        foreach ($supplier_quantity as $supplierId => $quantity) {
            // Insert to database
            $values = [
                'supplier' => $supplierId,
                'product' => $productId,
                'quantity_ordered' => $quantity,
                'status' => 'PENDING',
                'batch' => $batch,
                'created_by' => $user['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $insert_query = "INSERT INTO product_order(supplier, product, quantity_ordered, status, batch, created_by, created_at, updated_at) VALUES (:supplier, :product, :quantity_ordered, :status, :batch, :created_by, :created_at, :updated_at)";

            $connection->prepare($insert_query)->execute($values);
        }
    }
    $success=true;
    $message="Successfully ordered the products.";
} catch(\Exception $e) {
    $success=false;
    $message=$e->getMessage();
}

$_SESSION['response']= [
    'success' => $success,
    'message' => $message,
];
header('location: ../product-order.php');
?>