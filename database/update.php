<?php

session_start();
$user_id =(int)$_POST['user_id'];
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$email=$_POST['email'];

// Updating the user
try {
    include "connection.php";
    $table_name = $_SESSION['table'];

    $update_query = "UPDATE UserLoginInformation SET first_name=?, last_name=?, email=?, updated_at=? WHERE id=?";
    $connection->prepare($update_query)->execute([$first_name, $last_name,$email, date('Y-m-d h:i:s'), $user_id]);

    echo json_encode([
        'success'=> true,
        'message' => $first_name .' ' . $last_name . ' was successfully updated.',
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success'=> false,
        'message' => 'Error processing your request!',
    ]);
}

?>