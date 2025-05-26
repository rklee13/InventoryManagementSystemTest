<?php
include 'connection.php';

$id = $_GET['id'];

$stmt = $connection->prepare("SELECT * FROM product_order_history WHERE product_order_id=$id ORDER BY date_received DESC");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);

echo json_encode($stmt->fetchAll());
?>