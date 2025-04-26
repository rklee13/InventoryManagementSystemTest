<?php

session_start();
include "connection.php";

$id =(int)$_POST['id'];
$name=$_POST['name'];

// Deleting action
try {
    $table_name = $_SESSION['table'];

    // Delete junction table
    if ($table_name === 'suppliers') {
        $delete_junction_query = "DELETE FROM productSupplier WHERE supplier=$id";
        $connection->exec($delete_junction_query);
    } else if ($table_name === 'products') {
        $delete_junction_query = "DELETE FROM productSupplier WHERE product=$id";
        $connection->exec($delete_junction_query);
    }

    // Delete main table
    $delete_query = "DELETE FROM $table_name WHERE id=$id";
    $connection->exec($delete_query);

    echo json_encode([
        'success'=> true,
        'message' => $name . ' was successfully deleted.',
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success'=> false,
        'message' => 'Error processing your request!',
    ]);

}

?>