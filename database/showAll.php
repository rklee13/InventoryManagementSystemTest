<?php
include 'connection.php';

// $table_name = $_SESSION['table'];
$table_name=$show_table;

$stmt = $connection->prepare("SELECT * FROM $table_name ORDER BY created_at DESC");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
return $stmt->fetchAll();
?>