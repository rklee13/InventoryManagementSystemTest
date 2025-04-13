<?php

session_start();
include "connection.php";

$id =(int)$_POST['id'];
$name=$_POST['name'];

// Deleting action
try {
    $table_name = $_SESSION['table'];

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