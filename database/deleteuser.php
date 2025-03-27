<?php

session_start();
$user_id =(int)$_POST['user_id'];
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];

// Deleting the user
try {
    include "connection.php";
    $table_name = $_SESSION['table'];

    // DELETE FROM `UserLoginInformation` WHERE 0
    $delete_query = "DELETE FROM $table_name WHERE id=$user_id";
    $connection->exec($delete_query);

    echo json_encode([
        'success'=> true,
        'message' => $first_name .' ' . $last_name . ' was successfully deleted.',
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success'=> false,
        'message' => 'Error processing your request!',
    ]);

}

?>